<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownMonsterTownId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownMonsterTownId'])
    && $_POST['adminTownMonsterTownId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownMonsterTownId = htmlspecialchars(addslashes($_POST['adminTownMonsterTownId']));

        //On fait une requête pour vérifier si la ville choisit existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId = ?');
        $townQuery->execute([$adminTownMonsterTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1)
        {
            //On fait une requête pour rechercher tous les monstres présent dans cette ville
            $townMonsterQuery = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
            WHERE townMonsterMonsterId = monsterId
            AND townMonsterTownId = townId
            AND townId = ?");
            $townMonsterQuery->execute([$adminTownMonsterTownId]);
            $townMonsterRow = $townMonsterQuery->rowCount();

            //S'il existe un ou plusieurs monstre dans la ville on affiche le menu déroulant
            if ($townMonsterRow > 0) 
            {
                ?>
                
                <form method="POST" action="deleteTownMonster.php">
                    Monstres présent dans la ville : <select name="adminTownMonsterMonsterId" class="form-control">
                            
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($townMonster = $townMonsterQuery->fetch())
                        {
                            //On récupère les informations du monstre
                            $adminTownMonsterMonsterId = stripslashes($townMonster['monsterId']);
                            $adminTownMonsterMonsterName = stripslashes($townMonster['monsterName']);
                            ?>
                            <option value="<?php echo $adminTownMonsterMonsterId ?>"><?php echo "N°$adminTownMonsterMonsterId - $adminTownMonsterMonsterName"; ?></option>
                            <?php
                        }
                        $townMonsterQuery->closeCursor();
                        ?>
                        
                    </select>
                    <input type="hidden" name="adminTownMonsterTownId" value="<?php echo $adminTownMonsterTownId ?>">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer le monstre">
                </form>
                
                <hr>

                <?php
            }
            $townMonsterQuery->closeCursor();

            //On fait une requête pour afficher la liste des monstres du jeu qui ne sont pas dans la ville
            $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
			WHERE (SELECT COUNT(*) FROM car_towns_monsters
            WHERE townMonsterTownId = ?
            AND townMonsterMonsterId = monsterId) = 0");
            $monsterQuery->execute([$adminTownMonsterTownId]);
            $monsterRow = $monsterQuery->rowCount();
            //S'il existe un ou plusieurs monstres on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($monsterRow > 0) 
            {
                ?>
                
                <form method="POST" action="addTownMonster.php">
                    Monstres disponible : <select name="adminTownMonsterMonsterId" class="form-control">
                            
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($monster = $monsterQuery->fetch())
                        {
                            //On récupère les informations du monstre
                            $adminTownMonsterMonsterId = stripslashes($monster['monsterId']);
                            $adminTownMonsterMonsterName = stripslashes($monster['monsterName']);
                            ?>
                            <option value="<?php echo $adminTownMonsterMonsterId ?>"><?php echo "N°$adminTownMonsterMonsterId - $adminTownMonsterMonsterName"; ?></option>
                            <?php
                        }
                        $monsterQuery->closeCursor();
                        ?>
                        
                    </select>
                    <input type="hidden" name="adminTownMonsterTownId" value="<?php echo $adminTownMonsterTownId ?>">
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
        //Si la ville n'exite pas
        else
        {
            echo "Erreur : Ville indisponible";
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