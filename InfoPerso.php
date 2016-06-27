<?php
require("Package.php");
$Chp = "2";
$bModif = false;
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
        mysql_select_db(GetMySqlDB(),$Link);
        if(!Empty($ope))
        {   if($ope == 1)
            {   // Opération de MAJ du code confidentiel //////////////////////////////////////////////////////////////////////////////////////////////
                if((!Empty($nccf))||(!Empty($cnccf)))
                {   if(!strcmp(trim($nccf),""))
                    {   mysql_close($Link);
                        $Msg = "Code confidentiel non saisi!";
                        include("Message.php");
                        die();
                    }
                    else
                    {   if(!strcmp(trim($cnccf),""))
                        {   mysql_close($Link);
                            $Msg = "Confirmation du code confidentiel non saisi!";
                            include("Message.php");
                            die();
                        }
                        else
                        {   if(strcmp(trim($nccf),trim($cnccf)))
                            {   mysql_close($Link);
                                $Msg = "Code confidentiel non confirm&eacute;!";
                                include("Message.php");
                                die();
                            }
                            else
                            {   //MAJ du code confidentiel
                                $Query = "UPDATE Camarades SET CAM_CodeConf = '$nccf' WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Echec de la mise &agrave; jour de ton code confidentiel! Contact le <font color=\"#808080\">Webmaster</font>!";
                                    include("Message.php");
                                    die();
                                }
                                else
                                {   mysql_close($Link);
                                    $Msg = "Ok! La mise &agrave; jour de ton code confidentiel s'est d&eacute;roul&eacute;e sans accros!";
                                    $Tpe = "1";
                                    include("Message.php");
                                    die();
                                }
                            }
                        }
                    }
                }
            }
            else
            {   // Opération de MAJ des infos personnels
                $Query = "UPDATE Camarades SET";
                if((!Empty($nm))&&(strcmp(trim($nm),"")))
                {   $Query .= " CAM_Nom = '".trim($nm)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_Nom = NULL,";
                if((!Empty($prnm))&&(strcmp(trim($prnm),"")))
                {   $Query .= " CAM_Prenom = '".trim($prnm)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_Prenom = NULL,";
                if((!Empty($sx))&&(strcmp(trim($sx),"")))
                {   if(!strcmp($sx,"Masculin")) $Query .= " CAM_Sexe = 2,";
                    else $Query .= " CAM_Sexe = 1,";
                    $bModif = true;
                }
                if((!Empty($dtns))&&(strcmp(trim($dtns),""))&&(strcmp(trim($dtns),"AAAA-MM-JJ")))
                {   $Query .= " CAM_BornDate = '".trim($dtns)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_BornDate = NULL,";
                if((!Empty($adrs))&&(strcmp(trim($adrs),"")))
                {   $Query .= " CAM_Adresse = '".trim($adrs)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_Adresse = NULL,";
                if((!Empty($twn))&&(strcmp(trim($twn),"")))
                {   $Query .= " CAM_Ville = '".trim($twn)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_Ville = NULL,";
                if((!Empty($cpst))&&(strcmp(trim($cpst),"")))
                {   $Query .= " CAM_Postal = '".trim($cpst)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_Postal = NULL,";
                if((!Empty($mail))&&(strcmp(trim($mail),"")))
                {   $Query .= " CAM_Email = '".trim($mail)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_Email = NULL,";
                if((!Empty($hobbi))&&(strcmp(trim($hobbi),"")))
                {   $Query .= " CAM_Hobbies = '".trim($hobbi)."',";
                    $bModif = true;
                }
                else $Query .= " CAM_Hobbies = NULL,";
                if((!Empty($aprop))&&(strcmp(trim($aprop),"")))
                {   $Query .= " CAM_APropos = '".trim($aprop)."'";
                    $bModif = true;
                }
                else $Query .= " CAM_APropos = NULL";
                if($bModif)
                {   $Query .= " WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if(!mysql_query(trim($Query),$Link))
                    {   mysql_close($Link);
                        $Msg = "Echec de la mise &agrave; jour de tes infos personnels! Contact le <font color=\"#808080\">Webmaster</font>!";
                        include("Message.php");
                        die();
                    }
                    else
                    {   mysql_close($Link);
                        $Msg = "Ok! La mise &agrave; jour de tes infos personnels s'est d&eacute;roul&eacute;e sans accros!";
                        $Tpe = "1";
                        include("Message.php");
                        die();
                    }
                }
            }
        }
        // Lecture des infos personnels
        $Query = "SELECT * FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        $Result = mysql_query(trim($Query),$Link);
        if($aRow = mysql_fetch_array($Result))
        {   $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            if(is_null($aRow["CAM_Nom"])) $Nom = "";
            else $Nom = stripslashes($aRow["CAM_Nom"]);
            if(is_null($aRow["CAM_Prenom"])) $Prenom = "";
            else $Prenom = stripslashes($aRow["CAM_Prenom"]);
            if(is_null($aRow["CAM_Sexe"])) $Sexe = 0;
            else $Sexe = $aRow["CAM_Sexe"];
            if((is_null($aRow["CAM_BornDate"]))||(!strcmp(trim($aRow["CAM_BornDate"]),"0000-00-00"))) $Date = "";
            else $Date = $aRow["CAM_BornDate"];
            if(is_null($aRow["CAM_Ville"])) $Ville = "";
            else $Ville = stripslashes($aRow["CAM_Ville"]);
            if(is_null($aRow["CAM_Adresse"])) $Adresse = "";
            else $Adresse = stripslashes($aRow["CAM_Adresse"]);
            if(is_null($aRow["CAM_Postal"])) $Postal = "";
            else $Postal = stripslashes($aRow["CAM_Postal"]);
            if(is_null($aRow["CAM_Email"])) $Email = "";
            else $Email = stripslashes($aRow["CAM_Email"]);
            if(is_null($aRow["CAM_Hobbies"])) $Hobbies = "";
            else $Hobbies = stripslashes($aRow["CAM_Hobbies"]);
            if(is_null($aRow["CAM_APropos"])) $APropos = "";
            else $APropos = stripslashes($aRow["CAM_APropos"]);
            mysql_free_result($Result);
            //
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
<title>Le Classico: Infos Perso</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
form {padding: 0px; margin-bottom: 0px; border: 0px}
textarea {
    width: 310px;
    min-width: 310px;
    max-width: 310px;
    height: 102px;
    min-height: 102px;
    max-height: 102px;
    overflow-y: scroll;
    resize: none;
}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Entete {font-size: 12pt; font-family: Impact,Verdana,Lucida}
</style>
<script type="text/javascript">
<!--
// OnChangeCodePost ///////////////////////////////////////////////////////////////////////////
function OnChangeCodePost()
{   if((window.event.keyCode<48)||(window.event.keyCode>57)) window.event.keyCode = "";
}
// Initialize /////////////////////////////////////////////////
function Initialize()
{
    // Modifie la page si Netscape
    if (navigator.appName!="Microsoft Internet Explorer")
    {   
        // Merci IE!! :p
        document.getElementById("Pwd").style.marginTop="1px";
        document.getElementById("Cpw").style.marginTop="1px";
        document.getElementById("Nom").style.marginTop="1px";
        document.getElementById("Prenom").style.marginTop="1px";
        document.getElementById("Ddn").style.marginTop="1px";
        document.getElementById("Adresse").style.marginTop="1px";
        document.getElementById("Ville").style.marginTop="1px";
        document.getElementById("Postal").style.marginTop="1px";
        document.getElementById("Email").style.marginTop="1px";

        document.getElementById("Pwd").style.marginBottom="1px";
        document.getElementById("Cpw").style.marginBottom="1px";
        document.getElementById("Nom").style.marginBottom="1px";
        document.getElementById("Prenom").style.marginBottom="1px";
        document.getElementById("Ddn").style.marginBottom="1px";
        document.getElementById("Adresse").style.marginBottom="1px";
        document.getElementById("Ville").style.marginBottom="1px";
        document.getElementById("Postal").style.marginBottom="1px";
        document.getElementById("Email").style.marginBottom="1px";
    }
}
-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px" onload="Initialize()">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ********************************************************************************************************************************** INFOS PERSO -->
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
    <td width="100%" bgcolor="#ff0000" nowrap><font ID="BigTitle">&nbsp;<b>Infos&nbsp;Personnels</b></font></td>
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
<font face="Verdana,Lucida,Courier" size=2>Configures les informations suivantes afin que d'autres camarades puissent en savoir plus sur toi, ou
 tu peux &eacute;galement ne rien d&eacute;finir ici, &ccedil;a c'est &agrave; toi de voir. Pour ma part cela ne change rien. Par contre, n'oublies pas
 que ces infos seront visibles pour tout le monde! Donc n'en dis pas trop, on ne sait jamais!<br><br>
 Ce qui est s&ucirc;r c'est que c'est un autre camarade qui a cr&eacute;&eacute; ton compte utilisateur, alors si tu ne souhaites pas que ce dernier
 se fasse passer pour toi, et cr&eacute;e un album photos pornographique d'un go&ucirc;t tr&egrave;s douteux ou envoie des messages contenant des
 propositions obsc&egrave;nes &agrave; tous les autres camarades, une seule chose &agrave; faire: Changer son code confidentiel...</font><br><br>
<form action="InfoPerso.php?Clf=<?php echo $Clf; ?>" method="post">
<table border=0 width=300 cellspacing=0 cellpadding=0>
<tr>
<td width=140 valign="top">
    <table border=0 width=140 cellspacing=0 cellpadding=0>
    <tr>
    <td><font ID="Entete">Pseudo:</font></td>
    </tr>
    </table>
</td>
<td>
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td nowrap><font face="Verdana,Lucida,Courier" color="#8000ff" size=2><i><?php echo $Camarade; ?></i></font></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td><font ID="Entete">Code confidentiel:</font></td>
<td><input ID="Pwd" type="password" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="nccf" maxlength=20></td>
</tr>
<tr>
<td><font ID="Entete">Confirmes ton code:</font></td>
<td><input ID="Cpw" type="password" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="cnccf" maxlength=20></td>
</tr>
<tr>
<td colspan=2>
    <table border=0 height=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><input type="hidden" name="ope" value=1><input type="submit" style="font-family: Verdana;font-size: 10pt" value="Changer"></td>
</tr>
</table><br>
</form>
<form action="InfoPerso.php?Clf=<?php echo $Clf; ?>" method="post">
<table border=0 width=300 cellspacing=0 cellpadding=0>
<tr>
<td width=140>
    <table border=0 width=140 cellspacing=0 cellpadding=0>
    <tr>
    <td><td><font ID="Entete">Nom:</font></td></td>
    </tr>
    </table>
</td>
<td>
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td><input type="text" ID="Nom" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="nm" maxlength=20></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td><font ID="Entete">Pr&eacute;nom:</font></td>
<td><input type="text" ID="Prenom" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="prnm" maxlength=20></td>
</tr>
<tr>
<td><font ID="Entete">Sexe:</font></td>
<td>
    <select name="sx" style="font-size: 10pt; font-family: Verdana,Lucida,Courier">
    <?php
    // Sexe
    if(Empty($Sexe))
    {
    ?>
    <option></option>
    <?php
    }
    ?>
    <option<?php
    if((!Empty($Sexe))&&($Sexe == 1)) echo " selected";
    ?>>F&eacute;minin</option>
    <option<?php
    if((!Empty($Sexe))&&($Sexe == 2)) echo " selected";
    ?>>Masculin</option>
    </select>
</td>
</tr>
<tr>
<td><font ID="Entete">Date de naissance:</font></td>
<td><input ID="Ddn" type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="dtns" maxlength=10 value="<?php
// Date
if(!Empty($Date)) echo $Date;
else echo "AAAA-MM-JJ";
?>"></td>
</tr>
<tr>
<td><font ID="Entete">Adresse:</font></td>
<td><input type="text" ID="Adresse" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="adrs" maxlength=200></td>
</tr>
<tr>
<td><font ID="Entete">Ville:</font></td>
<td><input type="text" ID="Ville" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="twn" maxlength=30></td>
</tr>
<tr>
<td><font ID="Entete">Code postal:</font></td>
<td><input type="text" ID="Postal" onkeypress="javascript:OnChangeCodePost()" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="cpst" maxlength=5></td>
</tr>
<tr>
<td><font ID="Entete">E-mail:</font></td>
<td><input type="text" ID="Email" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="mail" maxlength=50></td>
</tr>
<tr>
<td valign="top"><font ID="Entete">Passe-temps:</font></td>
<td><textarea style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="hobbi"><?php
// Hobbies
if(!Empty($Hobbies)) echo $Hobbies;
?></textarea></td>
</tr>
<tr>
<td valign="top"><font ID="Entete">A propos de toi:</font></td>
<td><textarea style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="aprop"><?php
// APropos
if(!Empty($APropos)) echo $APropos;
?></textarea></td>
</tr>
<tr>
<td colspan=2>
    <table border=0 height=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><input type="hidden" name="ope" value=2><input type="submit" style="font-family: Verdana;font-size: 10pt" value="Modifier"></td>
</tr>
</table>
</form>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
<script type="text/javascript">
<!--
// Commandes //////////////////////////////////////////////////////////////////////////////////
document.getElementById("Nom").value="<?php
// Nom
if(!Empty($Nom)) echo str_replace("\'","'",addslashes($Nom));
?>";
document.getElementById("Prenom").value="<?php
// Prénom
if(!Empty($Prenom)) echo str_replace("\'","'",addslashes($Prenom));
?>";
document.getElementById("Adresse").value="<?php
// Adresse
if(!Empty($Adresse)) echo str_replace("\'","'",addslashes($Adresse));
?>";
document.getElementById("Ville").value="<?php
// Ville
if(!Empty($Ville)) echo str_replace("\'","'",addslashes($Ville));
?>";
document.getElementById("Postal").value="<?php
// Postal
if(!Empty($Postal)) echo str_replace("\'","'",addslashes($Postal));
?>";
document.getElementById("Email").value="<?php
// Email
if(!Empty($Email)) echo str_replace("\'","'",addslashes($Email));
?>";
//-->
</script>
</body>
</html>
