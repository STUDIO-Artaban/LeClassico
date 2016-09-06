<?php
require("Package.php");
$Chp = "6";
$Clf = $_GET['Clf'];
$ope = $_POST['ope'];
$msgdt = $_POST['msgdt'];
$msgtm = $_POST['msgtm'];
$CntNew = 0;
$CntRead = 0;
$CntWrite = 0;
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
        $Query = "SELECT 'X' FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   mysql_free_result($Result);
            if(!Empty($ope))
            {   if((!Empty($msgdt))&&(!Empty($msgtm))&&(strcmp(trim($msgdt),""))&&(strcmp(trim($msgtm),"")))
                {   $Query = "SELECT MSG_ReadStk, MSG_WriteStk FROM Messagerie";
                    if($ope == 1)
                    {   // Retire un message reçu //////////////////////////////////////////////////////////////////////////////////////
                        $Query .= " WHERE UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."')";
                        $Query .= " AND MSG_Date = '".trim($msgdt)."' AND MSG_Time = '".trim($msgtm)."'";
                        $Result = mysql_query(trim($Query),$Link);
                        $aRow = mysql_fetch_array($Result);
                        if(!Empty($aRow["MSG_WriteStk"]))
                        {   // Mise à jour du Flag de lecture
                            $Query = "UPDATE Messagerie SET MSG_ReadStk = 0";
                        }
                        else
                        {   // Supprime le message
                            $Query = "UPDATE Messagerie SET MSG_Status = 2, MSG_StatusDate = CURRENT_TIMESTAMP";
                        }
                        $Query .= " WHERE UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."')";
                        $Query .= " AND MSG_Date = '".trim($msgdt)."' AND MSG_Time = '".trim($msgtm)."'";
                        mysql_free_result($Result);
                        if(!mysql_query(trim($Query),$Link))
                        {   mysql_close($Link);
                            $Msg = "La suppression du message re&ccedil;u a &eacute;chou&eacute;e! Contact le <font color=\"#808080\">Webmaster</font>!";
                            include("Message.php");
                            die();
                        }
                    }
                    else
                    {   // Retire un message envoyé ///////////////////////////////////////////////////////////////////////////////////
                        $Query .= " WHERE UPPER(MSG_From) = UPPER('".addslashes($Camarade)."')";
                        $Query .= " AND MSG_Date = '".trim($msgdt)."' AND MSG_Time = '".trim($msgtm)."'";
                        $Result = mysql_query(trim($Query),$Link);
                        $aRow = mysql_fetch_array($Result);
                        if(!Empty($aRow["MSG_ReadStk"]))
                        {   // Mise à jour du Flag d'écriture
                            $Query = "UPDATE Messagerie SET MSG_WriteStk = 0";
                        }
                        else
                        {   // Supprime le message
                            $Query = "UPDATE Messagerie SET MSG_Status = 2, MSG_StatusDate = CURRENT_TIMESTAMP";
                        }
                        $Query .= " WHERE UPPER(MSG_From) = UPPER('".addslashes($Camarade)."')";
                        $Query .= " AND MSG_Date = '".trim($msgdt)."' AND MSG_Time = '".trim($msgtm)."'";
                        mysql_free_result($Result);
                        if(!mysql_query(trim($Query),$Link))
                        {   mysql_close($Link);
                            $Msg = "La suppression du message envoy&eacute; a &eacute;chou&eacute;e! Contact le <font color=\"#808080\">Webmaster</font>!";
                            include("Message.php");
                            die();
                        }
                    }
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
<title>Le Classico: Main Mail</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
p {padding: 0px; margin-bottom: 0px; margin-top: 0px; border: 0px}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Btn {font-size: 8pt}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: black}
#Content {font-size: 8pt; font-family: Verdana,Lucida,Courier; color: black}
</style>
<script type="text/javascript">
<!--
// AfficheNewMsg /////////////////////////////////////////////////////////////////////////
function AfficheNewMsg()
{   top.MailPad.location.href="MailPad.php?Clf=<?php echo $Clf; ?>";
    top.MailContent.location.href="Mail.php?Clf=<?php echo $Clf; ?>";
    top.MailPadTop.location.href="MailPadTop.php";
}
// ChgMsgRecus ///////////////////////////////////////////////////////////////////////////
function ChgMsgRecus()
{   switch(document.getElementById("RcMsgList").selectedIndex)
    {   <?php
        $Query = "SELECT MSG_From,MSG_Date,MSG_Time,MSG_LuFlag,MSG_Objet FROM Messagerie";
        $Query .= " WHERE UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."') AND MSG_ReadStk = 1";
        $Query .= " ORDER BY MSG_Date DESC, MSG_Time DESC";
        $Result = mysql_query(trim($Query),$Link);
        while($aRow = mysql_fetch_array($Result))
        {   // Tant qu'il y a des messages reçus
        ?>case <?php echo $CntRead; ?>:
        {   document.getElementById("RcMsgObj").innerHTML="<b>&bull;Objet:</b>&nbsp;<?php
            if(Empty($aRow["MSG_LuFlag"])) echo "<font color=\\\"#8000ff\\\">";
            if(!Empty($aRow["MSG_Objet"])) echo str_replace("\'","'",addslashes(str_replace($aSearch,$aReplace,trim($aRow["MSG_Objet"]))));
            else echo "[No Objet]";
            if(Empty($aRow["MSG_LuFlag"])) echo "</font>";
            ?>";
            document.getElementById("RcMsgFrom").innerHTML="<b>&bull;De:</b>&nbsp;<?php
            if(Empty($aRow["MSG_LuFlag"])) echo "<font color=\\\"#8000ff\\\">";
            echo str_replace("\'","'",addslashes($aRow["MSG_From"]));
            if(Empty($aRow["MSG_LuFlag"])) echo "</font>";
            ?>";
            document.getElementById("RcMsgDate").innerHTML="<b>&bull;Le:</b>&nbsp;<?php
            if(Empty($aRow["MSG_LuFlag"])) echo "<font color=\\\"#8000ff\\\">";
            echo substr(addslashes($aRow["MSG_Date"]),-2)."/".substr(addslashes($aRow["MSG_Date"]),5,-3)."/".substr(addslashes($aRow["MSG_Date"]),2,2);
            echo "-".addslashes($aRow["MSG_Time"]);
            if(Empty($aRow["MSG_LuFlag"])) echo "</font>";
            ?>";
            document.getElementById("RcDateSel").value="<?php echo $aRow["MSG_Date"]; ?>";
            document.getElementById("RcTimeSel").value="<?php echo $aRow["MSG_Time"]; ?>";
            document.getElementById("RcBtnSupp").disabled="";
            document.getElementById("RcBtnAff").disabled="";
            break;
        }
        <?php
            $CntRead++;
            if(Empty($aRow["MSG_LuFlag"])) $CntNew++;
            if(!Empty($aRow["MSG_Objet"]))
            {   if(strlen(trim($aRow["MSG_Objet"])) <= 7) $aRcObj[] = "$CntRead-".str_replace($aSearch,$aReplace,trim($aRow["MSG_Objet"]));
                else $aRcObj[] = "$CntRead-".str_replace($aSearch,$aReplace,substr(trim($aRow["MSG_Objet"]),0,7))."...";
            }
            else $aRcObj[] = "$CntRead-[No Objet]";
            // Tant qu'il y a des messages reçus
        }
        mysql_free_result($Result);
        ?>default:
        {   document.getElementById("RcMsgObj").innerHTML="<b>&bull;Objet:</b> ...";
            document.getElementById("RcMsgFrom").innerHTML="<b>&bull;De:</b> ...";
            document.getElementById("RcMsgDate").innerHTML="<b>&bull;Le:</b> ...";
            document.getElementById("RcDateSel").value="XXXX-XX-XX";
            document.getElementById("RcTimeSel").value="XX:XX:XX";
            document.getElementById("RcBtnSupp").disabled="disabled";
            document.getElementById("RcBtnAff").disabled="disabled";
            break;
        }
    }
}
// ChgMsgEnvoyes /////////////////////////////////////////////////////////////////////////
function ChgMsgEnvoyes()
{   switch(document.getElementById("SdMsgList").selectedIndex)
    {   <?php
        $Query = "SELECT MSG_Pseudo,MSG_Date,MSG_Time,MSG_LuFlag,MSG_Objet FROM Messagerie";
        $Query .= " WHERE UPPER(MSG_From) = UPPER('".addslashes($Camarade)."') AND MSG_WriteStk = 1";
        $Query .= " ORDER BY MSG_Date DESC, MSG_Time DESC";
        $Result = mysql_query(trim($Query),$Link);
        while($aRow = mysql_fetch_array($Result))
        {   // Tant qu'il y a des messages envoyés
        ?>case <?php echo $CntWrite; ?>:
        {   document.getElementById("SdMsgObj").innerHTML="<b>&bull;Objet:</b>&nbsp;<?php
            if(!Empty($aRow["MSG_Objet"])) echo str_replace("\'","'",addslashes(str_replace($aSearch,$aReplace,trim($aRow["MSG_Objet"]))));
            else echo "[No Objet]";
            ?>";
            document.getElementById("SdMsgTo").innerHTML="<b>&bull;A:</b>&nbsp;<?php
            echo str_replace("\'","'",addslashes($aRow["MSG_Pseudo"]));
            ?>";
            document.getElementById("SdMsgDate").innerHTML="<b>&bull;Le:</b>&nbsp;<?php
            echo substr(addslashes($aRow["MSG_Date"]),-2)."/".substr(addslashes($aRow["MSG_Date"]),5,-3)."/".substr(addslashes($aRow["MSG_Date"]),2,2); ?>-<?php echo addslashes($aRow["MSG_Time"]);
            ?>";
            document.getElementById("SdDateSel").value="<?php echo $aRow["MSG_Date"]; ?>";
            document.getElementById("SdTimeSel").value="<?php echo $aRow["MSG_Time"]; ?>";
            document.getElementById("SdBtnSupp").disabled="";
            document.getElementById("SdBtnAff").disabled="";
            break;
        }
        <?php
            $CntWrite++;
            if(!Empty($aRow["MSG_Objet"]))
            {   if(strlen(trim($aRow["MSG_Objet"])) <= 7) $aSdObj[] = "$CntWrite-".str_replace($aSearch,$aReplace,trim($aRow["MSG_Objet"]));
                else $aSdObj[] = "$CntWrite-".str_replace($aSearch,$aReplace,substr(trim($aRow["MSG_Objet"]),0,7))."...";
            }
            else $aSdObj[] = "$CntWrite-[No Objet]";
            // Tant qu'il y a des messages envoyés
        }
        mysql_free_result($Result);
        ?>default:
        {   document.getElementById("SdMsgObj").innerHTML="<b>&bull;Objet:</b> ...";
            document.getElementById("SdMsgTo").innerHTML="<b>&bull;A:</b> ...";
            document.getElementById("SdMsgDate").innerHTML="<b>&bull;Le:</b> ...";
            document.getElementById("SdDateSel").value="XXXX-XX-XX";
            document.getElementById("SdTimeSel").value="XX:XX:XX";
            document.getElementById("SdBtnSupp").disabled="disabled";
            document.getElementById("SdBtnAff").disabled="disabled";
            break;
        }
    }
}
// AfficheMsgRecu ///////////////////////////////////////////////////////////////////////////
function AfficheMsgRecu()
{   top.MailPad.location.href="MailPad.php?msgtpe=1&msgdt="+document.getElementById("RcDateSel").value+"&msgtm="+document.getElementById("RcTimeSel").value+"&Clf=<?php echo $Clf; ?>";
    top.MailContent.location.href="Mail.php?msgtpe=1&msgdt="+document.getElementById("RcDateSel").value+"&msgtm="+document.getElementById("RcTimeSel").value+"&Clf=<?php echo $Clf; ?>";
    top.MailPadTop.location.href="MailPadTop.php?msgtpe=1";
}
// AfficheMsgEnvoye /////////////////////////////////////////////////////////////////////////
function AfficheMsgEnvoye()
{   top.MailPad.location.href="MailPad.php?msgtpe=2&msgdt="+document.getElementById("SdDateSel").value+"&msgtm="+document.getElementById("SdTimeSel").value+"&Clf=<?php echo $Clf; ?>";
    top.MailContent.location.href="Mail.php?msgtpe=2&msgdt="+document.getElementById("SdDateSel").value+"&msgtm="+document.getElementById("SdTimeSel").value+"&Clf=<?php echo $Clf; ?>";
    top.MailPadTop.location.href="MailPadTop.php?msgtpe=2";
}
//-->
</script>
<?php
mysql_close($Link);
?>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-bottom: 0;margin-left: 0;margin-right: 0">
<table style="font-size: 8pt" style="font-size: 8pt" border=0 width="100%" height=94 cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=154 valign="top">
        <!-- ******************************************************************************************************************************* NEWS -->
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
        <td align="center"><font ID="Title">NEWS</font></td>
        <td align="right" valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
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
        <td nowrap><font ID="Content"><b>&bull;</b> Nouveaux: <b><?php
        if($CntNew != 0) echo "<font color=\"#8000ff\">$CntNew</font>";
        else echo "0";
        ?></b></font></td>
        <td width=5>
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
        </tr>
        <tr>
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
        <td nowrap><font ID="Content"><b>&bull;</b> Re&ccedil;us: <b><?php echo $CntRead; ?></b></font></td>
        <td width=5>
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
        </tr>
        <tr>
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
        <td nowrap><font ID="Content"><b>&bull;</b> Envoy&eacute;s: <b><?php echo $CntWrite; ?></b></font></td>
        <td width=5>
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
        </tr>
        <tr>
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
        <td>
            <table style="font-size: 8pt" border=0 height=10 cellspacing=0 cellpadding=0>
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
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
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
        <td bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 height=2 cellspacing=0 cellpadding=0>
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
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
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
        <td>
            <table style="font-size: 8pt" border=0 height=2 cellspacing=0 cellpadding=0>
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
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
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
        <td>
            <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td width="50%" align="right">
            <input type="button" tabindex=1 style="font-family: Verdana;font-size: 8pt" onclick="javascript:AfficheNewMsg()" value="Nouveau">
            </td>
            <td width=5>
                <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td width="50%" align="left">
            <form action="MailMain.php?Clf=<?php echo $Clf; ?>" method="post">
            <input type="hidden" name="ope" value=0>
            <input type="submit" tabindex=2 style="font-family: Verdana;font-size: 8pt" value="Actualiser">
            </form>
            </td>
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
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" height=3 cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td align="right" valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
        </tr>
        </table>
        <!-- **************************************************************************************************************************************** -->
    </td>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="50%" valign="top">
        <!-- ************************************************************************************************************************ MESSAGES RECUS -->
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
        <td align="center" nowrap><font ID="Title">MESSAGES RE&Ccedil;US</font></td>
        <td align="right" valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
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
        <td>
            <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td valign="top">
            <select tabindex=3 style="font-size: 7pt; font-family: Verdana,Lucida,Courier; color: black" ID="RcMsgList" onchange="javascript:ChgMsgRecus()" size=5>
            <?php
            if(count($aRcObj) != 0)
            {   foreach($aRcObj as $RcObj)
                {   // Tant qu'il y a des messages reçus
            ?><option><?php echo $RcObj; ?></option>
            <?php
                    // Tant qu'il y a des messages reçus
                }
            }
            else echo "<option>Vide...</option>";
            ?><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
            </select>
            </td>
            <td width=5>
                <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td width="100%" valign="top">
                <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr height=13>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" height=13 cellspacing=0 cellpadding=0>
                    <tr>
                    <td valign="top"><div style="position: float; width: 100%; height: 13px; overflow: hidden" align="left">
                        <table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
                        <tr>
                        <td nowrap><font ID="Content"><p ID="RcMsgObj"><b>&bull;Objet:</b> ...</p></font></td>
                        </tr>
                        </table>
                    </div></td>
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
                </tr>
                <tr height=13>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" height=13 cellspacing=0 cellpadding=0>
                    <tr>
                    <td valign="top"><div style="position: float; width: 100%; height: 13px; overflow: hidden" align="left">
                        <table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
                        <tr>
                        <td nowrap><font ID="Content"><p ID="RcMsgFrom"><b>&bull;De:</b> ...</p></font></td>
                        </tr>
                        </table>
                    </div></td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr height=13>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" height=13 cellspacing=0 cellpadding=0>
                    <tr>
                    <td valign="top"><div style="position: float; width: 100%; height: 13px; overflow: hidden" align="left">
                        <table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
                        <tr>
                        <td nowrap><font ID="Content"><p ID="RcMsgDate"><b>&bull;Le:</b> ...</p></font></td>
                        </tr>
                        </table>
                    </div></td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr>
                <td>
                    <table style="font-size: 8pt" border=0 height=10 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr>
                <td bgcolor="#bacc9a">
                    <table style="font-size: 8pt" border=0 height=2 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
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
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
                    <tr>
                    <td width="100%" align="right">
                    <form action="MailMain.php?Clf=<?php echo $Clf; ?>" method="post">
                    <input type="hidden" name="ope" value=1>
                    <input type="hidden" name="msgdt" ID="RcDateSel" value="XXXX-XX-XX">
                    <input type="hidden" name="msgtm" ID="RcTimeSel" value="XX:XX:XX">
                    <input type="submit" tabindex=4 style="font-family: Verdana;font-size: 8pt" ID="RcBtnSupp" value="Retirer" onclick="return confirm('Es-tu sûr de vouloir supprimer le message\nreçu ainsi sélectionné ?')" disabled>
                    </form>
                    </td>
                    <td width=5>
                        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                        </tr>
                        </table>
                    </td>
                    <td align="left">
                    <input type="button" tabindex=5 style="font-family: Verdana;font-size: 8pt" ID="RcBtnAff" onclick="javascript:AfficheMsgRecu()" value="Afficher" disabled>
                    </td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
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
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" height=3 cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td align="right" valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
        </tr>
        </table>
        <!-- *************************************************************************************************************************************** -->
    </td>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="50%" valign="top">
        <!-- ********************************************************************************************************************** MESSAGES ENVOYES -->
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
        <td align="center" nowrap><font ID="Title">MESSAGES ENVOYES</font></td>
        <td align="right" valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
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
        <td>
            <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td valign="top">
            <select tabindex=6 style="font-size: 7pt; font-family: Verdana,Lucida,Courier; color: black" ID="SdMsgList" onchange="javascript:ChgMsgEnvoyes()" size=5>
            <?php
            if(count($aSdObj) != 0)
            {   foreach($aSdObj as $SdObj)
                {   // Tant qu'il y a des messages reçus
            ?><option><?php echo $SdObj; ?></option>
            <?php
                    // Tant qu'il y a des messages reçus
                }
            }
            else echo "<option>Vide...</option>";
            ?><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
            </select>
            </td>
            <td width=5>
                <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td width="100%" valign="top">
                <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr height=13>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" height=13 cellspacing=0 cellpadding=0>
                    <tr>
                    <td valign="top"><div style="position: float; width: 100%; height: 13px; overflow: hidden" align="left">
                        <table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
                        <tr>
                        <td nowrap><font ID="Content"><p ID="SdMsgObj"><b>&bull;Objet:</b> ...</p></font></td>
                        </tr>
                        </table>
                    </div></td>
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
                </tr>
                <tr height=13>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" height=13 cellspacing=0 cellpadding=0>
                    <tr>
                    <td valign="top"><div style="position: float; width: 100%; height: 13px; overflow: hidden" align="left">
                        <table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
                        <tr>
                        <td nowrap><font ID="Content"><p ID="SdMsgTo"><b>&bull;A:</b> ...</p></font></td>
                        </tr>
                        </table>
                    </div></td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr height=13>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" height=13 cellspacing=0 cellpadding=0>
                    <tr>
                    <td valign="top"><div style="position: float; width: 100%; height: 13px; overflow: hidden" align="left">
                        <table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
                        <tr>
                        <td nowrap><font ID="Content"><p ID="SdMsgDate"><b>&bull;Le:</b> ...</p></font></td>
                        </tr>
                        </table>
                    </div></td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr>
                <td>
                    <table style="font-size: 8pt" border=0 height=10 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr>
                <td bgcolor="#bacc9a">
                    <table style="font-size: 8pt" border=0 height=2 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
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
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                <tr>
                <td>
                    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
                    <tr>
                    <td width="100%" align="right">
                    <form action="MailMain.php?Clf=<?php echo $Clf; ?>" method="post">
                    <input type="hidden" name="ope" value=2>
                    <input type="hidden" name="msgdt" ID="SdDateSel" value="XXXX-XX-XX">
                    <input type="hidden" name="msgtm" ID="SdTimeSel" value="XX:XX:XX">
                    <input type="submit" tabindex=7 style="font-family: Verdana;font-size: 8pt" ID="SdBtnSupp" value="Retirer" onclick="return confirm('Es-tu sûr de vouloir supprimer le message\nenvoyé ainsi sélectionné ?')" disabled>
                    </form>
                    </td>
                    <td width=5>
                        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                        </tr>
                        </table>
                    </td>
                    <td align="left">
                    <input type="button" tabindex=8 style="font-family: Verdana;font-size: 8pt" ID="SdBtnAff" onclick="javascript:AfficheMsgEnvoye()" value="Afficher" disabled>
                    </td>
                    </tr>
                    </table>
                </td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
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
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
        <tr>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
        <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
        <td width=2 bgcolor="#bacc9a">
            <table style="font-size: 8pt" border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" height=3 cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
        <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
        <tr>
        <td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td align="right" valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
        </tr>
        </table>
        <!-- *************************************************************************************************************************************** -->
    </td>
    <td width=5>
        <table style="font-size: 8pt" border=0 width=5 cellspacing=0 cellpadding=0>
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
</body>
</html>
