<?php
require("Package.php");
$Chp = $_GET['Chp'];
$Clf = $_GET['Clf'];
$Mnu = $_GET['Mnu'];
$iMenu = 0;
$bAdmin = false;
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
        $Query = "SELECT CAM_Admin FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if($aRow = mysql_fetch_array($Result))
        {   if($aRow["CAM_Admin"] == 1) $bAdmin = true;
            mysql_free_result($Result);
        }
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
if(!Empty($Mnu))
{   if(!strcmp($Mnu,"1")) $iMenu = 1;
    elseif(!strcmp($Mnu,"2")) $iMenu = 2;
    elseif(!strcmp($Mnu,"3")) $iMenu = 3;
    elseif(!strcmp($Mnu,"4")) $iMenu = 4;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Menu</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
table {font-size: 12pt; font-family: Impact,Verdana,Lucida,Courier}
a {font-size: 12pt; font-family: Impact,Verdana,Lucida,Courier; color: black}
</style>
</head>
<body bgcolor="#e4e4e4" style="margin-top: 0;margin-left: 0">
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=88><img src="<?php echo GetFolder(); ?>/Images/MenHauGau.jpg"></td>
<td width=49>
    <table border=0 width=49 cellspacing=0 cellpadding=0>
    <tr height=6>
    <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr height=9>
    <td bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=88><img src="<?php echo GetFolder(); ?>/Images/MenHauDro.jpg"></td>
</tr>
</table>
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/MilHauGau.jpg"></td>
<td width="100%" bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/MenTitGau.jpg"></td>
<td bgcolor="#000000"><font color="#ffffff"><?php
switch($iMenu)
{   case 0: // Menu Principal
         echo "Menu&nbsp;Principal";
         break;
    case 1: // Camarades
         echo "Camarades";
         break;
    case 2: // Photos
         echo "Photos";
         break;
    case 3: // Musique
         echo "Musique";
         break;
    case 4: // Evenement
         echo "Ev&eacute;nements";
         break;
}
?></font></td>
<td><img src="<?php echo GetFolder(); ?>/Images/MenTitDro.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/MilHauDro.jpg"></td>
</tr>
</table><?php
switch($iMenu)
{   case 0: // Menu Principal
?>
<!-- **************************************************************************************************************************** ACCUEIL -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=1&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"1"))) echo "<font color=\"gray\">Accueil</font>";
else echo "Accueil";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** CAMARADES -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/Menu.php?Mnu=1&Chp<?php echo $Chp; ?>=&Clf=<?php echo $Clf; ?>">Camarades</a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** PHOTOS -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<?php
if(!Empty($Clf))
{   // Connecté
    ?><td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/Menu.php?Mnu=2&Chp<?php echo $Chp; ?>=&Clf=<?php echo $Clf; ?>">Photos</a></td><?php
    // Connecté
}
else
{   // Non Connecté
    ?><td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=7" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"7"))) echo "<font color=\"gray\">Photos</font>";
    else echo "Photos";
    ?></a></td><?php
    // Non Connecté
}
?>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** MUSIQUE -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<!-- <td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/Menu.php?Mnu=3&Chp<?php echo $Chp; ?>=&Clf=<?php echo $Clf; ?>">Musique</a></td> -->
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=10&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"10"))) echo "<font color=\"gray\">Musique</font>";
else echo "Musique";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table><?php
         // Menu Principal
         break;
    case 1: // Camarades
         if(!Empty($Clf))
         {   // Connecté
?>
<!-- **************************************************************************************************************************** INFOS -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=2&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"2"))) echo "<font color=\"gray\">Profile</font>";
else echo "Profile";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<?php
    // Connecté
}
// Test s'il s'agit d'un Admin
if($bAdmin)
{   // Admin
?>
<!-- **************************************************************************************************************************** AJOUTER CAMARADE -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=3&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"3"))) echo "<font color=\"gray\">Ajouter&nbsp;1&nbsp;Camarade</font>";
else echo "Ajouter&nbsp;1&nbsp;Camarade";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table><?php
   // Admin
}
?>
<!-- **************************************************************************************************************************** RECHERCHER -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=4&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"4"))) echo "<font color=\"gray\">Rechercher</font>";
else echo "Rechercher";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** FORUM -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=5&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"5"))) echo "<font color=\"gray\">Fil d'actualité</font>";
else echo "Fil d'actualité";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<?php
if(!Empty($Clf))
{   // Connecté
?>
<!-- **************************************************************************************************************************** MESSAGERIE -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=6&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"6"))) echo "<font color=\"gray\">Messagerie</font>";
else echo "Messagerie";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
         <?php
             // Connecté
         }
         // Camarades
         break;
    case 2: // Photos
         // Photos
