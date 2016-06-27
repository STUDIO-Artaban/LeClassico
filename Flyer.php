<?php
require("Package.php");
$Chp = "14";
$Clf = $_GET['Clf'];
$aSearch = array("<",">");
$aReplace = array("&lt;","&gt;");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Flyer de l'Ev&eacute;nement</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: yellow}
</style>
<script type="text/javascript">
<!--
// OnCloseWindow ////////////////////////////////////////////////////////////////////////////////////
function OnCloseWindow()
{   self.close();
}
//-->
</script>
</head>
<body bgcolor="#ff8000" style="margin-top: 0;margin-bottom: 0;margin-left: 0;margin-right: 0">
<!-- ****************************************************************************************************************************************** FLYER -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 width="100%" height=35 cellspacing=0 cellpadding=0>
    <tr>
    <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosEvent.jpg"></td>
    <td width=10>
        <table border=0 width=10 height=33 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%" valign="middle" nowrap><font ID="Title"><font style="font-size: 16pt"><font color="#000000">Ev.&nbsp;:&nbsp;</font><?php echo str_replace($aSearch,$aReplace,base64_decode(urldecode($sr))); ?></font></font></td>
    <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    </tr>
    <tr bgcolor="#ffffff">
    <td>
        <table border=0 width="100%" height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td>
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#000000">
    <tr>
    <td width="100%" align="center" valign="middle"><img src="<?php echo GetFolder(); ?>/Flyers/<?php echo base64_decode(urldecode($fly)); ?>"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td>
    <table border=0 width="100%" height=35 cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr bgcolor="#ffffff">
    <td width=51>
        <table border=0 width=51 height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 height=33 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><font ID="Title"><font color="#000000">Date:&nbsp;</font><font color="#00ff00"><?php echo "$dy/$mn/$yr"; ?></font></font></td>
    <td align="right" valign="middle"><input type="button" onclick="javascript:OnCloseWindow()" value="Fermer"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<!-- ************************************************************************************************************************************************ -->
</body>
</html>
