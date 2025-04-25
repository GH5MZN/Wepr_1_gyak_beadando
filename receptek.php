<?php
session_start();

try {
    // Kapcsolódás az adatbázishoz
    $dbh = new PDO('mysql:host=localhost;dbname=receptek', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

    // Receptek lekérdezése
    $sqlSelect = "SELECT * FROM receptek";
    $receptek = $dbh->query($sqlSelect)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hiba: " . $e->getMessage();
    $receptek = []; // Üres tömb, ha hiba történik
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptek</title>
    <link rel="stylesheet" href="style_recept.css">
</head>
<body>
    <header>
        <h1>Receptek</h1>
        <nav>
            <ul class="menu">
                <li><a href="gyak_index.html">Főoldal</a></li>
                <li><a href="receptek.php" class="active">Receptek</a></li>
                <li><a href="kapcsolat.html">Kapcsolat</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Receptek listája -->
        <section class="receptek-lista">
            <h2>Receptek listája</h2>
            <div class="receptek">
                <?php if (empty($receptek)): ?>
                    <!-- Ha nincs recept -->
                    <p>Itt még nincs semmi, légy te az első recept feltöltő!</p>
                <?php else: ?>
                    <!-- Ha vannak receptek -->
                    <?php foreach ($receptek as $recept): ?>
                        <div class="recept">
                            <h3><a href="recept.php?id=<?= $recept['id'] ?>"><?= htmlspecialchars($recept['nev']) ?></a></h3>
                            <p><?= nl2br(htmlspecialchars($recept['leiras'])) ?></p>
                            <?php if ($recept['kep']): ?>
                                <img src="<?= htmlspecialchars($recept['kep']) ?>" alt="<?= htmlspecialchars($recept['nev']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Új recept feltöltése -->
        <aside class="uj-recept">
            <h2>Új recept feltöltése</h2>
            <button onclick="window.location.href='feltoltes.php'">Új recept hozzáadása</button>
        </aside>
    </div>

    <footer>
    <p>Készítette: Barcza Mónika és Jagicza Bence István, Neptun kód: VCVIAB és GH5MZN</p>
    </footer>
</body>
</html>