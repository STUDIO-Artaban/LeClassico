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
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#TitleRes {font-size: 10pt; font-family: Impact,Verdana,Lucida; font-weight: 100}
</style>
<script type="text/javascript">
<!--
// ModeAutonome //////////////////////////////////////////////////////////////////////////
function ModeAutonome()
{   var WndForum=window.open("FrmAuto.php?Clf=<?php echo $Clf; ?>","WndForum","left=0,top=0,width=570,height=500,resizable=1");
    WndForum.focus();
}
-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 0;margin-right: 0">
<!-- ******************************************************************************************************************************* FORUM TITLE -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5>
    <table border=0 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubMnuHG.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubMnuBG.jpg"></td>
    </tr>
    </table>
</td>
<td width=10>
    <table border=0 height=20 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td height="100%"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><font ID="Title">&nbsp;Liste&nbsp;des&nbsp;messages</font></td>
    </tr>
    </table>
</td>
<td width=5>
    <table border=0 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubMnuHD.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubMnuBD.jpg"></td>
    </tr>
    </table>
</td>
<td width=60>
    <table border=0 width=160 cellspacing=0 cellpadding=0>
    <tr>
    <td align="center"><input type="button" onclick="javascript:ModeAutonome()" style="font-family: Verdana;font-size: 10pt" value="Mode Enfenêtré"></td>
    </tr>
    </table>
</td>
<td width=16 bgcolor="#e4e4e4">
    <table border=0 width=16 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=16 bgcolor="#e4e4e4">
    <table border=0 width=16 height=8 cellspacing=0 cellpadding=0>
    <tr>
    <td height="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<!-- ********************************************************************************************************************************************* -->
</body>
</html>
