<?php
require("Package.php");
$Chp = "6";
$Clf = $_GET['Clf'];
$Title = "Nouveau Message";
if(!Empty($msgtpe))
{   if($msgtpe == 1) $Title = "Message Re&ccedil;u";
    else $Title = "Message Envoy&eacute;";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Mail Pad Top</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
#BigTitle {font-size: 22pt; font-family: Impact,Verdana,Lucida; color: white}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-bottom: 0;margin-left: 0;margin-right: 0">
<table border=0 width=300 cellspacing=0 cellpadding=0 bgcolor="#ff0000">
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/DosMail.jpg"></td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%"><font ID="BigTitle"><?php echo $Title; ?></font></td>
<td valign="bottom"><img src="<?php echo GetFolder(); ?>/Images/RedBlaBD.jpg"></td>
<td valign="top" bgcolor="#ffffff">
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td bgcolor="#ff0000">
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/RedCadInHG.jpg"></td>
    </tr>
    </table>
</td>
</tr>
</table>
</body>
</html>
