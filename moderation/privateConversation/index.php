<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits modérateurs (Accès 1) on le redirige vers l'accueil
if ($accountAccess < 1) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//On fait une requête pour vérifier la liste des conversations dans la base de donnée
$privateConversationReportedQuery = $bdd->query("SELECT * FROM car_private_conversation
WHERE privateConversationReported = 1");
$privateConversationReportedRow = $privateConversationReportedQuery->rowCount();

//S'il existe une ou plusieurs conversation dans la messagerie privée
if ($privateConversationReportedRow > 0) 
{
    ?>

    <form method="POST" action="showAllConversation.php">
        Liste des conversations signalée : <select name="adminPrivateConversationId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($privateConversationReported = $privateConversationReportedQuery->fetch())
            {
                //On récupère les informations de la conversation
                $adminPrivateConversationId = stripslashes($privateConversationReported['privateConversationId']);
                $adminPrivateConversationCharacterOneId = stripslashes($privateConversationReported['privateConversationCharacterOneId']);
                $adminPrivateConversationCharacterTwoId = stripslashes($privateConversationReported['privateConversationCharacterTwoId']);
                
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
                <option value="<?php echo $adminPrivateConversationId ?>"><?php echo "$adminPrivateConversationCharacterOneName et $adminPrivateConversationCharacterTwoName" ?></option>
                <?php
            }
            ?>
            
        </select>
        <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
        <input type="submit" name="showAllConversation" class="btn btn-default form-control" value="Afficher toute la conversation">
    </form>
    
    <hr>

    <?php
}
//Si il y a aucun autre joueur
else
{
    echo "Il n'y a actuellement aucune conversation signalée";
}
$privateConversationReportedQuery->closeCursor();
?>

<hr>

<form method="POST" action="index.php">
    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
</form>

<?php require_once("../../html/footer.php"); ?>