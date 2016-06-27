<?php
require("Package.php");
$aMonth = array("January","February","March","April","May","June","July","August","September","October","November","December");
$Chp = "14";
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
        $Query = "SELECT CAM_Pseudo,CAM_LogDate FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            $aDate = getdate();
            if(!Empty($ope))
            {   if($ope == 1)
                {   // Je Viens //////////////////////////////////////////////////////////////////////////////////////////////////
                    $Query = "INSERT INTO Presents (PRE_EventID,PRE_Pseudo) VALUES ($evnt,'".addslashes($Camarade)."')";
                    mysql_query(trim($Query),$Link);
                }
                else
                {   // Je Viens Pas //////////////////////////////////////////////////////////////////////////////////////////////
                    $Query = "DELETE FROM Presents WHERE PRE_EventID = $evnt AND PRE_Pseudo = '".addslashes($Camarade)."'";
                    mysql_query(trim($Query),$Link);
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
<title>Le Classico: Selected Events</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Title {font-size: 16pt; font-family: Impact,Verdana,Lucida; color: black}
#Comment {font-size: 10pt; font-family: Verdana,Lucida,Courier; color: #8000ff}
</style>
<script type="text/javascript">
<!--
// OnVoirFlyer //////////////////////////////////////////////////////////////////////////////
function OnVoirFlyer(sFlyer,sSoire,iWidth,iHeight)
{   var iHigh=0;
    var WndFlyer;
    if((iWidth==0)||(iHeight==0))
    {   WndFlyer=window.open("Flyer.php?fly="+sFlyer+"&sr="+sSoire+"&yr=<?php
        if((!Empty($dy))&&(!Empty($mn))&&(!Empty($yr))) echo "$yr&mn=$mn&dy=$dy";
        else echo trim($aDate["year"])."&mn=".trim($aDate["mon"])."&dy=".trim($aDate["mday"]);
        ?>","WndFlyer","left=0,top=0,width=400,height=300,scrollbars=1,resizable=1");
        WndFlyer.focus();
    }
    else
    {   iHigh=iHeight+70;
        WndFlyer=window.open("Flyer.php?fly="+sFlyer+"&sr="+sSoire+"&yr=<?php
        if((!Empty($dy))&&(!Empty($mn))&&(!Empty($yr))) echo "$yr&mn=$mn&dy=$dy";
        else echo trim($aDate["year"])."&mn=".trim($aDate["mon"])."&dy=".trim($aDate["mday"]);
        ?>","WndFlyer","left=0,top=0,width="+iWidth+",height="+iHigh+",resizable=1");
        WndFlyer.focus();
    }
}
-->
</script>
</head>
<body bgcolor="#e4e4e4" style="margin-top: 0;margin-bottom: 0;margin-left: 0;margin-right: 0">
<!-- ******************************************************************************************************************************** SELECTED EVENTS -->
<?php
$bFutur = false;
$bPass = false;
$bPresent = false;
$BckColor = "#D8E1C6";
$CntView = 0;
$Query = "SELECT EVE_EventID,EVE_Pseudo,EVE_Nom,EVE_Lieu,EVE_Flyer,EVE_Remark FROM Evenements WHERE EVE_Date = '";
if((!Empty($dy))&&(!Empty($mn))&&(!Empty($yr)))
{   $Query .= "$yr-$mn-$dy'";
    if((strtotime("now") >= strtotime("$dy ".$aMonth[$mn-1]." $yr"))&&
       (($dy != $aDate["mday"])||($mn != $aDate["mon"])||($yr != $aDate["year"])))
    {   $bFutur = true;
    }
}
else $Query .= trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
$Query .= " ORDER BY EVE_Nom";
$Result = mysql_query(trim($Query),$Link);
while($aRow = mysql_fetch_array($Result))
{   if(!strcmp($BckColor,"#D8E1C6")) $BckColor = "#BACC9A";
    else $BckColor = "#D8E1C6";
    // Boucle tant qu'il y a des événements
    if(!$Pass)
    {   // Lag
?>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ffffff">
<tr>
<td width="100%">
    <table border=0 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<?php
        // Lag
        $Pass = true;
    }
?>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
<tr>
<td width=5 bgcolor="#ffffff">
    <table border=0 width=5 height=5 cellspacing=0 cellpadding=0>
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
<td width=5 bgcolor="#ffffff">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width="100%" valign="top">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td><div style="position: float; width: 100%; height: 20px; overflow: hidden; background-color: #000000" align="left">
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
        <td><div style="position: float; width: 100%; height: 20px; overflow: hidden; background-color: <?php
        if(!strcmp($BckColor,"#D8E1C6")) echo "#BACC9A";
        else echo "#D8E1C6";
        ?>" align="left">
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
        <td><div style="position: float; width: 100%; height: 20px; overflow: hidden; background-color: <?php
        if(!strcmp($BckColor,"#D8E1C6")) echo "#BACC9A";
        else echo "#D8E1C6";
        ?>" align="left">
             <table border=0 cellspacing=0 cellpadding=0>
             <tr>
             <td nowrap><font ID="Title"><font style="font-size: 12pt">&nbsp;Lieu:&nbsp;&nbsp;<font color="#8000ff"><?php echo str_replace($aSearch,$aReplace,trim($aRow["EVE_Lieu"])); ?></font></font></font></td>
             </tr>
             </table>
        </div></td>
        </tr>
        </table>
        <table border=0 width="100%" height=19 cellspacing=0 cellpadding=0>
        <tr valign="bottom">
        <td><div style="position: float; width: 100%; height: 57px; overflow: hidden; background-color: <?php
             if(!strcmp($BckColor,"#D8E1C6")) echo "#BACC9A";
             else echo "#D8E1C6";
             ?>" align="left">
             <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php
             if(!strcmp($BckColor,"#D8E1C6")) echo "#BACC9A";
             else echo "#D8E1C6";
             ?>">
             <tr>
             <td width=3>
                 <table border=0 width=3 cellspacing=0 cellpadding=0>
                 <tr>
                 <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                 </tr>
                 </table>
             </td>
             <td width=70>
                 <table border=0 width=70 height=52 cellspacing=0 cellpadding=0>
                 <tr valign="top">
                 <td><font ID="Title"><font style="font-size: 12pt">Remarque:</font></font></td>
                 </tr>
                 <tr valign="bottom">
                 <td><input type="button" style="font-family: Verdana;font-size: 8pt" onclick="javascript:OnVoirFlyer(<?php
                 if(!Empty($aRow["EVE_Flyer"]))
                 {   echo "'".urlencode(base64_encode($aRow["EVE_Flyer"]))."','".urlencode(base64_encode($aRow["EVE_Nom"]))."'";
                     // Récupère la taille de l'image
                     $aSize = @getimagesize(GetSrvFlyFolder().trim($aRow["EVE_Flyer"]));
                     if((!Empty($aSize[0]))&&(!Empty($aSize[1]))) echo ",".$aSize[0].",".$aSize[1];
                     else echo ",0,0";
                 }
                 else echo "'','',0,0";
                 ?>)" value="Voir Flyer"<?php
                 if(Empty($aRow["EVE_Flyer"])) echo " disabled";
                 ?>></td>
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
             <td width="100%" valign="top"><div style="position: float; width: 100%; height: 52px; overflow: auto; background-color: <?php echo $BckColor; ?>" align="left"><font ID="Comment"><?php echo PrintString(str_replace($aSearch,$aReplace,trim($aRow["EVE_Remark"]))); ?></font></div></td>
             <td width=5>
                 <table border=0 width=5 cellspacing=0 cellpadding=0>
                 <tr>
                 <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                 </tr>
                 </table>
             </td>
             </tr>
             </table>
        </div></td>
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
    <td width=200>
        <table border=0 width=200 cellspacing=0 cellpadding=0>
        <tr>
        <?php
        $bPresent = false;
        $Query = "SELECT PRE_Pseudo FROM Presents WHERE PRE_EventID = ".trim($aRow["EVE_EventID"]);
        $ResPrt = mysql_query(trim($Query),$Link);
        ?>
        <td><font ID="Title"><font style="font-size: 12pt">Camarade(s) pr&eacute;sent(s):&nbsp;&nbsp;<font color="#ff0000"><?php echo mysql_num_rows($ResPrt); ?></font></font></font></td>
        </tr>
        <?php
        if(Empty($Clf))
        {   // Non Connecté
            ?><tr>
            <td width="100%">
                <table border=0 width="100%" height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr><?php
            // Non Connecté
        }
        ?>
        <tr>
        <td>
        <select style="width: 100%; font-size: 7pt; font-family: Verdana,Lucida,Courier; color: black; height: 60px" size=<?php
        if(!Empty($Clf)) echo "5";
        else echo "7";
        ?>>
        <?php
        while($aPseudo = mysql_fetch_array($ResPrt))
        {   if((!Empty($Clf))&&(!strcmp($aPseudo["PRE_Pseudo"],$Camarade))) $bPresent = true;
            if(strlen(trim($aPseudo["PRE_Pseudo"])) <= 20)
            {   // Pseudo complet
                ?><option><?php echo trim($aPseudo["PRE_Pseudo"]); ?></option><?php
            }
            else
            {   // Pseudo partiel
                ?><option><?php echo substr(trim($aPseudo["PRE_Pseudo"]),0,20)."..."; ?></option><?php
            }
        }
        mysql_free_result($ResPrt);
        ?>
        </select>
        </td>
        </tr>
        <?php
        if(!Empty($Clf))
        {   // Connecté
            ?>
            <tr>
            <td width="100%">
                <table border=0 width="100%" height=4 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td width="100%">
                <table border=0 width="100%" height=2 cellspacing=0 cellpadding=0 bgcolor="<?php
                if(!strcmp($BckColor,"#D8E1C6")) echo "#BACC9A";
                else echo "#D8E1C6";
                ?>">
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td width="100%">
                <table border=0 width="100%" height=3 cellspacing=0 cellpadding=0>
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
                <td width=65>
                    <table border=0 width=65 cellspacing=0 cellpadding=0>
                    <tr>
                    <td align="left">
                    <form action="EventSel.php?yr=<?php
                    if((!Empty($dy))&&(!Empty($mn))&&(!Empty($yr))) echo "$yr&mn=$mn&dy=$dy";
                    else echo trim($aDate["year"])."&mn=".trim($aDate["mon"])."&dy=".trim($aDate["mday"]);
                    ?>&Clf=<?php echo $Clf; ?>" method="post">
                    <input type="hidden" name="ope" value=1>
                    <input type="hidden" name="evnt" value=<?php echo $aRow["EVE_EventID"]; ?>>
                    <input type="submit" style="font-family: Verdana;font-size: 8pt" value=<?php
                    if($bFutur) echo "\"J'y &eacute;tait\"";
                    else echo "\"Je Viens\"";
                    if(!Empty($bPresent)) echo " disabled";
                    ?>>
                    </form>
                    </td>
                    </tr>
                    </table>
                </td>
                <td width="100%" align="left">
                <form action="EventSel.php?yr=<?php
                if((!Empty($dy))&&(!Empty($mn))&&(!Empty($yr))) echo "$yr&mn=$mn&dy=$dy";
                else echo trim($aDate["year"])."&mn=".trim($aDate["mon"])."&dy=".trim($aDate["mday"]);
                ?>&Clf=<?php echo $Clf; ?>" method="post">
                <input type="hidden" name="ope" value=2>
                <input type="hidden" name="evnt" value=<?php echo $aRow["EVE_EventID"]; ?>>
                <input type="submit" style="font-family: Verdana;font-size: 8pt" value=<?php
                if($bFutur) echo "\"J'y &eacute;tait Pas\"";
                else echo "\"Je Viens Pas\"";
                if(Empty($bPresent)) echo " disabled";
                ?>>
                </form>
                </td>
                </tr>
                </table>
            </td>
            </tr>
            <?php
            // Connecté
        }
        else
        {   // Non Connecté
            ?>
            <tr>
            <td width="100%">
                <table border=0 width="100%" height=3 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <?php
            // Non Connecté
        }
        ?>
        </table>
    </td>
    </tr>
    </table>
</td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td width=5 bgcolor="#ffffff">
    <table border=0 width=5 height=5 cellspacing=0 cellpadding=0>
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
<td width=5 bgcolor="#ffffff">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ffffff">
<tr>
<td width="100%">
    <table border=0 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<?php
    // Boucle tant qu'il y a des événements
}
if(!mysql_num_rows($Result))
{   // Pas d'événement
?>
<font ID="Title"><font style="font-size: 12pt">Pas d'&eacute;v&eacute;nement ce jour l&agrave;...</font></font>
<?php
    // Pas d'événement
}
mysql_free_result($Result);
mysql_close($Link);
?>
<!-- ************************************************************************************************************************************************ -->
</body>
</html>
