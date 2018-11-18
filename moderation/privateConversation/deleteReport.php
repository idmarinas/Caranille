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
&& isset($_POST['deleteSignaled']))
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
            $privateConversationReportedQuery = $bdd->prepare("SELECT * FROM car_private_conversation
            WHERE privateConversationReported = 1
            AND privateConversationId = ?");
            $privateConversationReportedQuery->execute([$adminPrivateConversationId]);
            $privateConversationReportedRow = $privateConversationReportedQuery->rowCount();

            //Si la conversation existe
            if ($privateConversationReportedRow == 1) 
            {
                ?>

                ATTENTION : Vous êtes sur le point de supprimer le signalement de cette conversation.<br />Si vous validez cette demande vous ne pourrez plus consulter la conversation sauf si celle-ci est à nouveau
                
                <hr>
                
                <form method="POST" action="deleteReportEnd.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminPrivateConversationId" value="<?php echo $adminPrivateConversationId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="deleteSignaledEnd" value="Je confirme">
                </form>
                
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
            $privateConversationReportedQuery->closeCursor();  
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