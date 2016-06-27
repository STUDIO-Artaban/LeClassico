<?php
require("Package.php");
$Chp = "4";
$bWhere = false;
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
    if((!Empty($ope))&&($ope == 1))
    {   // Création de la requête ////////////////////////////////////////////////////////////////////////////////////////////////////////
        $Query = "SELECT * FROM Camarades";
        if((!Empty($rpsd))&&(strcmp(trim($rpsd),"")))
        {   $Query .= " WHERE CAM_Pseudo LIKE '%".trim($rpsd)."%'";
            $bWhere = true;
        }
        if((!Empty($rprnm))&&(strcmp(trim($rprnm),"")))
        {   if($bWhere) $Query .= " AND CAM_Prenom LIKE '%".trim($rprnm)."%'";
            else
            {   $Query .= " WHERE CAM_Prenom LIKE '%".trim($rprnm)."%'";
                $bWhere = true;
            }
        }
        if((!Empty($rnm))&&(strcmp(trim($rnm),"")))
        {   if($bWhere) $Query .= " AND CAM_Nom LIKE '%".trim($rnm)."%'";
            else
            {   $Query .= " WHERE CAM_Nom LIKE '%".trim($rnm)."%'";
                $bWhere = true;
            }
        }
        if((!Empty($rsx))&&(strcmp(trim($rsx),"")))
        {   if(!strcmp($rsx,"Masculin"))
            {   if($bWhere) $Query .= " AND CAM_Sexe = 2";
                else
                {   $Query .= " WHERE CAM_Sexe = 2";
                    $bWhere = true;
                }
            }
            else
            {   if($bWhere) $Query .= " AND CAM_Sexe = 1";
                else
                {   $Query .= " WHERE CAM_Sexe = 1";
                    $bWhere = true;
                }
            }
        }
        if((!Empty($rendt))&&(strcmp(trim($rendt),""))&&(strcmp(trim($rendt),"AAAA-MM-JJ"))&&
           (!Empty($retdt))&&(strcmp(trim($retdt),""))&&(strcmp(trim($retdt),"AAAA-MM-JJ")))
        {   if($bWhere) $Query .= " AND CAM_BornDate BETWEEN '".trim($rendt)."' AND '".trim($retdt)."'";
            else
            {   $Query .= " WHERE CAM_BornDate BETWEEN '".trim($rendt)."' AND '".trim($retdt)."'";
                $bWhere = true;
            }
        }
        if((!Empty($radrs))&&(strcmp(trim($radrs),"")))
        {   if($bWhere) $Query .= " AND CAM_Adresse LIKE '%".trim($radrs)."%'";
            else
            {   $Query .= " WHERE CAM_Adresse LIKE '%".trim($radrs)."%'";
                $bWhere = true;
            }
        }
        if((!Empty($rvill))&&(strcmp(trim($rvill),"")))
        {   if($bWhere) $Query .= " AND CAM_Ville LIKE '%".trim($rvill)."%'";
            else
            {   $Query .= " WHERE CAM_Ville LIKE '%".trim($rvill)."%'";
                $bWhere = true;
            }
        }
        if((!Empty($rpriv))&&(!strcmp($rpriv,"on")))
        {   if($bWhere) $Query .= " AND CAM_Admin = 1";
            else
            {   $Query .= " WHERE CAM_Admin = 1";
                $bWhere = true;
            }
        }
        else
        {   if($bWhere) $Query .= " AND CAM_Admin = 0";
            else
            {   $Query .= " WHERE CAM_Admin = 0";
                $bWhere = true;
            }
        }
        if($bWhere)
        {   // Recherche
            $Query .= " ORDER BY CAM_Pseudo";
            if($Result = mysql_query(trim($Query),$Link)) $iResCnt = mysql_num_rows($Result);
            else
            {   mysql_close($Link);
                $Msg = "La recherche a &eacute;chou&eacute;! Les donn&eacute;es de certains crit&egrave;res de recherche ont d&ucirc; faire";
                $Msg .= " planter cette op&eacute;ration, et du coup ce con de chien c'est encore perdu... Il est con ce chien!<br><br>Pour";
                $Msg .= " en savoir plus, contact le <font color=\"#808080\">Webmaster</font>!";
                include("Message.php");
                die();
            }
            if((!Empty($rpsd))&&(strcmp(trim($rpsd),""))) $rpsd = stripslashes(trim($rpsd));
            if((!Empty($rprnm))&&(strcmp(trim($rprnm),""))) $rprnm = stripslashes(trim($rprnm));
            if((!Empty($rnm))&&(strcmp(trim($rnm),""))) $rnm = stripslashes(trim($rnm));
            if((!Empty($radrs))&&(strcmp(trim($radrs),""))) $radrs = stripslashes(trim($radrs));
            if((!Empty($rvill))&&(strcmp(trim($rvill),""))) $rvill = stripslashes(trim($rvill));
        }
    }
    else if((!Empty($ope))&&($ope == 2))
    {   // Affichage /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if((!Empty($rpsd))&&(strcmp(trim($rpsd),""))) $rpsd = stripslashes(trim($rpsd));
        if((!Empty($rprnm))&&(strcmp(trim($rprnm),""))) $rprnm = stripslashes(trim($rprnm));
        if((!Empty($rnm))&&(strcmp(trim($rnm),""))) $rnm = stripslashes(trim($rnm));
        if((!Empty($radrs))&&(strcmp(trim($radrs),""))) $radrs = stripslashes(trim($radrs));
        if((!Empty($rvill))&&(strcmp(trim($rvill),""))) $rvill = stripslashes(trim($rvill));
        $bWhere = true;
        $Query = base64_decode(urldecode($rqu));
        $Result = mysql_query(trim($Query),$Link);
        $iResCnt = mysql_num_rows($Result);
    }
    mysql_close($Link);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Rechercher 1 Camarade</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
