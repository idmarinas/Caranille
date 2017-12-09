<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
?>

Que souhaitez-vous consulter ?<br />

<hr>

<form method="POST" action="../../modules/inventoryEquipments/index.php">
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" class="btn btn-default form-control" name="delete" value="Mes équipements">
</form>

<hr>

<form method="POST" action="../../modules/inventoryItems/index.php">
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" class="btn btn-default form-control" name="edit" value="Mes objets">
</form>

<hr>

<form method="POST" action="../../modules/inventoryParchments/index.php">
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" class="btn btn-default form-control" name="edit" value="Mes parchemins">
</form>

<?php require_once("../../html/footer.php"); ?>