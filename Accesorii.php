<?php
require 'db_connect.php';
$pret_maxim = isset($_GET['pret']) ? $_GET['pret'] : '';
$sql = "SELECT * FROM Accesorii WHERE 1=1"; 
$params = [];
if (!empty($pret_maxim)) {
    $sql .= " AND Pret <= :pret";
    $params[':pret'] = $pret_maxim;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $accesorii = $stmt->fetchAll();
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
    <title>Accesorii Drone</title>
    <link rel="stylesheet" href="format.css">
</head>
<body>

    <div class="grid-container">
        <header class="site-header">
            <h1>Accesorii și Componente</h1>
        </header>

        <nav class="site-nav">
            <a href="Homepage.html">Homepage</a>
            <a href="Drone.php">Drone</a>
            <a href="Accesorii.php" class="active">Accesorii</a>
            <a href="Newsletter.html">Newsletter</a>
            <a href="Checkout.html">Checkout</a>
        </nav>

        <main class="site-main">
            
            <form method="GET" action="Accesorii.php" class="filters-container">
                
                <div class="filter-group">
                    <label for="pret">Buget Maxim (RON):</label>
                    <input type="number" name="pret" id="pret" placeholder="Ex: 300" value="<?php echo htmlspecialchars($pret_maxim); ?>">
                </div>

                <button type="submit" class="btn-filter">Aplică Filtre</button>
                
                <?php if(!empty($pret_maxim)): ?>
                    <a href="Accesorii.php" class="btn-reset">Resetează</a>
                <?php endif; ?>
            </form>

            <h2>Accesorii Disponibile <?php echo !empty($accesorii) ? "(" . count($accesorii) . ")" : ""; ?></h2>
            <p>Îmbunătățește performanța dronei tale.</p>
            
            <?php if (isset($error)): ?>
                <p style="color: red; text-align: center;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="products-flex-container">
                
                <?php if (!empty($accesorii)): ?>
                    <?php foreach ($accesorii as $accesoriu): ?>
                        
                        <?php 
                            $nume_fisier = genereazaNumeFisier($accesoriu['Nume']);
                            $cale_poza = "img/" . $nume_fisier;
                            $img_src = "https://placehold.co/300x200?text=" . urlencode($accesoriu['Nume']);
                            if (file_exists($cale_poza)) {
                                $img_src = $cale_poza;
                            }
                        ?>

                        <div class="product-card">
                            <img src="<?php echo $img_src; ?>" 
                                 alt="<?php echo htmlspecialchars($accesoriu['Nume']); ?>" 
                                 class="card-img"
                                 onerror="this.onerror=null; this.src='https://placehold.co/300x200?text=Fara+Poza';">
                            
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($accesoriu['Nume']); ?></h3>
                                <p class="descriere" style="min-height: 40px;">
                                    <?php echo htmlspecialchars($accesoriu['Descriere']); ?>
                                </p>
                                <p class="pret"><?php echo htmlspecialchars($accesoriu['Pret']); ?> RON</p>
                                
                                <button class="btn-cumpara" onclick="adaugaInCos('<?php echo htmlspecialchars($accesoriu['Nume']); ?>', '<?php echo $accesoriu['Pret']; ?>')">
                                    Adaugă în coș
                                </button>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="width: 100%; text-align: center; padding: 50px;">
                        Nu există accesorii în acest buget. <br>
                        <a href="Accesorii.php" style="color: #3f51b5;">Vezi toate accesoriile</a>
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