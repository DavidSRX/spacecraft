
<!DOCTYPE html>
<html>
<head>
    <title>Fleet Command</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">


    <style>

        body{
            margin:0;
            font-family:'Orbitron',sans-serif;
            background: radial-gradient(circle at top,#0b0f1a,#000);
            color:#fff;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:15px 30px;
            background:#050914;
            box-shadow:0 0 20px #00f7ff44;
        }

        .title{
            font-size:22px;
            letter-spacing:2px;
        }

        .add-btn{
            background:#00f7ff;
            color:#000;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
            font-weight:600;
            transition:.3s;
        }

        .add-btn:hover{
            box-shadow:0 0 15px #00f7ff;
        }

        .search-box{
            text-align:center;
            margin:25px;
        }

        .search-box input{
            width:50%;
            padding:12px;
            border-radius:25px;
            border:none;
            font-size:14px;
        }

        .card{
            background:linear-gradient(145deg,#0f172a,#0b1220);
            border-radius:14px;
            padding:15px;
            border:1px solid #1f2937;
            box-shadow:0 0 12px rgba(0,255,255,.15);
            transition:.3s;
            position:relative;
            cursor: pointer;
        }

        .card:hover{
            transform:translateY(-6px);
            box-shadow:0 0 25px rgba(0,255,255,.5);
        }

        .card img{
            width:100%;
            height:150px;
            object-fit:cover;
            border-radius:10px;
        }

        .ship-name{
            font-size:18px;
            margin:10px 0 5px;
        }

        .ship-model{
            font-size:13px;
            opacity:.7;
        }

        .status{
            margin-top:8px;
            font-size:12px;
            padding:4px 8px;
            border-radius:20px;
            display:inline-block;
        }

        .ship-nationality {
            font-family: "Segoe UI Emoji", "Apple Color Emoji", system-ui;
        }


        .Active{ background:#00ff99; color:#000;}
        .Docked{ background:#ffc107; color:#000;}
        .Maintenance{ background:#ff4d4d;}

        .edit-link{
            position:absolute;
            top:10px;
            right:12px;
            font-size:12px;
            color:#00f7ff;
            text-decoration:none;
        }

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

<div class="topbar">
    <div class="title">Dashboard</div>
    <a href="<?= site_url('dashboard/create') ?>" class="add-btn">+ Add Spacecraft</a>
    <a href="<?= site_url('users/logout') ?>" class="add-btn">Log Out</a>

</div>

<div class="container mt-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label text-info">Model</label>
            <input type="text" name="search" id="search" class="form-control bg-dark text-light border-info" placeholder="Search by model">
        </div>

        <div class="col-md-3">
            <label class="form-label text-info">Spacecraft Name</label>
            <input type="text" name="nameFilter" id="nameFilter" class="form-control bg-dark text-light border-info" placeholder="Filter by name">
        </div>

        <div class="col-md-3">
            <label class="form-label text-info">Build Year</label>
            <input type="text" name="buildFilter" id="buildFilter" class="form-control bg-dark text-light border-info" placeholder="Filter by nationality">
        </div>

        <div class="col-md-3">
            <label class="form-label text-info">Nationality</label>
            <input type="text" name="nationalityFilter" id="nationalityFilter" class="form-control bg-dark text-light border-info" placeholder="Filter by nationality">
        </div>

    </div>
</div>

<div class="container mt-5">
    <div class="row g-4" id="shipsContainer">
        <?php foreach($spacecrafts as $ship): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card bg-dark text-light border-info h-100 shadow"><a href="<?= base_url('dashboard/edit_dashboard/'.$ship->id) ?>" class="btn btn-primary">Edit</a>
                    <img src="<?= base_url('uploads/'.$ship->image) ?>" class="card-img-top" alt="">
                    <div class="card-body text-center">
                        <h5 class="ship-name">Name: <?= $ship->name ?></h5>
                        <div class="ship-model">Model: <?= $ship->model ?></div>
                        <div class="ship-nationality">
                            Nationality: <?= strtolower($ship->nationality)?> <img src="https://flagcdn.com/w40/<?= strtolower($ship->nationality) ?>.png" style="height: 20px !important;width: 20px !important;">
                        </div>                        
                        <div class="ship-build_year">Build year: <?= $ship->build_year ?></div>
                        <div class="ship-price">Price (in MXN): <?= $ship->price ?></div>
                        <span class="badge bg-info status <?= $ship->status ?>">Status: <?= $ship->status ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    createStars();

  let timer;
$('#search, #nameFilter, #buildFilter, #nationalityFilter').on('keyup change', function(){
    clearTimeout(timer);
    timer = setTimeout(runSearch, 300);
});

function runSearch(){
    $.post("<?= site_url('dashboard/search') ?>", {
        nationality: $('#nationalityFilter').val(),
        name: $('#nameFilter').val(),
        build: $('#buildFilter').val(),
        model: $('#search').val(),
    }, function(data){
        let ships = JSON.parse(data);
        let html = '';

        ships.forEach(ship => {
            html += `
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card bg-dark text-light border-info shadow">
                    <a href="dashboard/edit/${ship.id}" class="edit-link">Edit</a>
                    <img src="uploads/${ship.image}" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="ship-name">Name: ${ship.name}</h5>
                        <div class="ship-model">Model: ${ship.model}</div>

                        <div class="ship-nationality">
                            Nationality: ${ship.nationality.toLowerCase()}
                            <img src="https://flagcdn.com/w40/${ship.nationality.toLowerCase()}.png"
                                style="height:20px;width:20px;">
                        </div>

                        <div class="ship-build_year">Build Year: ${ship.build_year}</div>
                        <div class="ship-price">Price (in MXN): ${ship.price}</div>
                        <span class="badge bg-info status">Status: ${ship.status}</span>
                    </div>
                </div>
            </div>`;
        });


        $('#shipsContainer').html(html);
    });
}


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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
