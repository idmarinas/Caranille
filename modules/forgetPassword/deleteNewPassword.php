<?php 
require_once("../../kernel/kernel.php");
require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_GET['accountEmail']) 
&& isset($_GET['codeForgetPassword']))
{
    //On récupère les valeurs du formulaire dans une variable
    $accountEmail = htmlspecialchars(addslashes($_GET['accountEmail']));
    $codeForgetPassword = htmlspecialchars(addslashes($_GET['codeForgetPassword']));

    //On fait une requête pour vérifier si une demande de vérification est en cours
    $accountForgetPasswordQuery = $bdd->prepare('SELECT * FROM car_forgets_passwords 
    WHERE accountForgetPasswordEmailAdress = ?
    AND accountForgetPasswordEmailCode = ?');
    $accountForgetPasswordQuery->execute([$accountEmail, $codeForgetPassword]);
    $accountForgetPasswordRow = $accountForgetPasswordQuery->rowCount();

    //Si une vérification est en cours
    if ($accountForgetPasswordRow == 1) 
    {
        //Dans ce cas on boucle pour récupérer le tableau retourné par la base de donnée pour récupérer les informations du compte
        while ($accountForgetPassword = $accountForgetPasswordQuery->fetch())
        {
            //On récupère les informations de la demande de vérification
            $accountForgetPasswordId = stripslashes($accountForgetPassword['accountForgetPasswordId']);
            $accountForgetPasswordAccountId = stripslashes($accountForgetPassword['accountForgetPasswordAccountId']);
        }

        //On supprime la demande de réinitialisation du mot de passe
        $deleteForgetPasswordQuery = $bdd->prepare("DELETE FROM car_forgets_passwords
        WHERE accountForgetPasswordId = :accountForgetPasswordId");
        $deleteForgetPasswordQuery->execute(array(
        'accountForgetPasswordId' => $accountForgetPasswordId));
        $deleteForgetPasswordQuery->closeCursor();
        ?>

        La demande de nouveau mot de passe a été annulée

        <hr>

        <form method="POST" action="../../modules/login/index.php">
            <input type="submit" name="continue" class="btn btn-default form-control" value="Se connecter">
        </form>

        <?php
    }
    //Si le pseudo est déjà utilisé
    else 
    {
        ?>

        Erreur : Aucune demande de vérification en cours

        <hr>

        <form method="POST" action="../../modules/register/index.php">
            <input type="submit" name="continue" class="btn btn-default form-control" value="Recommencer">
        </form>

        <?php
    }
    $accountForgetPasswordQuery->closeCursor();
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>