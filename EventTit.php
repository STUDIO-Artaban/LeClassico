<?php
require("Package.php");
$Chp = "14";
$Clf = $_GET['Clf'];
$dy = $_GET['dy'];
$mn = $_GET['mn'];
$yr = $_GET['yr'];
$aJour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$aMois = array("Janvier","F&eacute;vrier","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre");
$aMonth = array("January","February","March","April","May","June","July","August","September","October","November","December");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Event Title</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
#Title {font-size: 16pt; font-family: Impact,Verdana,Lucida; color: black}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 0;margin-right: 0">
<!-- *********************************************************************************************************************************** EVENT TITLE -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr>
    <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosEvent.jpg"></td>
    <td width=10>
        <table border=0 width=10 height=34 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%" nowrap><font ID="Title">Date:&nbsp;<font color="#ffff00"><?php
    if((!Empty($dy))&&(!Empty($mn))&&(!Empty($yr))) echo $aJour[date("w",strtotime("$dy ".$aMonth[$mn-1]." $yr"))]." $dy ".$aMois[($mn-1)];
    else
    {   $aDate = getdate();
        echo $aJour[date("w")]." ".trim($aDate["mday"])." ".trim($aMois[($aDate["mon"]-1)]);
    }
    ?></font></font></td>
    <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    </tr>
    </table>
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td width=2 bgcolor="#ff8000">
        <table border=0 width=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/InOranHG.jpg"></td>
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/InOranHD.jpg"></td>
    <td width=2 bgcolor="#ff8000">
        <table border=0 width=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
</table>
<!-- ************************************************************************************************************************************************ -->
</body>
</html>
