<?php
require("Package.php");
$Chp = "2";
$Clf = $_GET['Clf'];
$Cam = $_GET['Cam'];
if(!Empty($Cam)) $Cam = base64_decode(urldecode($Cam));
$nccf = $_POST['nccf'];
$cnccf = $_POST['cnccf'];
$ope = $_POST['ope'];
$nm = $_POST['nm'];
$prnm = $_POST['prnm'];
$sx = $_POST['sx'];
$dtns = $_POST['dtns'];
$adrs = $_POST['adrs'];
$twn = $_POST['twn'];
$cpst = $_POST['cpst'];
$mail = $_POST['mail'];
$hobbi = $_POST['hobbi'];
$aprop = $_POST['aprop'];
$abo = $_POST['abo'];
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
        if((!Empty($Cam))&&(!strcmp($Cam,$Camarade))) unset($Cam);
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
            else if($ope == 2)
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
            else if($ope == 3)
            {   // Désabonnement
                if((!Empty($abo))&&(strcmp(trim($abo),"")))
                {   if(!strcmp(trim($abo),GetWebmaster()))
                    {   mysql_close($Link);
                        $Msg = "Impossible de se désabonner de <b>Webmaster</b>... il est trop fort!";
                        include("Message.php");
                        die();
                    }
                    $Query = "DELETE FROM Abonnements WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."') AND ABO_Camarade LIKE '".addslashes($abo)."'";
                    if(!mysql_query(trim($Query),$Link))
                    {   mysql_close($Link);
                        $Msg = "Echec de la mise &agrave; jour de tes abonnements! Contact le <font color=\"#808080\">Webmaster</font>!";
                        include("Message.php");
                        die();
                    }
                }
            }
            else
            {   // Abonnement
                if((!Empty($abo))&&(strcmp(trim($abo),"")))
                {   $Query = "INSERT INTO Abonnements (ABO_Pseudo,ABO_Camarade) VALUES ('".addslashes($Camarade)."','".addslashes($abo)."')";
                    if(!mysql_query(trim($Query),$Link))
                    {   mysql_close($Link);
                        $Msg = "Echec de la mise &agrave; jour de tes abonnements! Contact le <font color=\"#808080\">Webmaster</font>!";
                        include("Message.php");
                        die();
                    }
                }
            }
        }
        // Lecture des infos personnels/d'un camarade
        $Abonne = false;
        if(Empty($Cam))
            $Query = "SELECT * FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        else {
            $Query = "SELECT 'X' FROM Abonnements WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."') AND ABO_Camarade = '".addslashes($Cam)."'";
            $Result = mysql_query(trim($Query),$Link);
            $Abonne = mysql_num_rows($Result) > 0;
            mysql_free_result($Result);
            //
            $Query = "SELECT * FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Cam)."')";
        }
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
            $Msg = "Ton pseudo ou celui de ton camarade est inconnu!";
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
<title>Le Classico: Profile</title>
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
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
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
    <td width="100%" bgcolor="#ff0000" nowrap><font ID="BigTitle">&nbsp;<b>Profile<?php
    if(!Empty($Cam))
    {   // Profile d'un camarade
        echo ":</b></font>&nbsp;<font ID=\"Title\" style=\"color:yellow;font-size:23pt\">".stripslashes($Cam)."</font>";
    }
    else echo "</b></font>";
    ?></td>
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
<font face="Verdana,Lucida,Courier" size=2><?php
if(Empty($Cam))
{   // Son profile
?>Configures les informations suivantes afin que d'autres camarades puissent en savoir plus sur toi, ou
 tu peux &eacute;galement ne rien d&eacute;finir ici, &ccedil;a c'est &agrave; toi de voir. Pour ma part cela ne change rien. Par contre, n'oublies pas
 que ces infos seront visibles pour tout le monde! Donc n'en dis pas trop, on ne sait jamais!<br><br>
 Ce qui est s&ucirc;r c'est que c'est un autre camarade qui a cr&eacute;&eacute; ton compte utilisateur, alors si tu ne souhaites pas que ce dernier
 se fasse passer pour toi, et cr&eacute;e un album photos pornographique d'un go&ucirc;t tr&egrave;s douteux ou envoie des messages contenant des
 propositions obsc&egrave;nes &agrave; tous les autres camarades, une seule chose &agrave; faire: Changer son code confidentiel...</font><br><br>
<form action="InfoPerso.php?Clf=<?php echo $Clf; ?>" method="post">
<?php
}
else
{   // Profile d'un camarade
?>Bienvenue sur le profile de ton ou ta camarade <b><?php echo stripslashes($Cam); ?></b>! Retrouve ici ses infos personnelles, les personnes
auxquelles il ou elle est abonné, ainsi que toute son actualité.</font><br><br>
<?php
}
?>
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
    <td nowrap><font face="Verdana,Lucida,Courier" color="#8000ff" size=2><i><?php
    if(Empty($Cam)) echo stripslashes($Camarade);
    else echo stripslashes($Cam);
    ?></i></font></td>
    </tr>
    </table>
