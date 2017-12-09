<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterId'])
    && $_POST['adminMonsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterId']));

        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId = ?');
        $monsterQuery->execute([$adminMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre existe
        if ($monsterRow == 1) 
        {
            //On fait une recherche dans la base de donnée de tous les monstres
            $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
            WHERE monsterId = ?");
            $monsterQuery->execute([$adminMonsterId]);
            
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($monster = $monsterQuery->fetch())
            {
                //On récupère les informations du monstre
                $adminMonsterName = stripslashes($monster['monsterName']);
            }
            $monsterQuery->closeCursor();
            ?>
            
            Que souhaitez-vous faire du monstre <em><?php echo $adminMonsterName ?></em> ?

            <hr>
                
            <form method="POST" action="editMonster.php">
                <input type="hidden" class="btn btn-default form-control" name="adminMonsterId" value="<?php echo $adminMonsterId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier le monstre">
            </form>
            <form method="POST" action="../monstersDrops/manageMonsterDrop.php">
                <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropMonsterId" value="<?php echo $adminMonsterId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Objet(s) du monstre">
            </form>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le monstre n'exite pas
        else
        {
            echo "Erreur : Ce monstre n'existe pas";
        }
        $monsterQuery->closeCursor();
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