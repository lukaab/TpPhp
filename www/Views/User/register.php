<?php 
if (!empty($errors)) {
    echo '<div style="background-color: red">';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>'; // Sécurisation contre les XSS
    }
    echo '</ul>';
    echo '</div>';
}
?>


<form method="POST" action="/user/add">
    <input name="firstname" type="text" placeholder="votre prénom"><br>
    <input name="lastname" type="text" placeholder="votre nom"><br>
    <input name="email" type="email" placeholder="votre email"><br>
    <input name="country" type="text" placeholder="votre nationalité"><br>
    <input name="pwd" type="password" placeholder="votre mot de passe"><br>
    <input name="pwdconf" type="password" placeholder="confirmation"><br>
    <input type="submit" value="S'inscrire"><br>
</form>