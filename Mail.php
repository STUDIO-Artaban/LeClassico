<?php
require("Package.php");
$Chp = "6";
$Clf = $_GET['Clf'];
$msgtpe = $_GET['msgtpe'];
$msgdt = $_GET['msgdt'];
$msgtm = $_GET['msgtm'];
$msgcntt = $_POST['msgcntt'];
$msgstk = $_POST['msgstk'];
$msgpsd = $_POST['msgpsd'];
$msgobj = $_POST['msgobj'];
$ope = $_POST['ope'];
$aSearch = array("<",">");
$aReplace = array("&lt;","&gt;");
$Message = "Echec durant la lecture du message...";
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
        $Query = "SELECT CAM_Pseudo FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            if(!Empty($ope)) // Envoie d'un nouveau message ////////////////////////////////////////////////////////////////////////////////////
            {   if(((!Empty($msgpsd))&&(strcmp(trim($msgpsd),"")))||
                   ((!Empty($msgobj))&&(strcmp(trim($msgobj),"")))||
                   ((!Empty($msgcntt))&&(strcmp(trim($msgcntt),"")))||
                   (!Empty($msgstk)))
                {   if((!Empty($msgcntt))&&(strcmp(trim($msgcntt),"")))
                    {   if((!Empty($msgpsd))&&(strcmp(trim($msgpsd),"")))
                        {   $Query = "SELECT 'X' FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".trim($msgpsd)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   mysql_free_result($Result);
                                if(strcmp(strtoupper(trim($Camarade)),strtoupper(stripslashes(trim($msgpsd)))))
                                {   // Envoi du message
                                    $aDate = getdate();
                                    $Query = "INSERT INTO Messagerie (MSG_Pseudo,MSG_From,MSG_Message,MSG_Date,MSG_Time,MSG_WriteStk,MSG_Objet) VALUES (";
                                    // Pseudo
                                    $Query .= "'".trim($msgpsd)."',";
                                    // From
                                    $Query .= "'".addslashes($Camarade)."',";
                                    // Message
                                    $Query .= "'$msgcntt',";
                                    // Date
                                    $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."',";
                                    // Time
                                    $Query .= "'".trim($aDate["hours"]).":".trim($aDate["minutes"]).":".trim($aDate["seconds"])."',";
                                    // WriteStk
                                    if((!Empty($msgstk))&&(!strcmp($msgstk,"on"))) $Query .= "1,";
                                    else $Query .= "0,";
                                    // Objet
                                    if((!Empty($msgobj))&&(strcmp(trim($msgobj),""))) $Query .= "'".trim($msgobj)."')";
                                    else $Query .= "NULL)";
                                    if($Result = mysql_query(trim($Query),$Link))
                                    {   mysql_close($Link);
                                        $Msg = "Message [<font color=\"#808080\">";
                                        if((!Empty($msgobj))&&(strcmp(trim($msgobj),""))) $Msg .= str_replace($aSearch,$aReplace,stripslashes(trim($msgobj)));
                                        else $Msg .= "Pas d'Objet";
                                        $Msg .= "</font>] envoy&eacute; avec succ&eacute;s!";
                                        $Tpe = 1;
                                        $NoBack = true;
                                        include("Message.php");
                                        die();
                                    }
                                    else
                                    {   mysql_close($Link);
                                        $Msg = "Echec de l'envoie du message [<font color=\"#808080\">";
                                        if((!Empty($msgobj))&&(strcmp(trim($msgobj),""))) $Msg .= str_replace($aSearch,$aReplace,stripslashes(trim($msgobj)));
                                        else $Msg .= "Pas d'Objet";
                                        $Msg .= "</font>]! S&ucirc;rement du au contenu. Contact le <font color=\"#808080\">Webmaster</font>!";
                                        include("Message.php");
                                        die();
                                    }
                                }
                                else
                                {   mysql_close($Link);
                                    $Msg = "Alors, comme ça tu est du genre &agrave; t'envoyer un message &agrave; toi-m&ecirc;me!...";
                                    include("Message.php");
                                    die();
                                }
                            }
                            else
                            {   mysql_close($Link);
                                $Msg = "Le pseudo du camarade destinataire n'a pas &eacute;tait s&eacute;lectionn&eacute;! V&eacute;rifies son pseudo via le menu <font color=\"#808080\">Rechercher</font> si tu as un doute...";
                                include("Message.php");
                                die();
                            }
                        }
                        else
                        {   mysql_close($Link);
                            $Msg = "Tu n'as pas saisi le Pseudo du destinataire! Pas la peine de contacter le <font color=\"#808080\">Webmaster</font>!";
                            include("Message.php");
                            die();
                        }
                    }
                    else
                    {   mysql_close($Link);
                        $Msg = "Message vide! Si tu n'a rien &agrave; dire, n'envoie pas de message!";
                        include("Message.php");
                        die();
                    }
                }
            }
            elseif(!Empty($msgtpe)) // Lecture d'un message Reçu ou Envoyé ////////////////////////////////////////////////////////////////////////////
            {   if((!Empty($msgdt))&&(!Empty($msgtm))&&(strcmp(trim($msgdt),""))&&(strcmp(trim($msgtm),"")))
                {   $Query = "SELECT MSG_Message FROM Messagerie";
                    if($msgtpe == 1)
                    {   // Message reçu
                        $Query .= " WHERE MSG_Status <> 2 AND UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."') AND MSG_ReadStk = 1";
                    }
                    else
                    {   // Message envoyé
                        $Query .= " WHERE MSG_Status <> 2 AND UPPER(MSG_From) = UPPER('".addslashes($Camarade)."') AND MSG_WriteStk = 1";
                    }
                    $Query .= " AND MSG_Date = '".trim($msgdt)."' AND MSG_Time = '".trim($msgtm)."'";
                    if($Result = mysql_query(trim($Query),$Link))
                    {   $aRow = mysql_fetch_array($Result);
                        $Message = $aRow["MSG_Message"];
                        mysql_free_result($Result);
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
        mysql_close($Link);
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
<title>Le Classico: Mail Write/Read</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
textarea
{
    width: 449px;
    min-width: 449px;
    max-width: 449px;
    height: 182px;
    min-height: 182px;
    max-height: 182px;
    overflow-y: scroll;
    resize: none;
}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#Info {font-size: 8pt; font-family: Verdana,Lucida,Impact}
</style>
<script type="text/javascript">
<!--
// CheckSendMsg /////////////////////////////////////////////////////////////////////////
function CheckSendMsg()
{   var SelCam = top.MailPad.document.getElementById("MsgPadPsd").selectedIndex;
    if(SelCam==0)
    {   alert("Pas de destinataire !?!");
        return false;
    }
    if(document.getElementById("MsgSendCnt").value=="")
    {   alert("Message vide !?!");
        return false;
    }
    document.getElementById("MsgSendPsd").value=top.MailPad.document.getElementById("MsgPadPsd").options[SelCam].text;
    document.getElementById("MsgSendObj").value=top.MailPad.document.getElementById("MsgPadObj").value;
    top.MailPad.document.getElementById("MsgPadPsd").disabled="disabled";
    top.MailPad.document.getElementById("MsgPadObj").disabled="disabled";
    return true;
}
//-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-bottom: 0;margin-left: 5px;margin-right: 0">
<?php
if(!Empty($msgtpe))
{   // Lecture d'un message Reçu ou Envoyé
?>
<font face="Verdana,Lucida,Courier" size=2><?php echo PrintString($Message); ?></font>
<?php
    // Lecture d'un message Reçu ou Envoyé
}
else
{   // Nouveau message
?>
<form action="Mail.php?Clf=<?php echo $Clf; ?>" method="post">
<table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
    <table style="font-size: 8pt" border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td><textarea style="font-size: 9pt; font-family: Verdana,Lucida,Courier" ID="MsgSendCnt" name="msgcntt"></textarea></td>
    </tr>
    </table>
</td>
</tr>
<tr height=5>
<td>
    <table style="font-size: 8pt" border=0 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td>
    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td align="left"><input type="checkbox" name="msgstk"><font ID="Title">Conserver une copie</font></td>
    <td align="right">
    <input type="hidden" ID="MsgSendPsd" name="msgpsd" value="">
    <input type="hidden" ID="MsgSendObj" name="msgobj" value="">
    <input type="hidden" name="ope" value=1>
    <input type="submit" style="font-family: Verdana;font-size: 10pt" onclick="return CheckSendMsg()" value="Envoyer">
    </td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td>
    <table style="font-size: 8pt" border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width=3>
        <table style="font-size: 8pt" border=0 width=3 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td nowrap><font ID="Info"><b>Remarque:</b> Il s'agit l&agrave; d'un message en HTML!!</font></td>
    </tr>
    </table>
</td>
</tr>
</table>
</form>
<?php
    // Nouveau message
}
?>
</body>
</html>
