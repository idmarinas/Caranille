<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopPicture'])
&& isset($_POST['adminShopName'])
&& isset($_POST['adminShopDescription'])
&& isset($_POST['token'])
&& isset($_POST['finalAdd']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //On récupère les informations du formulaire
        $adminShopPicture = htmlspecialchars(addslashes($_POST['adminShopPicture']));
        $adminShopName = htmlspecialchars(addslashes($_POST['adminShopName']));
        $adminShopDescription = htmlspecialchars(addslashes($_POST['adminShopDescription']));

        //On ajoute le magasin dans la base de donnée
        $addShop = $bdd->prepare("INSERT INTO car_shops VALUES(
        NULL,
        :adminShopPicture,
        :adminShopName,
        :adminShopDescription)");
        $addShop->execute([
        'adminShopPicture' => $adminShopPicture,
        'adminShopName' => $adminShopName,
        'adminShopDescription' => $adminShopDescription]);
        $addShop->closeCursor();
        ?>

        Le magasin a bien été crée

        <hr>
            
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
            
        <?php
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