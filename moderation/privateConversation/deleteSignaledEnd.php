<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits modérateurs (Accès 1) on le redirige vers l'accueil
if ($accountAccess < 1) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPrivateConversationId'])
&& isset($_POST['token'])
&& isset($_POST['deleteSignaledEnd']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;
         
        //Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();

        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['adminPrivateConversationId'])
        && $_POST['adminPrivateConversationId'] >= 0)
        {
            //On récupère l'id du formulaire précédent
            $adminPrivateConversationId = htmlspecialchars(addslashes($_POST['adminPrivateConversationId']));
            
            //On vérifie si cette conversation est bien signalée
            $privateConversationSignaledQuery = $bdd->prepare("SELECT * FROM car_private_conversation
            WHERE privateConversationSignaled = 1
            AND privateConversationId = ?");
            $privateConversationSignaledQuery->execute([$adminPrivateConversationId]);
            $privateConversationSignaledRow = $privateConversationSignaledQuery->rowCount();

            //Si la conversation existe
            if ($privateConversationSignaledRow == 1) 
            {
                //On met à jour la fiche du personnage
                $updatePrivateConversation = $bdd->prepare("UPDATE car_private_conversation SET
                privateConversationSignaled = 0
                WHERE privateConversationId = :adminPrivateConversationId");
                $updatePrivateConversation->execute(array(
                'adminPrivateConversationId' => $adminPrivateConversationId));
                $updatePrivateConversation->closeCursor();
                ?>

                Cette conversation n'est plus signalée
                
                <hr>

                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                <?php
            }
            //Si la conversation n'exite pas ou que le joueur n'y a pas accès
            else
            {
                echo "Erreur : Cette conversation n'existe pas ou n'est pas signalée";
            }
            $privateConversationSignaledQuery->closeCursor();  
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
    echo "Erreur : Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>