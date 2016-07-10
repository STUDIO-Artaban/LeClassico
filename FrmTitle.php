<?php
require("Package.php");
$Chp = "5";
$Clf = $_GET['Clf'];
$LogDate = "";
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
        $Query = "SELECT 'X' FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0) mysql_free_result($Result);
        else
        {   mysql_close($Link);
            $Msg = "Ton pseudo est inconnu!";
            include("Message.php");
            die();
        }
        mysql_close($Link);
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
<title>Le Classico: Forum Title</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#TitleRes {font-size: 10pt; font-family: Impact,Verdana,Lucida; font-weight: 100}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 0;margin-right: 0">
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td>
<!-- ********************************************************************************************************************* FORUM TITLE -->
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/TitConHG.jpg"></td>
        </tr>
        <tr>
        <td bgcolor="#ff0000">
            <table border=0 height=28 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/TitConBG.jpg"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="#ff0000">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/PuceLC.gif"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%" bgcolor="#ff0000" nowrap><font ID="BigTitle">&nbsp;<b>Fil&nbsp;d'Actualit&eacute;</b></font></td>
    <td>
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/TitConHD.jpg"></td>
        </tr>
        <tr>
        <td bgcolor="#ff0000">
            <table border=0 height=28 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/TitConBD.jpg"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr height=15>
    <td colspan=4></td>
    </tr>
    <tr>
    <td colspan=4 height=162 valign="top"><font face="Verdana,Lucida,Courier" size=2>Retrouves ci-dessous toute l'actualit&eacute; de tes
        camarades du <b>Classico</b>!... Et si tu te demandes pourquoi personne n'a encore rien publi&eacute;, c'est que tu n'es
        probablement abonn&eacute; à aucun d'entre eux. Tu es donc invit&eacute; &agrave; d&eacute;couvrir leurs publications directement
        depuis leur profile, et si tu es int&eacute;ress&eacute; alors tu pourras t'abonner et suivre ainsi leur actualité.</font>
    </td>
    </tr>
    </table>
<!-- ******************************************************************************************************************************** -->
</td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
<td><div style="width:10px"></div></td>
<td width=16 bgcolor="#e4e4e4">
    <table border=0 width=16 height=8 cellspacing=0 cellpadding=0>
    <tr>
    <td height="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
</body>
</html>
