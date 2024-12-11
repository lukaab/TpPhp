<?php
session_start();

if (isset($_SESSION['firstname'])) {
    echo "<h1>Bienvenue, " . htmlspecialchars($_SESSION['firstname']) . " !</h1>";
    echo '<form method="POST" action="/se-deconnecter">
            <button type="submit">Se déconnecter</button>
          </form>';
} else {
    echo "<h1>Bienvenue ! Veuillez vous connecter.</h1>";
    echo '<form method="GET" action="/se-connecter">
    <button type="submit"> Se connecter </button>
    </form>';
}
?>