</td>
</tr>
<?php
if(Empty($Cam))
{   // Son profile
?>
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
</table><br>
</form>
<?php
}
else
{   // Profile d'un camarade
?>
<tr>
<td valign="top"><font ID="Entete">Privilège création:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php
if(!Empty($aRow["CAM_Admin"])) echo "Oui";
else echo "Non";
?></i></font></td>
</tr>
<tr>
<td valign="top"><font ID="Entete">Last connection:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php
if((!Empty($aRow["CAM_LogDate"]))&&(strcmp(trim($aRow["CAM_LogDate"]),"0000-00-00"))) echo stripslashes($aRow["CAM_LogDate"]);
else echo "Jamais connect&eacute;";
?></i></font></td>
</tr>
<?php
if((!is_null($aRow["CAM_Nom"]))&&(!Empty($aRow["CAM_Nom"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">Nom:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Nom"])); ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_Prenom"]))&&(!Empty($aRow["CAM_Prenom"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">Pr&eacute;nom:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Prenom"])); ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_Sexe"]))&&(!Empty($aRow["CAM_Sexe"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">Sexe:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php
if($aRow["CAM_Sexe"] == 2) echo "Masculin";
else echo "F&eacute;minin";
?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_BornDate"]))&&(strcmp(trim($aRow["CAM_BornDate"]),"0000-00-00")))
{
?>
<tr>
<td valign="top"><font ID="Entete">Date de naissance:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo $aRow["CAM_BornDate"]; ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_Adresse"]))&&(!Empty($aRow["CAM_Adresse"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">Adresse:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Adresse"])); ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_Ville"]))&&(!Empty($aRow["CAM_Ville"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">Ville:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Ville"])); ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_Postal"]))&&(!Empty($aRow["CAM_Postal"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">Code postal:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Postal"])); ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_Email"]))&&(!Empty($aRow["CAM_Email"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">E-mail:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Email"])); ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_Hobbies"]))&&(!Empty($aRow["CAM_Hobbies"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">Passe-temps:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo PrintString(str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_Hobbies"]))); ?></i></font></td>
</tr>
<?php
}
if((!is_null($aRow["CAM_APropos"]))&&(!Empty($aRow["CAM_APropos"])))
{
?>
<tr>
<td valign="top"><font ID="Entete">A Propos de lui/elle:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php echo PrintString(str_replace($aSearch,$aReplace,stripslashes($aRow["CAM_APropos"]))); ?></i></font></td>
</tr>
<?php
}
?>
</table><br><br>
<?php
}
?>
<!-- Abonnements -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5>
    <table border=0 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><img src="/Images/SubMnuHG.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="/Images/SubMnuBG.jpg"></td>
    </tr>
    </table>
</td>
<td width=10>
    <table border=0 height=20 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td height="100%"><img src="/Images/Puce.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><font ID="Title">&nbsp;Abonnements</font></td>
    </tr>
    </table>
