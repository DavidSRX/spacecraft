

<!DOCTYPE html>
<html>
<head>
<title>Spacecraft Editor</title>
<style>
body{background:#050914;color:#fff;font-family:Orbitron}
.form-box{width:400px;margin:50px auto;padding:20px;background:#0b1220;border-radius:10px}
input,select{width:100%;padding:10px;margin:10px 0;border-radius:6px;border:none}
button{background:#00f7ff;border:none;padding:10px;width:100%;border-radius:6px}
/* Fondo espacial animado */
        .space-background {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at bottom, #1b2735 0%, #090a0f 100%);
            z-index: 0;
        }

        /* Estrellas */
        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 3s infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        /* Planetas decorativos */
        .planet {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
        }

        .planet-1 {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            top: 10%;
            left: 5%;
            box-shadow: 0 0 60px rgba(102, 126, 234, 0.4);
            animation: float 20s infinite ease-in-out;
        }

        .planet-2 {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            bottom: 15%;
            right: 10%;
            box-shadow: 0 0 40px rgba(245, 87, 108, 0.4);
            animation: float 15s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }
</style>
</head>
<body>
<div class="space-background">
    <div class="stars" id="stars"></div>
    <div class="planet planet-1"></div>
    <div class="planet planet-2"></div>
</div>
<div class="form-box">
<h2><center><?= isset($ship) ? 'Edit Spacecraft' : 'Create Spacecraft' ?></center></h2>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Name" value="<?= $ship->name ?? '' ?>">
    <input type="text" name="model" placeholder="Model" value="<?= $ship->model ?? '' ?>">
    <select name="nationality" class="form-select">
        <option value="MX">🇲🇽 México</option>
        <option value="US">🇺🇸 USA</option>
        <option value="JP">🇯🇵 Japón</option>
        <option value="DE">🇩🇪 Alemania</option>
    </select>
    <input type="number" name="build_year" placeholder="Build year" value="<?= $ship->build_year ?? '' ?>">
    <input type="text" name="price" placeholder="Price in MXN" value="<?= $ship->price ?? '' ?>">

    <select name="status">
        <option value="Active">Active</option>
        <option value="Docked">Docked</option>
        <option value="Maintenance">Maintenance</option>
    </select>
    <input type="file" name="image">
    <button type="submit">Save</button>
</form>

</div>
<script>

document.addEventListener('DOMContentLoaded', function() {
    createStars();
});

function createStars() {
    const starsContainer = document.getElementById('stars');
    const numberOfStars = 200;

    for (let i = 0; i < numberOfStars; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        
        const size = Math.random() * 3;
        star.style.width = size + 'px';
        star.style.height = size + 'px';
        star.style.left = Math.random() * 100 + '%';
        star.style.top = Math.random() * 100 + '%';
        star.style.animationDelay = Math.random() * 3 + 's';
        
        starsContainer.appendChild(star);
    }
}
</script>
</body>
</html>
