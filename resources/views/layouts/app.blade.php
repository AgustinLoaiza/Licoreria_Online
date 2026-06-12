<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'R&E Distribuidora de Bebidas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand-ink: #090b10;
            --brand-panel: #11151d;
            --brand-red: #f10f3f;
            --brand-gold: #f59e0b;
            --brand-soft: #fff7ed;
        }
        .bg-gradient-primary {
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.28), transparent 28rem),
                radial-gradient(circle at bottom right, rgba(241, 15, 63, 0.24), transparent 24rem),
                linear-gradient(135deg, #07080c 0%, #151922 58%, #250813 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--brand-red) 0%, #a40829 100%);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #ff214f 0%, #bd0f35 100%);
        }
        .input-focus:focus {
            border-color: var(--brand-gold);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.16);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#090b10] text-gray-900">
    <main>
        @yield('content')
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
    <script>
        $(document).ready(function() {
            // Auto-ocultar mensajes de alerta después de 5 segundos
            setTimeout(function() {
                $('.alert-auto-dismiss').fadeOut('slow');
            }, 5000);

            // Toggle password visibility
            $('.toggle-password').click(function() {
                $(this).toggleClass('fa-eye fa-eye-slash');
                var input = $($(this).attr('toggle'));
                if (input.attr('type') == 'password') {
                    input.attr('type', 'text');
                } else {
                    input.attr('type', 'password');
                }
            });
        });
    </script>
</body>
</html>
