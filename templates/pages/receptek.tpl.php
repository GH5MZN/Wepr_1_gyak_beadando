<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}?>

<h1>Receptjeink</h1>

<?php if (empty($receptek)) { ?>
    <p>Még nincs recept feltöltve, légy te az első!</p>
<?php } else { ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Kép</th>
                <th>Név</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($receptek as $recept) { ?>
                <tr>
                    <td>
                        <?php if ($recept['kep']) { ?>
                            <img src="<?= htmlspecialchars($recept['kep']) ?>" alt="<?= htmlspecialchars($recept['nev']) ?>" width="100">
                        <?php } ?>
                    </td>
                    <td><a href="?recept_id=<?= $recept['id'] ?>"><?= htmlspecialchars($recept['nev']) ?></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<!-- Gomb a lebegő ablak megnyitásához -->
<button class="rounded" id="openModal">Új recept hozzáadása</button>

<!-- Lebegő ablak -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Új recept hozzáadása</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="nev">Név:</label><br>
            <input type="text" id="nev" name="nev" required><br><br>

            <label for="hozzavalok">Hozzávalók:</label><br>
            <textarea id="hozzavalok" name="hozzavalok" rows="4" cols="50" required></textarea><br><br>

            <label for="lepesek">Lépések:</label><br>
            <textarea id="lepesek" name="lepesek" rows="6" cols="50" required></textarea><br><br>

            <label for="kep">Kép feltöltése:</label><br>
            <input type="file" id="kep" name="kep" accept="image/*"><br><br>

            <button type="submit" class="rounded">Mentés</button>
        </form>
    </div>
</div>
<script>
    // Modal megnyitása
    document.getElementById('openModal').onclick = function() {
        document.getElementById('myModal').style.display = 'block';
    };

    // Modal bezárása
    document.getElementById('closeModal').onclick = function() {
        document.getElementById('myModal').style.display = 'none';
    };

    // Modal bezárása, ha a felhasználó a modalon kívülre kattint
    window.onclick = function(event) {
        if (event.target == document.getElementById('myModal')) {
            document.getElementById('myModal').style.display = 'none';
        }
    };
</script>