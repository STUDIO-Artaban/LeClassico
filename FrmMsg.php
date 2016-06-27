<?php
require("Package.php");
$iRefresh = 1;
$Chp = "5";
$Clf = $_GET['Clf'];
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
        $Query = "SELECT 'X' FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   mysql_free_result($Result);
            if(!Empty($Swp))
            {   if($Swp == 1) $iRefresh = 2;
                else $iRefresh = 1;
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
    if(!Empty($Swp))
    {   if($Swp == 1) $iRefresh = 2;
        else $iRefresh = 1;
    }
    //$Msg = "Tu n'est pas connect&eacute;!";
    //include("Message.php");
    //die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Forum Messages</title>
<meta http-equiv="refresh" content="10; URL=FrmMsg.php?Swp=<?php
echo $iRefresh;
if(!Empty($Clf)) echo "&Clf=$Clf"; ?>#EndMsg">
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-bottom: 0;margin-left: 10px">
<!-- *********************************************************************************************************************************** FORUM MSG -->
<?php
$bAddLag = false;
$BckColor = "#D8E1C6";
$iCntMsg = 0;
$Query = "SELECT COUNT(*) AS CntMsg FROM Forum";
if($Result = mysql_query(trim($Query),$Link))
{   $aRow = mysql_fetch_array($Result);
    $iCntMsg = $aRow["CntMsg"];
    mysql_free_result($Result);
}
$Query = "SELECT * FROM Forum ORDER BY FRM_Date ASC, FRM_Time ASC";
$Result = mysql_query(trim($Query),$Link);
while($aRow = mysql_fetch_array($Result))
{   if(!strcmp($BckColor,"#D8E1C6")) $BckColor = "#BACC9A";
    else $BckColor = "#D8E1C6";
    $iCntMsg = $iCntMsg - 1;
    // Tant qu'il y a des messages
    if($bAddLag)
    {   // Add Lag
?>
<table border=0 height=5 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<?php
        // Add Lag
    }
    else $bAddLag = true;
?>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=48 valign="top" bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/DosForum.jpg"></td>
        <td width="100%" valign="top" bgcolor="<?php echo $BckColor; ?>">
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td>
                <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#000000">
                <tr>
                <td width=5>
                    <table border=0 width=5 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td width="100%">
                    <table border=0 width="100%" cellspacing=0 cellpadding=0>
                    <tr>
                    <td nowrap><font face="Verdana,Lucida,Courier" size=1 color="#ffffff">Du&nbsp;camarade&nbsp;<font color="#ffff00"><b><?php echo $aRow["FRM_Pseudo"]; ?></b></font>&nbsp;le&nbsp;<font color="#00ff00"><b><?php echo $aRow["FRM_Date"]; ?></b></font>&nbsp;&agrave;&nbsp;<font color="#00ff00"><b><?php echo $aRow["FRM_Time"]; ?></b></font></font></td>
                    </tr>
                    </table>
                </td>
                <td width=5 valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubNoirHD.jpg"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td>
                <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
                <tr>
                <td width=5>
                    <table border=0 width=5 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td width="100%"><font face="Verdana,Lucida,Courier" size=2><?php echo PrintString($aRow["FRM_Message"]); ?></font></td>
                <td width=5>
                    <table border=0 width=5 cellspacing=0 cellpadding=0>
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
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=48>
            <table border=0 width=48 cellspacing=0 cellpadding=0 bgcolor="#ff8000">
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
            </tr>
            </table>
        </td>
        <td width="100%">
            <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
            <tr height=5>
            <td><?php
            if($iCntMsg == 0)
            {   // Dernier message
            ?><a name="EndMsg"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></a><?
            }
            else
            {   // Pas dernier message
            ?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
            }
            ?></td>
            </tr>
            </table>
        </td>
        <td width=5>
            <table border=0 width=5 cellspacing=0 cellpadding=0 bgcolor="<?php echo $BckColor; ?>">
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/<?php
            if(!strcmp($BckColor,"#D8E1C6")) echo "SubClaBD.jpg";
            else echo "SubFonBD.jpg";
            ?>"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
</table><?php
    // Tant qu'il y a des messages
}
mysql_free_result($Result);
mysql_close($Link);
?>
<!-- *********************************************************************************************************************************************** -->
</body>
</html>
