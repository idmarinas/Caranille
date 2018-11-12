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
&& isset($_POST['finalDelete']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['adminAccountId'])
        && $_POST['adminAccountId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

            //On fait une requête pour vérifier si le compte choisit existe
            $accountQuery = $bdd->prepare('SELECT * FROM car_accounts 
            WHERE accountId = ?');
            $accountQuery->execute([$adminAccountId]);
            $account = $accountQuery->rowCount();
            $accountQuery->closeCursor();

            //Si le compte existe
            if ($account == 1) 
            {
                //On supprime le compte de la base de donnée
                $accountDeleteQuery = $bdd->prepare("DELETE FROM car_accounts
                WHERE accountId = ?");
                $accountDeleteQuery->execute([$adminAccountId]);
                $accountDeleteQuery->closeCursor();

                //On supprime le personnage de la base de donnée
                $characterDeleteQuery = $bdd->prepare("DELETE FROM car_characters
                WHERE characterAccountId = ?");
                $characterDeleteQuery->execute([$adminAccountId]);
                $characterDeleteQuery->closeCursor();
                
                //On supprime les combats de ce personnage de la base de donnée
                $characterBattleDeleteQuery = $bdd->prepare("DELETE FROM car_battles
                WHERE battleCharacterId = ?");
                $characterBattleDeleteQuery->execute([$adminAccountId]);
                $characterBattleDeleteQuery->closeCursor();
                
                //On supprime l'inventaire de ce personnage de la base de donnée
                $characterInventoryDeleteQuery = $bdd->prepare("DELETE FROM car_inventory
                WHERE inventoryCharacterId = ?");
                $characterInventoryDeleteQuery->execute([$adminAccountId]);
                $characterInventoryDeleteQuery->closeCursor();
                
                //On supprime le bestiaire de ce personnage de la base de donnée
                $characterBestiaryDeleteQuery = $bdd->prepare("DELETE FROM car_bestiary
                WHERE bestiaryCharacterId = ?");
                $characterBestiaryDeleteQuery->execute([$adminAccountId]);
                $characterBestiaryDeleteQuery->closeCursor();
                ?>

                Le compte a bien été supprimé

                <hr>
                    
                <form method="POST" action="index.php">
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