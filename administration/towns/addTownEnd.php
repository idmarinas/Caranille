<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownPicture'])
&& isset($_POST['adminTownName'])
&& isset($_POST['adminTownDescription'])
&& isset($_POST['adminTownPriceInn'])
&& isset($_POST['adminTownChapter'])
&& isset($_POST['finalAdd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownPriceInn'])
    && ctype_digit($_POST['adminTownChapter'])
    && $_POST['adminTownPriceInn'] >= 0
    && $_POST['adminTownChapter'] >= 1)
    {
        //On récupère les informations du formulaire
        $adminTownPicture = htmlspecialchars(addslashes($_POST['adminTownPicture']));
        $adminTownName = htmlspecialchars(addslashes($_POST['adminTownName']));
        $adminTownDescription = htmlspecialchars(addslashes($_POST['adminTownDescription']));
        $adminTownPriceInn = htmlspecialchars(addslashes($_POST['adminTownPriceInn']));
        $adminTownChapter = htmlspecialchars(addslashes($_POST['adminTownChapter']));

        //On ajoute la ville dans la base de donnée
        $addTown = $bdd->prepare("INSERT INTO car_towns VALUES(
        NULL,
        :adminTownPicture,
        :adminTownName,
        :adminTownDescription,
        :adminTownPriceInn,
        :adminTownChapter)");
        $addTown->execute([
        'adminTownPicture' => $adminTownPicture,
        'adminTownName' => $adminTownName,
        'adminTownDescription' => $adminTownDescription,
        'adminTownPriceInn' => $adminTownPriceInn,
        'adminTownChapter' => $adminTownChapter]);
        $addTown->closeCursor();
        ?>

        La ville a bien été crée

        <hr>
            
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
            
        <?php
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