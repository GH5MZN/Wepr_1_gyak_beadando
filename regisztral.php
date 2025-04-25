<?php
if (isset($_POST['felhasznalo']) && isset($_POST['jelszo']) && isset($_POST['vezeteknev']) && isset($_POST['utonev'])) {
    try {
        // Kapcsolódás az adatbázishoz (adatbázis nélkül)
        $dbh = new PDO('mysql:host=localhost', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

        // Ellenőrizzük, hogy az adatbázis létezik-e, ha nem, akkor létrehozzuk
        $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS receptek_users CHARACTER SET utf8 COLLATE utf8_hungarian_ci";
        $dbh->exec($sqlCreateDB);

        // Új kapcsolat az adatbázissal
        $dbh = new PDO('mysql:host=localhost;dbname=receptek_users', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

        // Ellenőrizzük, hogy a `felhasznalok` tábla létezik-e, ha nem, akkor létrehozzuk
        $sqlCreateTable = "
            CREATE TABLE IF NOT EXISTS felhasznalok (
                id INT AUTO_INCREMENT PRIMARY KEY,
                csaladi_nev VARCHAR(255) NOT NULL,
                uto_nev VARCHAR(255) NOT NULL,
                bejelentkezes VARCHAR(255) NOT NULL UNIQUE,
                jelszo CHAR(40) NOT NULL
            )
        ";
        $dbh->exec($sqlCreateTable);

        // Létezik már a felhasználói név?
        $sqlSelect = "SELECT id FROM felhasznalok WHERE bejelentkezes = :bejelentkezes";
        $sth = $dbh->prepare($sqlSelect);
        $sth->execute([':bejelentkezes' => $_POST['felhasznalo']]);
        if ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $uzenet = "A felhasználói név már foglalt!";
            $ujra = true;
        } else {
            // Ha nem létezik, akkor regisztráljuk
            $sqlInsert = "INSERT INTO felhasznalok (id, csaladi_nev, uto_nev, bejelentkezes, jelszo)
                          VALUES (0, :csaladinev, :utonev, :bejelentkezes, :jelszo)";
            $stmt = $dbh->prepare($sqlInsert); 
            $stmt->execute([
                ':csaladinev' => $_POST['vezeteknev'],
                ':utonev' => $_POST['utonev'],
                ':bejelentkezes' => $_POST['felhasznalo'],
                ':jelszo' => sha1($_POST['jelszo'])
            ]); 
            if ($count = $stmt->rowCount()) {
                $newid = $dbh->lastInsertId();
                $uzenet = "A regisztrációja sikeres.<br>Azonosítója: {$newid}";                     
                $ujra = false;
            } else {
                $uzenet = "A regisztráció nem sikerült.";
                $ujra = true;
            }
        }
    } catch (PDOException $e) {
        $uzenet = "Hiba: " . $e->getMessage();
        $ujra = true;
    }      
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style_gyakmain.css">
</head>
<body>
    <div class="container">
        <h1>Regisztráció</h1>
        <p><?= isset($uzenet) ? $uzenet : "Hiba történt a regisztráció során." ?></p>
        <button onclick="window.location.href='gyak_index.html'">Visszatérés a főoldalra</button>
    </div>
</body>
</html>