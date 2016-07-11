<?php
require("Package.php");
$Chp = "5";
$Clf = $_GET['Clf'];
$ope = $_POST['ope'];
$pub = $_POST['pub'];
$Alert = false;
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
            if(!Empty($pub)) {
                // Ajoute une publication
                $iStatus = AjoutePublication($Link,$Camarade);
                if($iStatus != 15) {
                    $Msg = GetResult($iStatus);
                    $Alert = true;
                }
            }
            else if((!Empty($ope))&&($ope==69))
            {   // Ajoute un commentaire
                $iStatus = AjouteCommentaire($Link,$Camarade,'A',$_POST["act"],"txt");
                if($iStatus != 15) {
                    $Msg = GetResult($iStatus);
                    $Alert = true;
                }
            }
            mysql_close($Link);
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
{   //$Msg = "Tu n'est pas connect&eacute;!";
    //include("Message.php");
    //die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Forum Messages</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/publication.css">
<script src="Librairies/publication.js"></script>
<style type="text/css">
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Title {font-size: 12pt; font-family: Impact,Verdana,Lucida}
</style>
</head>
<body bgcolor="#ffffff" style="margin-top: 0px;margin-left: 0px"><?php
if(!Empty($Clf)) {
?>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
<!-- ******************************************************************************************************************* FORUM MSG -->
<!-- Publications: ENVOI @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
<table border=0 width="100%" cellspacing=0 cellpadding=0><?php
if($Alert) {
?>
<tr>
<td width=10><div style="width:10px" /></td>
<td no wrap><font face="Verdana,Lucida,Courier" size=3 color="red"><?php echo $Msg; ?></font></td>
</tr>
<tr height=5>
<td colspan=2></td>
</tr>
<?php
}
?>
<tr>
<td width=10><div style="width:10px" /></td>
<td width="100%">
    <div class="publicate"><!-- Publication -->
        <form action="FrmMsg.php?Clf=<?php echo $Clf; ?>" enctype="multipart/form-data" method="post">
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
        <td colspan=2><input type="hidden" name="to" value=""></td>
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
<!-- PUBLICATIONS ################################################################################################################ -->
<table border=0 width="100%" cellspacing=0 cellpadding=0 ID="Publications">
<tr>
<td width=10><div style="width:10px" /></td>
<td><hr></td>
</tr>
</table>
<!-- ***************************************************************************************************************************** -->
</td>
<td><div style="width:139px"></div></td><!-- Projo -->
</tr>
</table>
<script type="text/javascript">
<!--
// Commandes //////////////////////////////////////////////////////////////////////////////////
StartPubListener("<?php echo "$Clf\",\"".urlencode(base64_encode($Camarade)); ?>",10,10,"FrmMsg.php",false);
//-->
</script><?php
}
else { // Non connecté
?>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td><div style="width:10px"></div></td>
<td no wrap><a href="/index.php?Chp=11" style="font-size:18pt" target="_top">Connectes-toi!</a></td>
</tr>
</table>
<?php
}
?>
</body>
</html>
