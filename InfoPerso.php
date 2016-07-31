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
$pub = $_POST['pub'];
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
        {   switch($ope) {
                case 1:
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
                    break;
                }
                case 2:
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
                    break;
                }
                case 3:
                {   // Désabonnement
                    if((!Empty($abo))&&(strcmp(trim($abo),"")))
                    {   if(!strcmp(trim($abo),GetWebmaster()))
                        {   mysql_close($Link);
                            $Msg = "Impossible de se désabonner de <b>Webmaster</b>... il est trop fort!";
                            include("Message.php");
                            die();
                        }
                        $Query = "UPDATE Abonnements SET ABO_Status = 2, ABO_StatusDate = CURRENT_TIMESTAMP WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."') AND ABO_Camarade LIKE '".addslashes($abo)."'";
                        if(!mysql_query(trim($Query),$Link))
                        {   mysql_close($Link);
                            $Msg = "Echec de la mise &agrave; jour de tes abonnements! Contact le <font color=\"#808080\">Webmaster</font>!";
                            include("Message.php");
                            die();
                        }
                    }
                    break;
                }
                case 4:
                {   // Abonnement
                    if((!Empty($abo))&&(strcmp(trim($abo),"")))
                    {   $Query = "UPDATE Abonnements SET ABO_Status = 0, ABO_StatusDate = CURRENT_TIMESTAMP WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."') AND ABO_Camarade LIKE '".addslashes($abo)."'";
                        if(!mysql_query(trim($Query),$Link)) {
                            $Query = "INSERT INTO Abonnements (ABO_Pseudo,ABO_Camarade) VALUES ('".addslashes($Camarade)."','".addslashes($abo)."')";
                            if(!mysql_query(trim($Query),$Link))
                            {   mysql_close($Link);
                                $Msg = "Echec de la mise &agrave; jour de tes abonnements! Contact le <font color=\"#808080\">Webmaster</font>!";
                                include("Message.php");
                                die();
                            }
                        }
                    }
                    break;
                }
                case 5:
                {   // Change la bannière ou la photo du profile
                    $iStatus = 3;
                    $isBanner = true;
                    if(!Empty($_FILES["banFile"]["name"]))
                        $iStatus = DownloadImageFile($Link,GetSrvProFolder(),"banFile");
                    else if(!Empty($_FILES["proFile"]["name"])) {
                        $iStatus = DownloadImageFile($Link,GetSrvProFolder(),"proFile");
                        $isBanner = false;
                    }
                    if($iStatus > 12) $File = GetPhotoFile($Link,true).GetImageExtension(($isBanner)? "banFile":"proFile");
                    switch($iStatus) {
                        case 14: { // Ok...
                            $Query = "UPDATE Camarades SET CAM_".(($isBanner)? "Banner":"Profile")." = '$File'";
                            $Query .= " WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            if(!mysql_query(trim($Query),$Link)) {
                                mysql_close($Link);
                                @unlink(GetSrvProFolder()."$File");
                                $Msg = "Echec de la mise &agrave; jour de ton profile! Contact le <font color=\"#808080\">Webmaster</font>!";
                                include("Message.php");
                                die();
                            }
                            break;
                        }
                        default:
                        {   mysql_close($Link);
                            if($iStatus == 13) @unlink(GetSrvProFolder()."$File");
                            $Msg = GetResult($iStatus);
                            include("Message.php");
                            die();
                        }
                    }
                    break;
                }
                case 6:
                {   // Retire la bannière
                    $Query = "SELECT CAM_Banner FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    $Result = mysql_query(trim($Query),$Link);
                    if($aRow = mysql_fetch_array($Result))
                    {   $File = $aRow["CAM_Banner"];
                        mysql_free_result($Result);
                    }
                    $Query = "UPDATE Camarades SET CAM_Banner = NULL WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if(!mysql_query(trim($Query),$Link)) {
                        mysql_close($Link);
                        if(!Empty($File)) @unlink(GetSrvProFolder()."$File");
                        $Msg = "Echec durant la mise &agrave; jour de ton profile! Contact le <font color=\"#808080\">Webmaster</font>!";
                        include("Message.php");
                        die();
                    }
                    break;
                }
                case 7:
                {   // Retire la photo du profile
                    $Query = "SELECT CAM_Profile FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    $Result = mysql_query(trim($Query),$Link);
                    if($aRow = mysql_fetch_array($Result))
                    {   $File = $aRow["CAM_Profile"];
                        mysql_free_result($Result);
                    }
                    $Query = "UPDATE Camarades SET CAM_Profile = NULL WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if(!mysql_query(trim($Query),$Link)) {
                        mysql_close($Link);
                        if(!Empty($File)) @unlink(GetSrvProFolder()."$File");
                        $Msg = "Echec durant la mise &agrave; jour de ton profile! Contact le <font color=\"#808080\">Webmaster</font>!";
                        include("Message.php");
                        die();
                    }
                    break;
                }
                case 69:
                {   // Ajoute un commentaire (actualité)
                    $iStatus = AjouteCommentaire($Link,$Camarade,'A',$_POST["act"],"txt");
                    if($iStatus != 15) {
                        mysql_close($Link);
                        $Msg = GetResult($iStatus);
                        include("Message.php");
                        die();
                    }
                    break;
                }
            }
        }
        else if(!Empty($pub)) {
            // Ajoute une publication
            $iStatus = AjoutePublication($Link,$Camarade);
            if($iStatus != 15) {
                mysql_close($Link);
                $Msg = GetResult($iStatus);
                include("Message.php");
                die();
            }
        }
        // Lecture des infos personnels/d'un camarade
        $Abonne = false;
        if(Empty($Cam))
            $Query = "SELECT * FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        else {
            $Query = "SELECT 'X' FROM Abonnements WHERE CAM_Status <> 2 AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."') AND ABO_Camarade = '".addslashes($Cam)."'";
            $Result = mysql_query(trim($Query),$Link);
            $Abonne = mysql_num_rows($Result) > 0;
            mysql_free_result($Result);
            //
            $Query = "SELECT * FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Cam)."')";
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
            $isProfile = false;
            if(is_null($aRow["CAM_Profile"])) $Profile = "Images/".(($Sexe == 1)? "woman":"man").".png";
            else {
                $Profile = "Profiles/".$aRow["CAM_Profile"];
                $isProfile = true;
            }
            $isBanner = false;
            if(is_null($aRow["CAM_Banner"])) $Banner = "Images/banner.png";
            else {
                $Banner = "Profiles/".$aRow["CAM_Banner"];
                $isBanner = true;
            }
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
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/publication.css">
<style type="text/css">
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
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
.corner {
    border-radius: 10px;
    margin-right: 10px;
}
.icon {
    border: 5px solid white;
    border-radius: 15px;
    position: absolute;
    left: 50px;
    width: 100px;
    height: 100px;
}
.minBanner {
    min-width: 100px;
    min-height: 100px;
}
</style>
<script src="Librairies/publication.js"></script>
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
        if(document.getElementById("Pwd")) document.getElementById("Pwd").style.marginTop="1px";
        if(document.getElementById("Cpw")) document.getElementById("Cpw").style.marginTop="1px";
        if(document.getElementById("Nom")) document.getElementById("Nom").style.marginTop="1px";
        if(document.getElementById("Prenom")) document.getElementById("Prenom").style.marginTop="1px";
        if(document.getElementById("Ddn")) document.getElementById("Ddn").style.marginTop="1px";
        if(document.getElementById("Adresse")) document.getElementById("Adresse").style.marginTop="1px";
        if(document.getElementById("Ville")) document.getElementById("Ville").style.marginTop="1px";
        if(document.getElementById("Postal")) document.getElementById("Postal").style.marginTop="1px";
        if(document.getElementById("Email")) document.getElementById("Email").style.marginTop="1px";

        if(document.getElementById("Pwd")) document.getElementById("Pwd").style.marginBottom="1px";
        if(document.getElementById("Cpw")) document.getElementById("Cpw").style.marginBottom="1px";
        if(document.getElementById("Nom")) document.getElementById("Nom").style.marginBottom="1px";
        if(document.getElementById("Prenom")) document.getElementById("Prenom").style.marginBottom="1px";
        if(document.getElementById("Ddn")) document.getElementById("Ddn").style.marginBottom="1px";
        if(document.getElementById("Adresse")) document.getElementById("Adresse").style.marginBottom="1px";
        if(document.getElementById("Ville")) document.getElementById("Ville").style.marginBottom="1px";
        if(document.getElementById("Postal")) document.getElementById("Postal").style.marginBottom="1px";
        if(document.getElementById("Email")) document.getElementById("Email").style.marginBottom="1px";
    }
}
// OnChangePhoto /////////////////////////////////////////////
function OnChangePhoto(banner)
{   if(banner) {
        document.getElementById('banBtn').style.display = 'block';
        document.getElementById('banBtn').value = '<?php if($isBanner) echo "Remplacer";else echo "Ajouter"; ?>';
    }
    else {
        document.getElementById('proBtn').style.display = 'block';
        document.getElementById('proBtn').value = '<?php if($isProfile) echo "Remplacer";else echo "Ajouter"; ?>';
    }
    document.getElementById('formOpe').value = 5;
}
// OnResize //////////////////////////////////////////////////
function OnResize() {
    document.getElementById("Banner").style.width =  (window.innerWidth - 180) + "px";
    document.getElementById("Profile").style.top = (document.getElementById("Banner").height - 50) + "px";
}
// OnValidate ////////////////////////////////////////////////
function OnValidate(ope) {
    if(document.getElementById('formOpe').value == 0)
        document.getElementById('formOpe').value = ope;
    return true;
}
-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px" onload="Initialize()" onresize="OnResize()">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ********************************************************************************************************************************** INFOS PERSO -->
<img class="corner minBanner" ID="Banner" src="<?php echo $Banner; ?>">
<div class="icon" ID="Profile">
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td><img class="corner" width=100 height=100 src="<?php echo $Profile ?>"></td>
    <?php
    if(Empty($Cam)) {
    ?>
    <td>
        <form action="InfoPerso.php?Clf=<?php echo $Clf; ?>" enctype="multipart/form-data" method="post">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><input type="file" onchange="OnChangePhoto(true)" name="banFile" /></td>
        <td><div style="width:10px" /></td>
        <td><input type="submit" ID="banBtn" style="display:<?php if($isBanner) echo 'block';else echo 'none'; ?>" value="Retirer" onclick="OnValidate(6)"></td>
        </tr>
        <tr height=50>
        <td colspan=3><input type="hidden" ID="formOpe" name="ope" value=0></td>
        </tr>
        <tr>
        <td><input type="file" onchange="OnChangePhoto(false)" name="proFile" /></td>
        <td></td>
        <td><input type="submit" ID="proBtn" style="display:<?php if($isProfile) echo 'block';else echo 'none'; ?>" value="Retirer" onclick="OnValidate(7)"></td>
        </tr>
        </table>
        </form>
    </td>
    <?php
    }
    ?>
    </tr>
    </table>
