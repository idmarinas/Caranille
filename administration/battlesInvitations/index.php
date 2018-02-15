<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//On fait une recherche dans la base de donnée de toutes les lieux
$battleInvitationQuery = $bdd->query("SELECT * FROM car_battles_invitations, car_monsters
WHERE battleInvitationMonsterId = monsterId");
$battleInvitationRow = $battleInvitationQuery->rowCount();

//S'il existe une ou plusieurs invitation de combat on affiche le menu déroulant
if ($battleInvitationRow > 0) 
{
    ?>
    
    <form method="POST" action="manageBattleInvitation.php">
        Liste des invitations : <select name="battleInvitationId" class="form-control">

            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($battleInvitation = $battleInvitationQuery->fetch())
            {
                $battleInvitationId = stripslashes($battleInvitation['battleInvitationId']);
                $battleInvitationName = stripslashes($battleInvitation['battleInvitationName']);
                $battleInvitationMonsterName = stripslashes($battleInvitation['monsterName']);
                ?>
                <option value="<?php echo $battleInvitationId ?>"><?php echo "$battleInvitationName ($battleInvitationMonsterName)"; ?></option>
                <?php
            }
            ?>
        
        </select>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer l'invitation de combat">
    </form>
    
    <?php
}
//S'il n'y a aucune invitation de combat on prévient le joueur
else
{
    echo "Il n'y a actuellement aucune invitation de combat";
}
$battleInvitationQuery->closeCursor();
?>

<hr>

<form method="POST" action="addBattleInvitation.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Envoyer une invitation de combat">
</form>

<?php require_once("../html/footer.php");