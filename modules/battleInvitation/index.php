<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une recherche dans la base de donnée de toutes les lieux
$battleInvitationQuery = $bdd->prepare("SELECT * FROM car_battles_invitations, car_battles_invitations_characters, car_monsters
WHERE battleInvitationId = battleInvitationCharacterBattleInvitationId
AND battleInvitationMonsterId = monsterId
AND battleInvitationCharacterCharacterId = ?");
$battleInvitationQuery->execute([$characterId]);
$battleInvitationRow = $battleInvitationQuery->rowCount();

//S'il existe une ou plusieurs invitation de combat on affiche le menu déroulant
if ($battleInvitationRow > 0) 
{
    ?>
    
    <form method="POST" action="launchInvitation.php">
        Liste de vos invitations : <select name="battleInvitationCharacterId" class="form-control">

            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($battleInvitation = $battleInvitationQuery->fetch())
            {
                $battleInvitationId = stripslashes($battleInvitation['battleInvitationId']);
                $battleInvitationName = stripslashes($battleInvitation['battleInvitationName']);
                $battleInvitationMonsterName = stripslashes($battleInvitation['monsterName']);
                $battleInvitationMonsterLevel = stripslashes($battleInvitation['monsterLevel']);
                $battleInvitationCharacterId = stripslashes($battleInvitation['battleInvitationCharacterId']);
                ?>
                <option value="<?php echo $battleInvitationCharacterId ?>"><?php echo "$battleInvitationName (Monstre : $battleInvitationMonsterName - Niveau : $battleInvitationMonsterLevel)"; ?></option>
                <?php
            }
            ?>
        
        </select>
        <input type="submit" name="open" class="btn btn-default form-control" value="Ouvrir l'invitation">
    </form>
    
    <?php
}
//S'il n'y a aucune invitation de combat on prévient le joueur
else
{
    echo "Erreur : Vous n'avez aucune invitation de combat";
}
$battleInvitationQuery->closeCursor();
?>

<?php require_once("../../html/footer.php"); ?>