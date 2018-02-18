<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
	//On fait une recherche dans la base de donnée de tous les monstres
	$monsterQuery = $bdd->query("SELECT * FROM car_monsters");
	$monsterRow = $monsterQuery->rowCount();
	
	//S'il existe un ou plusieurs monstres on affiche le menu déroulant
	if ($monsterRow > 0) 
	{
		$characterQuery = $bdd->prepare("SELECT * FROM car_characters
		ORDER by characterName");
		$characterQuery->execute([$characterId, $characterId, $characterId]);
	    ?>
	    
	    <p>Informations de l'invitation de combat</p>
	    
	    <p>Ici vous aller pouvoir envoyer une invitation à un joueurs afin qu'il puisse affronter un monstre unique ou rare.</p>
	
	    <form method="POST" action="addBattleInvitationNominativeEnd.php">
	    	 Liste des joueurs <select name="adminBattleInvitationCharacterId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($character = $characterQuery->fetch())
            {
                //On récupère les informations du personnage
                $adminCharacterId = stripslashes($character['characterId']);
                $adminCharacterName = stripslashes($character['characterName']);
                ?>
                <option value="<?php echo $adminCharacterId ?>"><?php echo $adminCharacterName ?></option>
                <?php
            }
            ?>
        
        	</select>
	    	Liste des monstres : <select name="adminBattleInvitationMonsterId" class="form-control">
            
	            <?php
	            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
	            while ($monster = $monsterQuery->fetch())
	            {
	                $adminMonsterId = stripslashes($monster['monsterId']);
	                $adminMonsterName = stripslashes($monster['monsterName']);
	                ?>
	                <option value="<?php echo $adminMonsterId ?>"><?php echo "N°$adminMonsterId - $adminMonsterName" ?></option>
	                <?php
	            }
	            $monsterQuery->closeCursor();
	            ?>
            
        	</select>
	        Image : <input type="text" name="adminBattleInvitationPicture" class="form-control" placeholder="Image" value="../../img/empty.png" required>
	        Nom : <input type="text" name="adminBattleInvitationeName" class="form-control" placeholder="Nom" required>
	        Description : <br> <textarea class="form-control" name="adminBattleInvitationDescription" id="adminBattleInvitationDescription" rows="3"></textarea>
	        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
	    </form>
	    
	    <hr>
	
	    <form method="POST" action="index.php">
	        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
	    </form>
	    
	    <?php
	}
	//S'il n'y a aucun monstre on prévient le joueur
	else
	{
	    echo "Il n'y a actuellement aucun monstre";
	}
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");