<?php
require("Package.php");
$Chp = "1";
$Clf = $_GET['Clf'];
$LogDate = "";
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
            $LogDate = stripslashes($aRow["CAM_LogDate"]);
            mysql_free_result($Result);
            // Mise à jour de la date de connexion
            $aDate = getdate();
            $Query = "UPDATE Camarades SET CAM_LogDate = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."' WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
            $Result = mysql_query(trim($Query),$Link);
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
{   // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(Empty($Link))
    {   $Msg = "Connexion au serveur impossible!";
        include("Message.php");
        die();
    }
    mysql_select_db(GetMySqlDB(),$Link);
    //$Msg = "Tu n'est pas connect&eacute;!";
    //include("Message.php");
    //die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Accueil</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
p {padding: 0px; margin-bottom: 0px; margin-top: 0px; border: 0px}
a {font-size: 10pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; border: 0px}
table {padding: 0px; margin-bottom: 0px; border: 0px}
#Page {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: gray}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#Info {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#Comment {font-size: 8pt; font-family: Verdana,Lucida,Courier}
#ActuFont {font-size: 10pt; font-family: Verdana,Lucida,Courier}
</style>
<?php
if(!Empty($Clf))
{   // Connecté
?>
<script type="text/javascript">
<!--
// Constantes ///////////////////////////////////////////////////////////////////////////////
var sPrgShdAdd='<font ID="ActuFont">Photos ajout&eacute;es par les autres camarades:&nbsp;<font color="#8000ff">';
var sPrgShdTotal='<font ID="ActuFont">Nbr total de photos:&nbsp;<font color="#8000ff">';
var sPrgNewFrom='<font ID="ActuFont">De:&nbsp;<font color="#8000ff">';
var sPrgNewPht='<font ID="ActuFont">Nbr photos:&nbsp;<font color="#8000ff">';
var sPrgNewDate='<font ID="ActuFont">Date:&nbsp;<font color="#8000ff">';
// ChgSharedAlbList /////////////////////////////////////////////////////////////////////////
function ChgSharedAlbList()
{   switch(document.getElementById("ShdAlbList").selectedIndex)
    {   <?php
        $CntShdAlb = 0;
        $Count = 0;
        $Query = "SELECT ALB_Nom FROM Albums";
        $Query .= " WHERE UPPER(ALB_Pseudo) = UPPER('".addslashes($Camarade)."') AND ALB_Shared = 1 ORDER BY ALB_Nom";
        $Result = mysql_query(trim($Query),$Link);
        while($aRow = mysql_fetch_array($Result))
        {   // Tant qu'il y a des albums
        ?>case <?php echo $CntShdAlb; ?>:
        {   document.getElementById("PrgShdAdd").innerHTML=sPrgShdAdd+"<?php
            $Query = "SELECT COUNT(*) AS PHT_AddCnt FROM Photos WHERE UPPER(PHT_Album) = UPPER('".trim($aRow["ALB_Nom"])."')";
            $Query .= " AND UPPER(PHT_Pseudo) <> UPPER('".addslashes($Camarade)."')";
            $Count = mysql_result(mysql_query(trim($Query),$Link),0,"PHT_AddCnt");
            echo $Count;
            ?></font></font>";
            document.getElementById("PrgShdTotal").innerHTML=sPrgShdTotal+"<?php
            $Query = "SELECT COUNT(*) AS PHT_TotalCnt FROM Photos WHERE UPPER(PHT_Album) = UPPER('".trim($aRow["ALB_Nom"])."')";
            $Count = mysql_result(mysql_query(trim($Query),$Link),0,"PHT_TotalCnt");
            echo $Count;
            ?></font></font>";
            break;
        }
        <?php
            // Tant qu'il y a des albums
            $CntShdAlb++;
            if(strlen(trim($aRow["ALB_Nom"])) <= 13) $aShdAlb[] = str_replace($aSearch,$aReplace,trim($aRow["ALB_Nom"]));
            else $aShdAlb[] = str_replace($aSearch,$aReplace,substr(trim($aRow["ALB_Nom"]),0,13))."...";
        }
        mysql_free_result($Result);
        ?>default:
        {   document.getElementById("PrgShdAdd").innerHTML=sPrgShdAdd+"...</font></font>";
            document.getElementById("PrgShdTotal").innerHTML=sPrgShdTotal+"...</font></font>";
            break;
        }
    }
}
// ChgNewAlbList /////////////////////////////////////////////////////////////////////////
function ChgNewAlbList()
{   switch(document.getElementById("NewAlbList").selectedIndex)
    {   <?php
        $CntNewAlb = 0;
        $Count = 0;
        $Query = "SELECT ALB_Nom,ALB_Date,ALB_Pseudo FROM Albums";
        $Query .= " WHERE ALB_Date >= '$LogDate' AND UPPER(ALB_Pseudo) <> UPPER('".addslashes($Camarade)."') ORDER BY ALB_Date DESC LIMIT 0,30";
        $Result = mysql_query(trim($Query),$Link);
        while($aRow = mysql_fetch_array($Result))
        {   // Tant qu'il y a des albums
        ?>case <?php echo $CntNewAlb; ?>:
        {   document.getElementById("PrgNewFrom").innerHTML=sPrgNewFrom+"<?php echo trim($aRow["ALB_Pseudo"]); ?></font></font>";
            document.getElementById("PrgNewPht").innerHTML=sPrgNewPht+"<?php
            $Query = "SELECT COUNT(*) AS PHT_TotalCnt FROM Photos WHERE UPPER(PHT_Album) = UPPER('".addslashes($aRow["ALB_Nom"])."')";
            $Count = mysql_result(mysql_query(trim($Query),$Link),0,"PHT_TotalCnt");
            echo $Count;
            ?></font></font>";
            document.getElementById("PrgNewDate").innerHTML=sPrgNewDate+"<?php
            $aMnDy = sscanf(substr($aRow["ALB_Date"],8,10),"%d");
            $iTmp = $aMnDy[0];
            echo "$iTmp/";
            $aMnDy = sscanf(substr($aRow["ALB_Date"],5,2),"%d");
            $iTmp = $aMnDy[0];
            echo "$iTmp/";
            echo substr($aRow["ALB_Date"],0,4);
            ?></font></font>";
            break;
        }
        <?php
            // Tant qu'il y a des albums
            $CntNewAlb++;
            if(strlen(trim($aRow["ALB_Nom"])) <= 13) $aNewAlb[] = str_replace($aSearch,$aReplace,trim($aRow["ALB_Nom"]));
            else $aNewAlb[] = str_replace($aSearch,$aReplace,substr(trim($aRow["ALB_Nom"]),0,13))."...";
        }
        mysql_free_result($Result);
        ?>default:
        {   document.getElementById("PrgNewFrom").innerHTML=sPrgNewFrom+"...</font></font>";
            document.getElementById("PrgNewPht").innerHTML=sPrgNewPht+"...</font></font>";
            document.getElementById("PrgNewDate").innerHTML=sPrgNewDate+"...</font></font>";
            break;
        }
    }
}
// OnVoirFlyer //////////////////////////////////////////////////////////////////////////////
function OnVoirFlyer(sFlyer,sSoire,sYear,sMonth,sDay,iWidth,iHeight)
{   var iHigh=0;
    var WndFlyer;
    if((iWidth==0)||(iHeight==0))
    {   WndFlyer=window.open("Flyer.php?fly="+sFlyer+"&sr="+sSoire+"&yr="+sYear+"&mn="+sMonth+"&dy="+sDay,"WndFlyer","left=0,top=0,width=400,height=300,scrollbars=1,resizable=1");
        WndFlyer.focus();
    }
    else
    {   iHigh=iHeight+70;
        WndFlyer=window.open("Flyer.php?fly="+sFlyer+"&sr="+sSoire+"&yr="+sYear+"&mn="+sMonth+"&dy="+sDay,"WndFlyer","left=0,top=0,width="+iWidth+",height="+iHigh+",resizable=1");
        WndFlyer.focus();
    }
}
-->
</script>
<?php
    // Connecté
}
?>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px;margin-right: 10px">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- *************************************************************************************************************************************** ACCUEIL -->
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
    <td width="100%" bgcolor="#ff0000"><font ID="BigTitle">&nbsp;<b>Accueil</b></font></td>
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
if(!Empty($Clf))
{   // Connecté
    ?><font face="Impact,Verdana,Lucida" size=5><b>Salut &agrave; toi camarade</b> <font face="Verdana,Lucida,Courier" size=5 color="#8080ff"><?php echo str_replace($aSearch,$aReplace,$Camarade); ?></font> <b>!</b><br></font><?php
    // Connecté
}
else
{   // Non Connecté
    ?><font face="Impact,Verdana,Lucida" size=5><b>Salut &agrave; toi camarade !</b></font><br></font><?php
    // Non Connecté
}
?>
<font face="Verdana,Lucida,Courier" size=2>Soit le ou la bienvenue sur le site officiel du <b>Classico</b>...<br></font><hr>
<?php
$bPhtAdd = true;
$PhtIndex = 0;
$PhtVot = 0;
$PhtFirst = true;
$Query = "SELECT COUNT(*) AS PHT_Count FROM Photos";
$iPhtCnt = mysql_result(mysql_query(trim($Query),$Link),0,"PHT_Count");
$Query = "SELECT SUM(VOT_Note)+SUM(VOT_Total) AS VOT_Pos,VOT_Fichier FROM Votes WHERE VOT_Type = 0 GROUP BY VOT_Fichier ORDER BY VOT_Pos DESC";
$Result = mysql_query(trim($Query),$Link);
if(mysql_num_rows($Result) != 0)
{   $iLastVote = 0;
    while($aRow = mysql_fetch_array($Result))
    {   if($bPhtAdd)
        {   if(!$PhtFirst)
            {   if(($PhtVot != $aRow["VOT_Pos"])&&($PhtIndex >= 3)) $bPhtAdd = false;
                else
                {   $PhtVot = $aRow["VOT_Pos"];
                    $aPic[] = $aRow["VOT_Fichier"];
                    $PhtIndex++;
                }
            }
            else
            {   $PhtVot = $aRow["VOT_Pos"];
                $aPic[] = $aRow["VOT_Fichier"];
                $PhtIndex++;
                $PhtFirst = false;
            }
        }
        if($iLastVote != $aRow["VOT_Pos"]) $aClassement[] = $aRow["VOT_Pos"];
        $iLastVote = $aRow["VOT_Pos"];
    }
    mysql_free_result($Result);
    //
    $iResCnt = 0;
    $iResStart = 1;
    $Query = "SELECT PHT_Album,PHT_Pseudo,PHT_Fichier,PHT_Comment,V1.VOT_Note AS PHT_Note,V1.VOT_Total AS PHT_Total,SUM(V2.VOT_Note) AS PHT_AllNote,SUM(V2.VOT_Total) AS PHT_AllTotal";
    $Query .= " FROM Photos LEFT JOIN Votes AS V1 ON PHT_Fichier = V1.VOT_Fichier AND UPPER(V1.VOT_Pseudo) = UPPER('".addslashes($Camarade)."') AND V1.VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."' AND V1.VOT_Type = 0 LEFT JOIN Votes AS V2 ON PHT_Fichier = V2.VOT_Fichier AND V2.VOT_Type = 0";
    $Query .= " WHERE PHT_Fichier LIKE '".trim($aPic[rand(0,($PhtIndex-1))])."'";
    $Query .= " GROUP BY PHT_Album,PHT_Pseudo,PHT_Fichier,PHT_Comment,PHT_Note,PHT_Total ORDER BY PHT_Fichier";
    $Result = mysql_query(trim($Query),$Link);
    $iResCnt = mysql_num_rows($Result);
    $iResEnd = $iResCnt;
    $CntView = 0;
    $iPhtPos = 0;
    $indice = 1;
    $iVoteRef = 1;
    while($aRow = mysql_fetch_array($Result))
    {   // Tant qu'il y a des photos
        $CntView++;
        if(($CntView >= $iResStart)&&($CntView <= $iResEnd))
        {   // Affiche la photo
?>
<table border=0 height=8 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width="50%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td align="left">
    <table border=0 cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/SubOranHG.jpg"></td>
    <td width=50><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    <td width=5 bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=10 bgcolor="#ffffff">
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%" bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=50><img src="<?php echo GetFolder(); ?>/Images/DosPhoto.gif"></td>
    <td width=10><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td nowrap><font ID="Title"><font style="font-size: 22pt; color: yellow"><?php echo $aRow["PHT_Fichier"]; ?></font></font></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5 valign="bottom" bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/OranInBG.jpg"></td>
    <td width=10 bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width="100%" bgcolor="#ffffff" align="right">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width="100%" align="right" nowrap><font ID="Title">Album photos :</font></td>
        <td width=10>
            <table border=0 width=10 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td>
        <form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" name="albnm" value="<?php echo urlencode(base64_encode(trim($aRow["PHT_Album"]))); ?>">
        <input type="hidden" name="albvwu" value=0>
        <input type="hidden" name="albtri" value=0>
        <input type="submit" style="font-family: Verdana;font-size: 10pt" value="Voir Photos">
        </form>
        </td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    <td width=5 bgcolor="#ffffff">
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
    <td width=150 bgcolor="#BACC9A">
        <table border=0 width=150 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInHG.jpg"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInHD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td align="center" valign="middle" bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Photos/<?php echo $aRow["PHT_Fichier"]; ?>"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A" valign="top">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
        <td width="100%" bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><font ID="Info">Album:&nbsp;&nbsp;&nbsp;<input type="text" ID="AlbumPhoto" style="background-color: #d6d6d6; font-size: 8pt; font-family: Verdana,Lucida,Courier" size=12 readonly></font>
        <script type="text/javascript">
        <!--
        // Commande ///////////////////////////////////////////////////////////////////////////////
        document.getElementById("AlbumPhoto").value="<?php echo str_replace("\'","'",addslashes($aRow["PHT_Album"])); ?>";
        -->
        </script>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><div style="width: 139px; height: 20px; overflow: hidden">
             <table border=0 cellspacing=0 cellpadding=0>
             <tr>
             <td nowrap><font ID="Info">Par:&nbsp;</font><font ID="Comment"><font style="font-size: 12pt; color: #8000ff"><?php echo stripslashes($aRow["PHT_Pseudo"]); ?></font></font></td>
             </tr>
             </table>
        </div></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
        if(count($aClassement) != 0)
        {   $iPhtPos = 0;
            $indice = 1;
            foreach($aClassement as $Classement)
            {   if($Classement == ($aRow["PHT_AllNote"]+$aRow["PHT_AllTotal"])) $iPhtPos = $indice;
                $indice++;
            }
            if($iPhtPos != 0)
            {   // Affiche le classement
        ?>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><font ID="Info">Classement:&nbsp;&nbsp;<input type="text" style="background-color: #d6d6d6; font-size: 8pt; font-family: Verdana,Lucida,Courier" size=6 value="<?php echo "$iPhtPos/$iPhtCnt"; ?>" readonly></font></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
                // Affiche le classement
            }
        }
        ?>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td nowrap><font ID="Info">Commentaires:</font></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td>
        <div style="width: 139px; height: 182px; overflow: auto"><font ID="Comment"><?php echo GetComments($Clf,$Link,$aRow["PHT_Fichier"]); ?></font></div>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
        <td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInBG.jpg"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInBD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
    </tr>
    </table>
</td>
<td width="50%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table><br>
<?php
            // Affiche la photo
            $iVoteRef++;
        }
        // Tant qu'il y a des photos
    }
}
mysql_free_result($Result);
?>
<!-- ********************************************************************************************************** PUB -->
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
    <td><font ID="Title">&nbsp;Pub</font></td>
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
<table border=0 height=15 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width="100%" align="center"><font face="Verdana,Lucida,Courier" size=2><b>&quot;</b><i>COT! COT! COOOOOTTTTTTTJJJJJJJJJJJJJEEEEE N'IRAI JAMAIS!!!! AU CLASSICO! NON NON!</i><b>&quot;</b><br><b>-&gt; CEUX QUI ONT UN CERVEAU, VONT AU CLASSICO...</b></font></td>
</tr>
</table>
<table border=0 height=15 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- *************************************************************************************************************** -->
<?php
if(!Empty($Clf))
{   // Connecté
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
    <td><font ID="Title">&nbsp;Actualit&eacute;s</font></td>
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
<!-- *************************************************************************************************** ACTUALITES -->
<table border=0 height=15 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=20>
    <table border=0 width=20 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr bgcolor="#ff0000">
    <td align="center" valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosMailBox.jpg"></td>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=2 bgcolor="#ffff00">
        <table border=0 width=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
    <td width="100%">
        <?php
        $CntRead = 0;
        $CntNew = 0;
        $CntWrite = 0;
        $Query = "SELECT COUNT(*) AS MSG_ReadCnt,MSG_LuFlag FROM Messagerie";
        $Query .= " WHERE UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."') AND MSG_ReadStk = 1 GROUP BY MSG_LuFlag";
        $Result = mysql_query(trim($Query),$Link);
        while($aRow = mysql_fetch_array($Result))
        {   if(!Empty($aRow["MSG_LuFlag"])) $CntRead += $aRow["MSG_ReadCnt"];
            else
            {   $CntNew = $aRow["MSG_ReadCnt"];
                $CntRead += $aRow["MSG_ReadCnt"];
            }
        }
        mysql_free_result($Result);
        $Query = "SELECT COUNT(*) AS MSG_WriteCnt FROM Messagerie";
        $Query .= " WHERE UPPER(MSG_From) = UPPER('".addslashes($Camarade)."') AND MSG_WriteStk = 1";
        $CntWrite = mysql_result(mysql_query(trim($Query),$Link),0,"MSG_WriteCnt");
        ?>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td><font ID="ActuFont">&bull; <font color="#ffffff"><b>Nouveaux messages:</b></font> <font color="#ffff00"><b><?php echo $CntNew; ?></b></font></font></td>
        </tr>
        <tr>
        <td><font ID="ActuFont">&bull; <font color="#ffffff"><b>Messages re&ccedil;us:</b></font> <font color="#00ff00"><b><?php echo $CntRead; ?></b></font></font></td>
        </tr>
        <tr>
        <td><font ID="ActuFont">&bull; <font color="#ffffff"><b>Messages envoy&eacute;s:</b></font> <font color="#00ff00"><b><?php echo $CntWrite; ?></b></font></font></td>
        </tr>
        </table>
    </td>
    <td valign="top">
        <table border=0 cellspacing=0 cellpadding=0 bgcolor="#ffffff">
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/RedCadHD.gif"></td>
        </tr>
        <tr>
        <td bgcolor="#ff0000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr bgcolor="#ffff00">
    <td>
        <table border=0 height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr bgcolor="#bacc9a">
    <td align="center" valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosPhoto.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width="100%">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width="50%">
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td nowrap><font ID="ActuFont"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"> <b>Tes Albums Partag&eacute;s:</b> <font color="#ff0000"><b><?php echo $CntShdAlb; ?></b></font></font></td>
            </tr>
            <tr>
            <td>
                <table border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#d8e1c6">
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
            <td width="100%">
                <table border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr>
                <td><select style="font-size: 7pt; font-family: Verdana,Lucida,Courier; color: black" ID="ShdAlbList" onchange="javascript:ChgSharedAlbList()" size=6>
                <?php
                if(count($aShdAlb) != 0)
                {   foreach($aShdAlb as $AlbNom)
                    {   // Tant qu'il y a des albums
                        ?><option><?php echo stripslashes($AlbNom); ?></option>
                        <?php
                        // Tant qu'il y a des albums
                    }
                }
                else echo "<option>Pas d'Album...</option>";
                ?><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                </select></td>
                <td width=10>
                    <table border=0 width=10 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td width="100%" valign="top" bgcolor="#d8e1c6">
                    <table border=0 width="100%" cellspacing=0 cellpadding=0>
                    <tr>
                    <td width=5>
                        <table border=0 width=5 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                        </tr>
                        </table>
                    </td>
                    <td width=10 valign="top">
                        <table border=0 width=10 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><font ID="ActuFont">&bull;</font></td>
                        </tr>
                        </table>
                    </td>
                    <td width="100%"><p ID="PrgShdAdd"><font ID="ActuFont">Photos ajout&eacute;es par les autres camarades:&nbsp;<font color="#8000ff">...</font></font></p></td>
                    </tr>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    <td valign="top"><font ID="ActuFont">&bull;</font></td>
                    <td width="100%"><p ID="PrgShdTotal"><font ID="ActuFont">Nbr total de photos:&nbsp;<font color="#8000ff">...</font></font></p></td>
                    </tr>
                    </table>
                </td>
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
            </table>
        </td>
        <td width=10>
            <table border=0 width=10 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width="50%">
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td nowrap><font ID="ActuFont"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"> <b>Nouveaux Albums:</b> <font color="#ff0000"><b><?php echo $CntNewAlb; ?></b></font></font></td>
            </tr>
            <tr>
            <td>
                <table border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#d8e1c6">
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
            <td width="100%">
                <table border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr>
                <td><select style="font-size: 7pt; font-family: Verdana,Lucida,Courier; color: black" ID="NewAlbList" onchange="javascript:ChgNewAlbList()" size=6>
                <?php
                if(count($aNewAlb) != 0)
                {   foreach($aNewAlb as $AlbNom)
                    {   // Tant qu'il y a des albums
                        ?><option><?php echo stripslashes($AlbNom); ?></option>
                        <?php
                        // Tant qu'il y a des albums
                    }
                }
                else echo "<option>Pas d'Album...</option>";
                ?><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                </select></td>
                <td width=10>
                    <table border=0 width=10 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td width="100%" valign="top" bgcolor="#d8e1c6">
                    <table border=0 width="100%" cellspacing=0 cellpadding=0>
                    <tr>
                    <td width=5>
                        <table border=0 width=5 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                        </tr>
                        </table>
                    </td>
                    <td width=10 valign="top">
                        <table border=0 width=10 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><font ID="ActuFont">&bull;</font></td>
                        </tr>
                        </table>
                    </td>
                    <td width="100%">
                        <table border=0 width="100%" height=16 cellspacing=0 cellpadding=0>
                        <tr>
                        <td valign="top"><div style="position: float; width: 100%; height: 16px; overflow: hidden" align="left">
                            <table border=0 width=5 cellspacing=0 cellpadding=0>
                            <tr>
                            <td nowrap><p ID="PrgNewFrom"><font ID="ActuFont">De: <font color="#8000ff">...</font></font></p></td>
                            </tr>
                            </table>
                        </div></td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    <td valign="top"><font ID="ActuFont">&bull;</font></td>
                    <td width="100%"><p ID="PrgNewPht"><font ID="ActuFont">Nbr photos:&nbsp;<font color="#8000ff">...</font></font></p></td>
                    </tr>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    <td valign="top"><font ID="ActuFont">&bull;</font></td>
                    <td width="100%"><p ID="PrgNewDate"><font ID="ActuFont">Date:&nbsp;<font color="#8000ff">...</font></font></p></td>
                    </tr>
                    </table>
                </td>
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
            </table>
        </td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr bgcolor="#ffff00">
    <td>
        <table border=0 height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr bgcolor="#ff8000">
    <td align="center" valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosEve.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width="100%" valign="top">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width="100%">
            <table border=0 width="100%" height=20 cellspacing=0 cellpadding=0>
            <tr>
            <td nowrap><font ID="ActuFont"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"> <b>Ev&eacute;nements d'Aujourd'hui:</b></font></td>
            <td width=5>
                <table border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <?php
            $bPass = false;
            $Query = "SELECT EVE_Pseudo,EVE_Nom,EVE_Lieu,EVE_Flyer FROM Evenements WHERE EVE_Date = '";
            $Query .= trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
            $Query .= " ORDER BY EVE_Nom";
            $Result = mysql_query(trim($Query),$Link);
            if(mysql_num_rows($Result) != 0)
            {   while($aRow = mysql_fetch_array($Result))
                {   // Affiche événement
                    if(!$bPass)
                    {   // Lag
            ?>
            <td width="100%"><font ID="ActuFont"><font color="#ffffff"><b><?php echo trim($aDate["mday"])."/".trim($aDate["mon"])."/".trim($aDate["year"]); ?></b></font></font></td>
            </tr>
            </table>
            <?php
                        // Lag
                        $bPass = true;
                    }
            ?>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr>
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #000000" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt" color="#ffffff">&nbsp;Ev.&nbsp;:&nbsp;&nbsp;<font color="#ffff00"><?php echo str_replace($aSearch,$aReplace,trim($aRow["EVE_Nom"])); ?></font></font></font></td>
                 </tr>
                 </table>
            </div></td>
            </tr>
            </table>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr valign="bottom">
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #d0d0d0" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt">&nbsp;Pr&eacute;sent&eacute; par:&nbsp;&nbsp;<font color="#8000ff"><?php echo $aRow["EVE_Pseudo"]; ?></font></font></font></td>
                 </tr>
                 </table>
            </div></td>
            </tr>
            </table>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr valign="bottom">
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #d0d0d0" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt">&nbsp;Lieu:&nbsp;&nbsp;<font color="#8000ff"><?php echo str_replace($aSearch,$aReplace,trim($aRow["EVE_Lieu"])); ?></font></font></font></td>
                 </tr>
                 </table>
            </div></td>
            <?php
                    if(!Empty($aRow["EVE_Flyer"]))
                    {   // Flyer
            ?>
            </tr>
            </table>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr valign="bottom">
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #d0d0d0" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt">&nbsp;Flyer:&nbsp;&nbsp;<input type="button" style="font-size: 8pt" onclick="javascript:OnVoirFlyer(<?php
                 echo "'".urlencode(base64_encode($aRow["EVE_Flyer"]))."','".urlencode(base64_encode($aRow["EVE_Nom"]))."'";
                 echo ",".trim($aDate["year"]).",".trim($aDate["mon"]).",".trim($aDate["mday"]);
                 // Récupère la taille de l'image
                 $aSize = @getimagesize(GetSrvFlyFolder().$aRow["EVE_Flyer"]);
                 if((!Empty($aSize[0]))&&(!Empty($aSize[1]))) echo ",".$aSize[0].",".$aSize[1];
                 else echo ",0,0";
                 ?>)" value="Voir Flyer"></font></font></td>
                 </tr>
                 </table>
            </div></td>
            </tr>
            </table>
            <table border=0 width="100%" height=4 cellspacing=0 cellpadding=0 bgcolor="d0d0d0">
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <?php
                        // Flyer
                    }
                    // Affiche événement
                }
                mysql_free_result($Result);
            }
            else
            {   // Pas d'Evénement
            ?>
            <td width="100%"><font ID="ActuFont"><font color="#ffffff"><b><i>Aucun</i></b></font></font></td>
            <?php
                // Pas d'Evénement
            }
            ?>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td width="100%" valign="top">
            <table border=0 width="100%" height=20 cellspacing=0 cellpadding=0>
            <tr>
            <td nowrap><font ID="ActuFont"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"> <b>Prochain Ev&eacute;nements:</b></font></td>
            <td width=5>
                <table border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <?php
            $sNextDate = "";
            $bContinue = true;
            $bPass = false;
            $Query = "SELECT EVE_Date,EVE_Pseudo,EVE_Nom,EVE_Lieu,EVE_Flyer FROM Evenements";
            $Query .= " WHERE EVE_Date > '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."' ORDER BY EVE_Date";
            $Result = mysql_query(trim($Query),$Link);
            if(mysql_num_rows($Result) != 0)
            {   // Evénement trouvé
                while(($aRow = mysql_fetch_array($Result))&&($bContinue))
                {   if(!strcmp($sNextDate,"")) $sNextDate = $aRow["EVE_Date"];
                    else if(strcmp($sNextDate,$aRow["EVE_Date"])) $bContinue = false;
                    // Boucle tant qu'il y a des Evénements
                    if(!$bPass)
                    {   // Lag
            ?>
            <td width="100%"><font ID="ActuFont"><font color="#ffffff"><b><?php
            $aMnDy = sscanf(substr($aRow["EVE_Date"],8,10),"%d");
            $iTmp = $aMnDy[0];
            echo "$iTmp/";
            $aMnDy = sscanf(substr($aRow["EVE_Date"],5,2),"%d");
            $iTmp = $aMnDy[0];
            echo "$iTmp/";
            echo substr($aRow["EVE_Date"],0,4);
            ?></b></font></font></td>
            </tr>
            </table>
            <?php
                        // Lag
                        $bPass = true;
                    }
                    if($bContinue)
                    {   // Affiche événement
            ?>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr>
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #000000" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt" color="#ffffff">&nbsp;Ev.&nbsp;:&nbsp;&nbsp;<font color="#00ff00"><?php echo str_replace($aSearch,$aReplace,trim($aRow["EVE_Nom"])); ?></font></font></font></td>
                 </tr>
                 </table>
            </div></td>
            </tr>
            </table>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr valign="bottom">
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #d0d0d0" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt">&nbsp;Pr&eacute;sent&eacute; par:&nbsp;&nbsp;<font color="#8000ff"><?php echo $aRow["EVE_Pseudo"]; ?></font></font></font></td>
                 </tr>
                 </table>
            </div></td>
            </tr>
            </table>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr valign="bottom">
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #d0d0d0" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt">&nbsp;Lieu:&nbsp;&nbsp;<font color="#8000ff"><?php echo str_replace($aSearch,$aReplace,trim($aRow["EVE_Lieu"])); ?></font></font></font></td>
                 </tr>
                 </table>
            </div></td>
            <?php
                        if(!Empty($aRow["EVE_Flyer"]))
                        {   // Flyer
            ?>
            </tr>
            </table>
            <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
            <tr valign="bottom">
            <td valign="top"><div style="position: absolute; width: 100%; height: 20px; overflow: hidden; background-color: #d0d0d0" align="left">
                 <table border=0 cellspacing=0 cellpadding=0>
                 <tr>
                 <td nowrap><font ID="Title"><font style="font-size: 12pt">&nbsp;Flyer:&nbsp;&nbsp;<input type="button" style="font-size: 8pt" onclick="javascript:OnVoirFlyer(<?php
                 echo "'".urlencode(base64_encode($aRow["EVE_Flyer"]))."','".urlencode(base64_encode($aRow["EVE_Nom"]))."'";
                 echo ",".substr($aRow["EVE_Date"],0,4);
                 $aMnDy = sscanf(substr($aRow["EVE_Date"],5,2),"%d");
                 $iTmp = $aMnDy[0];
                 $iTmp = $iTmp * 1;
                 echo ",$iTmp";
                 $aMnDy = sscanf(substr($aRow["EVE_Date"],8,10),"%d");
                 $iTmp = $aMnDy[0];
                 $iTmp = $iTmp * 1;
                 echo ",$iTmp";
                 // Récupère la taille de l'image
                 $aSize = @getimagesize(GetSrvFlyFolder().$aRow["EVE_Flyer"]);
                 if((!Empty($aSize[0]))&&(!Empty($aSize[1]))) echo ",".$aSize[0].",".$aSize[1];
                 else echo ",0,0";
                 ?>)" value="Voir Flyer"></font></font></td>
                 </tr>
                 </table>
            </div></td>
            </tr>
            </table>
            <table border=0 width="100%" height=4 cellspacing=0 cellpadding=0 bgcolor="d0d0d0">
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <?php
                            // Flyer
                        }
                        // Affiche événement
                    }
                    // Boucle tant qu'il y a des Evénements
                }
                mysql_free_result($Result);
            }
            else
            {   // Pas d'Evénement
            ?>
            <td width="100%"><font ID="ActuFont"><font color="#ffffff"><b><i>Aucun</i></b></font></font></td>
            <?php
                // Pas d'Evénement
            }
            mysql_close($Link);
            ?>
            </tr>
            </table>
            <table border=0 height=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr bgcolor="#ff8000">
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffff00"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
    </tr>
    </table>
