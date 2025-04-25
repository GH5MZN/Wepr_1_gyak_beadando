<?php
session_start();
var_dump($_POST); // Kiírja a POST adatok tartalmát

if (isset($_POST['username']) && isset($_POST['password'])) { // Kulcsok javítva
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=receptek_users', 'root', '', 
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');
        
        $sqlSelect = "SELECT id, csaladi_nev, uto_nev 
                      FROM felhasznalok 
                      WHERE bejelentkezes = :bejelentkezes AND jelszo = sha1(:jelszo)";
        $sth = $dbh->prepare($sqlSelect);
        $sth->execute(array(':bejelentkezes' => $_POST['username'], ':jelszo' => $_POST['password'])); // Kulcsok javítva
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Sikeres bejelentkezés
            $_SESSION['user'] = [
                'last_name' => $row['csaladi_nev'],
                'first_name' => $row['uto_nev'],
                'username' => $_POST['username']
            ];
            header("Location: gyak_index.html"); // Átirányítás a főoldalra
            exit();
        } else {
            echo "Hibás felhasználónév vagy jelszó!";
            exit();
        }
    } catch (PDOException $e) {
        echo "Adatbázis hiba: " . $e->getMessage();
        exit();
    }
} else {
    echo "Hiányzó POST adatok!";
    exit();
}
?>