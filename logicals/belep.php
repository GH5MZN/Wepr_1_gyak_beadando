<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bejelentkezés ellenőrzése
if (isset($_POST['bejelentkezes']) && isset($_POST['jelszo'])) {
    try {
        // Kapcsolódás
        $dbh = new PDO('mysql:host=localhost;dbname=receptek_users', 'root', '',
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');
        
        // Felhasználó keresése
        $sqlSelect = "SELECT id, csaladi_nev, uto_nev FROM felhasznalok WHERE bejelentkezes = :bejelentkezes AND jelszo = sha1(:jelszo)";
        $sth = $dbh->prepare($sqlSelect);
        $sth->execute(array(':bejelentkezes' => $_POST['bejelentkezes'], ':jelszo' => $_POST['jelszo']));
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Sikeres bejelentkezés
            $_SESSION['csn'] = $row['csaladi_nev'];
            $_SESSION['un'] = $row['uto_nev'];
            $_SESSION['login'] = $_POST['bejelentkezes'];
            // Ellenőrzéshez: írd ki a munkamenet változókat
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    echo "<pre>";
print_r($_POST);
echo "</pre>";

         //   header("Location: ../index.php"); // Átirányítás a főoldalra
         //   exit();
        } else {
            // Sikertelen bejelentkezés
            echo "<p>Hibás felhasználónév vagy jelszó!</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Hiba történt: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    header("Location: ../index.php"); // Ha nincs POST adat, visszairányítjuk a főoldalra
    exit();
}
?>