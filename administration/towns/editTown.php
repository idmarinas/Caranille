<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownId'])
    && $_POST['adminTownId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownId = htmlspecialchars(addslashes($_POST['adminTownId']));

        //On fait une requête pour vérifier si la ville choisit existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId = ?');
        $townQuery->execute([$adminTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($town = $townQuery->fetch())
            {
                //On récupère les informations de la ville
                $adminTownPicture = stripslashes($town['townPicture']);
                $adminTownName = stripslashes($town['townName']);
                $adminTownDescription = stripslashes($town['townDescription']);
                $adminTownPriceInn = stripslashes($town['townPriceInn']);
                $adminTownChapter = stripslashes($town['townChapter']);
            }
            ?>

            <p><img src="<?php echo $adminTownPicture ?>" height="100" width="100"></p>

            <p>Informations de la ville</p>
            
            <form method="POST" action="editTownEnd.php">
                Image : <input type="text" name="adminTownPicture" class="form-control" placeholder="Image" value="<?php echo $adminTownPicture ?>" required>
                Nom : <input type="text" name="adminTownName" class="form-control" placeholder="Nom" value="<?php echo $adminTownName ?>" required>
                Description : <br> <textarea class="form-control" name="adminTownDescription" id="adminTownDescription" rows="3"><?php echo $adminTownDescription; ?></textarea>
                Prix de l'auberge : <input type="number" name="adminTownPriceInn" class="form-control" placeholder="Prix de l'auberge" value="<?php echo $adminTownPriceInn ?>" required>
                Ville disponible au chapitre : <input type="number" name="adminTownChapter" class="form-control" placeholder="Ville disponible au chapitre" value="<?php echo $adminTownChapter ?>" required>
                <input type="hidden" name="adminTownId" value="<?php echo $adminTownId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si la ville n'exite pas
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
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");