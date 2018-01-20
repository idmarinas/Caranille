<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPlaceMonsterPlaceId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminPlaceMonsterPlaceId'])
    && $_POST['adminPlaceMonsterPlaceId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminPlaceMonsterPlaceId = htmlspecialchars(addslashes($_POST['adminPlaceMonsterPlaceId']));

        //On fait une requête pour vérifier si le lieu choisit existe
        $placeQuery = $bdd->prepare('SELECT * FROM car_places 
        WHERE placeId = ?');
        $placeQuery->execute([$adminPlaceMonsterPlaceId]);
        $placeRow = $placeQuery->rowCount();

        //Si le lieu existe
        if ($placeRow == 1)
        {
            //On fait une requête pour rechercher tous les monstres présent dans cette lieu
            $placeMonsterQuery = $bdd->prepare("SELECT * FROM car_monsters, car_places, car_places_monsters
            WHERE placeMonsterMonsterId = monsterId
            AND placeMonsterPlaceId = placeId
            AND placeId = ?");
            $placeMonsterQuery->execute([$adminPlaceMonsterPlaceId]);
            $placeMonsterRow = $placeMonsterQuery->rowCount();

            //S'il existe un ou plusieurs monstre dans le lieu on affiche le menu déroulant
            if ($placeMonsterRow > 0) 
            {
                ?>
                
                <form method="POST" action="deletePlaceMonster.php">
                    Monstres présent dans le lieu : <select name="adminplaceMonsterMonsterId" class="form-control">
                            
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($placeMonster = $placeMonsterQuery->fetch())
                        {
                            //On récupère les informations du monstre
                            $adminplaceMonsterMonsterId = stripslashes($placeMonster['monsterId']);
                            $adminTownMonsterMonsterName = stripslashes($placeMonster['monsterName']);
                            ?>
                            <option value="<?php echo $adminplaceMonsterMonsterId ?>"><?php echo "N°$adminplaceMonsterMonsterId - $adminTownMonsterMonsterName"; ?></option>
                            <?php
                        }
                        $placeMonsterQuery->closeCursor();
                        ?>
                        
                    </select>
                    <input type="hidden" name="adminPlaceMonsterPlaceId" value="<?php echo $adminPlaceMonsterPlaceId ?>">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer le monstre">
                </form>
                
                <hr>

                <?php
            }
            $placeMonsterQuery->closeCursor();

            //On fait une requête pour afficher la liste des monstres du jeu qui ne sont pas dans le lieu
            $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
			WHERE (SELECT COUNT(*) FROM car_places_monsters
            WHERE placeMonsterPlaceId = ?
            AND placeMonsterMonsterId = monsterId) = 0");
            $monsterQuery->execute([$adminPlaceMonsterPlaceId]);
            $monsterRow = $monsterQuery->rowCount();
            //S'il existe un ou plusieurs monstres on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($monsterRow > 0) 
            {
                ?>
                
                <form method="POST" action="addPlaceMonster.php">
                    Monstres disponible : <select name="adminplaceMonsterMonsterId" class="form-control">
                            
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($monster = $monsterQuery->fetch())
                        {
                            //On récupère les informations du monstre
                            $adminplaceMonsterMonsterId = stripslashes($monster['monsterId']);
                            $adminTownMonsterMonsterName = stripslashes($monster['monsterName']);
                            ?>
                            <option value="<?php echo $adminplaceMonsterMonsterId ?>"><?php echo "N°$adminplaceMonsterMonsterId - $adminTownMonsterMonsterName"; ?></option>
                            <?php
                        }
                        $monsterQuery->closeCursor();
                        ?>
                        
                    </select>
                    <input type="hidden" name="adminPlaceMonsterPlaceId" value="<?php echo $adminPlaceMonsterPlaceId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter le monstre">
                </form>
                
                <?php
            }
            //Si il n'y a actuellement aucun monstre dans le jeu
            else
            {
                echo "Il n'y a actuellement aucun monstre";
            }
            ?>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le lieu n'exite pas
        else
        {
            echo "Erreur : lieu indisponible";
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