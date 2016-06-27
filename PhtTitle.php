<?php
require("Package.php");
$Clf = $_GET['Clf'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Photo Manager Title</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
#Desc {font-size: 10pt; font-family: Verdana,Lucida,Courier; color: black}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0px;margin-bottom: 0px;margin-left: 10px;margin-right: 0px">
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/PuceLC.gif"></td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%" valign="top"><font ID="Desc">D'ici, tu vas pouvoir g&eacute;rer les photos contenues dans tes albums, et
 tu pourras &eacute;galement ajouter des photos dans les albums partag&eacute;s, cr&eacute;&eacute;s par les autres camarades (Menu:
 <a href="<?php echo GetFolder(); ?>/index.php?Chp=8&Clf=<?php echo $Clf; ?>" style="font-size: 10pt" target="_top">Cr&eacute;er/D&eacute;truire 1
 Album</a>). Pour cela, tu dois sélectionner un album puis utiliser les commandes, pour en ajouter ou en supprimer une.</font></td>
</tr>
</table>
</body>
</html>