</td>
<td width=5>
    <table border=0 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><img src="/Images/SubMnuHD.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="/Images/SubMnuBD.jpg"></td>
    </tr>
    </table>
</td>
</tr>
<tr height=15>
<td colspan=5></td>
</tr>
<tr>
<td></td>
<td></td>
<td colspan=4>
<font face="Verdana,Lucida,Courier" size=2><?php
if(Empty($Cam))
{
?>Tu trouveras ci-dessous la liste des camarades auxquels tu es abonné. Ainsi, il te seras possible
de voir depuis le <a href="index.php?Chp=5&Clf=<?php echo $Clf; ?>" target="_top">fil d'actualité</a> tous ceux qu'ils publient, et cela sans avoir à aller sur chacun de
leur profile.<?php
}
else
{
?>Voici ci-dessous la liste des camarades auxquels <b><?php echo stripslashes($Cam); ?></b> est actuellement abonné(e). Tu as également la
possibilité de t'abonner à son actualité, ou éventuellement de t'en désabonner si cela avait déjà été le cas.<?php
}
?></font>
</td>
</tr>
<tr height=10>
<td colspan=6></td>
</tr>
<tr>
<td></td>
<td></td>
<td colspan=4>
    <form action="InfoPerso.php?Clf=<?php echo $Clf; if(!Empty($Cam)) echo "&Cam=".urlencode(base64_encode($Cam)); ?>" method="post">
    <table border=0 cellspacing=0 cellpadding=0 width="100%">
    <tr>
    <td valign="top" width=125><font ID="Title">&nbsp;Camarades:&nbsp;</font></td>
    <td>
        <select style="width:310px"<?php if(Empty($Cam)) echo " name=\"abo\" onChange=\"document.getElementById('desAbon').disabled = false;\""; ?> size=7>
        <?php
        if(Empty($Cam))
            $Query = "SELECT ABO_Camarade FROM Abonnements WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
        else
            $Query = "SELECT ABO_Camarade FROM Abonnements WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Cam)."')";
        $Result = mysql_query(trim($Query),$Link);
        while($aRow = mysql_fetch_array($Result))
        {   echo "<option>".stripslashes($aRow["ABO_Camarade"])."</option>\n";
        }
        mysql_free_result($Result);
        mysql_close($Link);
        ?></select>
    </td>
    </tr>
    <tr height=10>
    <td></td>
    <td></td>
    </tr><?php
    if(Empty($Cam)) {
    ?>
    <tr>
    <td><input type="hidden" name="ope" value=3></td>
    <td><input type="submit" style="font-family: Verdana;font-size: 10pt" onClick="return confirm('Es-tu sûr de vouloir te désabonner de ce camarade ?')" value="Désabonner" ID="desAbon" disabled></td>
    </tr><?php
    }
    else {
    ?>
    <tr>
    <td colspan=2><hr></td>
    </tr>
    <tr>
    <td colspan=2 align="right">
    <input type="hidden" name="ope" value=<?php echo ($Abonne)? "3":"4"; ?>><input type="hidden" name="abo" value="<?php echo $Cam ?>"><input type="submit" style="font-family: Verdana;font-size: 10pt" value="<?php echo ($Abonne)? "Se désabonner":"S'abonner"; ?>">
    </td>
    </tr><?php
    }
    ?>
    </table>
    </form>
</td>
</tr>
</table><br>
<!-- Publications -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5>
    <table border=0 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><img src="/Images/SubMnuHG.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="/Images/SubMnuBG.jpg"></td>
    </tr>
    </table>
</td>
<td width=10>
    <table border=0 height=20 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td height="100%"><img src="/Images/Puce.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><font ID="Title">&nbsp;Publications</font></td>
    </tr>
    </table>
</td>
<td width=5>
    <table border=0 cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
    <tr>
    <td><img src="/Images/SubMnuHD.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="/Images/SubMnuBD.jpg"></td>
    </tr>
    </table>
</td>
</tr>
</table>
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
