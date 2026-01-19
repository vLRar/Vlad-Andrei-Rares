<?php
require 'db_connect.php';

// --- LOGICA PENTRU FILTRARE ---
$marca_selectata = isset($_GET['marca']) ? $_GET['marca'] : '';
$pret_maxim = isset($_GET['pret']) ? $_GET['pret'] : '';

$sql = "SELECT * FROM Drone_Disponibile WHERE 1=1"; 
$params = [];

if (!empty($marca_selectata)) {
    $sql .= " AND Marca = :marca";
    $params[':marca'] = $marca_selectata;
}

if (!empty($pret_maxim)) {
    $sql .= " AND Pret <= :pret";
    $params[':pret'] = $pret_maxim;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $drone = $stmt->fetchAll();
    
    $stmtMarci = $pdo->query("SELECT DISTINCT Marca FROM Drone_Disponibile ORDER BY Marca");
    $toateMarcile = $stmtMarci->fetchAll(PDO::FETCH_COLUMN);

} catch (Exception $e) {
    $error = "Eroare: " . $e->getMessage();
}

function genereazaNumeFisier($nume) {
    $nume = strtolower(trim($nume)); 
    $nume = str_replace(' ', '_', $nume); 
    return $nume . ".jpg";
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drone - Magazin</title>
    <link rel="stylesheet" href="format.css"> 
</head>
<body>

    <div class="grid-container">
        
        <header class="site-header">
            <h1>Oferta Noastră de Drone</h1>
        </header>

        <nav class="site-nav">
            <a href="Homepage.html">Homepage</a>
            <a href="Drone.php" class="active">Drone</a>
            <a href="Accesorii.php">Accesorii</a>
            <a href="Newsletter.html">Newsletter</a>
            <a href="Checkout.html">Checkout</a>
        </nav>

        <main class="site-main">
            
            <form method="GET" action="Drone.php" class="filters-container">
                <div class="filter-group">
                    <label for="marca">Alege Marca:</label>
                    <select name="marca" id="marca">
                        <option value="">Toate Mărcile</option>
                        <?php foreach ($toateMarcile as $m): ?>
                            <option value="<?php echo htmlspecialchars($m); ?>" <?php if($marca_selectata == $m) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($m); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="pret">Preț Maxim (RON):</label>
                    <input type="number" name="pret" id="pret" placeholder="Ex: 5000" value="<?php echo htmlspecialchars($pret_maxim); ?>">
                </div>

                <button type="submit" class="btn-filter">Aplică Filtre</button>
                
                <?php if(!empty($marca_selectata) || !empty($pret_maxim)): ?>
                    <a href="Drone.php" class="btn-reset">Resetează</a>
                <?php endif; ?>
            </form>

            <h2>Modele Disponibile <?php echo !empty($drone) ? "(" . count($drone) . ")" : ""; ?></h2>

            <?php if (isset($error)): ?>
                <p style="color: red; text-align: center;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="products-flex-container">
                <?php if (!empty($drone)): ?>
                    <?php foreach ($drone as $drona): ?>
                        
                        <?php 
                            $nume_fisier = genereazaNumeFisier($drona['Nume']);
                            $cale_poza = "img/" . $nume_fisier;

                            if (file_exists($cale_poza)) {
                                $img_src = $cale_poza; 
                            } else {
                                $marca = strtolower(trim($drona['Marca']));
                                if (strpos($marca, 'dji') !== false && file_exists("img/dji.jpg")) {
                                    $img_src = "img/dji.jpg";
                                } elseif (strpos($marca, 'autel') !== false && file_exists("img/autel.jpg")) {
                                    $img_src = "img/autel.jpg";
                                } elseif (strpos($marca, 'skydio') !== false && file_exists("img/skydio.jpg")) {
                                    $img_src = "img/skydio.jpg";
                                } else {
                                    $img_src = "https://placehold.co/300x200?text=Fara+Poza";
                                }
                            }
                        ?>

                        <div class="product-card">
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($drona['Nume']); ?>" class="card-img">
                            
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($drona['Nume']); ?></h3>
                                <p class="descriere">
                                    <strong>Marca:</strong> <?php echo htmlspecialchars($drona['Marca']); ?><br>
                                    <strong>Serie:</strong> <?php echo htmlspecialchars($drona['Serie']); ?>
                                </p>
                                <p class="pret"><?php echo htmlspecialchars($drona['Pret']); ?> RON</p>
                                <button class="btn-cumpara" onclick="adaugaInCos('<?php echo htmlspecialchars($drona['Nume']); ?>', '<?php echo $drona['Pret']; ?>')">
                                    Adaugă în coș
                                </button>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="width: 100%; text-align: center; padding: 50px;">
                        Nu am găsit nicio dronă cu aceste filtre. <br>
                        <a href="Drone.php" style="color: #3f51b5;">Vezi toate dronele</a>
                    </p>
                <?php endif; ?>
            </div>
        </main>

        <footer class="site-footer">
            <p>&copy; 2026 Drone Management</p>
        </footer>
        <a href="#" id="backToTop">⬆ Sus</a>
    </div>

    <script>
        let mybutton = document.getElementById("backToTop");
        window.onscroll = function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        };
        mybutton.addEventListener("click", function(e) {
            e.preventDefault();
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    </script>
    
    <script>
        function adaugaInCos(nume, pret) {
            let cos = JSON.parse(localStorage.getItem('cosCumparaturi')) || [];
            cos.push({ nume: nume, pret: pret });
            localStorage.setItem('cosCumparaturi', JSON.stringify(cos));
            console.log(nume + " a fost adăugat în coș."); 
        }
    </script>
</body>
</html>