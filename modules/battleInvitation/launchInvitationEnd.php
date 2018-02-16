<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['battleInvitationCharacterId'])
&& isset($_POST['launch']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['battleInvitationCharacterId'])
    && $_POST['battleInvitationCharacterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $battleInvitationCharacterId = htmlspecialchars(addslashes($_POST['battleInvitationCharacterId']));

        //On fait une requête pour vérifier si l'invitation de combnat choisit existe
        $battleInvitationQuery = $bdd->prepare('SELECT * FROM car_battles_invitations, car_battles_invitations_characters, car_monsters
		WHERE battleInvitationId = battleInvitationCharacterBattleInvitationId
		AND battleInvitationMonsterId = monsterId
		AND battleInvitationCharacterId = ?
		AND battleInvitationCharacterCharacterId = ?');
        $battleInvitationQuery->execute([$battleInvitationCharacterId, $characterId]);
        $battleInvitationRow = $battleInvitationQuery->rowCount();

        //Si l'invitation de combat existe
        if ($battleInvitationRow == 1) 
        {
            //On fait une recherche dans la base de donnée de toutes les lieux
            while ($battleInvitation = $battleInvitationQuery->fetch())
            {
            	$battleInvitationId = stripslashes($battleInvitation['battleInvitationId']);
                $battleInvitationName = stripslashes($battleInvitation['battleInvitationName']);
                $battleInvitationMonsterId = stripslashes($battleInvitation['monsterId']);
                $battleInvitationMonsterName = stripslashes($battleInvitation['monsterName']);
            }
    
            //On fait une requête pour vérifier si le monstre est bien disponible dans le lieu du joueur
            $opponentQuery = $bdd->prepare("SELECT * FROM car_monsters
            WHERE monsterId = ?");
            $opponentQuery->execute([$battleInvitationMonsterId]);
            $opponentRow = $opponentQuery->rowCount();
    
            //Si le monstre existe
            if ($opponentRow == 1) 
            {
                while ($opponent = $opponentQuery->fetch())
                {
                    //On récupère les informations du monstre
                    $opponentHp = stripslashes($opponent['monsterHp']);
                    $opponentMp = stripslashes($opponent['monsterMp']);
                    $monsterLimited = stripslashes($opponent['monsterLimited']);
                    $monsterQuantity = stripslashes($opponent['monsterQuantity']);
                }
                $opponentQuery->closeCursor();

                //Insertion du combat dans la base de donnée avec les données
                $addBattle = $bdd->prepare("INSERT INTO car_battles VALUES(
                NULL,
                :characterId,
                :battleInvitationMonsterId,
                'battleInvitation',
                :opponentHp,
                :opponentMp)");
                $addBattle->execute([
                'characterId' => $characterId,
                'battleInvitationMonsterId' => $battleInvitationMonsterId,
                'opponentHp' => $opponentHp,
                'opponentMp' => $opponentMp]);
                $addBattle->closeCursor();
                
                //On supprime l'invitation
			    $deleteBattleInvitationCharacter = $bdd->prepare("DELETE FROM car_battles_invitations_characters 
			    WHERE battleInvitationCharacterId = :battleInvitationCharacterId");
			    $deleteBattleInvitationCharacter->execute(array('battleInvitationCharacterId' => $battleInvitationCharacterId));
			    $deleteBattleInvitationCharacter->closeCursor();
			    
			    //On vérifie si il reste encore des joueurs en attente pour cette invitation
				$battleInvitationQuery = $bdd->prepare("SELECT * FROM car_battles_invitations, car_battles_invitations_characters
				WHERE battleInvitationId = battleInvitationCharacterBattleInvitationId
				AND battleInvitationId = ?");
				$battleInvitationQuery->execute([$battleInvitationId]);
				$battleInvitationRow = $battleInvitationQuery->rowCount();
				
				//S'il n'existe plus d'invitation de combat pour ce monstre on supprime l'invitation
				if ($battleInvitationRow == 0) 
				{
					$deleteBattleInvitation = $bdd->prepare("DELETE FROM car_battles_invitations 
				    WHERE battleInvitationId = :battleInvitationId");
				    $deleteBattleInvitation->execute(array('battleInvitationId' => $battleInvitationId));
				    $deleteBattleInvitation->closeCursor();
				}
			    
                //On redirige le joueur vers le combat
                header("Location: ../../modules/battle/index.php");
            }
            else
            {
            	echo "Erreur : Le monstre n'existe pas";
            }
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

require_once("../../html/footer.php");