a {font-size: 14pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#TitleRes {font-size: 10pt; font-family: Impact,Verdana,Lucida; font-weight: 100}
#Entete {font-size: 12pt; font-family: Impact,Verdana,Lucida}
</style>
<script type="text/javascript">
<!--
// Initialize /////////////////////////////////////////////////
function Initialize()
{
    // Modifie la page si Netscape
    if (navigator.appName!="Microsoft Internet Explorer")
    {   
        // Merci IE!! :p
        document.getElementById("Psd").style.marginTop="1px";
        document.getElementById("Prn").style.marginTop="1px";
        document.getElementById("Nom").style.marginTop="1px";
        document.getElementById("Dnd").style.marginTop="1px";
        document.getElementById("Dnf").style.marginTop="1px";
        document.getElementById("Adr").style.marginTop="1px";
        document.getElementById("Vll").style.marginTop="1px";

        document.getElementById("Psd").style.marginBottom="1px";
        document.getElementById("Prn").style.marginBottom="1px";
        document.getElementById("Nom").style.marginBottom="1px";
        document.getElementById("Dnd").style.marginBottom="1px";
        document.getElementById("Dnf").style.marginBottom="1px";
        document.getElementById("Adr").style.marginBottom="1px";
        document.getElementById("Vll").style.marginBottom="1px";
    }
}
-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px" onload="Initialize()">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ************************************************************************************************************************ RECHERCHE 1 CAMARADE -->
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
    <td width="100%" bgcolor="#ff0000"><font ID="BigTitle">&nbsp;<b>Rechercher</b></font></td>
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
<?php
if(!$bWhere)
{   // Pas de recherche ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<font face="Verdana,Lucida,Courier" size=2>Pour ceux qui comme moi s'y perdent un peu dans les pseudos et ne sait plus qui est qui, ou qui par
 curiosit&eacute; souhaite en savoir plus sur un camarade, tu as trouv&eacute; le bon menu.<br><br>
 Saisis tes crit&egrave;res de recherche et... Vas chercher !!</font><br><br>
<form action="FindMember.php?Clf=<?php echo $Clf; ?>" method="post">
<table border=0 width=300 cellspacing=0 cellpadding=0>
<tr>
<td width=200>
    <table border=0 width=200 cellspacing=0 cellpadding=0>
    <tr>
    <td><font ID="Entete">Pseudo/Partie du pseudo:</font></td>
    </tr>
    </table>
</td>
<td width=100>
    <table border=0 width=100 cellspacing=0 cellpadding=0>
    <tr>
    <td><input ID="Psd" type="text" style=" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" size=32 name="rpsd" maxlength=30></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td><font ID="Entete">Pr&eacute;nom/Partie du pr&eacute;nom:</font></td>
<td><input ID="Prn" type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" size=32 name="rprnm" maxlength=20></td>
</tr>
<tr>
<td><font ID="Entete">Nom/Partie du nom:</font></td>
<td><input ID="Nom" type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" size=32 name="rnm" maxlength=20></td>
</tr>
<tr>
<td><font ID="Entete">Sexe:</font></td>
<td>
    <select name="rsx" style="font-size: 10pt; font-family: Verdana,Lucida,Courier">
    <option></option>
    <option>F&eacute;minin</option>
    <option>Masculin</option>
    </select>
</td>
</tr>
<tr>
<td><font ID="Entete">Date de naissance entre:</font></td>
<td><input ID="Dnd" type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="rendt" maxlength=10 size=11 value="AAAA-MM-JJ"><font ID="Entete">&nbsp;et:&nbsp;</font><input ID="Dnf" type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="retdt" maxlength=10 size=11 value="AAAA-MM-JJ"></td>
</tr>
<tr>
<td><font ID="Entete">Adresse/Partie de l'adresse:</font></td>
<td><input ID="Adr" type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" size=32 name="radrs" maxlength=200></td>
</tr>
<tr>
<td><font ID="Entete">Ville/Partie de la ville:</font></td>
<td><input ID="Vll" type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" size=32 name="rvill" maxlength=30></td>
</tr>
</table>
<table border=0 width=300 cellspacing=0 cellpadding=0>
<tr>
<td><font ID="Entete">Privil&egrave;ge de cr&eacute;ation d'un camarade:</font><input type="checkbox" name="rpriv" checked></td>
</tr>
</table>
<table border=0 width=300 cellspacing=0 cellpadding=0>
<tr>
<td width=200>
    <table border=0 width=200 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=100>
    <table border=0 width=100 cellspacing=0 cellpadding=0>
    <tr>
    <td><br><input type="hidden" name="ope" value=1><input type="submit" style="font-family: Verdana;font-size: 10pt" value="Vas chercher..."></td>
    </tr>
    </table>
</td>
</tr>
</table>
</form>
<?php
    // Pas de recherche
}
else
{   // Résultat de la recherche ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if(Empty($vwu))
    {   // Pas de changement d'affichage
?>
<font face="Verdana,Lucida,Courier" size=2>Waouf! Waouf! Oh le bon toutou! Il a trouv&eacute; <?php
echo "<b>$iResCnt</b>";
if($iResCnt > 1) echo " camarades...";
else
{   echo " camarade...";
    if($iResCnt == 0) echo "Il est con ce chien!";
}
?><br><br>
<?php
        // Pas de changement d'affichage
    }
    else
    {
?><font face="Verdana,Lucida,Courier" size=2><?php
    }
?>
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
    <td><font ID="Title">&nbsp;Tes&nbsp;crit&egrave;res&nbsp;de&nbsp;recherche</font></td>
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
<?php
    if((!Empty($rpsd))&&(strcmp(trim($rpsd),"")))
    {   // Pseudo
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Pseudo contenant:</b>&nbsp;<font color="#8080ff"><i><?php echo str_replace($aSearch,$aReplace,trim($rpsd)); ?></i></font></font></td>
</tr>
<?php
        // Pseudo
    }
    if((!Empty($rprnm))&&(strcmp(trim($rprnm),"")))
    {   // Prénom
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Pr&eacute;nom contenant:</b>&nbsp;<font color="#8080ff"><i><?php echo str_replace($aSearch,$aReplace,trim($rprnm)); ?></i></font></font></td>
</tr>
<?php
        // Prénom
    }
    if((!Empty($rnm))&&(strcmp(trim($rnm),"")))
    {   // Nom
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Nom contenant:</b>&nbsp;<font color="#8080ff"><i><?php echo str_replace($aSearch,$aReplace,trim($rnm)); ?></i></font></font></td>
</tr>
<?php
        // Nom
    }
    if((!Empty($rsx))&&(strcmp(trim($rsx),"")))
    {   // Sexe
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>De sexe:</b>&nbsp;<font color="#8080ff"><i><?php
        // Sexe
        if(!strcmp(trim($rsx),"Féminin")) echo "F&eacute;minin";
        else echo trim($rsx);
?></i></font></font></td>
</tr>
<?php
        // Sexe
    }
    if((!Empty($rendt))&&(strcmp(trim($rendt),""))&&(strcmp(trim($rendt),"AAAA-MM-JJ"))&&
       (!Empty($retdt))&&(strcmp(trim($retdt),""))&&(strcmp(trim($retdt),"AAAA-MM-JJ")))
    {   // Date de naissance
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Date de naissance entre:</b>&nbsp;<font color="#8080ff"><i><?php echo str_replace($aSearch,$aReplace,trim($rendt)); ?></i>&nbsp;<font color="#000000"><b>et:</b></font>&nbsp;<i><?php echo str_replace($aSearch,$aReplace,trim($retdt)); ?></i></font></font></td>
</tr>
<?php
        // Date de naissance
    }
    if((!Empty($radrs))&&(strcmp(trim($radrs),"")))
    {   // Adresse
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Adresse contenant:</b>&nbsp;<font color="#8080ff"><i><?php echo str_replace($aSearch,$aReplace,trim($radrs)); ?></i></font></font></td>
</tr>
<?php
        // Adresse
    }
    if((!Empty($rvill))&&(strcmp(trim($rvill),"")))
    {   // Ville
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top"><font face="Verdana,Lucida,Courier" size=2><b>Ville contenant:</b>&nbsp;<font color="#8080ff"><i><?php echo str_replace($aSearch,$aReplace,trim($rvill)); ?></i></font></font></td>
</tr>
<?php
        // Ville
    }
    echo "</table>\n";
    if((!Empty($rpriv))&&(!strcmp($rpriv,"on")))
    {   // Privilège
?>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=14>
    <table border=0 width=14 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td><font face="Verdana,Lucida,Courier" size=2><b>Privil&egrave;ge de cr&eacute;ation:</b>&nbsp;<font color="#8080ff"><i>Oui</i></font></td>
</tr>
</table>
<?php
        // Privilège
    }
    if($iResCnt != 0)
    {   // Affichage du résultat ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<br>
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
    <td nowrap><font ID="Title">&nbsp;Ton&nbsp;r&eacute;sultat:&nbsp;&nbsp;<font ID="TitleRes">de</font>&nbsp;<?php
    $iResStart = 1;
    $iResEnd = $iResCnt;
    if($iResCnt > 10)
    {   if(!Empty($vwu))
        {   $iResStart = ($vwu * 10) + 1;
            $iResEnd = $iResStart + 9;
            if($iResEnd > $iResCnt) $iResEnd = $iResStart + ($iResCnt - $iResStart);
        }
        else
        {   $iResStart = 1;
            $iResEnd = 10;
        }
        echo "$iResStart&nbsp;<font ID=\"TitleRes\">&agrave;</font>&nbsp;$iResEnd&nbsp;<font ID=\"TitleRes\">sur</font>&nbsp;$iResCnt";
    }
    else
    {   if($iResCnt != 1) echo "1&nbsp;<font ID=\"TitleRes\">&agrave;</font>&nbsp;$iResCnt&nbsp;<font ID=\"TitleRes\">sur</font>&nbsp;$iResCnt";
        else echo "1&nbsp;<font ID=\"TitleRes\">&agrave;</font>&nbsp;1";
    }
    ?></font></td>
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
<?php
        $BckColor = "#D8E1C6";
        $CntView = 0;
        while($aRow = mysql_fetch_array($Result))
        {   if(!strcmp($BckColor,"#D8E1C6")) $BckColor = "#BACC9A";
            else $BckColor = "#D8E1C6";
            // Boucle tant qu'il y a des camarades
            $CntView++;
            if(($CntView >= $iResStart)&&($CntView <= $iResEnd))
            {   // Affiche le camarade
?>
<table border=0 height=8 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width=48 valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/DosCam.jpg"></td>
    <td width=5 bgcolor="<?php echo $BckColor; ?>">
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%" bgcolor="<?php echo $BckColor; ?>">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Pseudo:&nbsp;<font color="<?php
        if(!strcmp($BckColor,"#D8E1C6")) echo "#8080ff";
        else echo "#ffff00";
        ?>"><?php echo stripslashes($aRow["CAM_Pseudo"]); ?></font></b></font></td>
        </tr>
        <?php
        if((!is_null($aRow["CAM_Nom"]))&&(!Empty($aRow["CAM_Nom"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Nom:</b>&nbsp;<?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Nom"])); ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_Prenom"]))&&(!Empty($aRow["CAM_Prenom"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Pr&eacute;nom:</b>&nbsp;<?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Prenom"])); ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_Sexe"]))&&(!Empty($aRow["CAM_Sexe"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Sexe:</b>&nbsp;<?php
        if($aRow["CAM_Sexe"] == 2) echo "Masculin";
        else echo "F&eacute;minin";
        ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_BornDate"]))&&(strcmp(trim($aRow["CAM_BornDate"]),"0000-00-00")))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Date de naissance:</b>&nbsp;<?php echo $aRow["CAM_BornDate"]; ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_Adresse"]))&&(!Empty($aRow["CAM_Adresse"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Adresse:</b>&nbsp;<?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Adresse"])); ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_Ville"]))&&(!Empty($aRow["CAM_Ville"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Ville:</b>&nbsp;<?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Ville"])); ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_Postal"]))&&(!Empty($aRow["CAM_Postal"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Code postal:</b>&nbsp;<?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Postal"])); ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_Email"]))&&(!Empty($aRow["CAM_Email"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;E-mail:</b>&nbsp;<?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Email"])); ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_Hobbies"]))&&(!Empty($aRow["CAM_Hobbies"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Passe-temps:</b><br><?php echo PrintString(str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Hobbies"]))); ?></font></td>
        </tr>
        <?php
        }
        if((!is_null($aRow["CAM_APropos"]))&&(!Empty($aRow["CAM_APropos"])))
        {
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;A Propos du camarade:</b><br><?php echo PrintString(str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_APropos"]))); ?></font></td>
        </tr>
        <?php
        }
        ?>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Privil&egrave;ge de cr&eacute;ation:</b>&nbsp;<?php
        if(!Empty($aRow["CAM_Admin"])) echo "Oui";
        else echo "Non";
        ?></font></td>
        </tr>
        <tr>
        <td><font face="Verdana,Lucida,Courier" size=1><b>&bull;&nbsp;Derni&egrave;re connexion:</b>&nbsp;<?php
        if((!Empty($aRow["CAM_LogDate"]))&&(strcmp(trim($aRow["CAM_LogDate"]),"0000-00-00"))) echo stripslashes($aRow["CAM_LogDate"]);
        else echo "Jamais connect&eacute;";
        ?></font></td>
        </tr>
        </table>
    </td>
    <td width=5 valign="top" bgcolor="<?php echo $BckColor; ?>"><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaHD.jpg";
    else echo "SubFonHD.jpg";
    ?>"></td>
    </tr>
    <tr>
    <td valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    <td bgcolor="<?php echo $BckColor; ?>"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="<?php echo $BckColor; ?>"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td valign="top" bgcolor="<?php echo $BckColor; ?>"><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaBD.jpg";
    else echo "SubFonBD.jpg";
    ?>"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<?php
                // Affiche le camarade
            }
            // Boucle tant qu'il y a des camarades
        }
        // Affichage du résultat
        mysql_free_result($Result);
    }
    else echo "<br>\n";
    // Résultat de la recherche
?>
<hr>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width="50%" valign="top">
<?php
if((!Empty($iResStart))&&($iResStart != 1))
{   // Précédent
?>
<form action="FindMember.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="ope" value=2>
<input type="hidden" name="rqu" value="<?php echo urlencode(base64_encode($Query)); ?>">
<?php
    if((!Empty($rpsd))&&(strcmp(trim($rpsd),""))) echo "<input type=\"hidden\" name=\"rpsd\" value=\"".trim($rpsd)."\">\n";
    if((!Empty($rprnm))&&(strcmp(trim($rprnm),""))) echo "<input type=\"hidden\" name=\"rprnm\" value=\"".trim($rprnm)."\">\n";
    if((!Empty($rnm))&&(strcmp(trim($rnm),""))) echo "<input type=\"hidden\" name=\"rnm\" value=\"".trim($rnm)."\">\n";
    if((!Empty($rsx))&&(strcmp(trim($rsx),""))) echo "<input type=\"hidden\" name=\"rsx\" value=\"".trim($rsx)."\">\n";
    if((!Empty($rendt))&&(strcmp(trim($rendt),""))&&(strcmp(trim($rendt),"AAAA-MM-JJ"))&&
       (!Empty($retdt))&&(strcmp(trim($retdt),""))&&(strcmp(trim($retdt),"AAAA-MM-JJ")))
    {   echo "<input type=\"hidden\" name=\"rendt\" value=\"".trim($rendt)."\">\n";
        echo "<input type=\"hidden\" name=\"retdt\" value=\"".trim($retdt)."\">\n";
    }
    if((!Empty($radrs))&&(strcmp(trim($radrs),""))) echo "<input type=\"hidden\" name=\"radrs\" value=\"".trim($radrs)."\">\n";
    if((!Empty($rvill))&&(strcmp(trim($rvill),""))) echo "<input type=\"hidden\" name=\"rvill\" value=\"".trim($rvill)."\">\n";
    if((!Empty($rpriv))&&(!strcmp($rpriv,"on"))) echo "<input type=\"hidden\" name=\"rpriv\" value=\"".trim($rpriv)."\">\n";
?><input type="hidden" name="vwu" value=<?php echo ($vwu-1); ?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/Previous.jpg">
</form>
<?php
}
else
{   // Pas de Précédent
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif">
<?php
}
?></td>
<td valign="top"><a href="index.php?Clf=<?php echo $Clf ?>&Chp=<?php echo $Chp; ?>" target="_top">Retour</a></td>
<td width="50%" align="right" valign="top">
<?php
if($iResEnd < $iResCnt)
{   // Suivant
?>
<form action="FindMember.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="ope" value=2>
<input type="hidden" name="rqu" value="<?php echo urlencode(base64_encode($Query)); ?>">
<?php
    if((!Empty($rpsd))&&(strcmp(trim($rpsd),""))) echo "<input type=\"hidden\" name=\"rpsd\" value=\"".trim($rpsd)."\">\n";
    if((!Empty($rprnm))&&(strcmp(trim($rprnm),""))) echo "<input type=\"hidden\" name=\"rprnm\" value=\"".trim($rprnm)."\">\n";
    if((!Empty($rnm))&&(strcmp(trim($rnm),""))) echo "<input type=\"hidden\" name=\"rnm\" value=\"".trim($rnm)."\">\n";
    if((!Empty($rsx))&&(strcmp(trim($rsx),""))) echo "<input type=\"hidden\" name=\"rsx\" value=\"".trim($rsx)."\">\n";
    if((!Empty($rendt))&&(strcmp(trim($rendt),""))&&(strcmp(trim($rendt),"AAAA-MM-JJ"))&&
       (!Empty($retdt))&&(strcmp(trim($retdt),""))&&(strcmp(trim($retdt),"AAAA-MM-JJ")))
    {   echo "<input type=\"hidden\" name=\"rendt\" value=\"".trim($rendt)."\">\n";
        echo "<input type=\"hidden\" name=\"retdt\" value=\"".trim($retdt)."\">\n";
    }
    if((!Empty($radrs))&&(strcmp(trim($radrs),""))) echo "<input type=\"hidden\" name=\"radrs\" value=\"".trim($radrs)."\">\n";
    if((!Empty($rvill))&&(strcmp(trim($rvill),""))) echo "<input type=\"hidden\" name=\"rvill\" value=\"".trim($rvill)."\">\n";
    if((!Empty($rpriv))&&(!strcmp($rpriv,"on"))) echo "<input type=\"hidden\" name=\"rpriv\" value=\"".trim($rpriv)."\">\n";
?><input type="hidden" name="vwu" value=<?php
if(!Empty($vwu)) echo ($vwu+1);
else echo "1";
?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/Next.jpg">
</form>
<?php
}
else
{   // Pas de Suivant
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
}
?></td>
</tr>
</table>
<?php
    // Résultat de la recherche
}
?>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
</body>
</html>
