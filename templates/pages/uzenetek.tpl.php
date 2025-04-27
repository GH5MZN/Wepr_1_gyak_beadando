<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Kapcsolódás az adatbázishoz
    $dbh = new PDO('mysql:host=localhost;dbname=receptek_users', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

    // Táblák létrehozása, ha nem léteznek
    $dbh->exec("
        CREATE TABLE IF NOT EXISTS felhasznalok (
            id INT AUTO_INCREMENT PRIMARY KEY,
            bejelentkezes VARCHAR(50) NOT NULL,
            jelszo VARCHAR(255) NOT NULL,
            csaladi_nev VARCHAR(50) NOT NULL,
            uto_nev VARCHAR(50) NOT NULL
        )
    ");

    $dbh->exec("
        CREATE TABLE IF NOT EXISTS uzenetek (
            id INT AUTO_INCREMENT PRIMARY KEY,
            uzenet TEXT NOT NULL,
            kuldes_ideje DATETIME NOT NULL,
            felhasznalo_id INT NULL,
            FOREIGN KEY (felhasznalo_id) REFERENCES felhasznalok(id) ON DELETE SET NULL
        )
    ");

    // Üzenet mentése az adatbázisba
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uzenet']) && !empty(trim($_POST['uzenet']))) {
        $uzenet = trim($_POST['uzenet']);
        $felhasznalo_id = isset($_SESSION['login']) ? $_SESSION['felhasznalo_id'] : null;

        $sql = "INSERT INTO uzenetek (uzenet, kuldes_ideje, felhasznalo_id) VALUES (:uzenet, NOW(), :felhasznalo_id)";
        $sth = $dbh->prepare($sql);
        $sth->execute([
            ':uzenet' => $uzenet,
            ':felhasznalo_id' => $felhasznalo_id
        ]);

        // JavaScript alert a sikeres üzenetküldés után
        echo "<script>alert('Üzenet sikeresen elküldve!');</script>";
    }

    // Üzenetek lekérdezése fordított időrendben
    $sql = "SELECT uzenetek.uzenet, uzenetek.kuldes_ideje, 
                   IFNULL(felhasznalok.bejelentkezes, 'Vendég') AS kuldo
            FROM uzenetek
            LEFT JOIN felhasznalok ON uzenetek.felhasznalo_id = felhasznalok.id
            ORDER BY uzenetek.kuldes_ideje DESC";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $uzenetek = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Hiba történt az adatbázis műveletek során: " . htmlspecialchars($e->getMessage()) . "</p>";
    return;
}
?>

<h2>Üzenetek</h2>

<!-- Üzenetküldő űrlap -->
<form method="post" action="">
    <label for="uzenet">Üzenet:</label><br>
    <textarea id="uzenet" name="uzenet" rows="4" cols="50" required></textarea><br>
    <button type="submit">Küldés</button>
</form>

<!-- Üzenetek megjelenítése -->
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Küldő</th>
            <th>Üzenet</th>
            <th>Küldés ideje</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($uzenetek)) { ?>
            <?php foreach ($uzenetek as $uzenet) { ?>
                <tr>
                    <td><?= htmlspecialchars($uzenet['kuldo']) ?></td>
                    <td><?= htmlspecialchars($uzenet['uzenet']) ?></td>
                    <td><?= htmlspecialchars($uzenet['kuldes_ideje']) ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="3">Nincs megjeleníthető üzenet.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>