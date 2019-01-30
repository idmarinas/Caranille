<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminAccountId'])
&& isset($_POST['token'])
&& isset($_POST['delete']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();
        
        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['adminAccountId'])
        && $_POST['adminAccountId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

            //On fait une requête pour vérifier si le compte choisit existe
            $accountQuery = $bdd->prepare("SELECT * FROM car_accounts 
            WHERE accountId = ?');
            $accountQuery->execute([$adminAccountId]);
            $account = $accountQuery->rowCount();

            //Si le compte existe
            if ($account == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($account = $accountQuery->fetch())
                {
                    //On récupère les informations du compte
                    $adminAccountPseudo = stripslashes($account['accountPseudo']);
                }
                ?>
                
                <p>ATTENTION</p> 

                Vous êtes sur le point de supprimer le compte <em><?php echo $adminAccountPseudo ?></em>.<br />
                Confirmez-vous la suppression ?

                <hr>
                    
                <form method="POST" action="deleteAccountEnd.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminAccountId" value="<?php echo $adminAccountId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
                </form>
                
                <hr>

                <form method="POST" action="index.php">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php
            }
            //Si le compte n'existe pas
            else
            {
                echo "Erreur : Ce compte n'existe pas";
            }
            $accountQuery->closeCursor();
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