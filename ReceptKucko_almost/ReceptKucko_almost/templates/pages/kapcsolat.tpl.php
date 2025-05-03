<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="kapcsolat-container">
    <!-- Térkép -->
    <div class="kapcsolat-info">
        <h2>Adatok:</h2>
        <p>Ügyvezető: <strong>Gipsz Jakab</strong></p>
        <p>E-mail: <strong>Gipsz.Jakab@kuckos.hu</strong></p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10905.999545842029!2d19.6574799003615!3d46.89288649670877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4743dbe3bf8485cb%3A0xc114afde97ee58a0!2sBistorant%20Kecskem%C3%A9t!5e0!3m2!1shu!2hu!4v1745749048252!5m2!1shu!2hu"
            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <!-- Űrlap -->
    <div class="kapcsolat-form">
        <h2>Kapcsolatfelvétel</h2>
        <form id="kapcsolatForm" method="post" action="index.php?page=kapcsolat">
            <label for="email">E-mail címed:</label><br>
            <input type="email" id="email" name="email" style="width: 100%;"><br><br>

            <label for="uzenet">Üzenet az oldal üzemeltetőinek:</label><br>
            <textarea id="uzenet" name="uzenet" rows="5" style="width: 100%; resize: none;"></textarea><br><br>

            <button type="submit" style="float: right; padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">Küldés</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('kapcsolatForm').addEventListener('submit', function (e) {
        const uzenet = document.getElementById('uzenet').value.trim();
        const email = document.getElementById('email').value.trim();

        if (!email || !uzenet) {
            e.preventDefault();
            alert('Kérjük, töltsd ki az e-mail címed és az üzenetet is!');
        }
    });
</script>
