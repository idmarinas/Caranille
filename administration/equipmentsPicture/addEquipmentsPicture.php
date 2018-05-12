<?php 
require_once("../html/header.php");

if(isset($_FILES['picture']))
{ 
    $dossier = '../../img/equipments/';
    $fichier = basename($_FILES['picture']['name']);
    if(move_uploaded_file($_FILES['picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
    {
        ?>

        Le fichier <?php echo $fichier ?> a bien été envoyé.

        <hr>

        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>

        <?php
    }
    else //Sinon (la fonction renvoie FALSE).
    {
        echo 'Echec de l\'upload !';
    }
}

require_once("../html/footer.php");