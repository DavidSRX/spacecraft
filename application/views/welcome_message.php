<?php
/**
 * Script de Prueba de Email
 * Coloca este archivo en la raíz de tu proyecto
 * Accede a: http://spacecraft.local/test_email.php
 * 
 * IMPORTANTE: Elimina este archivo después de probar
 */

// Prevenir ejecución en producción
if ($_SERVER['SERVER_NAME'] !== 'spacecraft.local' && $_SERVER['SERVER_NAME'] !== 'localhost') {
    die('Este script solo puede ejecutarse en desarrollo');
}

define('BASEPATH', true);
define('ENVIRONMENT', 'development');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar CodeIgniter
require_once 'system/core/Common.php';
require_once 'system/core/CodeIgniter.php';

// O de manera más simple, cargar el index
require_once 'index.php';

// Obtener instancia de CI
$CI =& get_instance();
$CI->load->library('email');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Email - CodeIgniter 3</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        pre {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border-left: 4px solid #667eea;
        }
        .section {
            margin: 30px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .section h2 {
            margin-top: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table td:first-child {
            font-weight: bold;
            width: 200px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Prueba de Email - CodeIgniter 3</h1>
        
        <?php
        // =====================================
        // SECCIÓN 1: Información del Sistema
        // =====================================
        ?>
        <div class="section">
            <h2>1. Información del Sistema</h2>
            <table>
                <tr>
                    <td>Versión PHP:</td>
                    <td><?php echo phpversion(); ?></td>
                </tr>
                <tr>
                    <td>OpenSSL:</td>
                    <td><?php echo extension_loaded('openssl') ? '✅ Habilitado' : '❌ No disponible'; ?></td>
                </tr>
                <tr>
                    <td>SMTP:</td>
                    <td><?php echo function_exists('fsockopen') ? '✅ Disponible' : '❌ No disponible'; ?></td>
                </tr>
                <tr>
                    <td>CodeIgniter:</td>
                    <td><?php echo CI_VERSION; ?></td>
                </tr>
            </table>
        </div>
        
        <?php
        // =====================================
        // SECCIÓN 2: Configuración de Email
        // =====================================
        ?>
        <div class="section">
            <h2>2. Configuración Actual de Email</h2>
            
            <?php
            // Cargar configuración
            $CI->config->load('email');
            $email_config = $CI->config->item('protocol');
            ?>
            
            <table>
                <tr>
                    <td>Protocol:</td>
                    <td><?php echo $CI->config->item('protocol') ?: 'mail (default)'; ?></td>
                </tr>
                <tr>
                    <td>SMTP Host:</td>
                    <td><?php echo $CI->config->item('smtp_host') ?: 'No configurado'; ?></td>
                </tr>
                <tr>
                    <td>SMTP Port:</td>
                    <td><?php echo $CI->config->item('smtp_port') ?: 'No configurado'; ?></td>
                </tr>
                <tr>
                    <td>SMTP User:</td>
                    <td><?php echo $CI->config->item('smtp_user') ? '***configurado***' : 'No configurado'; ?></td>
                </tr>
                <tr>
                    <td>SMTP Pass:</td>
                    <td><?php echo $CI->config->item('smtp_pass') ? '***configurado***' : '❌ No configurado'; ?></td>
                </tr>
                <tr>
                    <td>Mailtype:</td>
                    <td><?php echo $CI->config->item('mailtype') ?: 'text'; ?></td>
                </tr>
            </table>
        </div>
        
        <?php
        // =====================================
        // SECCIÓN 3: Prueba de Conexión SMTP
        // =====================================
        ?>
        <div class="section">
            <h2>3. Prueba de Conexión SMTP</h2>
            
            <?php
            $smtp_host = $CI->config->item('smtp_host');
            $smtp_port = $CI->config->item('smtp_port');
            
            if ($smtp_host && $smtp_port) {
                $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, 10);
                
                if ($socket) {
                    echo '<div class="success">';
                    echo '✅ <strong>Conexión exitosa</strong> al servidor SMTP: ' . $smtp_host . ':' . $smtp_port;
                    echo '</div>';
                    fclose($socket);
                } else {
                    echo '<div class="error">';
                    echo '❌ <strong>Error de conexión</strong><br>';
                    echo 'No se pudo conectar a: ' . $smtp_host . ':' . $smtp_port . '<br>';
                    echo 'Error: ' . $errstr . ' (' . $errno . ')';
                    echo '</div>';
                }
            } else {
                echo '<div class="info">';
                echo 'ℹ️ SMTP no configurado o usando método "mail"';
                echo '</div>';
            }
            ?>
        </div>
        
        <?php
        // =====================================
        // SECCIÓN 4: Envío de Email de Prueba
        // =====================================
        
        // Email de destino (cámbialo según necesites)
        $test_email = 'test@example.com';
        
        // Solo enviar si se presiona el botón
        if (isset($_GET['send'])) {
            ?>
            <div class="section">
                <h2>4. Resultado del Envío</h2>
                
                <?php
                try {
                    $CI->email->clear();
                    $CI->email->from('noreply@spacecraft.local', 'Portal Espacial');
                    $CI->email->to($test_email);
                    $CI->email->subject('🧪 Email de Prueba - ' . date('Y-m-d H:i:s'));
                    
                    $message = '
                    <html>
                    <body>
                        <h2>Email de Prueba</h2>
                        <p>Este es un email de prueba enviado desde CodeIgniter 3</p>
                        <p><strong>Fecha:</strong> ' . date('Y-m-d H:i:s') . '</p>
                        <p><strong>Servidor:</strong> ' . $_SERVER['SERVER_NAME'] . '</p>
                    </body>
                    </html>
                    ';
                    
                    $CI->email->message($message);
                    
                    if ($CI->email->send()) {
                        echo '<div class="success">';
                        echo '✅ <strong>¡Email enviado exitosamente!</strong><br>';
                        echo 'Destinatario: ' . $test_email;
                        echo '</div>';
                    } else {
                        echo '<div class="error">';
                        echo '❌ <strong>Error al enviar el email</strong>';
                        echo '</div>';
                        
                        echo '<h3>Debug del Error:</h3>';
                        echo '<pre>' . $CI->email->print_debugger() . '</pre>';
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="error">';
                    echo '❌ <strong>Excepción capturada:</strong><br>';
                    echo $e->getMessage();
                    echo '</div>';
                }
                ?>
            </div>
            <?php
        } else {
            ?>
            <div class="section">
                <h2>4. Enviar Email de Prueba</h2>
                <div class="info">
                    ℹ️ Haz clic en el botón para enviar un email de prueba a: <strong><?php echo $test_email; ?></strong><br>
                    <small>Puedes cambiar el email en la línea 153 de este archivo</small>
                </div>
                <a href="?send=1" class="btn">📧 Enviar Email de Prueba</a>
            </div>
            <?php
        }
        ?>
        
        <?php
        // =====================================
        // SECCIÓN 5: Recomendaciones
        // =====================================
        ?>
        <div class="section">
            <h2>5. Recomendaciones</h2>
            
            <h3>Para Gmail:</h3>
            <ul>
                <li>✅ Usa puerto 587 con TLS o puerto 465 con SSL</li>
                <li>✅ Genera una "Contraseña de Aplicación" en tu cuenta de Google</li>
                <li>✅ NO uses tu contraseña normal de Gmail</li>
                <li>✅ Activa la verificación en 2 pasos primero</li>
            </ul>
            
            <h3>Para Desarrollo:</h3>
            <ul>
                <li>🔧 Usa <a href="https://mailtrap.io" target="_blank">Mailtrap.io</a> - Captura emails sin enviarlos</li>
                <li>🔧 O configura sendmail en XAMPP</li>
            </ul>
            
            <h3>Archivos a revisar:</h3>
            <ul>
                <li>📄 <code>application/config/email.php</code> - Configuración de email</li>
                <li>📄 <code>application/logs/</code> - Logs de errores</li>
                <li>📄 <code>C:\xampp\apache\logs\error.log</code> - Logs de Apache</li>
            </ul>
        </div>
        
        <div class="section">
            <h2>⚠️ Importante</h2>
            <div class="error">
                <strong>¡Elimina este archivo después de usarlo!</strong><br>
                Este script no debe estar en producción por razones de seguridad.
            </div>
        </div>
    </div>
</body>
</html>