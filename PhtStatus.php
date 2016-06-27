<?php
require("Package.php");
$Chp = "9";
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
<td width="100%" valign="bottom"><font ID="Desc"><?php
switch($res)
{   case 1: // Uploading en cours
    {   echo "Transfert en cours...";
        break;
    }
    case 2: // Non connecté
    {   echo "<font color=\"#ff0000\">Non connect&eacute;</font>!";
        break;
    }
    case 3: // Echec de la connexion au serveur SQL
    {   echo "<font color=\"#ff0000\">Echec</font> de la connexion au serveur <font color=\"#ff0000\">SQL</font>!";
        break;
    }
    case 4: // Pseudo du camarade inconnu
    {   echo "Pseudo du camarade <font color=\"#ff0000\">inconnu</font>!";
        break;
    }
    case 5: // Fichier ou extension du fichier non valide
    {   echo "Fichier ou extension du fichier <font color=\"#ff0000\">non valide</font>!";
        break;
    }
    case 6: // Echec de la génération du nouveau nom du fichier
    {   echo "<font color=\"#ff0000\">Echec</font> de la <font color=\"#ff0000\">g&eacute;n&eacute;ration</font> du nouveau nom du fichier!";
        break;
    }
    case 7: // Echec de la connexion au serveur FTP
    {   echo "<font color=\"#ff0000\">Echec</font> de la connexion au serveur <font color=\"#ff0000\">FTP</font>!";
        break;
    }
    case 8: // Login FTP incorrect
    {   echo "Login FTP <font color=\"#ff0000\">incorrect</font>!";
        break;
    }
    case 9: // Le fichier source n'existe pas
    {   echo "Le fichier source <font color=\"#ff0000\">n'existe pas</font>!";
        break;
    }
    case 10: // Taille de la photo > 200 Ko
    {   echo "Taille de la photo <font color=\"#ff0000\">&gt;</font> &agrave; <font color=\"#ff0000\">200 Ko</font>! Espace disque limit&eacute;!!!";
        break;
    }
    case 11: // Espace disque insuffisant
    {   echo "Espace disque <font color=\"#ff0000\">insuffisant</font>!";
        break;
    }
    case 12: // Echec de l'upload
    {   echo "Le Transfert a <font color=\"#ff0000\">&eacute;chou&eacute;</font>!";
        break;
    }
    case 13: // Echec durant la mise à jour des nouveaux nom de fichier
    {   echo "<font color=\"#ff0000\">Echec</font> durant <font color=\"#ff0000\">la mise &agrave; jour</font> des nouveaux noms de fichier!";
        break;
    }
    case 14: // Echec de l'ajout dans l'album
    {   echo "<font color=\"#ff0000\">Echec de l'ajout</font> dans l'album!";
        break;
    }
    case 15: // Ajout réussi
    {   echo "Ajout r&eacute;ussi !!!";
        break;
    }
    case 16: // Suppression en cours
    {   echo "Suppression en cours...";
        break;
    }
    case 17: // Echec suppression: Droits insuffisants
    {   echo "<font color=\"#ff0000\">Echec de la suppression</font>! Tu n'as pas les droits sur cet album!!!!!!";
        break;
    }
    case 18: // Echec suppression
    {   echo "<font color=\"#ff0000\">Echec de la suppression</font>! Contact le <font color=\"#8080ff\">Webmaster</font>!";
        break;
    }
    case 19: // Suppression réussi
    {   echo "Suppression r&eacute;ussi !!";
        break;
    }
    default: // Prêt
    {   echo "Pr&ecirc;t...";
        break;
    }
}
?></font></td>
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
