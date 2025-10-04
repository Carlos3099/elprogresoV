<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - El Progreso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: url("{{ asset('img/fondo-galletas.png') }}") no-repeat center center fixed;
            background-size: cover; /* ✅ se ajusta a la pantalla sin repetirse */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-container {
            max-width: 650px;
            width: 100%;
            padding: 2.5rem;
            background: rgba(255,255,255,0.9); /* ✅ efecto glass */
            backdrop-filter: blur(8px);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-logo {
            width: 160px;
            margin-bottom: 1rem;
            animation: zoomIn 1s ease;
        }

        .register-header h2 {
            font-weight: 700;
            color: #d62828;
        }

        .register-header p {
            color: #6c757d;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #d62828;
            box-shadow: 0 0 0 0.25rem rgba(214,40,40,0.2);
        }

        .btn-register {
            background: linear-gradient(135deg, #d62828, #9d0208);
            border: none;
            padding: 14px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #9d0208, #6a040f);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(214,40,40,0.3);
        }

        .btn-outline-secondary {
            border-radius: 12px;
            padding: 12px;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.7); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('img/elprogreso.png') }}" alt="Logo El Progreso" class="register-logo">
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

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-register text-white">
                    <i class="fas fa-user-plus me-2"></i> Registrar Usuario
                </button>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Volver al login
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
