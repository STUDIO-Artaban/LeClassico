<?php
require("Package.php");
$Chp = "6";
$Clf = $_GET['Clf'];
$msgtpe = $_GET['msgtpe'];
$msgdt = $_GET['msgdt'];
$msgtm = $_GET['msgtm'];
$Objet = "";
$Pseudo = "Camarade...";
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
            if(!Empty($msgtpe))
            {   if((!Empty($msgdt))&&(!Empty($msgtm))&&(strcmp(trim($msgdt),""))&&(strcmp(trim($msgtm),"")))
                {   $Query = "SELECT MSG_From,MSG_Pseudo,MSG_LuFlag,MSG_Objet FROM Messagerie";
                    if($msgtpe == 1)
                    {   // Message reçu
                        $Query .= " WHERE UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."') AND MSG_ReadStk = 1";
                    }
                    else
                    {   // Message envoyé
                        $Query .= " WHERE UPPER(MSG_From) = UPPER('".addslashes($Camarade)."') AND MSG_WriteStk = 1";
                    }
                    $Query .= " AND MSG_Date = '".trim($msgdt)."' AND MSG_Time = '".trim($msgtm)."'";
                    $Result = mysql_query(trim($Query),$Link);
                    $aRow = mysql_fetch_array($Result);
                    $Objet = trim($aRow["MSG_Objet"]);
                    if($msgtpe == 1) $Pseudo = $aRow["MSG_From"]; // Reçu
                    else $Pseudo = $aRow["MSG_Pseudo"]; // Envoyé
                    // Mise à jour du Flag Lu
                    if(($msgtpe == 1)&&(Empty($aRow["MSG_LuFlag"])))
                    {   $Query = "UPDATE Messagerie SET MSG_LuFlag = 1 WHERE UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."') AND MSG_ReadStk = 1";
                        $Query .= " AND MSG_Date = '".trim($msgdt)."' AND MSG_Time = '".trim($msgtm)."'";
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
<title>Le Classico: Mail Pad</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-bottom: 0;margin-left: 0;margin-right: 0">
<table border=0 width=298 cellspacing=0 cellpadding=0>
<tr>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/RedCadInHG.jpg"></td>
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%" bgcolor="ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
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
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#bacc9a"><font ID="Title"><?php
if(!Empty($msgtpe))
{   if($msgtpe == 1) echo "Emetteur (pseudo): ";
    else echo "Destinataire (pseudo):";
}
else echo "Destinataire (pseudo):";
?>&nbsp;&nbsp;</font><select ID="MsgPadPsd" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: <?php
if(!Empty($msgtpe)) echo "gray";
else echo "black";
?>; width: <?php
if(!Empty($msgtpe))
{   if($msgtpe == 1) echo "146px\"";
    else echo "126px\"";
}
else echo "126px\"";
if(!Empty($msgtpe)) echo " disabled";
echo "><option selected>".$Pseudo."</option>";
// Liste de tous les camarades (excepté le camarade connecté)
$Query = "SELECT CAM_Pseudo FROM Camarades WHERE UPPER(CAM_Pseudo) <> UPPER('".addslashes($Camarade)."')";
mysql_select_db(GetMySqlDB(),$Link);
$Result = mysql_query(trim($Query),$Link);
while($aRow = mysql_fetch_array($Result))
{
	?><option><?php echo $aRow["CAM_Pseudo"]; ?></option>
	<?php
}
mysql_free_result($Result);
mysql_close($Link);
?></select></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#bacc9a"><font ID="Title">Objet:&nbsp;&nbsp;</font><input type="text" ID="MsgPadObj" style="width: 218px; font-size: 9pt; font-family: Verdana,Lucida,Courier; color: <?php
if(!Empty($msgtpe)) echo "gray";
else echo "black";
?>" maxlength=25 value="<?php
if(!Empty($msgtpe))
{   if(!Empty($Objet)) echo $Objet."\"";
    else echo "[Pas d'Objet]\"";
    echo " readonly";
}
else echo $Objet."\"";
?>></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
</body>
</html>