</td>
<td width=20>
    <table border=0 width=20 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 height=15 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<?php
    // Connecté
}
?>
<!-- ************************************************************************************************************** -->
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
    <td><font ID="Title">&nbsp;Raccourcis</font></td>
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
<!-- **************************************************************************************************** RACCOURCIS -->
<table border=0 height=10 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td width=20>
    <table border=0 width=20 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
<td width=8>
    <table border=0 width=8 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td><a href="index.php?Chp=5&Clf=<?php echo $Clf; ?>" target="_top">Le Forum de discussion</a></td>
</tr>
<?php
if(!Empty($Clf))
{   // Connecté
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><a href="index.php?Chp=6&Clf=<?php echo $Clf; ?>" target="_top">Ta Messagerie</a></td>
</tr>
<?php
    // Connecté
}
?>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><a href="index.php?Chp=7&Clf=<?php echo $Clf; ?>" target="_top">Tous les Albums photos</a></td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><a href="index.php?Chp=14&Clf=<?php echo $Clf; ?>" target="_top">Tous les Ev&eacute;nements</a></td>
</tr>
</table>
<table border=0 height=10 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<!-- ************************************************************************************************************** -->
<hr>
<font face="Verdana,Lucida,Courier" size=1><font color="#ff0000"><b>CAMARADE</b></font><b> [Kamarad]. n. &bull; 1&deg;</b> Personne qui a les
 m&ecirc;mes habitudes, les m&ecirc;mes occupations qu'une autre et des liens de familiarit&eacute; avec elle. <b>V. Coll&egrave;gue, compagnon,
 confr&egrave;re, copain, pote. &bull; 2&deg;</b> (<i>Trad. russe tovaritch</i>). Appellation, dans les partis socialistes, communistes.
</font>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
</body>
</html>
