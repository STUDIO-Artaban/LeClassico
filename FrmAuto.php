<?php
require("Package.php");
$Chp = "5";
$Clf = $_GET['Clf'];
if(!Empty($Clf))
{   // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(Empty($Link))
    {   $Msg = "Connexion au serveur impossible!";
        include("Message.php");
        die();
    }
    else
    {   $Camarade = UserKeyIdentifier($Clf);
        $Query = "SELECT 'X' FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0) mysql_free_result($Result);
        else
        {   mysql_close($Link);
            $Msg = "Ton pseudo est inconnu!";
            include("Message.php");
            die();
        }
    }
}
else
{   //$Msg = "Tu n'est pas connect&eacute;!";
    //include("Message.php");
    //die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Fil d'actualit&eacute;</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<frameset border=0 rows="*<?php
        if(!Empty($Clf)) echo ",75";
        else echo ",45";
        ?>">
        <frame src="FrmMsg.php?Clf=<?php echo $Clf; ?>#EndMsg" frameborder="no" scrolling="yes" style="overflow-x: auto">
        <?php
        if(!Empty($Clf))
        {   // Connecté
            ?><frame src="FrmSend.php?md=1&Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize><?php
            // Connecté
        }
        else
        {   // Non Connecté
            ?><frame src="FrmClose.html" frameborder="no" scrolling="no" noresize><?php
            // Non Connecté
        }
        ?>
</frameset>
</head>
</html>
