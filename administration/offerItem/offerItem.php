<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminCharacterId'])
&& isset($_POST['adminItemId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminCharacterId'])
    && ctype_digit($_POST['adminItemId'])
    && $_POST['adminCharacterId'] >= 0
    && $_POST['adminItemId'] >= 0)
    {
        //On récupère les informations du formulaire précédent
        $adminCharacterId = htmlspecialchars(addslashes($_POST['adminCharacterId']));
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
        
        //Si l'objet à offrir est pour tous les joueurs
        if ($adminCharacterId == 0)
        {
            //On fait une requête pour vérifier si l'objet choisit existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$adminItemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet existe
            if ($itemRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($item = $itemQuery->fetch())
                {
                    //On récupère les informations de l'objet
                    $adminItemId = stripslashes($item['itemId']);
                    $adminItemName = stripslashes($item['itemName']);
                }
                ?>
                
                <p>ATTENTION</p> 
                Vous êtes sur le point d'offrir l'objet <em><?php echo $adminItemName ?></em> à <em>tous les joueurs</em>.<br />
                Confirmez-vous  ?

                <hr>
                    
                <form method="POST" action="offerItemEnd.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminCharacterId" value="<?php echo $adminCharacterId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="adminItemId" value="<?php echo $adminItemId ?>">
                    <input type="submit" class="btn btn-default form-control" name="finalAdd" value="Je confirme">
                </form>
                
                <hr>

                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php

            }
            //Si l'objet n'exite pas
            else
            {
                echo "Erreur : Cet objet n'existe pas";
            }
            $itemQuery->closeCursor();
        }
        //Si l'objet à offrir est pour un seul joueur
        else 
        {
            //On fait une requête pour vérifier si le personnage existe
            $characterQuery = $bdd->prepare("SELECT * FROM car_characters 
            WHERE characterId = ?");
            $characterQuery->execute([$adminCharacterId]);
            $characterRow = $characterQuery->rowCount();
    
            //Si le personnage existe
            if ($characterRow == 1)
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    $adminCharacterName = stripslashes($character['characterName']);
                }

                //On fait une requête pour vérifier si l'objet choisit existe
                $itemQuery = $bdd->prepare('SELECT * FROM car_items 
                WHERE itemId = ?');
                $itemQuery->execute([$adminItemId]);
                $itemRow = $itemQuery->rowCount();

                //Si l'objet existe
                if ($itemRow == 1) 
                {
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($item = $itemQuery->fetch())
                    {
                        //On récupère les informations de l'objet
                        $adminItemId = stripslashes($item['itemId']);
                        $adminItemName = stripslashes($item['itemName']);
                    }
                    ?>
                    
                    <p>ATTENTION</p>

                    Vous êtes sur le point d'offrir l'objet <em><?php echo $adminItemName ?></em> à <em><?php echo $adminCharacterName ?></em>.<br />
                    Confirmez-vous ?
        
                    <hr>
                        
                    <form method="POST" action="offerItemEnd.php">
                        <input type="hidden" class="btn btn-default form-control" name="adminCharacterId" value="<?php echo $adminCharacterId ?>">
                        <input type="hidden" class="btn btn-default form-control" name="adminItemId" value="<?php echo $adminItemId ?>">
                        <input type="submit" class="btn btn-default form-control" name="finalAdd" value="Je confirme">
                    </form>
                    
                    <hr>
        
                    <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                    </form>
            
                    <?php

                }
                //Si l'objet n'exite pas
                else
                {
                    echo "Erreur : Cet objet n'existe pas";
                }
                $itemQuery->closeCursor();
            }
            //Si le compte n'existe pas
            else
            {
                echo "Erreur : Ce compte n'existe pas";
            }
            $accountQuery->closeCursor();
        }
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