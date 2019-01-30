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
&& isset($_POST['showAllConversation']))
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
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($privateConversationReported = $privateConversationReportedQuery->fetch())
                {
                    //On récupère les informations de la conversation
                    $adminPrivateConversationCharacterOneId = stripslashes($privateConversationReported['privateConversationCharacterOneId']);
                    $adminPrivateConversationCharacterTwoId = stripslashes($privateConversationReported['privateConversationCharacterTwoId']);
                }
                
                //On fait une requête pour récupérer le nom du personnage dans la base de donnée
                $characterOneQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");
                $characterOneQuery->execute([$adminPrivateConversationCharacterOneId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($characterOne = $characterOneQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $adminPrivateConversationCharacterOneName = stripslashes($characterOne['characterName']);
                }
                $characterOneQuery->closeCursor();

                //On fait une requête pour récupérer le nom du personnage dans la base de donnée
                $characterTwoQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");
                $characterTwoQuery->execute([$adminPrivateConversationCharacterTwoId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($characterTwo = $characterTwoQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $adminPrivateConversationCharacterTwoName = stripslashes($characterTwo['characterName']);
                }
                $characterTwoQuery->closeCursor();
                ?>
                
                <p>Conversation de <?php echo $adminPrivateConversationCharacterOneName ?> et <?php echo $adminPrivateConversationCharacterTwoName ?></p>

                <?php
                //On fait une recherche dans la base de donnée des messages de la conversation
                $privateConversationMessageQuery = $bdd->prepare("SELECT * FROM car_private_conversation_message
                WHERE privateConversationMessagePrivateConversationId = ?');
                $privateConversationMessageQuery->execute([$adminPrivateConversationId]);
                $privateConversationMessageRow = $privateConversationMessageQuery->rowCount();
                
                //Si il y a déjà eu au moins un message
                if ($privateConversationMessageRow > 0)
                {
                    ?>
                                        
                    <table class="table">
        
                        <tr>
                            <td>
                                Date/Heure
                            </td>
                            
                            <td>
                                Pseudo
                            </td>
                            
                            <td>
                                Message
                            </td>
                        </tr>
                        
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($privateConversationMessage = $privateConversationMessageQuery->fetch())
                        {
                            //On récupère les informations du message de la discution
                            $adminPrivateConversationMessageCharacterId = stripslashes($privateConversationMessage['privateConversationMessageCharacterId']);
                            $adminPrivateConversationMessageDateTime = stripslashes($privateConversationMessage['privateConversationMessageDateTime']);
                            $adminPrivateConversationMessage = stripslashes($privateConversationMessage['privateConversationMessage']);
                            
                            //On fait une requête pour récupérer le nom du personnage dans la base de donnée
                            $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                            WHERE characterId = ?");
                            $characterQuery->execute([$adminPrivateConversationMessageCharacterId]);
                            
                            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                            while ($character = $characterQuery->fetch())
                            {
                                //On récupère les informations du personnage
                                $adminPrivateConversationCharacterName = stripslashes($character['characterName']);
                            }
                            $characterQuery->closeCursor();
                            ?>
                            
                            <tr>
                                
                                <td>
                                    <?php echo strftime('%d-%m-%Y - %H:%M:%S',strtotime($adminPrivateConversationMessageDateTime)) ?> 
                                </td>
                                
                                <td>
                                    <?php echo $adminPrivateConversationCharacterName ?>
                                </td>
                                
                                <td>
                                    <?php echo $adminPrivateConversationMessage ?>
                                </td>
                                
                            </tr>
                            
                        <?php
                        }
                        ?>
                    
                    </table>
                    
                    <?php
                }
                $privateConversationMessageQuery->closeCursor();
                ?>
                
                <form method="POST" action="deleteReport.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminPrivateConversationId" value="<?php echo $adminPrivateConversationId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="deleteSignaled" value="Supprimer le signalement">
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
                echo "Erreur : Cette conversation n'existe pas ou vous n'en faite pas parti";
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