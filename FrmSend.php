<?php
require("Package.php");
$Chp = "5";
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
            mysql_free_result($Result);
            if((!Empty($ope))&&(!Empty($frmsg))&&(strcmp(trim($frmsg),"")))
            {   // Envoie le message ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $aDate = getdate();
                $Query = "INSERT INTO Forum (FRM_Pseudo,FRM_Message,FRM_Date,FRM_Time) VALUES ('".addslashes($Camarade)."','";
                $Query .= str_replace($aSearch,$aReplace,trim($frmsg))."','".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."','".trim($aDate["hours"]).":".trim($aDate["minutes"]).":".trim($aDate["seconds"])."')";
                if(!mysql_query(trim($Query),$Link))
                {   mysql_close($Link);
                    $Msg = "Echec de l'envoie du message! Il n'a peut-&ecirc;tre pas appr&eacute;ci&eacute; son contenu!?! Si tu veux en savoir plus contact le <font color=\"#808080\">Webmaster</font>!";
                    include("Message.php");
                    die();
                }
                else
                {   $Query = "SELECT COUNT(*) AS MSGCNT FROM Forum";
                    $Result = mysql_query(trim($Query),$Link);
                    $aRow = mysql_fetch_array($Result);
                    if($aRow["MSGCNT"] > 20)
                    {   // Supprime le dernier message
                        $Query = "SELECT MIN(FRM_Date) AS LASTDATE FROM Forum";
                        $Result = mysql_query(trim($Query),$Link);
                        $LastDate = mysql_result($Result,0,"LASTDATE");
                        mysql_free_result($Result);
                        $Query = "SELECT MIN(FRM_Time) AS LASTTIME FROM Forum WHERE FRM_Date = '$LastDate'";
                        $Result = mysql_query(trim($Query),$Link);
                        $LastTime = mysql_result($Result,0,"LASTTIME");
                        mysql_free_result($Result);
                        $Query = "DELETE FROM Forum WHERE FRM_Date = '$LastDate' AND FRM_Time = '$LastTime'";
                        $Result = mysql_query(trim($Query),$Link);
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
<title>Le Classico: Forum Send</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
textarea {
    width: 340px;
    min-width: 340px;
    max-width: 340px;
    height: 34px;
    min-height: 34px;
    max-height: 34px;
    overflow-y: scroll;
    resize: none;
}
form {padding: 0px; margin-bottom: 0px; margin-top: 0px; border: 0px}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
</style>
<?php
if(!Empty($md))
{   // Mode autonome
?>
<script type="text/javascript">
<!--
// OnCloseWindow ////////////////////////////////////////////////////////////////////////////////////
function OnCloseWindow()
{   parent.close();
}
// Initialize /////////////////////////////////////////////////
function Initialize()
{
    // Modifie la page si Netscape
    if (navigator.appName!="Microsoft Internet Explorer")
    {   
        // Merci IE!! :p
        document.getElementById("TabCls").width=11;
    }
}
//-->
</script>
<?php
    // Mode autonome
}
?>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 0;margin-right: 0" onload="Initialize()">
<!-- ********************************************************************************************************************************** FORUM SEND -->
<table border=0 width="100%" height=<?php
if(!Empty($md)) echo "68";
else echo "77";
?> cellspacing=0 cellpadding=0>
<?php
if(!Empty($md))
{   // Mode autonome
?>
<tr>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td align="right"><img src="<?php echo GetFolder(); ?>/Images/CadreBD.jpg"></td>
    </tr>
    </table>
</td>
<td width=16 bgcolor="#e4e4e4">
    <table border=0 width=16 cellspacing=0 cellpadding=0>
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
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=86 bgcolor="#e4e4e4">
        <table border=0 width=86 cellspacing=0 cellpadding=0>
        <tr>
        <td align="left"><img src="<?php echo GetFolder(); ?>/Images/CadreOutHG.jpg"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td width=16 bgcolor="#e4e4e4">
    <table border=0 width=16 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<?php
    // Mode autonome
}
?>
<tr>
<td>
<?php
if(Empty($md))
{   // Pas mode autonome
?>
    <table border=0 height=7 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5>
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
    <td width=10>
        <table border=0 height=20 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
        <tr>
        <td height="100%"><img src="<?php echo GetFolder(); ?>/Images/Puce.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%">
        <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
        <tr>
        <td><font ID="Title">&nbsp;Message&nbsp;&agrave;&nbsp;envoyer</font></td>
        </tr>
        </table>
    </td>
    <td width=5>
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
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
    <table border=0 height=7 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
<?php
    // Pas mode autonome
}
?>
    <form action="FrmSend.php?Clf=<?php echo $Clf; ?>" method="post">
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <?php
    if(Empty($md))
    {   // Pas mode autonome
    ?>
    <td width=95 valign="top">
        <table border=0 width=95 cellspacing=0 cellpadding=0>
        <tr>
        <td><font ID="Title">Ton message:</font></td>
        </tr>
        </table>
    </td>
    <?php
        // Pas mode autonome
    }
    ?>
    <td><textarea style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="frmsg"></textarea></td>
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=73 valign="bottom"><input type="hidden" name="ope" value=1><?php
    if(!Empty($md))
    {   // Mode autonome
    ?><input type="hidden" name="md" value=1><?php
        // Mode autonome
    }
    ?><input type="submit" style="font-family: Verdana;font-size: 10pt" value="Envoyer"></td>
    <?php
    if(!Empty($md))
    {   // Mode autonome
    ?>
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
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
    <td bgcolor="#e4e4e4">
        <table ID="TabCls" border=0 width=14 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=62 valign="bottom" bgcolor="#e4e4e4"><input type="button" onclick="javascript:OnCloseWindow()" style="font-family: Verdana;font-size: 10pt" value="Fermer"></td>
    <?php
        // Mode autonome
    }
    ?>
    </tr>
    </table>
    </form>
</td>
<td width=16 bgcolor="#e4e4e4">
    <table border=0 width=16 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<?php
if(!Empty($md))
{   // Mode autonome
?>
<tr>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=96 bgcolor="#e4e4e4">
        <table border=0 width=96 cellspacing=0 cellpadding=0>
        <tr>
        <td align="left"><img src="<?php echo GetFolder(); ?>/Images/CadreBD.jpg"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td width=16 bgcolor="#e4e4e4">
    <table border=0 width=16 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<?php
    // Mode autonome
}
?>
</table>
<!-- *********************************************************************************************************************************************** -->
</body>
</html>
