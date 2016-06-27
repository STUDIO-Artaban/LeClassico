<?php
require("Package.php");
$Chp = "8";
$Clf = $_GET['Clf'];
$aSearch = array("<",">");
$aReplace = array("&lt;","&gt;");
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
        $Query = "SELECT CAM_Pseudo FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            $aDate = getdate();
            mysql_free_result($Result);
            if(!Empty($ope))
            {   if((!Empty($albnm))&&(strcmp(trim($albnm),"")))
                {   switch($ope)
                    {   case 1: // Ajoute un album /////////////////////////////////////////////////////////////////////////////
                        {   $Query = "SELECT 'X' FROM Albums WHERE UPPER(ALB_Nom) = UPPER('".trim($albnm)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   mysql_free_result($Result);
                                mysql_close($Link);
                                $Msg = "Cet album existe d&eacute;j&agrave;! Choisis un autre nom d'album!";
                                include("Message.php");
                                die();
                            }
                            else
                            {   $Query = "INSERT INTO Albums (ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_EventID,ALB_Remark,ALB_Date) VALUES (";
                                // Nom
                                $Query .= "'".trim($albnm)."',";
                                // Pseudo
                                $Query .= "'".trim(addslashes($Camarade))."',";
                                // Shared
                                if(!Empty($albshrd)) $Query .= "1,";
                                else $Query .= "0,";
                                // EventID
                                $Query .= "$albevnt,";
                                // Remark
                                if((!Empty($albrmk))&&(strcmp(trim($albrmk),""))) $Query .= "'".trim($albrmk)."',";
                                else $Query .= "NULL,";
                                // Date
                                $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."')";
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Echec durant la cr&eacute;ation de l'album <font color=\"#808080\">".trim(stripslashes($albnm))."</font>! Faut voir &ccedil;a avec le <font color=\"#808080\">Webmaster</font>!";
                                    include("Message.php");
                                    die();
                                }
                            }
                            break;
                        }
                        case 2: // Modifie un album ////////////////////////////////////////////////////////////////////////////
                        {   if(strcmp(trim($albnm),trim($alblnm)))
                            {   $Query = "SELECT 'X' FROM Albums WHERE UPPER(ALB_Nom) = UPPER('".trim($albnm)."')";
                                $Result = mysql_query(trim($Query),$Link);
                                if(mysql_num_rows($Result) != 0)
                                {   mysql_free_result($Result);
                                    mysql_close($Link);
                                    $Msg = "L'album <font color=\"#808080\">".trim(stripslashes($albnm))."</font> existe d&eacute;j&agrave;! Choisis un autre nom d'album!";
                                    include("Message.php");
                                    die();
                                }
                                else
                                {   // Mise à jour de la table Photos
                                    $Query = "UPDATE Photos SET PHT_Album = '".trim($albnm)."'";
                                    $Query .= " WHERE UPPER(PHT_Album) = UPPER('".trim($alblnm)."')";
                                    if(!mysql_query(trim($Query),$Link))
                                    {   mysql_close($Link);
                                        $Msg = "Impossible de modifier le nom de cet album! Bizarre!?! Contacts le <font color=\"#808080\">Webmaster</font>!";
                                        include("Message.php");
                                        die();
                                    }
                                }
                            }
                            $Query = "UPDATE Albums SET";
                            // Nom
                            $Query .= " ALB_Nom = '".trim($albnm)."',";
                            // Shared
                            if(!Empty($albshrd)) $Query .= " ALB_Shared = 1,";
                            else $Query .= " ALB_Shared = 0,";
                            // EventID
                            $Query .= " ALB_EventID = $albevnt,";
                            // Remark
                            $Query .= " ALB_Remark = '".trim($albrmk)."'";
                            $Query .= " WHERE UPPER(ALB_Nom) = UPPER('".trim($alblnm)."')";
                            if(!mysql_query(trim($Query),$Link))
                            {   mysql_close($Link);
                                $Msg = "Echec durant la modification de l'album <font color=\"#808080\">".trim(stripslashes($alblnm))."</font>! Faut voir &ccedil;a avec le <font color=\"#808080\">Webmaster</font>!";
                                include("Message.php");
                                die();
                            }
                            break;
                        }
                        case 3: // Supprime un album ///////////////////////////////////////////////////////////////////////////
                        {   $Query = "SELECT PHT_Fichier FROM Photos WHERE UPPER(PHT_Album) = UPPER('".trim($albnm)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   while($aRow = mysql_fetch_array($Result))
                                {   $Query = "SELECT DISTINCT 'X' FROM Photos WHERE PHT_Fichier LIKE '".$aRow["PHT_Fichier"]."'";
                                    $Query .= " AND UPPER(PHT_Album) <> UPPER('".trim($albnm)."')";
                                    $PhtRes = mysql_query(trim($Query),$Link);
                                    if(mysql_num_rows($PhtRes) != 0)
                                    {   mysql_free_result($PhtRes);
                                        // Supprime la photo de la table Photos pour cet album
                                        $Query = "DELETE FROM Photos WHERE UPPER(PHT_Album) = UPPER('".trim($albnm)."') AND PHT_Fichier LIKE '".$aRow["PHT_Fichier"]."'";
                                        mysql_query(trim($Query),$Link);
                                    }
                                    else
                                    {   // Supprime la photo de toute la table Photos
                                        $Query = "DELETE FROM Photos WHERE PHT_Fichier LIKE '".$aRow["PHT_Fichier"]."'";
                                        if(mysql_query(trim($Query),$Link))
                                        {   // Supprime la photo de la table Votes
                                            $Query = "DELETE FROM Votes WHERE VOT_Fichier LIKE '".$aRow["PHT_Fichier"]."'";
                                            mysql_query(trim($Query),$Link);
                                            // Supprime la photo du serveur
                                            @unlink(GetSrvPhtFolder().$aRow["PHT_Fichier"]);
                                        }
                                    }
                                }
                                mysql_free_result($Result);
                            }
                            $Query = "DELETE FROM Albums WHERE UPPER(ALB_Pseudo) = UPPER('".addslashes($Camarade)."') AND UPPER(ALB_Nom) = UPPER('".trim($albnm)."')";
                            if(!mysql_query(trim($Query),$Link))
                            {   mysql_close($Link);
                                $Msg = "Echec durant la suppression de l'album <font color=\"#808080\">".trim(stripslashes($albnm))."</font>! Contacts le <font color=\"#808080\">Webmaster</font>!";
                                include("Message.php");
                                die();
                            }
                            break;
                        }
                        default:{   break;}
                    }
                }
                else
                {   mysql_close($Link);
                    $Msg = "Il te faut saisir un nom d'album!!! Et oui, les photos sont dans des albums photos et chaque album a un nom...";
                    include("Message.php");
                    die();
                }
            }
        }
        else
        {   mysql_close($Link);
            $Msg = "Ton pseudo est inconnu!";
            include("Message.php");
            die();
        }
    }
}
else
{   $Msg = "Tu n'est pas connect&eacute;!";
    include("Message.php");
    die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Gestion des Albums</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Title {font-size: 22pt; font-family: Impact,Verdana,Lucida; color: yellow}
#InTitle {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: black}
#Content {font-size: 10pt; font-family: Verdana,Lucida,Courier; color: black}
</style>
<script type="text/javascript">
<!--
// Variables //////////////////////////////////////////////////////////////////////////////////
var aEventTab=new Array(0<?php
$Query = "SELECT EVE_Nom,EVE_Date,EVE_EventID FROM Evenements WHERE EVE_Date <= '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."' ORDER BY EVE_Date DESC LIMIT 0,30";
$Result = mysql_query(trim($Query),$Link);
while($aRow = mysql_fetch_array($Result))
{   if(strlen(trim($aRow["EVE_Nom"])) <= 15) $aEvent[] = substr($aRow["EVE_Date"],8,10)."/".substr($aRow["EVE_Date"],5,2)."/".substr($aRow["EVE_Date"],2,2)." - ".str_replace($aSearch,$aReplace,trim($aRow["EVE_Nom"]));
    else $aEvent[] = substr($aRow["EVE_Date"],8,10)."/".substr($aRow["EVE_Date"],5,2)."/".substr($aRow["EVE_Date"],2,2)." - ".str_replace($aSearch,$aReplace,substr($aRow["EVE_Nom"],0,15))."...";
    echo ",".trim($aRow["EVE_EventID"]);
}
mysql_free_result($Result);
?>);
var sNoSave="Tu n'as pas sauvegardé les modifications apportées\nà l'album sélectionné! Veux-tu continuer malgré tout?";
var bNewAlb=false;
var bLock=false;
var sAlbNom="";
var iSelAlb=0;
var sCurDate="<?php echo trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"]); ?>";
// ModifyAlbInfo //////////////////////////////////////////////////////////////////////////////
function ModifyAlbInfo()
{   if(document.getElementById("AlbNom").value!="")
    {   document.getElementById("ModAlbNom").value=document.getElementById("AlbNom").value;
        document.getElementById("ModAlbLastNom").value=sAlbNom;
        if(document.getElementById("AlbShared").checked) document.getElementById("ModAlbShared").value=1;
        else document.getElementById("ModAlbShared").value=0;
        if((document.getElementById("AlbEvent").selectedIndex!=(-1))&&(aEventTab.length>=1)) document.getElementById("ModAlbEvent").value=aEventTab[document.getElementById("AlbEvent").selectedIndex];
        else document.getElementById("ModAlbEvent").value=0;
        document.getElementById("ModAlbRemark").value=document.getElementById("AlbRemark").value;
        return true;
    }
    alert("Nom de l'album vide!!!");
    return false;
}
// CreateNewAlbum /////////////////////////////////////////////////////////////////////////////
function CreateNewAlbum()
{   if(bNewAlb)
    {   if(document.getElementById("AlbNom").value!="")
        {   document.getElementById("NewAlbNom").value=document.getElementById("AlbNom").value;
            if(document.getElementById("AlbShared").checked) document.getElementById("NewAlbShared").value=1;
            else document.getElementById("NewAlbShared").value=0;
            if((document.getElementById("AlbEvent").selectedIndex!=(-1))&&(aEventTab.length>=1)) document.getElementById("NewAlbEvent").value=aEventTab[document.getElementById("AlbEvent").selectedIndex];
            else document.getElementById("NewAlbEvent").value=0;
            document.getElementById("NewAlbRemark").value=document.getElementById("AlbRemark").value;
            return true;
        }
        alert("Nom de l'album vide!!!");
        return false;
    }
    else
    {   if((document.getElementById("BtnModif").disabled!="")||(confirm(sNoSave)))
        {   document.getElementById("AlbNom").disabled="";
            document.getElementById("AlbShared").disabled="";
            document.getElementById("AlbEvent").disabled="";
            document.getElementById("AlbRemark").disabled="";
            document.getElementById("AlbNom").value="";
            document.getElementById("AlbShared").checked="";
            document.getElementById("AlbEvent").selectedIndex=0;
            document.getElementById("AlbRemark").value="";
            document.getElementById("AlbDate").innerHTML=sCurDate;
            document.getElementById("AlbPhoto").innerHTML="0";
            document.getElementById("BtnModif").disabled="disabled";
            document.getElementById("BtnSupp").value="Annuler";
            document.getElementById("BtnSupp").disabled="";
            document.getElementById("BtnNew").value=" Créer ";
            bNewAlb=true;
        }
    }
    return false;
}
// ChgAlbumList ///////////////////////////////////////////////////////////////////////////////
function ChgAlbumList()
{   var i=0;
    var bFind=false;
    if(!bNewAlb)
    {   if((document.getElementById("BtnModif").disabled!="")||(confirm(sNoSave)))
        {   bLock=true;
            switch(document.getElementById("AlbList").selectedIndex)
            {   <?php
                $CntAlb = 0;
                $Query = "SELECT ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_EventID,ALB_Remark,ALB_Date,COUNT(PHT_Fichier) AS PHT_Count FROM Albums LEFT JOIN Photos ON ALB_Nom = PHT_Album";
                $Query .= " WHERE UPPER(ALB_Pseudo) = UPPER('".addslashes($Camarade)."')";
                $Query .= " GROUP BY ALB_Nom,ALB_Pseudo,ALB_Shared,ALB_EventID,ALB_Remark,ALB_Date";
                $Query .= " ORDER BY ALB_Nom";
                $Result = mysql_query(trim($Query),$Link);
                while($aRow = mysql_fetch_array($Result))
                {   // Tant qu'il y a des albums
                ?>case <?php echo $CntAlb; ?>:
                {   iSelAlb=<?php echo $CntAlb; ?>;
                    document.getElementById("AlbNom").disabled="";
                    document.getElementById("AlbShared").disabled="";
                    document.getElementById("AlbEvent").disabled="";
                    document.getElementById("AlbRemark").disabled="";
                    //
                    document.getElementById("AlbNom").value="<?php echo str_replace("\'","'",addslashes($aRow["ALB_Nom"])); ?>";
                    sAlbNom=document.getElementById("AlbNom").value;
                    document.getElementById("AlbShared").checked="<?php
                    if(!Empty($aRow["ALB_Shared"])) echo "on";
                    ?>";
                    while((i!=aEventTab.length)&&(!bFind))
                    {   if(aEventTab[i]=="<?php echo $aRow["ALB_EventID"]; ?>")
                        {   document.getElementById("AlbEvent").selectedIndex=i;
                            bFind=true;
                        }
                        i++;
                    }
                    document.getElementById("AlbRemark").value="<?php echo str_replace("\'","'",addslashes($aRow["ALB_Remark"])); ?>";
                    document.getElementById("AlbDate").innerHTML="<?php echo $aRow["ALB_Date"]; ?>";
                    document.getElementById("AlbPhoto").innerHTML="<?php echo $aRow["PHT_Count"]; ?>";
                    //
                    document.getElementById("SuppAlbNom").value="<?php echo str_replace("\'","'",addslashes($aRow["ALB_Nom"])); ?>";
                    //
                    document.getElementById("BtnSupp").disabled="";
                    document.getElementById("BtnModif").disabled="disabled";
                    document.getElementById("BtnNew").value="Nouveau";
                    document.getElementById("BtnSupp").value="Supprimer";
                    break;
                }
                <?php
                    // Tant qu'il y a des albums
                    $CntAlb++;
                    if(strlen(trim($aRow["ALB_Nom"])) <= 13) $aAlbNom[] = str_replace($aSearch,$aReplace,trim($aRow["ALB_Nom"]));
                    else $aAlbNom[] = str_replace($aSearch,$aReplace,substr(trim($aRow["ALB_Nom"]),0,13))."...";
                }
                mysql_free_result($Result);
                ?>default:
                {   document.getElementById("AlbNom").disabled="disabled";
                    document.getElementById("AlbShared").disabled="disabled";
                    document.getElementById("AlbEvent").disabled="disabled";
                    document.getElementById("AlbRemark").disabled="disabled";
                    //
                    document.getElementById("ModAlbNom").value="";
                    document.getElementById("ModAlbShared").value="";
                    document.getElementById("ModAlbEvent").value="";
                    document.getElementById("ModAlbRemark").value="";
                    document.getElementById("AlbDate").innerHTML="";
                    document.getElementById("AlbPhoto").innerHTML="";
                    //
                    document.getElementById("SuppAlbNom").value="";
                    //
                    document.getElementById("NewAlbNom").value="";
                    document.getElementById("NewAlbShared").value="";
                    document.getElementById("NewAlbEvent").value="";
                    document.getElementById("NewAlbRemark").value="";
                    //
                    document.getElementById("AlbNom").value="";
                    document.getElementById("AlbShared").checked="";
                    document.getElementById("AlbEvent").selectedIndex=0;
                    document.getElementById("AlbRemark").value="";
                    //
                    document.getElementById("BtnModif").disabled="disabled";
                    document.getElementById("BtnSupp").disabled="disabled";
                    document.getElementById("BtnSupp").value="Supprimer";
                    document.getElementById("BtnNew").value="Nouveau";
                    break;
                }
            }
            bLock=false;
        }
        else document.getElementById("AlbList").selectedIndex=iSelAlb;
    }
}
// ConfirmSuppAlb ///////////////////////////////////////////////////////////////////////////////
function ConfirmSuppAlb()
{   if(bNewAlb)
    {   bNewAlb=false;
        ChgAlbumList();
        return false;
    }
    else if(confirm("Es-tu sûr de vouloir supprimer l'album sélectionné, et\ndétruire ainsi toutes les photos, commentaires, et votes\nqui s'y trouvent ? (Si toutefois il y en a)")) return true;
    return false;
}
// ChangeAlbInfo ////////////////////////////////////////////////////////////////////////////////
function ChangeAlbInfo()
{   if(!bLock)
    {   if(!bNewAlb) document.getElementById("BtnModif").disabled="";
        else document.getElementById("BtnSupp").disabled="";
    }
}
// Initialize /////////////////////////////////////////////////
function Initialize()
{
    // Modifie la page si Netscape
    if (navigator.appName!="Microsoft Internet Explorer")
    {   
        // Merci IE!! :p
        document.getElementById("AlbNom").style.marginTop="1px";
        document.getElementById("AlbRemark").style.marginTop="1px";

        document.getElementById("AlbNom").style.marginBottom="1px";
        document.getElementById("AlbRemark").style.marginBottom="1px";
    }
}
-->
</script>
<?php
mysql_close($Link);
?>
</head>
<body bgcolor="#ffffff" style="margin-top: 0; margin-left: 10px" onload="Initialize()">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ****************************************************************************************************************************** ALBUM MANAGER -->
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
    <td width="100%" bgcolor="#ff0000"><font ID="BigTitle">&nbsp;<b>Gestion des Albums</b></font></td>
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
<font face="Verdana,Lucida,Courier" size=2>Tu as des photos aux formats <b>JPEG</b> (<b>*.jpg</b>), <b>GIF</b> (<b>*.gif</b>) ou <b>PNG</b>
 (<b>*.png</b>)...Fais nous en profiter!!! Crées un album et ajoutes y tes photos via le menu
 <a href="<?php echo GetFolder(); ?>/index.php?Chp=9&Clf=<?php echo $Clf; ?>" style="font-size: 10pt" target="_top">Ajouter/Supprimer 1 Photo</a>.<br><br></font>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ff0000">
