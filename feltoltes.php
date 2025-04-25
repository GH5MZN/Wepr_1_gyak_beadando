<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új recept feltöltése</title>
    <link rel="stylesheet" href="style_recept.css">
</head>
<body>
    <header>
        <h1>Új recept feltöltése</h1>
        <nav>
            <ul class="menu">
                <li><a href="gyak_index.html">Főoldal</a></li>
                <li><a href="receptek.php">Receptek</a></li>
                <li><a href="kapcsolat.html">Kapcsolat</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <form action="receptek.php" method="POST" enctype="multipart/form-data">
            <label for="nev">Recept neve:</label><br>
            <input type="text" id="nev" name="nev" required><br><br>

            <label for="leiras">Leírás:</label><br>
            <textarea id="leiras" name="leiras" rows="5" required></textarea><br><br>

            <label for="kep">Kép feltöltése:</label><br>
            <input type="file" id="kep" name="kep" accept="image/*"><br><br>

            <input type="submit" value="Feltöltés">
        </form>
    </div>

    <footer>
        <p>Készítette: Jagicza Bence István, Neptun kód: GH5MZN</p>
    </footer>
</body>
</html>