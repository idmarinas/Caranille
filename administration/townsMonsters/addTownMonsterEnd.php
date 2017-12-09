<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownMonsterTownId'])
&& isset($_POST['adminTownMonsterMonsterId'])
&& isset($_POST['finalAdd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownMonsterTownId'])
    && ctype_digit($_POST['adminTownMonsterMonsterId'])
    && $_POST['adminTownMonsterTownId'] >= 1
    && $_POST['adminTownMonsterMonsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownMonsterTownId = htmlspecialchars(addslashes($_POST['adminTownMonsterTownId']));
        $adminTownMonsterMonsterId = htmlspecialchars(addslashes($_POST['adminTownMonsterMonsterId']));

        //On fait une requête pour vérifier si la ville choisie existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId = ?');
        $townQuery->execute([$adminTownMonsterTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1) 
        {
            //On fait une requête pour vérifier si le monstre choisit existe
            $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
            WHERE monsterId = ?');
            $monsterQuery->execute([$adminTownMonsterMonsterId]);
            $monsterRow = $monsterQuery->rowCount();

            //Si le monstre existe
            if ($monsterRow == 1) 
            {
                //On fait une requête pour vérifier si le monstre n'est pas déjà dans cette ville
                $townMonsterQuery = $bdd->prepare('SELECT * FROM car_towns_monsters 
                WHERE townMonsterTownId = ?
                AND townMonsterMonsterId = ?');
                $townMonsterQuery->execute([$adminTownMonsterTownId, $adminTownMonsterMonsterId]);
                $townMonsterRow = $townMonsterQuery->rowCount();

                //Si le monstre n'est pas dans la ville
                if ($townMonsterRow == 0) 
                {
                    //On ajoute le monstre dans la ville la base de donnée
                    $addTownMonster = $bdd->prepare("INSERT INTO car_towns_monsters VALUES(
                    NULL,
                    :townMonsterTownId,
                    :townMonsterMonsterId)");
                    $addTownMonster->execute([
                    'townMonsterTownId' => $adminTownMonsterTownId,
                    'townMonsterMonsterId' => $adminTownMonsterMonsterId]);
                    $addTownMonster->closeCursor();
                    ?>

                    Le monstre a bien été ajouté à la ville

                    <hr>
                        
                    <form method="POST" action="manageTownMonster.php">
                        <input type="hidden" name="adminTownMonsterTownId" value="<?php echo $adminTownMonsterTownId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                    </form>
                    
                    <?php
                }
                //Si le monstre est déjà dans cette ville
                else
                {
                    ?>
                    
                    Erreur : Ce monstre est déjà dans cette ville
                    
                    <form method="POST" action="manageTownMonster.php">
                        <input type="hidden" name="adminTownMonsterTownId" value="<?php echo $adminTownMonsterTownId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                    </form>
                    
                    <?php
                }
                $monsterQuery->closeCursor();
            }
            //Si le monstre existe pas
            else
            {
                echo "Erreur : Ce monstre n'existe pas";
            }
            $monsterQuery->closeCursor();
        }
        //Si la ville existe pas
        else
        {
            echo "Erreur : Cette ville n'existe pas";
        }
        $townQuery->closeCursor();
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