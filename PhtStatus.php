<?php
require("Package.php");
$Chp = "9";
$Clf = $_GET['Clf'];
$res = $_GET['res'];
/* ERROR MESSAGE (res):
   0 - Prêt
   1 - Transfert en cours
   2 - Clef vide (non connecté)
   3 - Echec de la connexion au serveur SQL
   4 - Pseudo du camarade inconnu
   5 - Fichier ou extension du fichier non valide
   6 - Echec de la génération du nouveau nom du fichier
   7 - Echec de la connexion au serveur FTP
   8 - Echec du login FTP
   9 - Fichier source inexistant
   10 - Taille de la photo > 200 ko
   11 - Espace disque du serveur insuffisant
   12 - Echec de l'upload
   13 - Echec durant la mise à jour des nouveaux nom de fichier
   14 - Echec de l'ajout dans l'album
   15 - Ok...
   16 - Suppression en cours
   17 - Echec de la suppression de la photo: Droits insuffisants
   18 - Echec de la suppression de la photo: ???
   19 - Suppression réussi
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Photo Status</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
#Desc {font-size: 10pt; font-family: Verdana,Lucida,Courier; color: black}
#Entete {font-size: 10pt; font-family: Impact,Verdana,Lucida}
</style>
<?php
if((!Empty($res))&&($res == 1))
{   // Uploading
?>
<script type="text/javascript">
<!--
// Commandes /////////////////////////////////////////////////////////////////////////////
top.PhtManager.document.getElementById("BtnAddPhoto").disabled="disabled";
top.PhtManager.document.getElementById("NewFile").disabled="disabled";
-->
</script>
<?php
    // Uploading
}
?>
</head>
<body bgcolor="#ffffff" style="margin-top: 0px;margin-left: 10px">
<!-- *********************************************************************************************************************************** PHOTO STATUS -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=45 valign="top">
    <table border=0 width=45 cellspacing=0 cellpadding=0>
    <tr>
    <td><font ID="Entete">Statut :</font></td>
    </tr>
    </table>
</td>
<td width="100%" valign="bottom"><font ID="Desc"><?php echo GetResult($res); ?></font></td>
</tr>
</table><hr>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ff8000">
<tr>
<td width="100%" bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<?php
if((!Empty($res))&&($res <= 19)&&($res >= 16))
{   // Opération de suppression
?>
<td width=50>
    <table border=0 width=50 height=47 cellspacing=0 cellpadding=0>
    <tr>
    <td align="left"><img src="<?php echo GetFolder(); ?>/Images/SubOranHG.jpg"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/DosPhoto.gif"></td>
    </tr>
    <tr>
    <td align="left"><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    </tr>
    </table>
<?php
    // Opération de suppression
}
else
{   // Pas opération de suppression
?>
<td width=42>
    <table border=0 width=42 height=47 cellspacing=0 cellpadding=0>
    <tr>
    <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosCam.jpg"></td>
    </tr>
    <tr>
    <td align="left" valign="bottom"><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    </tr>
    </table>
<?php
    // Pas opération de suppression
}
?>
</td>
<td width=2>
    <table border=0 width=2 cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=325>
    <table border=0 width=325 cellspacing=0 cellpadding=0>
    <tr>
    <td width=5><?php
    if((!Empty($res))&&(($res == 1)||($res == 16)))
    {   // Transfert/Suppression en cours
    ?><img src="<?php echo GetFolder(); ?>/Images/Load.gif"></td>
    <?php
        // Transfert/Suppression en cours
    }
    else
    {   // Pas Transfert/Suppression
    ?><img src="<?php echo GetFolder(); ?>/Images/NoLoad.gif"></td>
    <?php
        // Pas Transfert/Suppression
    }
    ?>
    </tr>
    </table>
</td>
<td width=7>
    <table border=0 width=7 cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<?php
if((!Empty($res))&&($res <= 19)&&($res >= 16))
{   // Opération de suppression
?>
<td width=42>
    <table border=0 width=42 height=47 cellspacing=0 cellpadding=0>
    <tr>
    <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosTrash.jpg"></td>
    </tr>
    <tr>
    <td align="right"><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
    </tr>
    </table>
<?php
    // Opération de suppression
}
else
{   // Pas opération de suppression
?>
<td width=50>
    <table border=0 width=50 cellspacing=0 cellpadding=0>
    <tr>
    <td align="right"><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/DosPhoto.gif"></td>
    </tr>
    <tr>
    <td align="right"><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
    </tr>
    </table>
<?php
    // Pas opération de suppression
}
?>
</td>
</tr>
</table>
<!-- ************************************************************************************************************************************************ -->
</body>
</html>
