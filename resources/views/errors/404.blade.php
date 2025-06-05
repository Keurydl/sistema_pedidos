<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1d29;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .error-container {
            text-align: center;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #3498db;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .btn-home {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn-home:hover {
            background-color: #2980b9;
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">NOT FOUND</div>
        <p class="mb-4">La página que estás buscando no existe o ha sido movida.</p>
        <a href="index.php" class="btn-home">Volver al inicio</a>
    </div>
</body>
</html>