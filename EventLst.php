<?php
require("Package.php");
$Chp = "14";
$Clf = $_GET['Clf'];
$trcfg = $_POST['trcfg'];
$vwu = $_POST['vwu'];
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
        $Query = "SELECT CAM_Pseudo,CAM_LogDate FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
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
<title>Le Classico: Event List</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; margin-top: 0px; border: 0px}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
</style>
<script type="text/javascript">
<!--
// AfficherEvent //////////////////////////////////////////////////////////////////////////////////
function AfficherEvent(iViewYear,iViewMnth,iViewDay,sKey)
{   top.EvntCal.location.href="EventCal.php?yr="+iViewYear+"&mn="+iViewMnth+"&dy="+iViewDay+"&Clf="+sKey;
    top.EvntTitle.location.href="EventTit.php?yr="+iViewYear+"&mn="+iViewMnth+"&dy="+iViewDay;
    top.EvntSelect.location.href="EventSel.php?yr="+iViewYear+"&mn="+iViewMnth+"&dy="+iViewDay+"&Clf="+sKey;
}
-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px;margin-right: 10px">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ************************************************************************************************************************************ EVENTS LIST -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/PuceLC.gif"></td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td>
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/TitConHG.jpg"></td>
    </tr>
    <tr>
    <td bgcolor="#ff0000">
        <table border=0 height=18 cellspacing=0 cellpadding=0>
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
<td width="100%" bgcolor="#ff0000"><font ID="BigTitle">&nbsp;<b>Les Ev&eacute;nements</b></font></td>
<td>
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/TitConHD.jpg"></td>
    </tr>
    <tr>
    <td bgcolor="#ff0000">
        <table border=0 height=18 cellspacing=0 cellpadding=0>
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
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table><br>
<font face="Verdana,Lucida,Courier" size=2>Retrouves tous les &eacute;v&eacute;nements du <b>Classico</b> et plus, via le calendrier ci-dessus ou a
 liste ci-dessous, tri&eacute;e selon ton choix. Et si tu souhaites pr&eacute;senter un &eacute;v&eacute;nement particulier, vas donc dans le menu
 <?php
 if(!Empty($Clf))
 {   // Connecté
     ?><a href="<?php echo GetFolder(); ?>/index.php?Chp=15&Clf=<?php echo $Clf; ?>" style="font-size: 10pt" target="_top">Ajouter/Supprimer 1 Ev&eacute;nement</a><?php
     // Connecté
 }
 else
 {   // Non Connecté
     ?><font style="font-size: 10pt; font-family: Impact,Verdana,Lucida; color: blue"><u>Ajouter/Supprimer 1 Ev&eacute;nement</u></font><?php
     // Non Connecté
 }
 ?>
 ...</font><br><br>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=300 valign="top">
    <table border=0 width=300 cellspacing=0 cellpadding=0>
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
        <td nowrap><font ID="Title">&nbsp;Choix du Tri</font></td>
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
    <table border=0 height=9 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <font style="font-family: Verdana,Lucida,Courier; font-size: 10pt"><b>Tri actuel:</b>&nbsp;<font color="#8080ff"><i><?php
    if(!Empty($trcfg))
    {   switch($trcfg)
        {   case 1:
            {   echo "Nom de l'&eacute;v&eacute;nement";
                break;
            }
            case 2:
            {   echo "Camarades pr&eacute;sents";
                break;
            }
        }
    }
    else echo "Date d&eacute;croissante";
    ?></i></font></font>
    <table border=0 height=7 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <form action="EventLst.php?Clf=<?php echo $Clf; ?>" method="post">
    <table border=0 width=255 cellspacing=0 cellpadding=0>
    <tr>
    <td nowrap><input type="radio" name="trcfg" value=0<?php
    if(Empty($trcfg)) echo " checked";
    ?>><font style="font-family: Verdana,Lucida,Courier; font-size: 10pt">&nbsp;Trier sur la <b>date</b> des &eacute;v&eacute;nements</font></td>
    </tr>
    <tr>
    <td nowrap><input type="radio" name="trcfg" value=1<?php
    if((!Empty($trcfg))&&($trcfg == 1)) echo " checked";
    ?>><font style="font-family: Verdana,Lucida,Courier; font-size: 10pt">&nbsp;Trier sur le <b>nom</b> des &eacute;v&eacute;nements</font></td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td>
            <table border=0 height=3 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td width=25 valign="top">
            <table border=0 width=25 cellspacing=0 cellpadding=0>
            <tr>
            <td valign="top"><input type="radio" name="trcfg" value=2<?php
            if((!Empty($trcfg))&&($trcfg == 2)) echo " checked";
            ?>></td>
            </tr>
            </table>
        </td>
        <td width="100%"><font style="font-family: Verdana,Lucida,Courier; font-size: 10pt">Trier sur le nombre d&eacute;croissant de<br><b>camarades pr&eacute;sents</b></font></td>
        </tr>
        </table>
    </td>
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
    <table border=0 height=9 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <table border=0 width=300 cellspacing=0 cellpadding=0>
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
        <td nowrap><font ID="Title">&nbsp;Prochain Ev&eacute;nement</font></td>
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
    <table border=0 height=9 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <!-- **************************************************************************************************** PROCHAIN EVENEMENT -->
    <?php
    $aDate = getdate();
    $sNextDate = "";
    $bContinue = true;
    $bPass = false;
    $Query = "SELECT EVE_Date,EVE_Nom,COUNT(PRE_Pseudo) AS EVE_CamCnt FROM Evenements LEFT JOIN Presents ON EVE_EventID = PRE_EventID AND PRE_Status <> 2";
    $Query .= " WHERE EVE_Status <> 2 AND EVE_Date >= '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."' GROUP BY EVE_Date,EVE_Nom ORDER BY EVE_Date";
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
    <table border=0 width=300 cellspacing=0 cellpadding=0>
    <tr>
    <td width=5>
        <table border=0 width=5 height=33 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
    <td valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/DosEvent.jpg"></td>
    <td valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr bgcolor="#bacc9a">
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
    </tr>
    </table>
    <table border=0 width=300 cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
    <tr>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=100 valign="top">
        <table border=0 width=100 cellspacing=0 cellpadding=0>
        <tr>
        <td><font ID="Title" style="font-size: 10pt">Le&nbsp;<font color="#ffff00"><?php
        echo substr($aRow["EVE_Date"],8,10)."/".substr($aRow["EVE_Date"],5,2)."/".substr($aRow["EVE_Date"],0,4);
        ?></font></font></td>
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
        <tr bgcolor="#d8e1c6">
        <td>
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
        <td><input type="button" style="font-family: Verdana;font-size: 8pt" onclick="javascript:AfficherEvent(<?php
        echo substr($aRow["EVE_Date"],0,4).",";
        $aMnDy = sscanf(substr($aRow["EVE_Date"],5,2),"%d");
        $iTmp = $aMnDy[0];
        echo "$iTmp,";
        $aMnDy = sscanf(substr($aRow["EVE_Date"],8,10),"%d");
        $iTmp = $aMnDy[0];
        echo "$iTmp,";
        echo "'$Clf'";
        ?>)" value="Afficher"></td>
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
    <td width="100%" valign="top" bgcolor="#ffffff">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonInHG.jpg"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonInHD.jpg"></td>
        </tr>
        <?php
                // Lag
                $bPass = true;
            }
            if($bContinue)
            {   // Affiche événement
        ?>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width="100%">
            <table border=0 width="100%" height=40 cellspacing=0 cellpadding=0>
            <tr>
            <td valign="top"><div style="position: float; width: 100%; height: 20px; overflow: hidden; background-color: #000000">
                <table border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr>
                <td nowrap><font ID="Title" color="#ffff00">Ev.&nbsp;:&nbsp;<font color="8080ff"><?php echo str_replace($aSearch,$aReplace,trim($aRow["EVE_Nom"])); ?></font></font></td>
                </tr>
                </table>
            </div></td>
            </tr>
            <tr>
            <td valign="top"><div style="position: float; width: 100%; height: 20px; overflow: hidden; background-color: #c0c0c0">
                <table border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr>
                <td nowrap><font ID="Title">Nbr. De Camarade(s)&nbsp;:&nbsp;<font color="ff0000"><?php echo $aRow["EVE_CamCnt"]; ?></font></font></td>
                </tr>
                </table>
            </div></td>
            </tr>
            </table>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
                // Affiche événement
            }
            // Boucle tant qu'il y a des Evénements
        }
        ?>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonInBG.jpg"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonInBD.jpg"></td>
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
    <table border=0 width=300 cellspacing=0 cellpadding=0>
    <tr bgcolor="#bacc9a">
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
    </tr>
    <tr>
    <td width=5>
        <table border=0 width=5 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
    <td width=41 bgcolor="#ff8000">
        <table border=0 width=41 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5 bgcolor="#ff8000">
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
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <?php
        // Evénement trouvé
    }
    else
    {   // Pas d'événement
    ?>
    <table border=0 height=15 cellspacing=0 cellpadding=0>
    <tr>
    <td valign="bottom"><font style="font-family: Verdana,Lucida,Courier; font-size: 10pt; color: #8080ff"><i>Aucun...</i></font></td>
    </tr>
    </table>
    <?php
        // Pas d'événement
    }
    mysql_free_result($Result);
    ?>
    <!-- *********************************************************************************************************************** -->
