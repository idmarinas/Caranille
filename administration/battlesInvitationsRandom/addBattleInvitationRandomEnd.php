<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminBattleInvitationMonsterId'])
&& isset($_POST['adminBattleInvitationPicture'])
&& isset($_POST['adminBattleInvitationeName'])
&& isset($_POST['adminBattleInvitationDescription'])
&& isset($_POST['adminBattleInvitationeRateOld'])
&& isset($_POST['adminBattleInvitationeRateNew'])
&& isset($_POST['finalAdd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminBattleInvitationMonsterId'])
	&& ctype_digit($_POST['adminBattleInvitationeRateNew'])
    && ctype_digit($_POST['adminBattleInvitationeRateOld'])
    && $_POST['adminBattleInvitationMonsterId'] >= 1
    && $_POST['adminBattleInvitationeRateOld'] >= 0
    && $_POST['adminBattleInvitationeRateNew'] >= 0)
    {
        //On récupère les informations du formulaire
        $adminBattleInvitationMonsterId = htmlspecialchars(addslashes($_POST['adminBattleInvitationMonsterId']));
        $adminBattleInvitationPicture = htmlspecialchars(addslashes($_POST['adminBattleInvitationPicture']));
        $adminBattleInvitationeName = htmlspecialchars(addslashes($_POST['adminBattleInvitationeName']));
        $adminBattleInvitationDescription = htmlspecialchars(addslashes($_POST['adminBattleInvitationDescription']));
        $adminBattleInvitationeRateOld = htmlspecialchars(addslashes($_POST['adminBattleInvitationeRateOld']));
        $adminBattleInvitationeRateNew = htmlspecialchars(addslashes($_POST['adminBattleInvitationeRateNew']));
		$date = date('Y-m-d H:i:s');
        
        //On ajoute l'invitation de combat dans la base de donnée
        $addInvitationBattle = $bdd->prepare("INSERT INTO car_battles_invitations VALUES(
        NULL,
        :adminBattleInvitationMonsterId,
        :adminBattleInvitationPicture,
        :adminBattleInvitationeName,
        :adminBattleInvitationDescription,
        :date,
        :date)");
        $addInvitationBattle->execute([
        'adminBattleInvitationMonsterId' => $adminBattleInvitationMonsterId,
        'adminBattleInvitationPicture' => $adminBattleInvitationPicture,
        'adminBattleInvitationeName' => $adminBattleInvitationeName,
        'adminBattleInvitationDescription' => $adminBattleInvitationDescription,
        'date' => $date,
        'date' => $date]);
        $addInvitationBattle->closeCursor();
        
        //On fait une requête pour récupérer l'Id de l'invitation faite à l'instant
        $battleInvitationQuery = $bdd->query("SELECT * FROM car_battles_invitations 
        ORDER BY battleInvitationId DESC 
        LIMIT 1");
		
		//On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
		while ($battleInvitation = $battleInvitationQuery->fetch())
        {
        	 //On récupère les informations de l'invitation
            $adminBattleInvitationId = stripslashes($battleInvitation['battleInvitationId']);
        }
        
        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters, car_monsters_categories
        WHERE monsterCategory = monsterCategoryId
        AND monsterId = ?');
        $monsterQuery->execute([$adminBattleInvitationMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre existe
        if ($monsterRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($monster = $monsterQuery->fetch())
            {
                $adminBattleInvitationMonsterName = stripslashes($monster['monsterName']);
            }
        }
        
        $battleInvitationNumber = 0;
        
        //On fait une recherche dans la base de donnée de tous les comptes et personnages
		$accountQuery = $bdd->query("SELECT * FROM car_accounts, car_characters
		WHERE accountId = characterAccountId
		ORDER by characterName");

        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
        while ($account = $accountQuery->fetch())
        {
        	$adminAccountId = stripslashes($account['accountId']);
            $adminAccountPseudo = stripslashes($account['accountPseudo']);
            $adminAccountCharacterId = stripslashes($account['characterId']);
            $adminAccountCharacterName =  stripslashes($account['characterName']);
            
            //On fait une requête pour vérifier si le joueur à déjà vaincu le monstre
			$characterBestiaryQuery = $bdd->prepare("SELECT * FROM car_characters, car_bestiary
			WHERE characterId = bestiaryCharacterId
			AND characterId = ?
			AND bestiaryMonsterId = ?");
			$characterBestiaryQuery->execute([$adminAccountCharacterId, $adminBattleInvitationMonsterId]);
			$characterBestiaryRow = $characterBestiaryQuery->rowCount();
			
			//On fait une requête pour vérifier si le joueur à déjà une invitation en attente pour ce monstre
			$characterBattleInvitationQuery = $bdd->prepare("SELECT * FROM car_battles_invitations, car_battles_invitations_characters
			WHERE battleInvitationId = battleInvitationCharacterBattleInvitationId
			AND battleInvitationMonsterId = ?
			AND battleInvitationCharacterCharacterId = ?");
			$characterBattleInvitationQuery->execute([$adminBattleInvitationMonsterId, $adminAccountCharacterId]);
			$characterBattleInvitationRow = $characterBattleInvitationQuery->rowCount();
			
			//On génère un nombre entre 0 et 101 (Pour que 100 puisse aussi être choisi)
            $numberRandom = mt_rand(0, 101);
            
            if ($characterBattleInvitationRow == 1)
            {
            	echo "$adminAccountCharacterName n'est pas éligible (Invitation en attente pour ce même monstre)<br />";
            }
            else
            {
            	//Si le joueur à déjà vaincu le monstre
		        if ($characterBestiaryRow >= 1)
		        {
		        	//Si le taux d'obtentintion est en dessous de la valeur demandée on envoi l'invitation
		        	if ($numberRandom <= $adminBattleInvitationeRateOld)
		        	{
			        	//On ajoute l'invitation de combat dans la base de donnée
		                $addInvitationBattleCharacter = $bdd->prepare("INSERT INTO car_battles_invitations_characters VALUES(
		                NULL,
		                :adminBattleInvitationId,
		                :adminAccountCharacterId)");
		                $addInvitationBattleCharacter->execute([
		                'adminBattleInvitationId' => $adminBattleInvitationId,
		                'adminAccountCharacterId' => $adminAccountCharacterId]);
		                $addInvitationBattleCharacter->closeCursor();
		                
		                $notificationDate = date('Y-m-d H:i:s');
                        $notificationMessage = "Félicitation : Vous avez reçu une invitation de combat contre le monstre $adminBattleInvitationMonsterName.<br />Rendez-vous dans le menu Personnage -> Invitation de combat pour y prendre part.";
                        
                        //On envoie une notification au joueur
                        $addNotification = $bdd->prepare("INSERT INTO car_notifications VALUES(
                        NULL,
                        :adminAccountCharacterId,
                        :notificationDate,
                        :notificationMessage,
                        'No')");
                        $addNotification->execute(array(
                        'adminAccountCharacterId' => $adminAccountCharacterId,  
                        'notificationDate' => $notificationDate,
                        'notificationMessage' => $notificationMessage));
                        $addNotification->closeCursor();
                        
                        echo "$adminAccountCharacterName (Déjà vaincu) a été invité <br />";
		        		$battleInvitationNumber++;
		        	}
		        }
		        //Si le joueur n'a pas vaincu le monstre
		        else
		        {
		        	//Si le taux d'obtentintion est en dessous de la valeur demandée on envoi l'invitation
		        	if ($numberRandom <= $adminBattleInvitationeRateNew)
		        	{
		        		//On ajoute l'invitation de combat dans la base de donnée
		                $addInvitationBattleCharacter = $bdd->prepare("INSERT INTO car_battles_invitations_characters VALUES(
		                NULL,
		                :adminBattleInvitationId,
		                :adminAccountCharacterId)");
		                $addInvitationBattleCharacter->execute([
		                'adminBattleInvitationId' => $adminBattleInvitationId,
		                'adminAccountCharacterId' => $adminAccountCharacterId]);
		                $addInvitationBattleCharacter->closeCursor();
		                
		                $notificationDate = date('Y-m-d H:i:s');
                        $notificationMessage = "Félicitation : Vous avez reçu une invitation de combat contre le monstre $adminBattleInvitationMonsterName.<br />Rendez-vous dans le menu Personnage -> Invitation de combat pour y prendre part.";
                        
                        //On envoie une notification au joueur
                        $addNotification = $bdd->prepare("INSERT INTO car_notifications VALUES(
                        NULL,
                        :adminAccountCharacterId,
                        :notificationDate,
                        :notificationMessage,
                        'No')");
                        $addNotification->execute(array(
                        'adminAccountCharacterId' => $adminAccountCharacterId,  
                        'notificationDate' => $notificationDate,
                        'notificationMessage' => $notificationMessage));
                        $addNotification->closeCursor();
                        
                        echo "$adminAccountCharacterName (Jamais vaincu) a été invité <br />";
                        $battleInvitationNumber++;
		        	}
		        }
            }
        }
        
        //Si il y a eu au moins un joueur invité
        if ($battleInvitationNumber > 0)
        {
        	echo "Les invitations de combats ont bien été envoyée aux joueurs sélectionné.";
        }
        //Sinon on supprime l'invitation
        else
        {
        	echo "Aucun joueur n'a été invité avec le taux d'obtention, l'invitation N°$adminBattleInvitationId est donc supprimée";
        	
        	//On supprime l'invitation de combat de la base de donnée
            $battleInvitationDeleteQuery = $bdd->prepare("DELETE FROM car_battles_invitations
            WHERE battleInvitationId = ?");
            $battleInvitationDeleteQuery->execute([$adminBattleInvitationId]);
            $battleInvitationDeleteQuery->closeCursor();
        }
        ?>
        
        <hr>
            
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
            
        <?php
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
    echo "Erreur : Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");