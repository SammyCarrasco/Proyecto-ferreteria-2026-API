<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ferretería 2026 / Hardware Store 2026</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <style>
        body { background: #1e2537; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-idioma { background: #fff; border-radius: 10px; padding: 40px; text-align: center; width: 380px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
    </style>
</head>
<body>
 
    <div class="card-idioma">
        <h4 class="mb-4">Selecciona tu idioma / Select your language</h4>
        <button class="btn btn-primary btn-lg w-100 mb-3" onclick="elegirIdioma('es')">Español</button>
        <button class="btn btn-outline-primary btn-lg w-100" onclick="elegirIdioma('en')">English</button>
    </div>
 
    <script>
        function elegirIdioma(idioma) {
            localStorage.setItem('idioma', idioma);
            window.location.href = 'login';
        }
    </script>
</body>
</html>