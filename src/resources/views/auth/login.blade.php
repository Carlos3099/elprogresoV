<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Boys - Sistema de Gestión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos generales */
        body.bg-dark {
            background-image: url('/img/fondo1.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Contenedor del login */
        .full-height-center {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Caja de login */
        .login-box {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.7);
        }

        /* Animaciones */
        .attention-grabbing-logo {
            animation: popIn 2s ease-in-out forwards;
            width: 180px;
            height: 180px;
            object-fit: contain;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.5));
        }

        .slide-in-title {
            animation: slideIn 0.8s ease-in-out forwards;
            font-weight: 300;
            letter-spacing: 1px;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Campos de formulario */
        .form-control {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white !important;
            padding: 14px 20px !important;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.18) !important;
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.3rem rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        /* Botón de mostrar contraseña */
        #togglePassword {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            transition: color 0.3s ease;
        }

        #togglePassword:hover {
            color: rgba(255, 255, 255, 1);
        }

        .input-group {
            position: relative;
        }

        /* Botón de login */
        .btn-login {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 14px;
            font-weight: 500;
            letter-spacing: 0.8px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.15));
            transform: translateY(-3px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.3);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        /* Mensajes de error y alertas */
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.85);
            border: 1px solid rgba(220, 53, 69, 0.6);
            color: white;
            border-radius: 12px;
            padding: 1rem;
            backdrop-filter: blur(5px);
        }

        .alert-info {
            background-color: rgba(23, 162, 184, 0.85);
            border: 1px solid rgba(23, 162, 184, 0.6);
            color: white;
            border-radius: 12px;
            padding: 1rem;
            backdrop-filter: blur(5px);
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.85);
            border: 1px solid rgba(40, 167, 69, 0.6);
            color: white;
            border-radius: 12px;
            padding: 1rem;
            backdrop-filter: blur(5px);
        }

        /* Keyframes para animaciones */
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.5) rotate(-15deg); }
            60% { opacity: 1; transform: scale(1.15) rotate(5deg); }
            80% { transform: scale(0.95) rotate(-2deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        /* Efecto hover para el logo */
        #logo:hover {
            animation: popIn 0.8s ease-in-out forwards;
            cursor: pointer;
            filter: brightness(1.1) drop-shadow(0 7px 20px rgba(255, 255, 255, 0.2));
        }

        /* Loading spinner */
        .spinner-border {
            width: 1.2rem;
            height: 1.2rem;
            border-width: 0.15em;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-box {
                padding: 2rem;
                margin: 1rem;
            }
            
            .attention-grabbing-logo {
                width: 140px;
                height: 140px;
            }
            
            .slide-in-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-dark">
    <div class="container full-height-center d-flex justify-content-center align-items-center">
        <div class="login-box text-center text-white">
            <!-- Logo con animación -->
            <img id="logo" src="{{ asset('img/logo_inicio.png') }}" alt="Logo Fresh Boys"
                class="w-75 h-75 img-fluid mb-4 mx-auto d-block attention-grabbing-logo">


            <!-- Mostrar errores de validación -->
            @if($errors->any())
                <div class="alert alert-danger mb-4 animate__animated animate__shakeX">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error de autenticación</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Mostrar mensajes de sesión -->
            @if(session('status'))
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulario de login -->
            <form id="loginForm" method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                
                <div class="form-group mb-4">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-user me-2"></i>Usuario
                    </label>
                    <input type="text" id="input_nombre" name="nombre" class="form-control" 
                           value="{{ old('nombre') }}" required placeholder="Ingrese su nombre de usuario"
                           autocomplete="username">
                </div>
                            
                <div class="form-group mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Contraseña
                    </label>
                    <div class="input-group">
                        <input type="password" id="input_password" name="password" class="form-control" 
                               required placeholder="Ingrese su contraseña" autocomplete="current-password">
                        <button type="button" class="btn" id="togglePassword" aria-label="Mostrar contraseña" 
                                title="Mostrar/ocultar contraseña">
                            <i class="bi bi-eye-slash" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Recordar mi sesión
                        </label>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-login text-white" id="loginButton">
                        <span id="loginText">Iniciar Sesión</span>
                        <span id="loginSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>

                <div class="text-center">
                    <a href="#" class="text-white-50 text-decoration-none small">
                        <i class="fas fa-question-circle me-1"></i>¿Problemas para acceder?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('input_password');
            const passwordIcon = document.getElementById('passwordIcon');
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loginText = document.getElementById('loginText');
            const loginSpinner = document.getElementById('loginSpinner');
            
            // Toggle de contraseña
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                if (type === 'text') {
                    passwordIcon.classList.remove('bi-eye-slash');
                    passwordIcon.classList.add('bi-eye');
                } else {
                    passwordIcon.classList.remove('bi-eye');
                    passwordIcon.classList.add('bi-eye-slash');
                }
            });
            
            // Efecto de loading al enviar el formulario
            loginForm.addEventListener('submit', function() {
                loginText.classList.add('d-none');
                loginSpinner.classList.remove('d-none');
                loginButton.disabled = true;
            });
            
            // Efecto de shake en errores
            if (document.querySelector('.alert-danger')) {
                document.querySelector('.alert-danger').classList.add('animate__animated', 'animate__shakeX');
            }
            
            // Enfocar el campo de usuario al cargar
            document.getElementById('input_nombre').focus();
            
            // Efectos de hover en inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', () => {
                    input.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
</body>
</html>