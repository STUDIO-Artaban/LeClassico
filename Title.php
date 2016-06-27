<?php
require("Package.php");
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
<title>Le Classico: Title</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
#UserFont {font-family: Impact,Verdana,Lucida; font-size: 11pt; color: #ff8000}
</style>
</head>
<body bgcolor="#8080ff" style="margin-top: 0;margin-left: 0">
<table border=0 width=574 cellspacing=0 cellpadding=0>
<tr>
<td>
        <table border=0 width=574 cellspacing=0 cellpadding=0>
        <tr height=3>
        <td width=72><img src="/Images/nopic.gif"></td>
        <td width=400><img src="/Images/nopic.gif"></td>
        <td width=71><img src="/Images/nopic.gif"></td>
        <td width=23><img src="/Images/nopic.gif"></td>
        <td width=8 bgcolor="#8000ff"><img src="/Images/nopic.gif"></td>
        </tr>
        <tr height=110>
        <td align="left"><img src="/Images/BaffleGau.jpg"></td>
        <td><img src="/Images/Title.jpg"></td>
        <td align="right"><img src="/Images/BaffleDro.jpg"></td>
        <td valign="bottom"><img src="/Images/ClaFonGH.jpg"></td>
        <td bgcolor="#8000ff"><img src="/Images/nopic.gif"></td>
        </tr>
        </table>
</td>
</tr>
<tr>
<td>
        <table border=0 width=574 cellspacing=0 cellpadding=0>
        <tr>
        <td width="100%" bgcolor="#8000ff" align="right"><?php
        if(!Empty($Clf))
        {   // Connecté
            ?><font ID="UserFont">Camarade <font color="#ffff00"><?php echo str_replace($aSearch,$aReplace,$Camarade); ?></font> connect&eacute; <font color="#000000"><b>-</b></font> <font color="#00ff00">Le <?php
            $aDate = getdate();
            $iJour = $aDate["mday"] * 1;
            $iMois = $aDate["mon"] * 1;
            if($iJour > 9) echo trim($iJour)."/";
            else echo "0".trim($iJour)."/";
            if($iMois > 9) echo trim($iMois)."/";
            else echo "0".trim($iMois)."/";
            echo trim($aDate["year"]);
            ?></font></font></td><?php
            // Connecté
        }
        else
        {   // Non Connecté
            ?><img src="/Images/nopic.gif"></td><?php
            // Non Connecté
        }
        ?>
        <td bgcolor="#8000ff"><img src="/Images/FonBlaGH.jpg"></td>
        </tr>
        </table>
</td>
</tr>
</table>
</body>
</html>
