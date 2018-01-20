<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans un lieu on le redirige vers la carte du monde
if ($characterplaceId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
?>

<p><img src="<?php echo $placePicture ?>" height="100" width="100"></p>

<?php echo $placeName ?><br />
<?php echo $placeDescription ?><br /><br />
<a href="../../modules/dungeon/index.php">Donjon</a><br>
<a href="../../modules/inn/index.php">Auberge</a><br>
<a href="../../modules/shops/index.php">Magasin(s)</a>

<hr>

<form method="POST" action="leavePlace.php">
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" name="leave" class="btn btn-default form-control" value="Quitter le lieu">
</form>

<?php require_once("../../html/footer.php"); ?>