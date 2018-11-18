<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits modérateur (Accès 1) on le redirige vers l'accueil
if ($accountAccess < 1) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//On fait une recherche dans la base de donnée de tous les comptes et personnages
$accountQuery = $bdd->query("SELECT * FROM car_accounts, car_characters
WHERE accountId = characterAccountId
AND accountStatus = 0
ORDER by characterName");
?>

<form method="POST" action="banAccount.php">
    Liste des joueurs non banni <select name="modoAccountId" class="form-control">
        
        <?php
        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
        while ($account = $accountQuery->fetch())
        {
            $modoAccountId = stripslashes($account['accountId']);
            $modoAccountPseudo = stripslashes($account['accountPseudo']);
            $modoAccountCharacterName =  stripslashes($account['characterName']);
            ?>
            <option value="<?php echo $modoAccountId ?>"><?php echo "$modoAccountCharacterName ($modoAccountPseudo)"; ?></option>
            <?php
        }
        $accountQuery->closeCursor();
        ?>
    
    </select>
    Raison du ban <input type="text" name="modoBanReason" class="form-control" required>
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" name="ban" class="btn btn-default form-control" value="Bannir le compte">
</form>

<?php
//On fait une recherche dans la base de donnée de tous les comptes et personnages
$accountQuery = $bdd->query("SELECT * FROM car_accounts, car_characters
WHERE accountId = characterAccountId
AND accountStatus = 1
ORDER by characterName");
$accountRow = $accountQuery->rowCount();

//Si il y a des comptes banni
if ($accountRow > 0)
{
    ?>
    
    <hr>

    <form method="POST" action="unbanAccount.php">
        Liste des joueurs banni <select name="modoAccountId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($account = $accountQuery->fetch())
            {
                $modoAccountId = stripslashes($account['accountId']);
                $modoAccountPseudo = stripslashes($account['accountPseudo']);
                $modoAccountCharacterName = stripslashes($account['characterName']);
                $modoAccountCharacterReason = stripslashes($account['accountReason']);
                ?>
                <option value="<?php echo $modoAccountId ?>"><?php echo "$modoAccountCharacterName ($modoAccountPseudo) - ($modoAccountCharacterReason)"; ?></option>
                <?php
            }
            $accountQuery->closeCursor();
            ?>
        
        </select>
        <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
        <input type="submit" name="unban" class="btn btn-default form-control" value="Débannir le compte">
    </form>

    <?php
}

require_once("../html/footer.php");