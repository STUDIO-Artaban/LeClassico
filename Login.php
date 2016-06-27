<?php
require("Package.php");
$Msg = $_GET['Msg'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Login</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
a {font-size: 14pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; margin-top: 0px; border: 0px}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Police {font-size: 12pt; font-family: Impact,Verdana,Lucida,Courier; color: white}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px;margin-right: 10px">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ************************************************************************************************************************ LOGIN -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
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
    <td width="100%" bgcolor="#ff0000"><font ID="BigTitle">&nbsp;<b>Login</b></font></td>
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
    <td width=15>
        <table border=0 width=15 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
</table><br>
<font face="Verdana,Lucida,Courier" size=2>Tu n’est pas inscrit ! Dommage ! Tu veux savoir comment t’inscrire ?<br>
 C’est facile, tire bien sur la chaîne d’évènements qui t’as permis d’arriver jusqu’ici...<br><br>
 Et si c’est le hasard qui ta conduit là, alors exerces toi encore un peu en saisissant un pseudo et un code confidentiel au hasard. On ne sait jamais...Ah! Ah!</font>
<br><br>
<form action="index.php" target="_top" method="post">
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width="50%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=250>
        <table border=0 width=250 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/WBlaFonBD.jpg"></td>
        <td width="100%" bgcolor="#8000ff" align="center" colspan=3 nowrap><?
        if(strcmp($Msg,""))
        {   // Message
            if(!strcmp($Msg,"0")) echo "<font ID='Police'><font color=\"yellow\">A Bient&ocirc;t...</font></font>";
            else echo "<font ID='Police'><font color=\"red\">$Msg</font></font></td>\n";
        }
        else
        {   ?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <?php
        }
        ?>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonWBlaGB.jpg"></td>
        </tr>
        <tr>
        <td width=23 bgcolor="#8000ff">
            <table border=0 width=23 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=23>
            <table border=0 width=23 height=100 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/FonClaBD.jpg"></td>
            </tr>
            <tr>
            <td height=100 bgcolor="#8080ff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/FonClaHD.jpg"></td>
            </tr>
            </table>
        </td>
        <td width=158 bgcolor="#8080ff">
            <table border=0 cellspacing=0 cellpadding=0 bgcolor="#8080ff">
            <tr>
            <td align="left"><font ID="Police">Pseudo</font></td>
            </tr>
            <tr>
            <td><input type="text" style="font-size: 10pt; width: 161px; font-family: Verdana,Lucida,Courier" name="psd" maxlength=30></td>
            </tr>
            <tr>
            <td align="left"><font ID="Police">Code Confidentiel</font></td>
            </tr>
            <tr>
            <td><input type="password" style="font-size: 10pt; width: 161px; font-family: Verdana,Lucida,Courier" name="ccf" maxlength=20></td>
            </tr>
            <tr>
            <td height=10><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            <tr>
            <td align="center"><input type="submit" style="font-family: Verdana;font-size: 10pt" value="Connexion"></td>
            </tr>
            </table>
        </td>
        <td width=23>
            <table border=0 width=23 height=100 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/ClaFonGB.jpg"></td>
            </tr>
            <tr>
            <td height=100 bgcolor="#8080ff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/ClaFonGH.jpg"></td>
            </tr>
            </table>
        </td>
        <td width=23 bgcolor="#8000ff">
            <table border=0 width=23 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/WBlaFonHD.jpg"></td>
        <td width="100%" bgcolor="#8000ff" colspan=3><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonWBlaGH.jpg"></td>
        </tr>
        </table>
</td>
<td width="50%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
</form>
<table border=0 height=20 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
</body>
</html>
