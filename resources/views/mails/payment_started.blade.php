<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tu pago esta siendo procesado</h1>
        </div>
        <div class="content">
            <p>Hola {{ $buyer['name'] }}</p>
            <p>
                Tu pago esta siendo procesado, en breve recibirás un correo con la confirmación de tu pago.
            </p>
        </div>
        <div class="footer">
            <p>
                Gracias por confiar en nosotros.
                {{ date('Y') }} {{ config('app.name') }} - Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
