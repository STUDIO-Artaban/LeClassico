<?php
require("Package.php");
$Chp = "9";
$Clf = $_GET['Clf'];
$phtpth = $_GET['phtpth'];
$shrd = $_GET['shrd'];
$albnm = $_POST['albnm'];
$pht = $_POST['pht'];
$ope = $_POST['ope'];
if(Empty($albnm)) $albnm = $_GET['albnm'];
if(Empty($ope)) $ope = $_GET['ope'];
$iSuppRes = 0;
$iAjoutRes = 0;
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
        $Query = "SELECT CAM_Pseudo,CAM_LogDate FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            if((!Empty($ope))&&($ope == 1))
            {   // Supprimer 1 Photo ///////////////////////////////////////////////////////////////////////////////////////////////////
                $iSuppRes = 1; // Ok
                $Query = "SELECT 'X' FROM Albums WHERE UPPER(ALB_Nom) = UPPER('".trim($albnm)."') AND UPPER(ALB_Pseudo) = UPPER('".addslashes($Camarade)."')";
                $Result = mysql_query(trim($Query),$Link);
                if(mysql_num_rows($Result) != 0)
                {   mysql_free_result($Result);
                    $Query = "SELECT DISTINCT 'X' FROM Photos WHERE PHT_Fichier LIKE '$pht'";
                    $Query .= " AND UPPER(PHT_Album) <> UPPER('".trim($albnm)."')";
                    $Result = mysql_query(trim($Query),$Link);
                    if(mysql_num_rows($Result) != 0)
                    {   mysql_free_result($Result);
                        // Supprime la photo de la table Photos pour cet album
                        $Query = "DELETE FROM Photos WHERE UPPER(PHT_Album) = UPPER('".trim($albnm)."') AND PHT_Fichier LIKE '$pht'";
                        if(!mysql_query(trim($Query),$Link)) $iSuppRes = 3; // Suppression
                    }
                    else
                    {   // Supprime la photo de toute la table Photos
                        $Query = "DELETE FROM Photos WHERE PHT_Fichier LIKE '$pht'";
                        if(mysql_query(trim($Query),$Link))
                        {   // Supprime la photo de la table Votes
                            $Query = "DELETE FROM Votes WHERE VOT_Fichier LIKE '$pht'";
                            mysql_query(trim($Query),$Link);
                            // Supprime la photo du serveur
                            @unlink(GetSrvPhtFolder()."$pht");
                        }
                        else $iSuppRes = 3; // Suppression
                    }
                }
                else $iSuppRes = 2; // Droits
            }
            elseif((!Empty($ope))&&(!Empty($albnm))) $iAjoutRes = 1; // Opération d'ajout terminé
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
<title>Le Classico: Photos Manager</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Title {font-size: 18pt; font-family: Impact,Verdana,Lucida; color: yellow}
#SubTitle {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: black}
#Content {font-size: 8pt; font-family: Verdana,Lucida,Courier; color: black}
</style>
<script type="text/javascript">
<!--
<?php
if(!Empty($iSuppRes))
{   // Affiche le résultat de la suppression
?>
// Commandes /////////////////////////////////////////////////////////////////////////////
top.PhtStatus.location.href="PhtStatus.php?res=<?php
switch($iSuppRes)
{   case 1: // Ok
    {   echo "19";
        break;
    }
    case 2: // Droits insuffisants
    {   echo "17";
        break;
    }
    case 3: // Echec de la suppression
    {   echo "18";
        break;
    }
}
?>&Clf=<?php echo $Clf; ?>";
<?php
    // Affiche le résultat de la suppression
}
$iPhtCnt = 0;
$Query = "SELECT COUNT(*) AS PHT_Count FROM Photos";
$iPhtCnt = mysql_result(mysql_query(trim($Query),$Link),0,"PHT_Count");
$Query = "SELECT SUM(VOT_Note)+SUM(VOT_Total) AS VOT_Pos,VOT_Fichier FROM Votes WHERE VOT_Type = 0 GROUP BY VOT_Fichier ORDER BY VOT_Pos DESC";
$Result = mysql_query(trim($Query),$Link);
$iLastVote = 0;
while($aRow = mysql_fetch_array($Result))
{   if($iLastVote != $aRow["VOT_Pos"]) $aClassement[] = $aRow["VOT_Pos"];
    $iLastVote = $aRow["VOT_Pos"];
}
mysql_free_result($Result);
?>
// Définitions ////////////////////////////////////////////////////////////////////////////
var sNoPhoto="Pas de Photo...";
var sNothing="...";
var aYourList;
var aSharedList;
// VidePhotosInfos ////////////////////////////////////////////////////////////////////////
function VidePhotosInfos(bEmpty)
{   var iPhtLen=0;
    if(bEmpty)
    {   // Vide la liste des photos
        iPhtLen=document.getElementById("PhotoList").length;
        while(iPhtLen!=0)
        {   iPhtLen--;
            document.getElementById("PhotoList").remove(iPhtLen);
        }
    }
    // Vide Les Infos
    document.getElementById("AlbSel").value=sNothing;
    document.getElementById("FileSel").value=sNothing;
    document.getElementById("PseudoSel").value=sNothing;
    document.getElementById("ClassSel").value=sNothing;
    document.getElementById("BtnSuppPhoto").disabled="disabled";
    document.getElementById("BtnShowPhoto").disabled="disabled";
}
// FillPhotoArray //////////////////////////////////////////////////////////////////////
function FillPhotoArray()
{   // Your album list
    <?php
    $iAlbPht = 1;
    $CurAlbum = "";
    $Query = "SELECT ALB_Nom,PHT_Pseudo,PHT_Fichier,SUM(VOT_Note) AS PHT_Note,SUM(VOT_Total) AS PHT_Total";
    $Query .= " FROM Albums LEFT JOIN Photos ON ALB_Nom = PHT_Album LEFT JOIN Votes ON PHT_Fichier = VOT_Fichier AND VOT_Type = 0";
    $Query .= " WHERE UPPER(ALB_Pseudo) = UPPER('".addslashes($Camarade)."') GROUP BY ALB_Nom,PHT_Pseudo,PHT_Fichier ORDER BY ALB_Nom,PHT_Fichier";
    $Result = mysql_query(trim($Query),$Link);
    while($aRow = mysql_fetch_array($Result))
    {   // Tant qu'il y a des photos dans l'album
        if(strcmp($CurAlbum,$aRow["ALB_Nom"]))
        {   // Nouvel album
            if(strcmp($CurAlbum,""))
            {   $iAlbPht = 1;
                $aYourList[] = $aAlbInfo;
                $aAlbInfo = array();
            }
            $CurAlbum = $aRow["ALB_Nom"];
            if(strlen($aRow["ALB_Nom"]) <= 20) $aYourAlb[] = str_replace($aSearch,$aReplace,trim($aRow["ALB_Nom"]));
            else $aYourAlb[] = str_replace($aSearch,$aReplace,substr(trim($aRow["ALB_Nom"]),0,20))."...";
        }
        if(!Empty($aRow["PHT_Fichier"]))
        {   if(strlen(addslashes($aRow["PHT_Fichier"])) <= 9) $aPhtInfo[] = "n°$iAlbPht - ".addslashes($aRow["PHT_Fichier"]);
            else $aPhtInfo[] = "n°$iAlbPht - ".substr(addslashes($aRow["PHT_Fichier"]),0,9)."...";
        }
        else $aPhtInfo[] = "Pas de Photo...";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = addslashes($aRow["ALB_Nom"]);
        else $aPhtInfo[] = "...";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = urlencode(base64_encode(trim($aRow["ALB_Nom"])));
        else $aPhtInfo[] = "";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = addslashes($aRow["PHT_Fichier"]);
        else $aPhtInfo[] = "...";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = urlencode(base64_encode(trim($aRow["PHT_Fichier"])));
        else $aPhtInfo[] = "";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = addslashes($aRow["PHT_Pseudo"]);
        else $aPhtInfo[] = "...";
        if(!Empty($aRow["PHT_Fichier"]))
        {   if(count($aClassement) != 0)
            {   $iPhtPos = 0;
                $indice = 1;
                foreach($aClassement as $Classement)
                {   if($Classement == ($aRow["PHT_Note"]+$aRow["PHT_Total"])) $iPhtPos = $indice;
                    $indice++;
                }
                if($iPhtPos != 0) $aPhtInfo[] = "$iPhtPos/$iPhtCnt";
                else $aPhtInfo[] = "Non classé";
            }
            else $aPhtInfo[] = "Non classé";
        }
        else $aPhtInfo[] = "...";
        $aAlbInfo[] = $aPhtInfo;
        $aPhtInfo = array();
        $iAlbPht++;
        // Tant qu'il y a des photos dans l'album
    }
    if(strcmp($CurAlbum,"")) $aYourList[] = $aAlbInfo;
    mysql_free_result($Result);
    ?>
    aYourList = new Array(<?php echo count($aYourAlb); ?>);
    <?php
    for($i=0;$i<count($aYourAlb);$i++)
    {   // Album
    ?>
    aYourList[<?php echo $i; ?>] = new Array(<?php echo count($aYourList[$i]); ?>);
    <?php
        for($j=0;$j<count($aYourList[$i]);$j++)
        {   // Photo
    ?>
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>] = new Array(7);
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>][0] = "<?php echo $aYourList[$i][$j][0]; ?>";
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>][1] = "<?php echo $aYourList[$i][$j][1]; ?>";
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>][2] = "<?php echo $aYourList[$i][$j][2]; ?>";
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>][3] = "<?php echo $aYourList[$i][$j][3]; ?>";
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>][4] = "<?php echo $aYourList[$i][$j][4]; ?>";
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>][5] = "<?php echo $aYourList[$i][$j][5]; ?>";
    aYourList[<?php echo $i; ?>][<?php echo $j; ?>][6] = "<?php echo $aYourList[$i][$j][6]; ?>";
    <?php
            // Photo
        }
        // Album
    }
    ?>
    // Shared album list
    <?php
    $aAlbInfo = array();
    $aPhtInfo = array();
    $iAlbPht = 1;
    $CurAlbum = "";
    $Query = "SELECT ALB_Nom,PHT_Pseudo,PHT_Fichier,SUM(VOT_Note) AS PHT_Note,SUM(VOT_Total) AS PHT_Total";
    $Query .= " FROM Albums LEFT JOIN Photos ON ALB_Nom = PHT_Album LEFT JOIN Votes ON PHT_Fichier = VOT_Fichier AND VOT_Type = 0";
    $Query .= " WHERE ALB_Shared = 1 AND UPPER(ALB_Pseudo) <> UPPER('".addslashes($Camarade)."') GROUP BY ALB_Nom,PHT_Pseudo,PHT_Fichier ORDER BY ALB_Nom,PHT_Fichier";
    $Result = mysql_query(trim($Query),$Link);
    while($aRow = mysql_fetch_array($Result))
    {   // Tant qu'il y a des photos dans l'album
        if(strcmp($CurAlbum,$aRow["ALB_Nom"]))
        {   // Nouvel album
            if(strcmp($CurAlbum,""))
            {   $iAlbPht = 1;
                $aSharedList[] = $aAlbInfo;
                $aAlbInfo = array();
            }
            $CurAlbum = $aRow["ALB_Nom"];
            if(strlen($aRow["ALB_Nom"]) <= 20) $aSharedAlb[] = str_replace($aSearch,$aReplace,trim($aRow["ALB_Nom"]));
            else $aSharedAlb[] = str_replace($aSearch,$aReplace,substr(trim($aRow["ALB_Nom"]),0,20))."...";
        }
        if(!Empty($aRow["PHT_Fichier"]))
        {   if(strlen(addslashes($aRow["PHT_Fichier"])) <= 9) $aPhtInfo[] = "n°$iAlbPht - ".addslashes($aRow["PHT_Fichier"]);
            else $aPhtInfo[] = "n°$iAlbPht - ".substr(addslashes($aRow["PHT_Fichier"]),0,9)."...";
        }
        else $aPhtInfo[] = "Pas de Photo...";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = addslashes($aRow["ALB_Nom"]);
        else $aPhtInfo[] = "...";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = urlencode(base64_encode(trim($aRow["ALB_Nom"])));
        else $aPhtInfo[] = "";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = addslashes($aRow["PHT_Fichier"]);
        else $aPhtInfo[] = "...";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = urlencode(base64_encode(trim($aRow["PHT_Fichier"])));
        else $aPhtInfo[] = "";
        if(!Empty($aRow["PHT_Fichier"])) $aPhtInfo[] = addslashes($aRow["PHT_Pseudo"]);
        else $aPhtInfo[] = "...";
        if(!Empty($aRow["PHT_Fichier"]))
        {   if(count($aClassement) != 0)
            {   $iPhtPos = 0;
                $indice = 1;
                foreach($aClassement as $Classement)
                {   if($Classement == ($aRow["PHT_Note"]+$aRow["PHT_Total"])) $iPhtPos = $indice;
                    $indice++;
                }
                if($iPhtPos != 0) $aPhtInfo[] = "$iPhtPos/$iPhtCnt";
                else $aPhtInfo[] = "Non classé";
            }
            else $aPhtInfo[] = "Non classé";
        }
        else $aPhtInfo[] = "...";
        $aAlbInfo[] = $aPhtInfo;
        $aPhtInfo = array();
        $iAlbPht++;
        // Tant qu'il y a des photos dans l'album
    }
    if(strcmp($CurAlbum,"")) $aSharedList[] = $aAlbInfo;
    mysql_free_result($Result);
    ?>
    aSharedList = new Array(<?php echo count($aSharedAlb); ?>);
    <?php
    for($i=0;$i<count($aSharedAlb);$i++)
    {   // Album
    ?>
    aSharedList[<?php echo $i; ?>] = new Array(<?php echo count($aSharedList[$i]); ?>);
    <?php
        for($j=0;$j<count($aSharedList[$i]);$j++)
        {   // Photo
    ?>
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>] = new Array(7);
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>][0] = "<?php echo $aSharedList[$i][$j][0]; ?>";
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>][1] = "<?php echo $aSharedList[$i][$j][1]; ?>";
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>][2] = "<?php echo $aSharedList[$i][$j][2]; ?>";
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>][3] = "<?php echo $aSharedList[$i][$j][3]; ?>";
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>][4] = "<?php echo $aSharedList[$i][$j][4]; ?>";
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>][5] = "<?php echo $aSharedList[$i][$j][5]; ?>";
    aSharedList[<?php echo $i; ?>][<?php echo $j; ?>][6] = "<?php echo $aSharedList[$i][$j][6]; ?>";
    <?php
            // Photo
        }
        // Album
    }
    ?>
}
// ChgYourAlbList /////////////////////////////////////////////////////////////////////////
function ChgYourAlbList()
{   var oElement;
    var i;
    VidePhotosInfos(true);
    if(document.getElementById("YourList").value!=0)
    {   document.getElementById("SharedList").value=0;
        document.getElementById("BtnAddPhoto").disabled="";
        // Rempli la liste des photos
        if(aYourList[(document.getElementById("YourList").value)-1].length!=0)
        {   document.getElementById("NewAlb").value=aYourList[(document.getElementById("YourList").value)-1][0][1];
            for(i=0;i<aYourList[(document.getElementById("YourList").value)-1].length;i++)
            {   oElement=document.createElement("option");
                oElement.text=aYourList[(document.getElementById("YourList").value)-1][i][0];
                oElement.value=i;
                document.getElementById("PhotoList").add(oElement);
            }
        }
        else
        {   document.getElementById("NewAlb").value="";
            oElement=document.createElement("option");
            oElement.text=sNoPhoto;
            oElement.value=0;
            document.getElementById("PhotoList").add(oElement);
        }

    }
    else if(document.getElementById("SharedList").value==0)
    {   document.getElementById("BtnAddPhoto").disabled="disabled";
        oElement=document.createElement("option");
        oElement.text=sNoPhoto;
        oElement.value=0;
        document.getElementById("PhotoList").add(oElement);
    }
}
// ChgSharedAlbList ///////////////////////////////////////////////////////////////////////
function ChgSharedAlbList()
{   var oElement;
    var i;
    VidePhotosInfos(true);
    if(document.getElementById("SharedList").value!=0)
    {   document.getElementById("YourList").value=0;
        document.getElementById("BtnAddPhoto").disabled="";
        // Rempli la liste des photos
        if(aSharedList[(document.getElementById("SharedList").value)-1].length!=0)
        {   document.getElementById("NewAlb").value=aSharedList[(document.getElementById("SharedList").value)-1][0][1];
            for(i=0;i<aSharedList[(document.getElementById("SharedList").value)-1].length;i++)
            {   oElement=document.createElement("option");
                oElement.text=aSharedList[(document.getElementById("SharedList").value)-1][i][0];
                oElement.value=i;
                document.getElementById("PhotoList").add(oElement);
            }
        }
        else
        {   document.getElementById("NewAlb").value="";
            oElement=document.createElement("option");
            oElement.text=sNoPhoto;
            oElement.value=0;
            document.getElementById("PhotoList").add(oElement);
        }
    }
    else if(document.getElementById("YourList").value==0)
    {   document.getElementById("BtnAddPhoto").disabled="disabled";
        oElement=document.createElement("option");
        oElement.text=sNoPhoto;
        oElement.value=0;
        document.getElementById("PhotoList").add(oElement);
    }
}
// ChgPhotoSelList ////////////////////////////////////////////////////////////////////////
function ChgPhotoSelList()
{   if(document.getElementById("YourList").value!=0)
    {   if(aYourList[(document.getElementById("YourList").value)-1].length!=0)
        {   document.getElementById("AlbSel").value=aYourList[(document.getElementById("YourList").value)-1][document.getElementById("PhotoList").value][1];
            document.getElementById("AlbHidden").value=aYourList[(document.getElementById("YourList").value)-1][document.getElementById("PhotoList").value][2];
            document.getElementById("FileSel").value=aYourList[(document.getElementById("YourList").value)-1][document.getElementById("PhotoList").value][3];
            document.getElementById("PhtHidden").value=aYourList[(document.getElementById("YourList").value)-1][document.getElementById("PhotoList").value][4];
            document.getElementById("PseudoSel").value=aYourList[(document.getElementById("YourList").value)-1][document.getElementById("PhotoList").value][5];
            document.getElementById("ClassSel").value=aYourList[(document.getElementById("YourList").value)-1][document.getElementById("PhotoList").value][6];
            if(document.getElementById("LockHidden").value!=1)
            {   if(document.getElementById("PhtHidden").value!="")
                {   document.getElementById("BtnSuppPhoto").disabled="";
                    document.getElementById("BtnShowPhoto").disabled="";
                }
                else
                {   document.getElementById("BtnSuppPhoto").disabled="disabled";
                    document.getElementById("BtnShowPhoto").disabled="disabled";
                }
            }
            else
            {   document.getElementById("BtnSuppPhoto").disabled="disabled";
                document.getElementById("BtnShowPhoto").disabled="disabled";
                document.getElementById("BtnAddPhoto").disabled="disabled";
            }
        }
        else VidePhotosInfos(false);
    }
    else if(document.getElementById("SharedList").value!=0)
    {   if(aSharedList[(document.getElementById("SharedList").value)-1].length!=0)
        {   document.getElementById("AlbSel").value=aSharedList[(document.getElementById("SharedList").value)-1][document.getElementById("PhotoList").value][1];
            document.getElementById("AlbHidden").value=aSharedList[(document.getElementById("SharedList").value)-1][document.getElementById("PhotoList").value][2];
            document.getElementById("FileSel").value=aSharedList[(document.getElementById("SharedList").value)-1][document.getElementById("PhotoList").value][3];
            document.getElementById("PhtHidden").value=aSharedList[(document.getElementById("SharedList").value)-1][document.getElementById("PhotoList").value][4];
            document.getElementById("PseudoSel").value=aSharedList[(document.getElementById("SharedList").value)-1][document.getElementById("PhotoList").value][5];
            document.getElementById("ClassSel").value=aSharedList[(document.getElementById("SharedList").value)-1][document.getElementById("PhotoList").value][6];
            if(document.getElementById("LockHidden").value!=1)
            {   if(document.getElementById("PhtHidden").value!="")
                {   document.getElementById("BtnSuppPhoto").disabled="";
                    document.getElementById("BtnShowPhoto").disabled="";
                }
                else
                {   document.getElementById("BtnSuppPhoto").disabled="disabled";
                    document.getElementById("BtnShowPhoto").disabled="disabled";
                }
            }
            else
            {   document.getElementById("BtnSuppPhoto").disabled="disabled";
                document.getElementById("BtnShowPhoto").disabled="disabled";
                document.getElementById("BtnAddPhoto").disabled="disabled";
            }
        }
        else VidePhotosInfos(false);
    }
    else VidePhotosInfos(false);
}
// OnRefresh //////////////////////////////////////////////////////////////////////////////
function OnRefresh()
{   top.PhtStatus.location.href="PhtStatus.php?Clf=<?php echo $Clf; ?>";
    return true;
}
// ConfirmSuppPhotoSel ////////////////////////////////////////////////////////////////////
function ConfirmSuppPhotoSel()
{   if(confirm("Es-tu sûr de vouloir supprimer la photo sélectionné\net détruire ainsi les commentaires et les votes qui\ns'y trouvent ? (Si toutefois il y en a)"))
    {   document.getElementById("SuppAlb").value=document.getElementById("AlbSel").value;
        document.getElementById("SuppPht").value=document.getElementById("FileSel").value;
        top.PhtStatus.location.href="PhtStatus.php?res=16&Clf=<?php echo $Clf; ?>";
        return true;
    }
    return false;
}
// ShowSelectedPhoto //////////////////////////////////////////////////////////////////////
function ShowSelectedPhoto()
{   top.PhtStatus.location.href="Photo.php?albnm="+document.getElementById("AlbHidden").value+"&vwpht="+document.getElementById("PhtHidden").value+"&Clf=<?php echo $Clf; ?>";
}
// AddNewPhoto ////////////////////////////////////////////////////////////////////////////
function AddNewPhoto()
{   if(document.getElementById("NewFile").value!="")
    {   document.getElementById("BtnSuppPhoto").disabled="disabled";
        document.getElementById("BtnShowPhoto").disabled="disabled";
        document.getElementById("YourList").disabled="disabled";
        document.getElementById("SharedList").disabled="disabled";
        document.getElementById("BtnRefresh").disabled="disabled";
        document.getElementById("LockHidden").value=1;
        top.PhtStatus.location.href="PhtStatus.php?res=1&Clf=<?php echo $Clf; ?>";
        return true;
    }
    alert("Tu n'as pas saisi le chemin complet de la photo que\ntu souhaites ajouter à l'album sélectionné!");
    return false;
}
// Initialize /////////////////////////////////////////////////
function Initialize()
{
    // Modifie la page si Netscape
    if (navigator.appName!="Microsoft Internet Explorer")
    {   
        // Merci IE!! :p
        document.getElementById("AlbSel").style.marginTop="1px";
        document.getElementById("FileSel").style.marginTop="1px";
        document.getElementById("PseudoSel").style.marginTop="1px";
        document.getElementById("ClassSel").style.marginTop="1px";

        document.getElementById("AlbSel").style.marginBottom="1px";
        document.getElementById("FileSel").style.marginBottom="1px";
        document.getElementById("PseudoSel").style.marginBottom="1px";
        document.getElementById("ClassSel").style.marginBottom="1px";
    }
}
// Commandes ////////////////////////////////////////////////////////////////////////////
FillPhotoArray();
-->
</script>
<?php
mysql_close($Link);
?>
</head>
<body bgcolor="#e4e4e4" style="margin-top: 0px;margin-bottom: 0px;margin-left: 10px;margin-right: 0px" onload="Initialize()">
<!-- ******************************************************************************************************************************** PHOTOS MANAGER -->
<table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/RedCadHG.gif"></td>
<td width="100%" bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/RedCadHD.gif"></td>
<td width=16>
    <table style="font-size: 8pt" border=0 width=16 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=200 valign="top" bgcolor="#ff0000">
    <table style="font-size: 8pt" border=0 width=200 cellspacing=0 cellpadding=0>
    <tr>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><font ID="Title">Tes Albums:</font></td>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><input type="hidden" ID="LockHidden" value=0><select tabindex=1 style="font-size: 8pt; font-family: Verdana,Lucida,Courier; color: black" ID="YourList" onchange="javascript:ChgYourAlbList()">
    <option value=0>Non S&eacute;lectionn&eacute;</option>
    <?php
    $iAlbPos = 1;
    if(count($aYourAlb) != 0)
    {   foreach($aYourAlb as $YourAlb)
        {   // Tant qu'il y a des albums
        ?><option value=<?php echo "$iAlbPos>$YourAlb"; ?></option>
        <?php
            // Tant qu'il y a des albums
            $iAlbPos++;
        }
    }
    ?>
    </select></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><font ID="Title">Albums Partagés:</font></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><select tabindex=2 style="font-size: 8pt; font-family: Verdana,Lucida,Courier; color: black" ID="SharedList" onchange="javascript:ChgSharedAlbList()">
    <option value=0>Non S&eacute;lectionn&eacute;</option>
    <?php
    $iAlbPos = 1;
    if(count($aSharedAlb) != 0)
    {   foreach($aSharedAlb as $SharedAlb)
        {   // Tant qu'il y a des albums partagés
        ?><option value=<?php echo "$iAlbPos>$SharedAlb"; ?></option>
        <?php
            // Tant qu'il y a des albums partagés
            $iAlbPos++;
        }
    }
    ?>
    </select></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td>
        <table style="font-size: 8pt" border=0 height=13 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td>
        <table style="font-size: 8pt" border=0 height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="#ffff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td>
        <table style="font-size: 8pt" border=0 height=3 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td align="right"><form action="PhtMan.php?Clf=<?php echo $Clf; ?>" method="post">
    <input type="hidden" name="ope" value=0>
    <input type="submit" ID="BtnRefresh" onclick="return OnRefresh()" style="font-family: Verdana;font-size: 10pt" tabindex=3 value="Actualiser">
    </form></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%" bgcolor="#ffffff">
    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr bgcolor="#ff0000">
    <td width=5>
        <table style="font-size: 8pt" border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=137>
        <table style="font-size: 8pt" border=0 width=137 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=2>
        <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/RedCadInHG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/RedCadInHD.jpg"></td>
    <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
    <td bgcolor="#bacc9a" align="center"><font ID="SubTitle">PHOTOS</font></td>
    <td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
    <td bgcolor="#bacc9a" align="center" nowrap><font ID="SubTitle">INFOS & COMMANDES</font></td>
    <td valign="top" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width=5 bgcolor="#ffffff">
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=145 valign="top" bgcolor="#bacc9a">
        <table style="font-size: 8pt" border=0 width=145 cellspacing=0 cellpadding=0>
        <tr>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
        <td width=146 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=126 bgcolor="#d8e1c6">
            <table style="font-size: 8pt" border=0 width=126 cellspacing=0 cellpadding=0>
            <tr>
            <td align="center"><select tabindex=4 style="width: 133px; font-size: 8pt; font-family: Verdana,Lucida,Courier; color: black" ID="PhotoList" size=7 onchange="javascript:ChgPhotoSelList()">
            <option>Pas de Photo...</option>
            </select></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
        <td width=126 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%">
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
        <td width="100%" bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5>
            <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=2 bgcolor="#ff0000">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width="100%" bgcolor="#d8e1c6">
            <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td align="left">
                <table style="font-size: 8pt" border=0 width=68 cellspacing=0 cellpadding=0>
                <tr>
                <td><font ID="Content"><b>&bull;Album:</b></font></td>
                </tr>
                </table>
            </td>
            <td width="50%" align="left"><input type="hidden" ID="AlbHidden"><input type="text" tabindex=5 style="background-color: #808080; font-size: 7pt; font-family: Verdana,Lucida,Courier; color: white" ID="AlbSel" size=18 value="..." readonly></td>
            <td width=5>
                <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td align="left">
                <table style="font-size: 8pt" border=0 width=68 cellspacing=0 cellpadding=0>
                <tr>
                <td><font ID="Content"><b>&bull;Fichier:</b></font></td>
                </tr>
                </table>
            </td>
            <td width="50%" align="left"><input type="hidden" ID="PhtHidden"><input type="text" tabindex=6 style="background-color: #808080; font-size: 7pt; font-family: Verdana,Lucida,Courier; color: white; width: 90px" ID="FileSel" size=18 value="..." readonly></td>
            </tr>
            <tr>
            <td align="left"><font ID="Content"><b>&bull;Pseudo:</b></font></td>
            <td align="left"><input type="text" tabindex=7 style="background-color: #808080; font-size: 7pt; font-family: Verdana,Lucida,Courier; color: white" ID="PseudoSel" size=18 value="..." readonly></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td align="left"><font ID="Content"><b>&bull;Position:</b></font></td>
            <td align="left"><input type="text" tabindex=8 style="background-color: #808080; font-size: 7pt; font-family: Verdana,Lucida,Courier; color: white; width: 90px" ID="ClassSel" size=18 value="..." readonly></td>
            </tr>
            <tr>
            <td>
                <table style="font-size: 8pt" border=0 height=4 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            <tr bgcolor="#bacc9a">
            <td>
                <table style="font-size: 8pt" border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            <tr>
            <td>
                <table style="font-size: 8pt" border=0 height=3 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6">
            <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width="100%" bgcolor="#d8e1c6">
            <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td width="100%" align="right"><form action="PhtMan.php?Clf=<?php echo $Clf; ?>" method="post">
            <input type="hidden" ID="SuppAlb" name="albnm">
            <input type="hidden" ID="SuppPht" name="pht">
            <input type="hidden" name="ope" value=1>
            <input type="submit" ID="BtnSuppPhoto" tabindex=9 style="font-family: Verdana;font-size: 8pt" onclick="return ConfirmSuppPhotoSel()" value="Supprimer" disabled>
            </form></td>
            <td width=5>
                <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td align="right"><input type="button" ID="BtnShowPhoto" tabindex=10 style="font-family: Verdana;font-size: 8pt" onclick="javascript:ShowSelectedPhoto()" value=" Afficher " disabled></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6">
            <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5>
            <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=2 bgcolor="#ff0000">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 height=3 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width="100%" bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 height=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width="100%" bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 height=3 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width="100%" bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5 bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5 bgcolor="#d8e1c6">
            <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width="100%" bgcolor="#d8e1c6">
            <form action="PhtNew.php?Clf=<?php echo $Clf; ?>" enctype="multipart/form-data" target="PhtNewFile" method="post">
            <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td width="100%" align="right"><input type="file" tabindex=11 ID="NewFile" name="pht" style="font-family: Verdana;font-size: 8pt" size=31></td>
            <td width=10>
                <table style="font-size: 8pt" border=0 width=10 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td align="right"><input type="hidden" ID="NewAlb" name="albnm"><input type="hidden" name="ope" value=1><input type="submit" ID="BtnAddPhoto" onclick="return AddNewPhoto()" tabindex=12 style="font-family: Verdana;font-size: 8pt" value=" Ajouter " disabled></td>
            </tr>
            </table>
            </form>
        </td>
        <td width=5 bgcolor="#d8e1c6">
            <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5>
            <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=2 bgcolor="#ff0000">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
        <td width="100%" bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
        <td width=2 bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=2 bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table style="font-size: 8pt" border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
    <td  width=137 bgcolor="#bacc9a">
        <table style="font-size: 8pt" border=0 width=137 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=2 bgcolor="#ff0000">
        <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/RedCadInBG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/RedCadInBD.jpg"></td>
    <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr bgcolor="#ff0000">
    <td width=5>
        <table style="font-size: 8pt" border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=137><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=2><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=16>
    <table style="font-size: 8pt" border=0 width=16 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/RedCadBG.gif"></td>
