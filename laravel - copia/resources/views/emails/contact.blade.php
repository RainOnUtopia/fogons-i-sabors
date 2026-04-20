<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nou missatge de contacte</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; border: 1px solid #e1e4e8; overflow: hidden; }
        .header { background-color: #be3144; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .field { margin-bottom: 20px; }
        .label { font-weight: bold; color: #be3144; margin-bottom: 5px; display: block; }
        .value { background: #f1f3f5; padding: 15px; border-radius: 5px; border-left: 4px solid #be3144; }
        .footer { text-align: center; font-size: 0.85em; color: #6c757d; padding: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Fogons i Sabors</h1>
            <p>Nou missatge des del formulari de contacte</p>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Nom:</span>
                <div class="value">{{ $data['name'] }}</div>
            </div>
            <div class="field">
                <span class="label">Email:</span>
                <div class="value">{{ $data['email'] }}</div>
            </div>
            <div class="field">
                <span class="label">Missatge:</span>
                <div class="value">{!! nl2br(e($data['message'])) !!}</div>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Fogons i Sabors. Aquest missatge ha estat generat automàticament.
        </div>
    </div>
</body>
</html>
