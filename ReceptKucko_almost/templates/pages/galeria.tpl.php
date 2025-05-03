<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Képfeltöltés – csak ha POST és be van jelentkezve
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['felhasznalo_id'])) {
    try {
        $dbh = new PDO(
            'mysql:host=127.0.0.1;dbname=barcza17;charset=utf8',
            'barcza17',
            'Nethely_123',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        if (isset($_FILES['kep']) && $_FILES['kep']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['kep']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['kep']['tmp_name'], $filePath)) {
                $sql = "INSERT INTO kepek (fajlnev) VALUES (:fajlnev)";
                $sth = $dbh->prepare($sql);
                $sth->execute([':fajlnev' => $fileName]);
            } else {
                echo "<p>Hiba történt a kép feltöltése során.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "<p>Hiba történt az adatbázis műveletek során: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Képek lekérdezése
$kepek = [];
try {
    $dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=barcza17;charset=utf8',
        'barcza17',
        'Nethely_123',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $sql = "SELECT * FROM kepek ORDER BY id DESC";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $kepek = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Hiba történt az adatbázis műveletek során: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<h1>Galéria</h1>

<?php if (isset($_SESSION['felhasznalo_id'])): ?>
    <form method="post" action="" enctype="multipart/form-data" class="kapcsolat-form">
        <input type="file" name="kep" accept="image/*" required>
        <button type="submit">Feltöltés</button>
    </form>
<?php else: ?>
    <p style="color: red;">A kép feltöltéséhez jelentkezz be!</p>
<?php endif; ?>


<div class="kepek-container">
    <?php if (!empty($kepek)): ?>
        <?php foreach ($kepek as $kep): ?>
            <div class="kep">
                <img src="/uploads/<?= htmlspecialchars($kep['fajlnev']) ?>" alt="Kép">
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nincs feltöltött kép.</p>
    <?php endif; ?>
</div>
