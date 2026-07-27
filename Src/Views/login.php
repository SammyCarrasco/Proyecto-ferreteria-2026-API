<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EL Yunque - Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #1e2537; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 10px; padding: 40px; width: 380px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
        .login-card h3 { margin-bottom: 25px; text-align: center; }
    </style>
</head>
<body>


    <div class="login-card">
        <h3><i class="bi bi-tools"></i>El Yunque</h3>

        <div id="mensaje-error" class="alert alert-danger d-none"></div>

         <div class="topbar">
          <a href="#" id="btn-idioma" class="text-decoration-none">🌐 <span id="btn-idioma-texto"></span></a>
        
         </div>

        <form id="form-login">
            <div class="mb-3">
                <label class="form-label" data-i18n="login_correo">Correo</label>
                <input type="email" class="form-control" id="email" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label" data-i18n="login_clave">Contraseña</label>
                <input type="password" class="form-control" id="clave" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="btn-login" data-i18n="login_btn">Ingresar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/idiomas.js"></script>

    <script>
                let idiomaActual = localStorage.getItem('idioma') || 'es';
                $('#btn-idioma-texto').text(idiomaActual === 'es' ? 'English' : 'Español');

                $('#btn-idioma').on('click', function (e) {
                    e.preventDefault();
                    let nuevo = (localStorage.getItem('idioma') || 'es') === 'es' ? 'en' : 'es';
                    localStorage.setItem('idioma', nuevo);
                    location.reload();
                });

        $('#form-login').off('submit').on('submit', function (e) {
            e.preventDefault();

            let email = $('#email').val().trim();
            let clave = $('#clave').val().trim();
            let $btn = $('#btn-login');

            $('#mensaje-error').addClass('d-none');
            $btn.prop('disabled', true).text('Ingresando...');

            $.ajax({
                url: 'auth',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: email,
                    clave: clave
                }),
                dataType: 'json',
                success: function (resp) {
                    if (resp.token) {
                        localStorage.setItem('token', resp.token);
                        if (resp.rol) localStorage.setItem('rol', resp.rol);
                        if (resp.nombre) localStorage.setItem('nombre', resp.nombre);
                        window.location.href = 'menu?caso=1';
                    } else {
                        mostrarError(resp.message || 'Usuario o contraseña incorrectos.');
                    }
                },
                error: function (xhr) {
                    let mensaje = 'Error de conexión o credenciales inválidas (' + xhr.status + ').';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    }
                    mostrarError(mensaje);
                },
                complete: function () {
                    // Vuelve a traducir el botón por si el idioma no es español
                    $btn.prop('disabled', false);
                    let idioma = localStorage.getItem('idioma') || 'es';
                    $btn.text(idioma === 'en' ? 'Log in' : 'Ingresar');
                }
            });
        });

        function mostrarError(msg) {
            $('#mensaje-error').removeClass('d-none').text(msg);
        }
    </script>
</body>
</html>