</td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%" valign="top">
    <!-- **************************************************************************************************** LISTE EVENEMENTS -->
    <?php
    $iTmp = 0;
    $iResCnt = 0;
    $Query = "SELECT EVE_Date,EVE_Nom,COUNT(PRE_Pseudo) AS EVE_CamCnt FROM Evenements LEFT JOIN Presents ON EVE_EventID = PRE_EventID AND PRE_Status <> 2";
    $Query .= " WHERE EVE_Status <> 2 GROUP BY EVE_Date,EVE_Nom";
    if(!Empty($trcfg))
    {   switch($trcfg)
        {   case 1:
            {   $Query .= " ORDER BY EVE_Nom";
                break;
            }
            case 2:
            {   $Query .= " ORDER BY EVE_CamCnt DESC";
                break;
            }
        }
    }
    else $Query .= " ORDER BY EVE_Date DESC";
    $Result = mysql_query(trim($Query),$Link);
    $iResCnt = mysql_num_rows($Result);
    if($iResCnt != 0)
    {   // Evénement trouvé
    ?>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width=5>
        <table border=0 width=5 height=33 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
    <td valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/DosEvent.jpg"></td>
    <td valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    <td width="100%">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=15>
            <table border=0 width=15 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width="100%" valign="middle"><font style="font-family: Verdana,Lucida,Courier; font-size: 10pt"><b>Affichage:</b>&nbsp;de&nbsp;<?php
        $iResStart = 1;
        $iResEnd = $iResCnt;
        if($iResCnt > 5)
        {   if(!Empty($vwu))
            {   $iResStart = ($vwu * 5) + 1;
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
    </td>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr bgcolor="#bacc9a">
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
    </tr>
    </table>
    <?php
        // Evénement trouvé
        $sNextDate = "";
        $CntView = 1;
        $BckColor = "#D8E1C6";
        $bNextEve = true;
        $bContinue = true;
        $aRow = mysql_fetch_array($Result);
        while($bContinue)
        {   // Boucle Principale
            if(!strcmp($BckColor,"#D8E1C6")) $BckColor = "#BACC9A";
            else $BckColor = "#D8E1C6";
            while(($CntView < $iResStart)&&($bContinue))
            {   if(!($aRow = mysql_fetch_array($Result))) $bContinue = false;
                $CntView++;
            }
    ?>
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
    <tr>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=100 valign="top">
        <table border=0 width=100 cellspacing=0 cellpadding=0>
        <tr>
        <td><font ID="Title" style="font-size: 10pt">Le&nbsp;<font color=<?php
        if(!strcmp($BckColor,"#D8E1C6")) echo "\"#ff8000\">";
        else echo "\"#ffff00\">";
        echo substr($aRow["EVE_Date"],8,10)."/".substr($aRow["EVE_Date"],5,2)."/".substr($aRow["EVE_Date"],0,4);
        ?></font></font></td>
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
        <tr bgcolor="<?php
        if(!strcmp($BckColor,"#D8E1C6")) echo "#BACC9A";
        else echo "#D8E1C6";
        ?>">
        <td>
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
        <td><input type="button" style="font-family: Verdana;font-size: 8pt" onclick="javascript:AfficherEvent(<?php
        echo substr($aRow["EVE_Date"],0,4).",";
        $aMnDy = sscanf(substr($aRow["EVE_Date"],5,2),"%d");
        $iTmp = $aMnDy[0];
        echo "$iTmp,";
        $aMnDy = sscanf(substr($aRow["EVE_Date"],8,10),"%d");
        $iTmp = $aMnDy[0];
        echo "$iTmp,";
        echo "'$Clf'";
        ?>)" value="Afficher"></td>
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
    <td width="100%" valign="top" bgcolor="#ffffff">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(!strcmp($BckColor,"#D8E1C6")) echo "ClaInHG.jpg";
        else echo "FonInHG.jpg";
        ?>"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(!strcmp($BckColor,"#D8E1C6")) echo "ClaInHD.jpg";
        else echo "FonInHD.jpg";
        ?>"></td>
        </tr>
        <?php
                // Affiche les événements de ce jour
                $bNextEve = true;
                while(($bNextEve)&&($bContinue))
                {   // Boucle la même date
                    if(!strcmp($sNextDate,"")) $sNextDate = $aRow["EVE_Date"];
                    if(strcmp($sNextDate,$aRow["EVE_Date"]))
                    {   $bNextEve = false;
                        $sNextDate = $aRow["EVE_Date"];
                    }
                    else
                    {   // Tant qu'il y a des événements
        ?>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width="100%">
            <table border=0 width="100%" height=40 cellspacing=0 cellpadding=0>
            <tr>
            <td valign="top"><div style="position: float; width: 100%; height: 20px; overflow: hidden; background-color: #000000">
                <table border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr>
                <td nowrap><font ID="Title" color="<?php
                if(!strcmp($BckColor,"#D8E1C6")) echo "#ff8000";
                else echo "#ffff00";
                ?>">Ev.&nbsp;:&nbsp;<font color="8080ff"><?php echo str_replace($aSearch,$aReplace,trim($aRow["EVE_Nom"])); ?></font></font></td>
                </tr>
                </table>
            </div></td>
            </tr>
            <tr>
            <td valign="top"><div style="position: float; width: 100%; height: 20px; overflow: hidden; background-color: #c0c0c0">
                <table border=0 cellspacing=0 cellpadding=0>
                <tr>
                <td nowrap><font ID="Title">Camarade(s)&nbsp;Pr&eacute;sent(s)&nbsp;:&nbsp;<font color="ff0000"><?php echo $aRow["EVE_CamCnt"]; ?></font></font></td>
                </tr>
                </table>
            </div></td>
            </tr>
            </table>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
                        // Tant qu'il y a des événements
                        if(!($aRow = mysql_fetch_array($Result))) $bContinue = false;
                        if((($CntView+1) < $iResStart)||(($CntView+1) > $iResEnd)) $bContinue = false;
                        else $CntView++;
                    }
                    // Boucle la même date
                }
                // Affiche événement
        ?>
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
    <?php
            // Boucle Principale
            if($bContinue)
            {   // Evénement suivant
    ?>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr bgcolor="<?php echo $BckColor; ?>">
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaBG.jpg";
    else echo "SubFonBG.jpg";
    ?>"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaBD.jpg";
    else echo "SubFonBD.jpg";
    ?>"></td>
    </tr>
    <tr>
    <td width=5>
        <table border=0 width=5 height=8 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
    <td width=41 bgcolor="#ff8000">
        <table border=0 width=41 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5 bgcolor="#ff8000">
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
    </tr>
    <tr bgcolor="<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "#BACC9A";
    else echo "#D8E1C6";
    ?>">
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubFonHG.jpg";
    else echo "SubClaHG.jpg";
    ?>"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubFonHD.jpg";
    else echo "SubClaHD.jpg";
    ?>"></td>
    </tr>
    </table>
    <?php
                // Evénement suivant
            }
            // Boucle Principale
        }
    ?>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr bgcolor="<?php echo $BckColor; ?>">
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaBG.jpg";
    else echo "SubFonBG.jpg";
    ?>"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/<?php
    if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaBD.jpg";
    else echo "SubFonBD.jpg";
    ?>"></td>
    </tr>
    <tr>
    <td width=5>
        <table border=0 width=5 height=<?php
        if($iResCnt > 5) echo "33";
        else echo "5";
        ?> cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
    <td width=41 bgcolor="#ff8000">
        <table border=0 width=41 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5 bgcolor="#ff8000">
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=15>
            <table border=0 width=15 height=<?php
            if($iResCnt > 5) echo "33";
            else echo "5";
            ?> cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width="100%" valign="bottom"><?php
        if($iResCnt > 5)
        {   // Gestion de l'affichage
        ?>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=52><?php
        if((!Empty($iResStart))&&($iResStart != 1))
        {   // Précédent
        ?>
        <form action="EventLst.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" name="trcfg" value=<?php
        if(!Empty($trcfg)) echo $trcfg;
        else echo "0";
        ?>>
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
        <form action="EventLst.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" name="trcfg" value=<?php
        if(!Empty($trcfg)) echo $trcfg;
        else echo "0";
        ?>>
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
        <td width="100%" align="center" nowrap><?php
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
                        ?><td><font ID="Page">...</font></td><?php
                        // Suspension
                        $bPass = true;
                    }
                    // Affiche le lien
                    if($bPass)
                    {   // Lag
                        ?><td>
                              <table border=0 width=5 cellspacing=0 cellpadding=0>
                              <tr>
                              <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                              </tr>
                              </table>
                        </td><?php
                        // Lag
                    }
                    else $bPass = true;
                    if($iLien != $iCurPage)
                    {   // Autre page
                        ?><td><a href="EventLst.php?trcfg=<?php
                        if(!Empty($trcfg)) echo $trcfg;
                        else echo "0";
                        ?>&vwu=<?php echo ($iLien - 1); ?>&Clf=<?php echo $Clf; ?>"><?php echo $iLien; ?></a></td><?php
                        // Autre page
                    }
                    else
                    {   // Page courante
                        ?><td><font ID="Page"><?php echo $iLien; ?></font></td><?php
                        // Page courante
                    }
                    if(($iAffCnt == 9)&&($iCntPage > 10)&&(($iCurPage + 4) < $iCntPage))
                    {   // Suspension
                        ?><td>
                              <table border=0 width=5 cellspacing=0 cellpadding=0>
                              <tr>
                              <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                              </tr>
                              </table>
                        </td>
                        <td><font ID="Page">...</font></td><?php
                        // Suspension
                    }
                    // Affiche le lien
                    $iAffCnt++;
                }
                $iLien++;
            }
            ?></tr>
            </table><?php
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
            ?><form action="EventLst.php?Clf=<?php echo $Clf; ?>" method="post">
            <input type="hidden" name="trcfg" value=<?php
            if(!Empty($trcfg)) echo $trcfg;
            else echo "0";
            ?>>
            <input type="hidden" name="vwu" value=<?php echo ceil($iResCnt / 5) - 1; ?>>
            <input type="image" src="<?php echo GetFolder(); ?>/Images/EndRes.jpg">
            </form><?php
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
            ?><form action="EventLst.php?Clf=<?php echo $Clf; ?>" method="post">
            <input type="hidden" name="trcfg" value=<?php
            if(!Empty($trcfg)) echo $trcfg;
            else echo "0";
            ?>>
            <input type="hidden" name="vwu" value=<?php
            if(!Empty($vwu)) echo ($vwu+1);
            else echo "1";
            ?>>
            <input type="image" src="<?php echo GetFolder(); ?>/Images/Next.jpg">
            </form><?php
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
        else
        {   // Pas de gestion
            ?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
            // Pas de gestion
        }
        ?></td>
        <td width=2>
            <table border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
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
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <?php
        // Evénement trouvé
    }
    else
    {   // Pas d'événement
        // Pas d'événement
    }
    mysql_free_result($Result);
    mysql_close($Link);
    ?>
    <!-- *********************************************************************************************************************** -->
</td>
</tr>
</table>
<!-- ************************************************************************************************************************************************ -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
</body>
</html>
