<?php
require("Package.php");
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
    {   $Camarade = DistUserKeyId($Clf);
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
<title>Le Classico: ThePlayer v1.0</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<frameset border=0 rows="17,*,12">
        <frame src="MscTop.html?1" frameborder="no" scrolling="no" noresize>
        <frameset border=0 cols="4,1,*">
<!--                <frame src="<?php echo GetLocalSrvAddr(); ?>Black.html" frameborder="no" scrolling="no" noresize> -->
                <frame name="MscPad" src="MscPad.html" frameborder="no" scrolling="no" noresize>
                <frameset border=0 rows="*,*">
                        <frame name="MscMan" src="<?php echo GetDistSrvAddr(); ?>MscMan.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize>
                        <frame name="MscOpe" src="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
                        echo $Clf;
                        if(!Empty($man)) echo "&man=1";
                        else echo "&man=2"; ?>" frameborder="no" scrolling="no" noresize>
                </frameset>
                <frame name="MscMain" src="<?php echo GetDistSrvAddr(); ?>MscMain.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no">
        </frameset>
        <frame src="MscBot.html?1" frameborder="no" scrolling="no" noresize>
</frameset>
</head>
</html>
