<?php 
if (!empty($errors)) {
    echo '<div style="background-color: red">';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>'; // Sécuriser les erreurs
    }
    echo '</ul>';
    echo '</div>';
}
?>

<form method="POST" action="/se-connecter">
    <input name="email" type="email" placeholder="votre email"><br>
    <input name="pwd" type="password" placeholder="votre mot de passe"><br>
    <input type="submit" value="Se connecter"><br>
</form>
<p>
<b>
Vous n'avez pas de compte ? Inscrivez-vous !
</b>
<form method="GET" action="/user/add">
    <button type="submit">Créer un compte</button>
</form>