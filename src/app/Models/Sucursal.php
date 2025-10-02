<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales'; // Nombre correcto de tu tabla
    protected $fillable = [
        'nombre',
        'direccion', 
        'telefono',
        'logo_path', // Ruta completa del logo
        'qr_path',   // Ruta completa del QR
    ];

    protected $attributes = [
        'logo_path' => 'images/tickets/logos/logo_default.png',
        'qr_path' => 'images/tickets/qr/qr_default.jpeg',
    ];

    // Accesor para obtener la ruta física absoluta del logo
    public function getLogoAbsolutePathAttribute()
    {
        return public_path($this->logo_path);
    }

    // Accesor para obtener la ruta física absoluta del QR
    public function getQrAbsolutePathAttribute()
    {
        return public_path($this->qr_path);
    }

    // Accesor para verificar si el logo existe
    public function getLogoExistsAttribute()
    {
        return file_exists($this->logo_absolute_path);
    }

    // Accesor para verificar si el QR existe
    public function getQrExistsAttribute()
    {
        return file_exists($this->qr_absolute_path);
    }

    // Accesor para obtener la URL del logo
    public function getLogoUrlAttribute()
    {
        return asset($this->logo_path);
    }

    // Accesor para obtener la URL del QR
    public function getQrUrlAttribute()
    {
        return asset($this->qr_path);
    }

    // Método para obtener el path del logo con lógica de respaldo
    public function getFinalLogoPath()
    {
        // Si el logo configurado existe, usarlo
        if ($this->logo_exists) {
            return $this->logo_absolute_path;
        }

        // Lógica de respaldo basada en el nombre de la sucursal
        $sucursalNombre = strtolower($this->nombre);
        
        $logosMap = [
            'mariano' => 'images/tickets/logos/logo_freshboys.png',
            'mariano1' => 'images/tickets/logos/logo_freshboys.png',
            'mariano2' => 'images/tickets/logos/logo_freshboys.png',
            'mariano3' => 'images/tickets/logos/logo_freshboys.png',
            'centro' => 'images/tickets/logos/logo_freshboys.png',
            'fresh2' => 'images/tickets/logos/logo_freshboys.png',
            'pilar' => 'images/tickets/logos/logo_freshhype2.png',
            'society' => 'images/tickets/logos/society2.png',
            'sbhype' => 'images/tickets/logos/logo_sbhype2.png'
        ];

        foreach ($logosMap as $key => $logoPath) {
            if (str_contains($sucursalNombre, $key)) {
                $absolutePath = public_path($logoPath);
                if (file_exists($absolutePath)) {
                    return $absolutePath;
                }
            }
        }

        // Logo por defecto
        $defaultPath = public_path('images/tickets/logos/logo_default.png');
        return file_exists($defaultPath) ? $defaultPath : null;
    }

    // Método para obtener el path del QR con lógica de respaldo
    public function getFinalQrPath()
    {
        // Si el QR configurado existe, usarlo
        if ($this->qr_exists) {
            return $this->qr_absolute_path;
        }

        // Lógica de respaldo basada en el nombre de la sucursal
        $sucursalNombre = strtolower($this->nombre);
        
        // SBHype tiene QR especial
        if (str_contains($sucursalNombre, 'sbhype')) {
            $qrPath = public_path('images/tickets/qr/qr_sbhype.jpeg');
            if (file_exists($qrPath)) {
                return $qrPath;
            }
        }

        // QR por defecto
        $defaultPath = public_path('images/tickets/qr/qr_default.jpeg');
        return file_exists($defaultPath) ? $defaultPath : null;
    }

    // Relación con usuarios
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    // Relación con ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}