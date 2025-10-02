<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Empleado::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('rol', 'like', "%{$search}%");
            });
        }

        $empleados = $query->orderBy('nombre')->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $empleado = new Empleado();
        return view('empleados.create', compact('empleado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:empleados,email',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:empleado,vendedor,gerente',
            'activo' => 'sometimes|boolean'
        ]);

        // Usar transacción para asegurar la consistencia
        DB::beginTransaction();
        
        try {
            // Hashear la contraseña antes de guardar
            $data['password'] = Hash::make($data['password']);
            $data['activo'] = $request->has('activo') ? 1 : 0;
            
            $empleado = Empleado::create($data);
            
            DB::commit();
            
            return redirect()->route('empleados.index')
                ->with('success', 'Empleado creado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el empleado: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Empleado $empleado)
    {
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:empleados,email,' . $empleado->id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol' => 'required|in:empleado,vendedor,gerente',
            'activo' => 'sometimes|boolean'
        ]);

        // Si no se proporciona nueva contraseña, mantener la actual
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $data['activo'] = $request->has('activo') ? 1 : 0;
        
        $empleado->update($data);
        
        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Empleado $empleado)
    {
        // No permitir eliminar al propio usuario si está autenticado
        if (auth()->check() && auth()->user()->id === $empleado->id) {
            return redirect()->route('empleados.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }
        
        $empleado->delete();
        
        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}