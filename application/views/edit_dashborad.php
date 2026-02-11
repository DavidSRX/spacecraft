<?php

function fake_metric($min, $max, $decimals = 2) {
    return round(mt_rand($min * 100, $max * 100) / 100, $decimals);
}

$cabin_temp = isset($ship->cabin_temp) ? $ship->cabin_temp : fake_metric(18, 27);
$pressure   = isset($ship->pressure)   ? $ship->pressure   : fake_metric(13.5, 15.2);
$co2        = isset($ship->co2)        ? $ship->co2        : fake_metric(0.02, 0.09);
$loop_a     = isset($ship->loop_a)     ? $ship->loop_a     : fake_metric(24, 32);
$loop_b     = isset($ship->loop_b)     ? $ship->loop_b     : fake_metric(19, 28);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Overview</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/gauge.css') ?>">
</head>

<body class="telemetry-body">

<div class="space-background">
    <div class="stars" id="stars"></div>
    <div class="planet planet-1"></div>
    <div class="planet planet-2"></div>
</div>

<div class="top-title">
    <h1>VEHICLE OVERVIEW</h1>
    <h2><?= $ship->name ?></h2>
</div>

<div class="dashboard-grid">

    <!-- ===== ROW 1 ===== -->
    <div class="systems-panel">
        <div class="system ok">ALL SYSTEMS CHECK <br> <span>Normal</span></div>
        <div class="system warn">RENDEZVOUS BURN SLOW<br>  <span>Awaiting</span></div>
        <div class="system ok">THERMAL SHIELD <br> <span>Applied</span></div>
        <div class="system ok">BURN GO/NO-GO <br> <span>Normal</span></div>
        <div class="system warn">POWER COMPLETION <br> <span>Awaiting</span></div>
        <div class="system ok">STATION DECK CHECK <br> <span>Normal</span></div>

    </div>

    <?php function gauge($label,$value,$unit,$percent){ ?>
        <div class="gauge" data-min="18" data-max="27">
            <svg viewBox="0 0 36 36">
                <path class="circle-bg"
                    d="M18 2.0845
                       a 15.9155 15.9155 0 0 1 0 31.831
                       a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <path class="circle progress-circle"
                    data-percent="<?= $percent ?>"
                    stroke-dasharray="0, 100"
                    d="M18 2.0845
                       a 15.9155 15.9155 0 0 1 0 31.831
                       a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <text x="18" y="16" class="gauge-value"><?= $value ?></text>
                <text x="18" y="21" class="gauge-unit"><?= $unit ?></text>
            </svg>
            <div class="gauge-label"><?= $label ?></div>
        </div>
        <?php } ?>

    <div class="gauges-panel">
        <?php gauge("Cabin Temp", $cabin_temp, "°C", 65); ?>
        <?php gauge("Cabin Pressure", $pressure, "psia", 70); ?>
        <?php gauge("CO2", $co2, "mmHg", 20); ?>
        <?php gauge("Loop A", $loop_a, "°C", 55); ?>
        <?php gauge("Loop B", $loop_b, "°C", 50); ?>
    </div>

    <div class="telemetry-panel">
        <div class="telemetry-bar">
            <span>Velocity</span>
            <div class="bar"><div class="fill" data-value="80"></div></div>
            <span><?= 300 ?> km/s</span>
        </div>
        <div class="telemetry-bar">
            <span>Altitude</span>
            <div class="bar"><div class="fill" data-value="60"></div></div>
            <span><?= 3000 ?> km</span>
        </div>
        <div class="telemetry-bar">
            <span>Inclination</span>
            <div class="bar"><div class="fill" data-value="70"></div></div>
            <span><?= 60 ?>°</span>
        </div>

        <div class="ship-info">
        <div><strong>Model:</strong> <?= htmlspecialchars($ship->model ?? '—') ?></div>
        <div><strong>Build Year:</strong> <?= htmlspecialchars($ship->build_year ?? '—') ?></div>
        <div class="flag-row">
            <strong>Nationality:</strong>
            <?php if(!empty($ship->nationality)): ?>
                <img src="https://flagcdn.com/w40/<?= strtolower($ship->nationality) ?>.png" alt="flag">
            <?php else: ?> — <?php endif; ?>
        </div>
        <div><strong>Price:</strong> $<?= number_format((float)($ship->price ?? 0), 2) ?></div>
    </div>

    </div>

    <!-- ===== ROW 2 ===== -->
    <div class="ship-center">
        <div class="ship-container">
            <img src="<?= base_url('uploads/'.$ship->image) ?>" class="ship-model">
            <div class="engine-glow"></div>
        </div> 
    </div>

    <!-- ===== ROW 3 ===== -->
    <div class="edit-panel">

    <h3 class="panel-title text-center" style="text-align:center">SHIP DATA</h3>
    
    <div class="divider"></div>

    <!-- EDIT FORM -->
    <form method="post" enctype="multipart/form-data" class="edit-form" action="<?= site_url('dashboard/edit/'.$ship->id) ?>">
            <input type="hidden" name="id" value="<?= $ship->id ?>">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($ship->name ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Model</label>
            <input type="text" name="model" value="<?= htmlspecialchars($ship->model ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Nationality</label>
            <select name="nationality">
                <?php
                $countries = ['MX'=>'México','US'=>'USA','JP'=>'Japón','DE'=>'Alemania'];
                foreach($countries as $code=>$label):
                ?>
                <option value="<?= $code ?>" <?= ($ship->nationality ?? '') == $code ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Build Year</label>
                <input type="number" name="build_year" min="1950" max="<?= date('Y') ?>" value="<?= htmlspecialchars($ship->build_year ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Price (MXN)</label>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($ship->price ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <?php
                $statuses = ['Active','Docked','Maintenance'];
                foreach($statuses as $s):
                ?>
                <option value="<?= $s ?>" <?= ($ship->status ?? '') == $s ? 'selected' : '' ?>>
                    <?= $s ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-save">SAVE CHANGES</button>

    </form>

</div>


</div>


<script>
document.addEventListener("DOMContentLoaded", () => {

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

    // Gauge animation
    document.querySelectorAll(".progress-circle").forEach(circle => {
        let target = circle.dataset.percent;
        let current = 0;
        let anim = setInterval(() => {
            current += 2;
            if(current >= target){ current = target; clearInterval(anim); }
            circle.setAttribute("stroke-dasharray", current + ", 100");
        }, 20);
    });

    // Bars animation
    document.querySelectorAll(".fill").forEach(bar => {
        setTimeout(()=> bar.style.width = bar.dataset.value + "%",300);
    });

    setInterval(() => {
    document.querySelectorAll('.gauge-value').forEach(el => {
        let min = parseFloat(el.dataset.min);
        let max = parseFloat(el.dataset.max);
        let val = (Math.random() * (max - min) + min).toFixed(2);
        el.innerText = val;
    });
}, 3000);

    
});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector(".edit-panel form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const btn = form.querySelector("button[type='submit']");
        btn.disabled = true;
        btn.innerText = "Saving...";

        const formData = new FormData(form);

        try {
            const response = await fetch('/dashboard/edit', {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.status === "success") {
                showToast("Ship updated successfully", "success");
                location.href = "/dashboard";
            } else {
                showToast(result.message || "Error saving", "error");
            }

        } catch (err) {
            showToast("Server error", "error");
            console.error(err);
        }

        btn.disabled = false;
        btn.innerText = "Save";
    });

});


/* ---------- UI FEEDBACK ---------- */

function showToast(msg, type) {
    let toast = document.createElement("div");
    toast.className = "toast " + type;
    toast.innerText = msg;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add("show"), 50);
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}
</script>


</body>
</html>