<tr>
<td width=52>
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=5 bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/RedCadHG.gif"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/DosPhoto.gif"></td>
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
<td width="100%" nowrap><font ID="Title">Tes Albums Photos</font></td>
<td width=5 valign="top">
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/RedCadHD.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/RedCadInHG.jpg"></td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=147>
    <table border=0 width=147 cellspacing=0 cellpadding=0>
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
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
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
<td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/RedCadInHD.jpg"></td>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
<td align="center" bgcolor="#bacc9a"><font ID="InTitle">LISTE</font></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
<td align="center" bgcolor="#bacc9a"><font ID="InTitle">PROPRIETES</font></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
<td width=143 bgcolor="#d8e1c6">
    <table border=0 width=143 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
<td width="100%" bgcolor="#d8e1c6">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#d8e1c6">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=143 valign="top" bgcolor="#d8e1c6">
    <table border=0 width=143 cellspacing=0 cellpadding=0>
    <tr>
    <td align="center"><select tabindex=1 style="font-size: 7pt; font-family: Verdana,Lucida,Courier; color: black" ID="AlbList" onchange="javascript:ChgAlbumList()" size=9>
    <?php
    if(count($aAlbNom) != 0)
    {   foreach($aAlbNom as $AlbNom)
        {   // Tant qu'il y a des albums
    ?><option><?php echo stripslashes($AlbNom); ?></option>
    <?php
            // Tant qu'il y a des albums
        }
    }
    else echo "<option>Pas d'Album...</option>";
    ?><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
    </select></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=7 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td bgcolor="#bacc9a">
        <table border=0 height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#d8e1c6">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#d8e1c6">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%" valign="top" bgcolor="#d8e1c6">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=135>
            <table border=0 width=135 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Nom:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><input type="text" tabindex=2 ID="AlbNom" onchange="javascript:ChangeAlbInfo()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: black" maxlength=30 size=40 disabled></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td align="left"><font ID="Content"><b>&bull;&nbsp;Album partag&eacute;:</b>&nbsp;</font><input type="checkbox" tabindex=3 ID="AlbShared" onchange="javascript:ChangeAlbInfo()" disabled></td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=135>
            <table border=0 width=135 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Ev&eacute;nement li&eacute;:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><select tabindex=4 ID="AlbEvent" onchange="javascript:ChangeAlbInfo()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: black" disabled>
        <?php
        echo "<option>Aucun</option>\n        ";
        if(count($aEvent) != 0)
        {   foreach($aEvent as $Event)
            {   // Tant qu'il y a des événements
        ?><option><?php echo stripslashes($Event); ?></option>
        <?php
                // Tant qu'il y a des événements
            }
        }
        ?></select></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=135>
            <table border=0 width=135 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Remarque:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><input type="text" tabindex=5 ID="AlbRemark" onchange="javascript:ChangeAlbInfo()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: black" maxlength=100 size=40 disabled></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=135>
            <table border=0 width=135 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Date:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><font ID="Content"><p ID="AlbDate" style="margin-top: 0;margin-bottom: 0"></p></font></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=135>
            <table border=0 width=135 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Photo(s):</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><font ID="Content"><p ID="AlbPhoto" style="margin-top: 0;margin-bottom: 0"></p></font></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td bgcolor="#bacc9a">
        <table border=0 height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 height=3 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width="100%" align="right"><form action="AlbMan.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" ID="ModAlbNom" name="albnm">
        <input type="hidden" ID="ModAlbLastNom" name="alblnm">
        <input type="hidden" ID="ModAlbShared" name="albshrd">
        <input type="hidden" ID="ModAlbEvent" name="albevnt">
        <input type="hidden" ID="ModAlbRemark" name="albrmk">
        <input type="hidden" name="ope" value=2>
        <input type="submit" ID="BtnModif" style="font-family: Verdana;font-size: 10pt" tabindex=6 onclick="return ModifyAlbInfo()" value="Modifier" disabled>
        </form></td>
        <td width=5>
            <table border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><form action="AlbMan.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" ID="SuppAlbNom" name="albnm">
        <input type="hidden" name="ope" value="3">
        <input type="submit"  ID="BtnSupp" style="font-family: Verdana;font-size: 10pt" tabindex=7 onclick="return ConfirmSuppAlb()" value="Supprimer" disabled>
        </form></td>
        <td width=5>
            <table border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><form action="AlbMan.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" ID="NewAlbNom" name="albnm">
        <input type="hidden" ID="NewAlbShared" name="albshrd">
        <input type="hidden" ID="NewAlbEvent" name="albevnt">
        <input type="hidden" ID="NewAlbRemark" name="albrmk">
        <input type="hidden" name="ope" value=1>
        <input type="submit" ID="BtnNew" style="font-family: Verdana;font-size: 10pt" tabindex=8 onclick="return CreateNewAlbum()" value="Nouveau">
        </form></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#d8e1c6">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