?>
<!-- **************************************************************************************************************************** VOIR ALBUMS -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=7&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"7"))) echo "<font color=\"gray\">Voir&nbsp;Albums Photos</font>";
else echo "Voir&nbsp;Albums Photos";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** CREER/DETRUIRE ALBUM -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=8&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"8"))) echo "<font color=\"gray\">Cr&eacute;er/D&eacute;truire&nbsp;1&nbsp;Album</font>";
else echo "Cr&eacute;er/D&eacute;truire&nbsp;1&nbsp;Album";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** AJOUTER/SUPPRIMER PHOTO -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00">
    <table border=0 width=117 cellspacing=0 cellpadding=0>
    <tr>
    <td width=13>
        <table border=0 width=13 height=20 cellspacing=0 cellpadding=0>
        <tr height=5>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr height=15>
        <td><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
        </tr>
        </table>
    </td>
    <td width=104><a href="<?php echo GetFolder(); ?>/index.php?Chp=9&Clf=<?php echo $Clf; ?>" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"9"))) echo "<font color=\"gray\">Ajouter/Supprimer&nbsp;1</font>";
    else echo "Ajouter/Supprimer&nbsp;1";
    ?></a></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><a href="<?php echo GetFolder(); ?>/index.php?Chp=9&Clf=<?php echo $Clf; ?>" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"9"))) echo "<font color=\"gray\">Photo</font>";
    else echo "Photo";
    ?></a></td>
    </tr>
    </table>
</td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table><?php
         // Photos
         break;
    case 3: // Musique
         // Musique
?>
<!-- **************************************************************************************************************************** VOIR COMPILS -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=10&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"10"))) echo "<font color=\"gray\">Voir&nbsp;Compils</font>";
else echo "Voir&nbsp;Compils";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** CREER/DETRUIRE COMPIL -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=11&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"11"))) echo "<font color=\"gray\">Cr&eacute;er/D&eacute;truire&nbsp;1&nbsp;Compil</font>";
else echo "Cr&eacute;er/D&eacute;truire&nbsp;1&nbsp;Compil";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** AJOUTER/SUPPRIMER MORCEAU -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00">
    <table border=0 width=117 cellspacing=0 cellpadding=0>
    <tr>
    <td width=13>
        <table border=0 width=13 height=20 cellspacing=0 cellpadding=0>
        <tr height=5>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr height=15>
        <td><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
        </tr>
        </table>
    </td>
    <td width=104><a href="<?php echo GetFolder(); ?>/index.php?Chp=12&Clf=<?php echo $Clf; ?>" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"12"))) echo "<font color=\"gray\">Ajouter/Supprimer&nbsp;1</font>";
    else echo "Ajouter/Supprimer&nbsp;1";
    ?></a></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><a href="<?php echo GetFolder(); ?>/index.php?Chp=12&Clf=<?php echo $Clf; ?>" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"12"))) echo "<font color=\"gray\">Morceau</font>";
    else echo "Morceau";
    ?></a></td>
    </tr>
    </table>
</td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** CLASSEMENT -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=13&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"13"))) echo "<font color=\"gray\">Classement</font>";
else echo "Classement";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table><?php
         // Musique
         break;
    case 4: // Evenements
?>
<!-- **************************************************************************************************************************** VOIR EVENEMENTS -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=14&Clf=<?php echo $Clf; ?>" target="_top"><?php
if((!Empty($Chp))&&(!strcmp($Chp,"14"))) echo "<font color=\"gray\">Voir&nbsp;Ev&eacute;nements</font>";
else echo "Voir&nbsp;Ev&eacute;nements";
?></a></td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- **************************************************************************************************************************** AJOUTER/SUPPRIMER EVENEMENT -->
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=10 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=177 bgcolor="#00ff00">
    <table border=0 width=117 cellspacing=0 cellpadding=0>
    <tr>
    <td width=13>
        <table border=0 width=13 height=20 cellspacing=0 cellpadding=0>
        <tr height=5>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr height=15>
        <td><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
        </tr>
        </table>
    </td>
    <td width=104><a href="<?php echo GetFolder(); ?>/index.php?Chp=15&Clf=<?php echo $Clf; ?>" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"15"))) echo "<font color=\"gray\">Ajouter/Supprimer&nbsp;1</font>";
    else echo "Ajouter/Supprimer&nbsp;1";
    ?></a></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><a href="<?php echo GetFolder(); ?>/index.php?Chp=15&Clf=<?php echo $Clf; ?>" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"15"))) echo "<font color=\"gray\">Ev&eacute;nement</font>";
    else echo "Ev&eacute;nement";
    ?></a></td>
    </tr>
    </table>
