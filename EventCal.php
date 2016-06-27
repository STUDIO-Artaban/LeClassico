<?php
require("Package.php");
$Chp = "14";
$Clf = $_GET['Clf'];
$dy = $_GET['dy'];
$mn = $_GET['mn'];
$yr = $_GET['yr'];
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
{   //$Msg = "Tu n'est pas connect&eacute;!";
    //include("Message.php");
    //die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Event Calendar</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
p {padding: 0px; margin-bottom: 0px; margin-top: 0px; border: 0px}
#Title {font-size: 8pt; font-family: Impact,Verdana,Lucida; color: black}
</style>
<script src="Librairies/events.js" type="text/javascript" language="javascript"></script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px;margin-right: 0">
<!-- ******************************************************************************************************************************** EVENT CALENDAR -->
<script type="text/javascript">
<!--
// Commandes ///////////////////////////////////////////////////////////////////
SetToDay(<?php
$aDate = getdate();
echo trim($aDate["year"]).",".trim($aDate["mon"]).",".trim($aDate["mday"]);
?>);
-->
</script>
<table border=0 width=212 cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
<tr>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
<td width="100%" align="center">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width=30 align="right">
        <table border=0 width=30 cellspacing=0 cellpadding=0>
        <tr>
        <td><input type="image" onclick="javascript:OnSetLastMonth()" src="<?php echo GetFolder(); ?>/Images/LastMnth.jpg"></td>
        </tr>
        </table>
    </td>
    <td width="100%" align="center" nowrap><font ID="Title"><font style="font-size: 12pt"><p ID="MonthSel"></p></font></font></td>
    <td width=30 align="left">
        <table border=0 width=30 cellspacing=0 cellpadding=0>
        <tr>
        <td><input type="image" onclick="javascript:OnSetNextMonth()" src="<?php echo GetFolder(); ?>/Images/NextMnth.jpg"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
</tr>
</table>
<table border=0 width=212 cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
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
<table border=0 width=212 cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
<tr>
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
<td>
    <table border=1 bordercolor="#ffffff" width="100%" cellspacing=0 cellpadding=0 bgcolor="#bacc9a" style="border-style: solid">
    <tr>
    <td width=26 align="center" style="border-style: solid">
        <table border=0 width=26 height=19 cellspacing=0 cellpadding=0>
        <tr>
        <td align="center"><font ID="Title"><font color="#ffffff">L</font></font></td>
        </tr>
        </table>
    </td>
    <td width=26 align="center" style="border-style: solid"><font ID="Title"><font color="#ffffff">M</font></font></td>
    <td width=26 align="center" style="border-style: solid"><font ID="Title"><font color="#ffffff">M</font></font></td>
    <td width=26 align="center" style="border-style: solid"><font ID="Title"><font color="#ffffff">J</font></font></td>
    <td width=26 align="center" style="border-style: solid"><font ID="Title"><font color="#ffffff">V</font></font></td>
    <td width=26 align="center" style="border-style: solid"><font ID="Title"><font color="#ffffff">S</font></font></td>
    <td width=26 align="center" style="border-style: solid"><font ID="Title"><font color="#ffffff">D</font></font></td>
    </tr>
    <tr ID="Week1" height=19>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr ID="Week2" height=19>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr ID="Week3" height=19>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr ID="Week4" height=19>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr ID="Week5" height=19>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr ID="Week6" height=19>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,false)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=26 align="center" style="background-color: #dddddd;border-style: solid" onmouseover="javascript:OnMouseIn(this)" onmouseout="javascript:OnMouseOut(this,true)" onclick="javascript:OnSelectDay(this,'<?php echo $Clf; ?>')"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
</tr>
</table>
<table border=0 width=212 cellspacing=0 cellpadding=0 bgcolor="#d8e1c6">
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
<table border=0 width=212 height=5 cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width=212 cellspacing=0 cellpadding=0 bgcolor="#bacc9a">
<tr>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td align="right" valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
</tr>
</table>
<script type="text/javascript">
<!--
// Commandes ///////////////////////////////////////////////////////////////////
<?php
if((!Empty($dy))&&(!Empty($mn))&&(!Empty($yr)))
{   // Sélectionne la date
?>
SelectDate(<?php echo "$yr,$mn,$dy"; ?>);
<?php
    // Sélectionne la date
}
?>
InitCalendrier();
-->
</script>
<!-- *********************************************************************************************************************************************** -->
</body>
</html>