<td width=143 bgcolor="#d8e1c6">
    <table border=0 width=143 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
<td width="100%" bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 height=5 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#bacc9a">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=143 bgcolor="#bacc9a">
    <table border=0 width=143 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#bacc9a">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#bacc9a">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5 bgcolor="#bacc9a">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=2 bgcolor="#bacc9a">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
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
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
<td align="center" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
<td align="center" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/RedCadInBG.jpg"></td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=147>
    <table border=0 width=147 cellspacing=0 cellpadding=0>
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
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
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
<td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/RedCadInBD.jpg"></td>
<td width=2 bgcolor="#ff0000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr bgcolor="#ff0000">
<td width=2>
    <table border=0 width=2 height=5 cellspacing=0 cellpadding=0>
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
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=147>
    <table border=0 width=147 cellspacing=0 cellpadding=0>
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
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
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
<td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
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
<td width=2>
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/RedCadBG.gif"></td>
<td width="100%" bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/RedCadBD.gif"></td>
</tr>
</table><br>
<font face="Verdana,Lucida,Courier" size=2>Pour ceux qui se demandent, &agrave; juste titre, &agrave; quoi peut servir la propri&eacute;t&eacute;
 <b>Album partag&eacute;</b>, et bien cela permet d'autoriser ou non l'ajout de photos dans cet album par d'autres camarades.<br><br>Bien entendu,
 une fois que les autres camarades ont plac&eacute; leurs photos dans cet album, ils ne pourront plus les retirer &eacute;tant donn&eacute;
 que tu es le seul &agrave; avoir les droits sur ton album. Alors si elles ne te conviennent pas, tu auras toujours la possibilit&eacute; de les
 supprimer. Vu ?!?</font>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
</body>
</html>
