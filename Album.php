<?php
require("Package.php");
$Chp = "7";
$Clf = $_GET['Clf'];
$trcfg = $_GET['trcfg'];
$vwu = $_GET['vwu'];
if(Empty($trcfg)) $trcfg = $_POST['trcfg'];
if(Empty($vwu)) $vwu = $_POST['vwu'];
$Tri = 0;
$iResCnt = 0;
$aSearch = array("<",">");
$aReplace = array("&lt;","&gt;");
// Connexion
$Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
if(Empty($Link))
{   $Msg = "Connexion au serveur impossible!";
    include("Message.php");
    die();
}
else
{   mysql_select_db(GetMySqlDB(),$Link);
    if(!Empty($Clf))
    {   $Camarade = UserKeyIdentifier($Clf);
        $Query = "SELECT 'X' FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0) mysql_free_result($Result);
        else
        {   mysql_close($Link);
            $Msg = "Ton pseudo est inconnu!";
            include("Message.php");
            die();
        }
    }
    $Query = "SELECT ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_Remark,ALB_Date,EVE_Nom,COUNT(PHT_Fichier) AS PHT_Count FROM Albums LEFT JOIN Evenements ON ALB_EventID = EVE_EventID LEFT JOIN Photos ON ALB_Nom = PHT_Album";
    if(!Empty($trcfg)) $Tri = $trcfg;
    switch($Tri)
    {   case 0: // Date
        {   $Query .= " GROUP BY ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_Remark,ALB_Date,EVE_Nom";
            $Query .= " ORDER BY ALB_Date DESC, ALB_Nom ASC";
            break;
        }
        case 1: // Nom album
        {   $Query .= " GROUP BY ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_Remark,ALB_Date,EVE_Nom";
            $Query .= " ORDER BY ALB_Nom ASC";
            break;
        }
        case 2: // Pseudo
        {   $Query .= " GROUP BY ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_Remark,ALB_Date,EVE_Nom";
            $Query .= " ORDER BY ALB_Pseudo ASC, ALB_Date DESC";
            break;
        }
        case 3: // Shared
        {   $Query .= " WHERE ALB_Shared = 1";
            $Query .= " GROUP BY ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_Remark,ALB_Date,EVE_Nom";
            $Query .= " ORDER BY ALB_Date DESC";
            break;
        }
        case 4: // Evénement
        {   $Query .= " WHERE ALB_EventID <> 0";
            $Query .= " GROUP BY ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_Remark,ALB_Date,EVE_Nom";
            $Query .= " ORDER BY EVE_Nom ASC, ALB_Date DESC";
            break;
        }
        default:
        {   $Query .= " GROUP BY ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_Remark,ALB_Date,EVE_Nom";
            $Query .= " ORDER BY ALB_Date DESC, ALB_Nom ASC";
            break;
        }
    }
    $Result = mysql_query(trim($Query),$Link);
    $iResCnt = mysql_num_rows($Result);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Albums</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#Page {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: gray}
</style>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- *************************************************************************************************************************************** ALBUMS -->
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
    <td width="100%" bgcolor="#ff0000" nowrap><font ID="BigTitle">&nbsp;<b>Albums&nbsp;Photos</b></font></td>
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
<font face="Verdana,Lucida,Courier" size=2>Pour pouvoir voir les photos du <b>Classico</b> ou d'ailleurs, il te faut avant tout choisir un album
 photos. Et oui! Toutes les photos sont dans des albums. C'est original, non? Ses albums sont cr&eacute;&eacute;s par tous les camarades inscrits via le menu
 <?php
 if(!Empty($Clf))
 {   // Connecté
     ?><a href="<?php echo GetFolder(); ?>/index.php?Chp=8&Clf=<?php echo $Clf; ?>" style="font-size: 10pt" target="_top">Cr&eacute;er/D&eacute;truire 1 Album</a><?php
     // Connecté
 }
 else
 {   // Non Connecté
     ?><font style="font-size: 10pt; font-family: Impact,Verdana,Lucida; color: blue"><u>Cr&eacute;er/D&eacute;truire 1 Album</u></font><?php
     // Non Connecté
 }
 ?>
 , et si tu cherche bien tu devrais trouv&eacute; les tiens dans la liste des albums photos ci-dessous, tri&eacute; selon ton
 choix.<br><br></font>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
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
<td>
    <table border=0 height=20 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td height="100%"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><font ID="Title">&nbsp;Liste des Albums photos</font></td>
    </tr>
    </table>
</td>
<td>
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
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr height=7>
<td width=14>
    <table border=0 width=14 height=7 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Tri&eacute;s par:</b>&nbsp;<font color="#8080ff"><i><?php
switch($Tri)
{   case 0: // Date
    {   echo "Date de cr&eacute;ation (d&eacute;croissante)";
        break;
    }
    case 1: // Nom album
    {   echo "Nom d'album";
        break;
    }
    case 2: // Pseudo
    {   echo "Pseudo de l'auteur";
        break;
    }
    case 3: // Shared
    {   echo "Album partag&eacute;";
        break;
    }
    case 4: // Evénement
    {   echo "Ev&eacute;nement";
        break;
    }
    default:
    {   echo "Date de cr&eacute;ation (d&eacute;croissante)";
        break;
    }
}
?></i></font></font></td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Affichage:</b>&nbsp;de&nbsp;<?php
$iResStart = 1;
$iResEnd = $iResCnt;
if($iResCnt > 5)
{   if(!Empty($vwu))
    {   $iResStart = ($vwu * 5) + 1;
        // Vérifie que le nombre d'album n'a pas diminué depuis le visionnage des photos //
        if($iResStart > $iResCnt)
        {   $vwu = 0;
            $iResStart = 1;
        }
        ///////////////////////////////////////////////////////////////////////////////////
        $iResEnd = $iResStart + 4;
        if($iResEnd > $iResCnt) $iResEnd = $iResStart + ($iResCnt - $iResStart);
    }
    else
    {   $iResStart = 1;
        $iResEnd = 5;
    }
    echo "<b>$iResStart</b>&nbsp;&agrave;&nbsp;<b>$iResEnd</b>&nbsp;sur&nbsp;<b>$iResCnt</b>";
}
else
{   if($iResCnt > 1) echo "<b>1</b>&nbsp;&agrave;&nbsp;<b>$iResCnt</b>&nbsp;sur&nbsp;<b>$iResCnt</b>";
    elseif($iResCnt == 1) echo "<b>1</b>&nbsp;<font ID=\"TitleRes\">&agrave;</font>&nbsp;<b>1</b>";
    else echo "<b>0</b>&nbsp;<font ID=\"TitleRes\">&agrave;</font>&nbsp;<b>0</b>";
}
?></font></td>
</tr>
</table>
<?php
$bPass = false;
$BckColor = "#D8E1C6";
$CntView = 0;
while($aRow = mysql_fetch_array($Result))
{   if(!strcmp($BckColor,"#D8E1C6")) $BckColor = "#BACC9A";
    else $BckColor = "#D8E1C6";
    // Boucle tant qu'il y a des albums
    $CntView++;
    if(($CntView >= $iResStart)&&($CntView <= $iResEnd))
    {   // Affiche l'album
?>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 height=<?php
    if($bPass) echo "5";
    else
    {   echo "10";
        $bPass = true;
    }
    ?> cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
<tr>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/SubOranHG.jpg"></td>
<td width=45 bgcolor="#ff8000">
    <table border=0 width=45 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/<?php
if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaHD.jpg";
else echo "SubFonHD.jpg";
?>"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
<tr>
<td width=50 valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/DosPhoto.gif"></td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%" bgcolor="#ffffff">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr bgcolor="<?php echo $BckColor; ?>">
    <td width="100%" nowrap><font ID="Title" color="<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "#000000\">";
    else echo "#ffffff\">";
    echo str_replace($aSearch,$aReplace,stripslashes($aRow["ALB_Nom"]));
    ?></font></td>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr height=5 bgcolor="<?php echo $BckColor; ?>">
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%">
        <table border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "ClaInHG.jpg";
    else echo "FonInHG.jpg";
    ?>"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "ClaInHD.jpg";
    else echo "FonInHD.jpg";
    ?>"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width="40%" valign="top">
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td nowrap><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Auteur:&nbsp;</b><?php
            if(Empty($Clf)) echo "<font color=\"#8000ff\">".stripslashes($aRow["ALB_Pseudo"])."</font>";
            else echo "<a href=\"index.php?Chp=2&Cam=".urlencode(base64_encode($aRow["ALB_Pseudo"]))."&Clf=$Clf\" target=\"_top\" style=\"font-size:10pt\">".$aRow["ALB_Pseudo"]."</a>";
            ?></font></td>
            </tr>
            <tr>
            <td nowrap><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Date:&nbsp;</b><?php echo stripslashes($aRow["ALB_Date"]); ?></font></td>
            </tr>
            <tr>
            <td nowrap><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Photo(s):&nbsp;</b><font color="#ff0000"><?php echo $aRow["PHT_Count"]; ?></font></font></td>
            </tr>
            </table>
        </td>
        <td width="60%" valign="top">
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Ev&eacute;nement:&nbsp;</b><?php
            if(!Empty($aRow["EVE_Nom"])) echo str_replace($aSearch,$aReplace,stripslashes($aRow["EVE_Nom"]));
            else echo "Aucun";
            ?></font></td>
            </tr>
            <tr>
            <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Partag&eacute;:&nbsp;</b><?php
            if(!Empty($aRow["ALB_Shared"])) echo "Oui";
            else echo "Non";
            ?></font></td>
            </tr>
            <tr>
            <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Remarque:&nbsp;</b><?php
            if((!Empty($aRow["ALB_Remark"]))&&(strcmp(stripslashes($aRow["ALB_Remark"]),""))) echo str_replace($aSearch,$aReplace,stripslashes($aRow["ALB_Remark"]));
            else echo "Aucune";
            ?></font></td>
            </tr>
            </table>
        </td>
        <td valign="bottom">
        <form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" name="albnm" value="<?php echo urlencode(base64_encode(trim($aRow["ALB_Nom"]))); ?>">
        <input type="hidden" name="albvwu" value="<?php
        if(!Empty($vwu)) echo $vwu;
        else echo "0";
        ?>">
        <input type="hidden" name="albtri" value="<?php echo $Tri; ?>">
        <input type="submit" style="font-family: Verdana;font-size: 8pt" value="Voir Photos"<?php
        if(Empty($aRow["PHT_Count"])) echo " disabled";
        ?>>
        </form>
        </td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "ClaInBG.jpg";
    else echo "FonInBG.jpg";
    ?>"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "ClaInBD.jpg";
    else echo "FonInBD.jpg";
    ?>"></td>
    </tr>
    <tr height=5 bgcolor="<?php echo $BckColor; ?>">
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td>
        <table border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
<tr>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
<td width=45 bgcolor="#ff8000">
    <table border=0 width=45 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/<?php
if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaBD.jpg";
else echo "SubFonBD.jpg";
?>"></td>
</tr>
</table>
<?php
        // Affiche l'album
    }
    // Boucle tant qu'il y a des albums
}
if(!$bPass)
{   // Pas de résultat
?>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 height=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td width=14>
    <table border=0 width=14 height=7 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td><font face="Verdana,Lucida,Courier" size=2><i>Pas de r&eacute;sultat...</i></font></td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<?php
    // Pas de résultat
}
mysql_free_result($Result);
mysql_close($Link);
if($iResCnt > 5)
{   // Gestion de l'affichage
?>
<br>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=52><?php
if((!Empty($iResStart))&&($iResStart != 1))
{   // Précédent
?>
<form action="Album.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="trcfg" value=<?php echo $Tri; ?>>
<input type="hidden" name="vwu" value=<?php echo ($vwu-1); ?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/Previous.jpg">
</form>
<?php
    // Précédent
}
else
{   // Pas Précédent
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas Précédent
}
?></td>
<td width=35><?php
if((!Empty($iResStart))&&($iResStart != 1))
{   // Begin
?>
<form action="Album.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="trcfg" value=<?php echo $Tri; ?>>
<input type="hidden" name="vwu" value=<?php echo 0; ?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/BeginRes.jpg">
</form>
<?php
    // Begin
}
else
{   // Pas Begin
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas Begin
}
?></td>
<td width="100%" align="center"><?php
$iCntPage = ceil($iResCnt / 5);
if($iCntPage >= 3)
{   // Affiche les liens
?>
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
<?php
    $iAffCnt = 1;
    $iCurPage = $vwu + 1;
    $iLien = 1;
    if(($iCntPage > 10)&&(($iCurPage - 4) > 1))
    {   if(($iCurPage - 4) > ($iCntPage - 8)) $iLien = $iCntPage - 8;
        else $iLien = $iCurPage - 4;
    }
    $bPass = false;
    while($iLien != ($iCntPage + 1))
    {   if((($iAffCnt <= 10)&&($iCntPage <= 10))||(($iAffCnt <= 9)&&($iCntPage > 10)))
        {   if(($iAffCnt == 1)&&(($iCurPage - 4) > 1)&&($iCntPage > 10))
            {   // Suspension
?>
    <td><font ID="Page">...</font></td>
<?php
                // Suspension
                $bPass = true;
            }
            // Affiche le lien
            if($bPass)
            {   // Lag
?>
    <td>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
<?php
                // Lag
            }
            else $bPass = true;
            if($iLien != $iCurPage)
            {   // Autre page
?>
    <td><a href="Album.php?trcfg=<?php echo $Tri; ?>&vwu=<?php echo ($iLien - 1); ?>&Clf=<?php echo $Clf; ?>"><?php echo $iLien; ?></a></td>
<?php
                // Autre page
            }
            else
            {   // Page courante
?>
    <td><font ID="Page"><?php echo $iLien; ?></font></td>
<?php
                // Page courante
            }
            if(($iAffCnt == 9)&&($iCntPage > 10)&&(($iCurPage + 4) < $iCntPage))
            {   // Suspension
?>
    <td>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><font ID="Page">...</font></td>
<?php
                // Suspension
            }
            // Affiche le lien
            $iAffCnt++;
        }
        $iLien++;
    }
?>
    </tr>
    </table>
<?php
    // Affiche les liens
}
else
{   // Pas de liens
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas de liens
}
?></td>
<td width=35><?php
if($iResEnd < $iResCnt)
{   // End
?>
<form action="Album.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="trcfg" value=<?php echo $Tri; ?>>
<input type="hidden" name="vwu" value=<?php echo ceil($iResCnt / 5) - 1; ?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/EndRes.jpg">
</form>
<?php
    // End
}
else
{   // Pas End
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas End
}
?></td>
<td width=52><?php
if($iResEnd < $iResCnt)
{   // Suivant
?>
<form action="Album.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="trcfg" value=<?php echo $Tri; ?>>
<input type="hidden" name="vwu" value=<?php
if(!Empty($vwu)) echo ($vwu+1);
else echo "1";
?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/Next.jpg">
</form>
<?php
    // Suivant
}
else
{   // Pas Suivant
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas Suivant
}
?></td>
</tr>
</table>
<?php
    // Gestion de l'affichage
}
elseif(($iResCnt <= 5)&&($iResCnt != 0))
{   // Lag
?>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 height=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<?php
    // Lag
}
?>
<hr>
<font face="Verdana,Lucida,Courier" size=2>A l'affichage des photos tu remarqueras que chacune d'entre elles contient toute une s&eacute;rie
 d'information, comprenant le pseudo du camarade qui a d&eacute;pos&eacute; la photo, des commentaires, et m&ecirc;me un classement. Ce classement
 d&eacute;pend des votes de tous les camarades. Chaque camarade peut voter une fois par jour, sur chacune des photos.<br><br>
 L'int&eacute;r&ecirc;t est de pouvoir choisir &quot;d&eacute;mocratiquement&quot; la photo qui sera affich&eacute;e sur la page d'accueil, ou plus
 exactement les trois photos les plus appr&eacute;ci&eacute;es. Alors si tu veux ta photo en premi&egrave;re page, votes pour elle et votes contre
 les autres photos, celles qui sont bien class&eacute;es par exemple.<br><br></font>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
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
<td>
    <table border=0 height=20 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td height="100%"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><font ID="Title">&nbsp;Choix du Tri</font></td>
    </tr>
    </table>
</td>
<td>
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
</tr>
</table><br>
<form action="Album.php?Clf=<?php echo $Clf; ?>" method="post">
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td nowrap><input type="radio" name="trcfg" value=0<?php
if(Empty($Tri)) echo " checked";
?>><font face="Verdana,Lucida,Courier" size=2>&nbsp;Trier sur la <b>date</b> de cr&eacute;ation des albums</font></td>
</tr>
<tr>
<td nowrap><input type="radio" name="trcfg" value=1<?php
if((!Empty($Tri))&&($Tri == 1)) echo " checked";
?>><font face="Verdana,Lucida,Courier" size=2>&nbsp;Trier sur le <b>nom</b> des albums</font></td>
</tr>
<tr>
<td nowrap><input type="radio" name="trcfg" value=2<?php
if((!Empty($Tri))&&($Tri == 2)) echo " checked";
?>><font face="Verdana,Lucida,Courier" size=2>&nbsp;Trier sur le pseudo de l'<b>auteur</b> des albums</font></td>
</tr>
<tr>
<td nowrap><input type="radio" name="trcfg" value=3<?php
if((!Empty($Tri))&&($Tri == 3)) echo " checked";
?>><font face="Verdana,Lucida,Courier" size=2>&nbsp;Trier sur le <b>partage</b> des albums</font></td>
</tr>
<tr>
<td nowrap><input type="radio" name="trcfg" value=4<?php
if((!Empty($Tri))&&($Tri == 4)) echo " checked";
?>><font face="Verdana,Lucida,Courier" size=2>&nbsp;Trier sur le nom de l'<b>&eacute;v&eacute;nement</b> lié aux albums</font></td>
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
<td align="right"><input type="submit" style="font-family: Verdana;font-size: 10pt" value="Appliquer"></td>
</tr>
</table>
</form>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
</body>
</html>
