<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['viewPicture']))
{
    //On récupère le nom de l'image du formulaire précédent
    $adminFile = htmlspecialchars(addslashes($_POST['pictureFile']));
    ?>
    
    <p><img src="../../img/races/<?php echo $adminFile ?>"></p>

    Pour utiliser cette image veuillez copier/coller le texte ci-dessous dans le champs image : <br/>

    ../../img/races/<?php echo $adminFile ?>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    
    <?php
}
else if (isset($_POST['deletePicture']))
{
    //On récupère le nom de l'image du formulaire précédent
    $adminFile = htmlspecialchars(addslashes($_POST['pictureFile']));

    if ($adminFile != "default.png")
    {
        ?>

        <p>ATTENTION</p> 

        Vous êtes sur le point de supprimer cette image : 
    
        <p><img src="../../img/races/<?php echo $adminFile ?>"></p>
    
        Confirmez-vous la suppression ?
    
        <hr>
            
        <form method="POST" action="deleteRacesPicture.php">
            <input type="hidden" class="btn btn-default form-control" name="pictureFile" value="<?php echo $adminFile ?>">
            <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
        </form>
        
        <hr>
    
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
    
        <?php
    }
    else
    {
        ?>

        Erreur : Il est impossible de supprimer l'image par défaut
    
        <hr>
    
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>

        <?php
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");