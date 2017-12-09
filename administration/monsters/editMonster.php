<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterId'])
&& isset($_POST['edit']))
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
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($monster = $monsterQuery->fetch())
            {
                //On récupère les informations du monstre
                $adminMonsterId = stripslashes($monster['monsterId']);
                $adminMonsterPicture = stripslashes($monster['monsterPicture']);
                $adminMonsterName = stripslashes($monster['monsterName']);
                $adminMonsterLevel = stripslashes($monster['monsterLevel']);
                $adminMonsterDescription = stripslashes($monster['monsterDescription']);
                $adminMonsterHp = stripslashes($monster['monsterHp']);
                $adminMonsterMp = stripslashes($monster['monsterMp']);
                $adminMonsterStrength = stripslashes($monster['monsterStrength']);
                $adminMonsterMagic = stripslashes($monster['monsterMagic']);
                $adminMonsterAgility = stripslashes($monster['monsterAgility']);
                $adminMonsterDefense = stripslashes($monster['monsterDefense']);
                $adminMonsterDefenseMagic = stripslashes($monster['monsterDefenseMagic']);
                $adminMonsterExperience = stripslashes($monster['monsterExperience']);              
                $adminMonsterGold = stripslashes($monster['monsterGold']);
            }
            ?>

            <p><img src="<?php echo $adminMonsterPicture ?>" height="100" width="100"></p>

            <p>Informations du monstre</p>

            Numéro : <?php echo $adminMonsterId ?><br />

            <form method="POST" action="editMonsterEnd.php">
                Image : <input type="text" name="adminMonsterPicture" class="form-control" placeholder="Image" value="<?php echo $adminMonsterPicture ?>" required>
                Nom : <input type="text" name="adminMonsterName" class="form-control" placeholder="Nom" value="<?php echo $adminMonsterName ?>" required>
                Niveau : <input type="number" name="adminMonsterLevel" class="form-control" placeholder="Niveau" value="<?php echo $adminMonsterLevel ?>" required>
                Description : <br> <textarea class="form-control" name="adminMonsterDescription" id="adminMonsterDescription" rows="3" required><?php echo $adminMonsterDescription; ?></textarea>
                HP : <input type="number" name="adminMonsterHp" class="form-control" placeholder="HP" value="<?php echo $adminMonsterHp ?>" required>
                MP : <input type="number" name="adminMonsterMp" class="form-control" placeholder="MP" value="<?php echo $adminMonsterMp ?>" required>
                Force : <input type="number" name="adminMonsterStrength" class="form-control" placeholder="Force" value="<?php echo $adminMonsterStrength ?>" required>
                Magie : <input type="number" name="adminMonsterMagic" class="form-control" placeholder="Magie" value="<?php echo $adminMonsterMagic ?>" required>
                Agilité : <input type="number" name="adminMonsterAgility" class="form-control" placeholder="Agilité" value="<?php echo $adminMonsterAgility ?>" required>
                Défense : <input type="number" name="adminMonsterDefense" class="form-control" placeholder="Défense" value="<?php echo $adminMonsterDefense ?>" required>
                Défense Magique : <input type="number" name="adminMonsterDefenseMagic" class="form-control" placeholder="Défense Magique" value="<?php echo $adminMonsterDefenseMagic ?>" required>
                Experience : <input type="number" name="adminMonsterExperience" class="form-control" placeholder="Expérience" value="<?php echo $adminMonsterExperience ?>" required>
                Argent : <input type="number" name="adminMonsterGold" class="form-control" placeholder="Argent" value="<?php echo $adminMonsterGold ?>" required>
                <input type="hidden" name="adminMonsterId" value="<?php echo $adminMonsterId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
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