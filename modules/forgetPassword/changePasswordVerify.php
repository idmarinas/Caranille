<?php 
require_once("../../kernel/kernel.php");
require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['accountPseudo'])
&& isset($_POST['token'])
&& isset($_POST['resetPassword']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();

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
                ?>

                Afin de pouvoir réinitialiser votre mot de passe veuillez répondre à votre question secrète

                <hr>

                Question : <?php echo $accountSecretQuestion ?><br />

                <form method="POST" action="changePasswordVerifyEnd.php">
                    Réponse : <input type="text" class="form-control" name="secretAnswer" maxlength="100" required>
                    <input type="hidden" class="btn btn-default form-control" name="accountPseudo" value="<?php echo $accountPseudo ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" name="resetPasswordEnd" class="btn btn-default form-control" value="Modifier la question secrête"><br>
                </form>

                <?php
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