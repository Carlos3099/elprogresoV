<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - Fresh Boys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .register-logo {
            width: 120px;
            margin-bottom: 1rem;
        }
        .btn-register {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            padding: 12px;
            font-weight: 500;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #495057, #343a40);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <img src="{{ asset('img/logo_inicio.png') }}" alt="Logo Fresh Boys" class="register-logo">
                <h2>Registrar Nuevo Usuario</h2>
                <p class="text-muted">Complete la información del nuevo usuario</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

      <!-- ... (código anterior del header y estilos) ... -->

<form action="{{ route('register.submit') }}" method="POST">
    @csrf
    
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre de usuario *</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="{{ old('nombre') }}" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Correo electrónico *</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="{{ old('email') }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="password" class="form-label">Contraseña *</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="col-md-6">
            <label for="password_confirmation" class="form-label">Confirmar contraseña *</label>
            <input type="password" class="form-control" id="password_confirmation" 
                   name="password_confirmation" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="rol" class="form-label">Rol *</label>
            <select class="form-select" id="rol" name="rol" required>
                <option value="">Seleccionar rol</option>
                <option value="vendedor" {{ old('rol') == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="gerente" {{ old('rol') == 'gerente' ? 'selected' : '' }}>Gerente</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="sucursal_id" class="form-label">Sucursal *</label>
            <select class="form-select" id="sucursal_id" name="sucursal_id" required>
                <option value="">Seleccionar sucursal</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->id }}" {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                        {{ $sucursal->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-register text-white">
            <i class="fas fa-user-plus me-2"></i>Registrar Usuario
        </button>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver al login
        </a>
    </div>
</form>

<!-- ... (código posterior) ... -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>