<td width="100%" bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/RedCadBD.gif"></td>
<td width=16>
    <table style="font-size: 8pt" border=0 width=16 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<?php
if((!Empty($iSuppRes))||(!Empty($iAjoutRes)))
{   // Commandes
?>
<script type="text/javascript">
<!--
// Commandes /////////////////////////////////////////////////////////////////////////////
var index=1;
<?php
if(!Empty($iSuppRes))
{   // Sélectionne l'album de suppression
?>
while(document.getElementById("YourList").item(index))
{   if(document.getElementById("YourList").item(index).text=="<?php
    if(strlen($albnm) <= 20) echo str_replace("\'","'",$albnm);
    else echo str_replace("\'","'",substr($albnm,0,20))."...";
    ?>") document.getElementById("YourList").selectedIndex = index;
    index++;
}
ChgYourAlbList();
<?php
    // Sélectionne l'album de suppression
}
else
{   // Sélectionne l'album d'ajout
    if(!Empty($shrd))
    {   // Shared
?>
while(document.getElementById("SharedList").item(index))
{   if(document.getElementById("SharedList").item(index).text=="<?php
    if(strlen(base64_decode(urldecode(trim($albnm)))) <= 20) echo str_replace("\'","'",base64_decode(urldecode(trim($albnm))));
    else echo str_replace("\'","'",substr(base64_decode(urldecode(trim($albnm))),0,20))."...";
    ?>") document.getElementById("SharedList").selectedIndex = index;
    index++;
}
ChgSharedAlbList();
<?php
        // Shared
    }
    else
    {   // Your
?>
while(document.getElementById("YourList").item(index))
{   if(document.getElementById("YourList").item(index).text=="<?php
    if(strlen(base64_decode(urldecode(trim($albnm)))) <= 20) echo str_replace("\'","'",base64_decode(urldecode(trim($albnm))));
    else echo str_replace("\'","'",substr(base64_decode(urldecode(trim($albnm))),0,20))."...";
    ?>") document.getElementById("YourList").selectedIndex = index;
    index++;
}
ChgYourAlbList();
<?php
        // Your
    }
?>
document.getElementById("NewFile").value="<?php echo base64_decode(urldecode(trim($phtpth))); ?>";
<?php
    // Sélectionne l'album d'ajout
}
?>
document.getElementById("BtnAddPhoto").disabled="";
//-->
</script>
<?php
    // Commandes
}
?>
<!-- *********************************************************************************************************************************************** -->
</body>
</html>
