<?php 
require_once("../../kernel/kernel.php");
require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['secretAnswer']) 
&& isset($_POST['accountPseudo'])
&& isset($_POST['token'])
&& isset($_POST['resetPasswordEnd']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //On récupère les valeurs du formulaire dans une variable
        $secretAnswer = htmlspecialchars(addslashes($_POST['secretAnswer']));
        $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));

        $accountQuery = $bdd->prepare("SELECT * FROM car_accounts
        WHERE accountPseudo = ?");
        $accountQuery->execute([$accountPseudo]);
        $accountRow = $accountQuery->rowCount();

        //S'il existe une ou plusieurs conversation dans la messagerie privée
        if ($accountRow > 0) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($account = $accountQuery->fetch())
            {
                //On récupère les informations de la conversation
                $accountId = stripslashes($account['accountId']);
                $accountSecretQuestion = stripslashes($account['accountSecretQuestion']);
                $accountSecretAnswer = stripslashes($account['accountSecretAnswer']);
            }

            //On vérifie si le joueur à jamais crée sa question secrête
            if ($accountSecretQuestion != "" && $accountSecretAnswer != "")
            {
                //Si la réponse secrète entrée est correcte
                if ($secretAnswer == $accountSecretAnswer)
                {
                    //On génère un nouveau mot de passe
                    $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

                    for($i=0;$i<20;$i++)
                    {
                        $newPasswordEmail .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
                    }

                    $newPassword = sha1($newPasswordEmail);

                    //On met à jour le mot de passe dans la base de donnée
                    $updateAccount = $bdd->prepare('UPDATE car_accounts 
                    SET accountPassword = :newPassword
                    WHERE accountId = :accountId');
                    $updateAccount->execute(array(
                    'newPassword' => $newPassword,
                    'accountId' => $accountId));
                    $updateAccount->closeCursor();
                    ?>

                    Voici le nouveau mot de passe : <?php echo $newPasswordEmail ?>

                    <hr>

                    <form method="POST" action="../../modules/login/index.php">
                        <input type="submit" name="back" class="btn btn-default form-control" value="Retour"><br>
                    </form>

                    <?php
                }
                else
                {
                    ?>
    
                    La réponse entrée est incorrect
                
                    <hr>
                
                    <form method="POST" action="index.php">
                        <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                        <input type="submit" name="back" class="btn btn-default form-control" value="Retour"><br>
                    </form>
                
                    <?php
                }
            }
            else
            {
                ?>
            
                Vous n'avez actuellement aucune question secrète
            
                <hr>
            
                <form method="POST" action="index.php">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" name="back" class="btn btn-default form-control" value="Retour"><br>
                </form>
            
                <?php
            }
        }
        else
        {
            ?>

            Ce nom de joueur n'existe pas

            <hr>

            <form method="POST" action="index.php">
                <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                <input type="submit" name="back" class="btn btn-default form-control" value="Retour"><br>
            </form>

            <?php
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
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>