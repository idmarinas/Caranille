<?php
require_once("../../kernel/kernel.php");
require_once("../../html/header.php");
?>

<form method="POST" action="verifyRegistration.php">
    Adresse Email : <input type="email" class="form-control" name="accountEmail" required>
    Code reçu : <input type="number" class="form-control" name="codeAccountVerification" required>
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" class="btn btn-default form-control" name="register" value="Je valide mon inscription">
</form>

<?php require_once("../../html/footer.php"); ?>