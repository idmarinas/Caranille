<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow == 0) { exit(header("Location: ../../modules/main/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['token'])
&& isset($_POST['escape']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //Si il s'agit d'un combat d'arène on ajoute 1 point de défaite lié à la fuite
        if ($battleType == "Arena")
        {
            //On ajoute un point de défaite au joueur
            $updateCharacter = $bdd->prepare("UPDATE car_characters
            SET characterArenaDefeate = characterArenaDefeate + 1
            WHERE characterId = :characterId");
            $updateCharacter->execute([
            'characterId' => $characterId]);
            $updateCharacter->closeCursor();
        }
        
        //On détruit le combat en cours
        $deleteBattle = $bdd->prepare("DELETE FROM car_battles 
        WHERE battleId = :battleId");
        $deleteBattle->execute(array('battleId' => $battleId));
        $deleteBattle->closeCursor();
        ?>
        
        Vous avez fuit le combat !
            
        <hr>
    
        <form method="POST" action="../../modules/town/index.php">
            <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
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
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>