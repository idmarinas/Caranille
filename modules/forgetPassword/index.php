<?php 
require_once("../../kernel/kernel.php");
require_once("../../html/header.php");
?>

<center>
<h3>Vous avez oublié votre mot de passe ?</h3>
</center>

<hr>
Réinitialiser le mot de passe grâce à votre question/réponse secrète
<hr>

<form method="POST" action="changePasswordVerify.php">
    Nom de votre personnage : <input type="text" class="form-control" name="accountPseudo" maxlength="15" required>
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" class="btn btn-default form-control" name="resetPassword" value="Recevoir un nouveau mot de passe">
</form>

<?php require_once("../../html/footer.php"); ?>