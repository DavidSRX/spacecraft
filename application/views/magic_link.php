<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔐 Enlace de Acceso</h2>
        </div>
        
        <p>Hola <?php echo htmlspecialchars($name); ?>,</p>
        
        <p>Has solicitado acceder a tu cuenta. Haz clic en el siguiente botón para iniciar sesión:</p>
        
        <center>
            <a href="<?php echo $magic_link; ?>" class="button">
                🚀 Iniciar Sesión
            </a>
        </center>
        
        <p>O copia y pega este enlace en tu navegador:</p>
        <p style="word-break: break-all; background: #fff; padding: 10px; border-radius: 5px;">
            <?php echo $magic_link; ?>
        </p>
        
        <div class="warning">
            ⚠️ <strong>Importante:</strong> Este enlace expira en <?php echo $expiry_minutes; ?> minutos y solo puede usarse una vez.
        </div>
        
        <p>Si no solicitaste este enlace, puedes ignorar este email de forma segura.</p>
        
        <div class="footer">
            <p>Este es un email automático, por favor no respondas a este mensaje.</p>
            <p>&copy; <?php echo date('Y'); ?> Tu Aplicación. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>