</div>
<table border=0 cellspacing=0 cellpadding=0>
<tr height=70><td></td></tr>
</table>
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
<table border=0 width="100%" cellspacing=0 cellpadding=0>
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
<td><input type="hidden" name="ope" value=1><input type="submit" style="font-family: Verdana;font-size: 10pt" value="Changer"><hr></td>
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
<td valign="top"><font ID="Entete">En ligne:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php
if(!is_null($aRow["CAM_LogDate"])) {
    $logDate = DateTime::createFromFormat("Y-m-d H:i:s",$aRow["CAM_LogDate"]);
    $logDate->add(new DateInterval('PT3600S'));
    $curDate = new DateTime();
    if($logDate > $curDate) echo "Oui";
    else echo "Non";
}
else echo "Non";
?></i></font></td>
</tr>
<tr>
<td valign="top"><font ID="Entete">Last connection:</font></td>
<td><font face="Verdana,Lucida,Courier" size=2><i><?php
if(!is_null($aRow["CAM_LogDate"])) echo substr($aRow["CAM_LogDate"],0,10);
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
de voir depuis le <a href="index.php?Chp=5&Clf=<?php echo $Clf; ?>" style="font-size: 10pt" target="_top">fil d'actualité</a> tous ceux qu'ils publient, et cela sans avoir à aller sur chacun de
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
        if(Empty($Cam)) {
            $Query = "SELECT ABO_Camarade FROM Abonnements WHERE ABO_Status <> 2 AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
            $CurCam = $Camarade;
        }
        else {
            $Query = "SELECT ABO_Camarade FROM Abonnements WHERE ABO_Status <> 2 AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Cam)."')";
            $CurCam = $Cam;
        }
        $Result = mysql_query(trim($Query),$Link);
        while($aRow = mysql_fetch_array($Result))
        {   if(strcmp($CurCam,$aRow["ABO_Camarade"]))
                echo "<option>".stripslashes($aRow["ABO_Camarade"])."</option>\n";
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
</table><br>
<!-- Publications: ENVOI @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=10><div style="width:10px" /></td>
<td width="100%">
    <div class="publicate"><!-- Publication -->
        <form action="InfoPerso.php?Clf=<?php echo $Clf; if(!Empty($Cam)) echo "&Cam=".urlencode(base64_encode($Cam)); ?>" enctype="multipart/form-data" method="post">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td valign="top"><div style="width:110px"><font ID="Title">Ton message:</font></div></td>
        <td><textarea class="message" name="msg"></textarea></td>
        <td rowspan=3 width="100%" align="right"><div class="separator"></div></td>
        <td rowspan=3 valign="bottom"><img src="<?php echo GetFolder(); ?>/Images/DosActu.jpg" style="margin-bottom:5px"><input type="submit" value="Publier"></td>
        </tr>
        <tr>
        <td><input type="radio" name="join" ID="lnkRadio" value=0><font ID="Title">Lien:</font></td>
        <td><input class="lien" type="text" onchange="OnPublicationChange(true)" placeholder="http://" name="lnk"></td>
        <td colspan=2><input type="hidden" name="to" value="<?php echo $Cam; ?>"></td>
        </tr>
        <tr>
        <td><input type="radio" name="join" ID="imgRadio" value=1><font ID="Title">Image:</font></td>
        <td><input type="file" onchange="OnPublicationChange(false)" name="img"></td>
        <td colspan=2><input type="hidden" name="pub" value=1></td>
        </tr>
        </table>
        </form>
    </div>
</td>
</tr>
</table><br>
<!-- PUBLICATIONS ################################################################################################################# -->
<table border=0 width="100%" cellspacing=0 cellpadding=0 ID="Publications">
<tr>
<td width=10><div style="width:10px" /></td>
<td><hr></td>
</tr>
</table>
<!-- ****************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
<script type="text/javascript">
<!--
// Commandes //////////////////////////////////////////////////////////////////////////////////
OnResize();
StartPubListener("<?php echo $Clf; ?>",<?php echo ((Empty($Cam))? "\"".urlencode(base64_encode($Camarade))."\"":"\"".urlencode(base64_encode($Cam))."\""); ?>,4,10,"InfoPerso.php",true);
<?php
if(Empty($Cam)) {
?>
if(document.getElementById("Nom")) document.getElementById("Nom").value="<?php
// Nom
if(!Empty($Nom)) echo str_replace("\'","'",addslashes($Nom));
?>";
if(document.getElementById("Prenom")) document.getElementById("Prenom").value="<?php
// Prénom
if(!Empty($Prenom)) echo str_replace("\'","'",addslashes($Prenom));
?>";
if(document.getElementById("Adresse")) document.getElementById("Adresse").value="<?php
// Adresse
if(!Empty($Adresse)) echo str_replace("\'","'",addslashes($Adresse));
?>";
if(document.getElementById("Ville")) document.getElementById("Ville").value="<?php
// Ville
if(!Empty($Ville)) echo str_replace("\'","'",addslashes($Ville));
?>";
if(document.getElementById("Postal")) document.getElementById("Postal").value="<?php
// Postal
if(!Empty($Postal)) echo str_replace("\'","'",addslashes($Postal));
?>";
if(document.getElementById("Email")) document.getElementById("Email").value="<?php
// Email
if(!Empty($Email)) echo str_replace("\'","'",addslashes($Email));
?>";
<?php
}
?>
//-->
</script>
</body>
</html>