</td>
<td width=19 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table><?php
          // Evenements
          break;
}
?>
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr><?php
if($iMenu == 0)
{  // Pas de sous Menu
?>
<!-- ****************************************************************************************************************************EVENEMENTS -->
<td><img src="<?php echo GetFolder(); ?>/Images/MilBasGau.jpg"></td>
<?php
if(!Empty($Clf))
{   // Connecté
    ?><td width="100%" bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/Menu.php?Mnu=4&Chp<?php echo $Chp; ?>=&Clf=<?php echo $Clf; ?>">Ev&eacute;nements</a></td><?php
    // Connecté
}
else
{   // Non Connecté
    ?><td width=177 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;<a href="<?php echo GetFolder(); ?>/index.php?Chp=14" target="_top"><?php
    if((!Empty($Chp))&&(!strcmp($Chp,"14"))) echo "<font color=\"gray\">Ev&eacute;nements</font>";
    else echo "Ev&eacute;nements";
    ?></a></td><?php
    // Non Connecté
}
?>
<td><img src="<?php echo GetFolder(); ?>/Images/MilBasDro.jpg"></td><?php
}
else
{   // Sous Menu
?>
<td><img src="<?php echo GetFolder(); ?>/Images/MilBacGau.jpg"></td>
<td width="100%" bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/MilBacDro.jpg"></td><?php
}
?>
</tr>
</table>
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr><?php
if($iMenu == 0)
{  // Pas de sous Menu
?>
<td width=88><img src="<?php echo GetFolder(); ?>/Images/MenBasGau.jpg"></td><?php
}
else
{   // Sous Menu
?>
<td width=90><img src="<?php echo GetFolder(); ?>/Images/MenBacGau.jpg"></td><?php
}
if($iMenu == 0)
{  // Pas de sous Menu
?>
<td width=49>
    <table border=0 width=49 cellspacing=0 cellpadding=0><?php
}
else
{   // Sous Menu
?>
<td width=45>
    <table border=0 width=45 cellspacing=0 cellpadding=0><?php
}
if($iMenu == 0)
{  // Pas de sous Menu
?>
    <tr height=9>
    <td bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr height=6>
    <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr><?php
}
else
{   // Sous Menu
?>
    <tr>
    <td bgcolor="#ff0000"><a href="<?php echo GetFolder(); ?>/Menu.php?Mnu=0&Chp=<?php echo $Chp; ?>&Clf=<?php echo $Clf; ?>"><font color="white">Retour</font></a></td>
    </tr><?php
}
?>
    </table>
</td><?php
if($iMenu == 0)
{  // Pas de sous Menu
?>
<td width=88><img src="<?php echo GetFolder(); ?>/Images/MenBasDro.jpg"></td><?php
}
else
{   // Sous Menu
?>
<td width=90><img src="<?php echo GetFolder(); ?>/Images/MenBacDro.jpg"></td><?php
}
?>
</tr>
</table>
<table border=0 width=188 cellspacing=0 cellpadding=0>
<tr height=5>
<td width=180 bgcolor="#8080ff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=8 bgcolor="#8000ff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width=225 cellspacing=0 cellpadding=0>
<tr>
<td width=13 bgcolor="#8080ff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=212>
    <table border=0 width=212 height=34 cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 width=88 cellspacing=0 cellpadding=0>
        <tr height=14>
        <td><img src="<?php echo GetFolder(); ?>/Images/MenHauGau.jpg"></td>
        </tr>
        <tr height=6>
        <td>
            <table border=0 width=88 height=6 cellspacing=0 cellpadding=0>
            <tr>
            <td width=12 bgcolor="#8080ff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td width=16 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/QuitGau.jpg"></td>
            <td width=60 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr height=14>
        <td><img src="<?php echo GetFolder(); ?>/Images/MenBasGau.jpg"></td>
        </tr>
        </table>
    </td>
    <td width="100%">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr height=6>
        <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr height=24>
        <!-- **************************************************************************************************************** QUITTER/CONNEXION -->
        <td bgcolor="#00ff00"><a href="<?php echo GetFolder(); ?>/index.php<?php
        if(Empty($Clf)) echo "?Chp=11";
        ?>" target="_top"><font color="#ff0000"><?php
        if(Empty($Clf)) echo "Login";
        else echo "Quitter"
        ?></font></a></td>
        </tr>
        <tr height=6>
        <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td>
        <table border=0 width=88 cellspacing=0 cellpadding=0>
        <tr height=14>
        <td><img src="<?php echo GetFolder(); ?>/Images/MenHauDro.jpg"></td>
        </tr>
        <tr height=6>
        <td>
            <table border=0 width=88 height=6 cellspacing=0 cellpadding=0>
            <tr>
            <td width=60 bgcolor="#00ff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td width=16 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/QuitDro.jpg"></td>
            <td width=12><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr height=14>
        <td><img src="<?php echo GetFolder(); ?>/Images/MenBasDro.jpg"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width=188 cellspacing=0 cellpadding=0>
<tr>
<td>
        <table border=0 width=188 cellspacing=0 cellpadding=0>
        <tr>
        <td width=180 align="right" bgcolor="#8080ff"><img src="<?php echo GetFolder(); ?>/Images/ClaFonGH.jpg"></td>
        <td width=8 bgcolor="#8000ff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
</td>
</tr>
<tr>
<td align="right" bgcolor="#8000ff"><img src="<?php echo GetFolder(); ?>/Images/FonBlaGH.jpg"></td>
</tr>
</table>
</body>
</html>
