<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmació de recepció</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; border: 1px solid #e1e4e8; overflow: hidden; }
        .header { background-color: #be3144; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .footer { text-align: center; font-size: 0.85em; color: #6c757d; padding: 20px; border-top: 1px solid #eee; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #be3144; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Fogons i Sabors</h1>
        </div>
        <div class="content">
            <h2>Hola, {{ $data['name'] }}!</h2>
            <p>Gràcies per posar-te en contacte amb nosaltres. Hem rebut correctament el teu missatge enviat des del nostre formulari de contacte.</p>
            <p>El nostre equip revisarà la teva consulta i et respondrem el més aviat possible.</p>
            
            <p style="margin-top: 30px; font-style: italic;">"La bona cuina és el fonament de la felicitat genuïna."</p>

            <a href="{{ config('app.url') }}" class="btn">Torna al Receptari</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Fogons i Sabors. Aquest és un correu generat automàticament, si us plau no responguis a aquesta adreça.
        </div>
    </div>
</body>
</html>
