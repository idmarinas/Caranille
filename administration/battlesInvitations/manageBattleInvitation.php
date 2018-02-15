<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['battleInvitationId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['battleInvitationId'])
    && $_POST['battleInvitationId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $battleInvitationId = htmlspecialchars(addslashes($_POST['battleInvitationId']));

        //On fait une requête pour vérifier si l'objet choisit existe
        $battleInvitationQuery = $bdd->prepare('SELECT * FROM car_battles_invitations');
        $battleInvitationQuery->execute([$battleInvitationId]);
        $battleInvitationRow = $battleInvitationQuery->rowCount();

        //Si l'invitation de combat existe
        if ($battleInvitationRow == 1) 
        {
            //On fait une recherche dans la base de donnée de toutes les lieux
            while ($battleInvitation = $battleInvitationQuery->fetch())
            {
                $battleInvitationName = stripslashes($battleInvitation['battleInvitationName']);
            }
            
            ?>
            
            <p>Information de l'invitation <em><?php echo $battleInvitationName ?></em> ?</p>
            
            <p>Liste des joueurs invité : </p>
            
            <?php
            
            //On fait une requête pour vérifier si l'objet choisit existe
	        $battleInvitationCharacterQuery = $bdd->prepare('SELECT * FROM car_battles_invitations_characters, car_characters
	        WHERE battleInvitationCharacterCharacterId = characterId
	        AND battleInvitationCharacterBattleInvitationId = ?');
	        $battleInvitationCharacterQuery->execute([$battleInvitationId]);
	        
	        //On fait une recherche dans la base de donnée de toutes les lieux
            while ($battleInvitationCharacter = $battleInvitationCharacterQuery->fetch())
            {
                $battleInvitationCharacterId = stripslashes($battleInvitationCharacter['characterId']);
                $battleInvitationCharacterName = stripslashes($battleInvitationCharacter['characterName']);
                
            	echo "$battleInvitationCharacterName (Déjà vaincu) est invité <br />";
            }
            ?>
            
            <hr>
                
            <form method="POST" action="deleteBattleInvitation.php">
                <input type="hidden" class="btn btn-default form-control" name="battleInvitationId" value="<?php echo $battleInvitationId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer l'invitation">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si l'invitation de combat n'exite pas
        else
        {
            echo "Erreur : Cette invitation de combat n'existe pas";
        }
        $placeQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur : Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");