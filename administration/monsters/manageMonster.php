<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterId'])
&& isset($_POST['token'])
&& isset($_POST['manage']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();
        
        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['adminMonsterId'])
        && $_POST['adminMonsterId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $adminMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterId']));

            //On fait une requête pour vérifier si le monstre choisit existe
            $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters 
            WHERE monsterId = ?");
            $monsterQuery->execute([$adminMonsterId]);
            $monsterRow = $monsterQuery->rowCount();

            //Si le monstre existe
            if ($monsterRow == 1) 
            {
                //On récupère les informations du formulaire
                $adminMonsterName = stripslashes($monsterQuery['monsterName']);
                ?>
                
                Que souhaitez-vous faire du monstre <em><?php echo $adminMonsterName ?></em> ?

                <hr>
                    
                <form method="POST" action="editMonster.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminMonsterId" value="<?php echo $adminMonsterId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier le monstre">
                </form>
                <form method="POST" action="../monstersDrops/manageMonsterDrop.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropMonsterId" value="<?php echo $adminMonsterId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="manage" value="Objet(s) du monstre">
                </form>
                <form method="POST" action="../monstersBattlesStats/manageMonsterStatsLight.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminMonsterStatsMonsterId" value="<?php echo $adminMonsterId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="viewStats" value="Statistiques de combat">
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
    //Si le token de sécurité n'est pas correct
    else
    {
        echo "Erreur : Impossible de valider le formulaire, veuillez réessayer";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");