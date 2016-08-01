<?php
require("Package.php");
$Chp = "3";
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
        $Query = "SELECT CAM_Admin FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if($aRow = mysql_fetch_array($Result))
        {   if($aRow["CAM_Admin"] == 1)
            {   mysql_free_result($Result);
                $ope = $_POST['ope'];
                if(!Empty($ope))
                {    $npsd = $_POST['npsd'];
                     $nccf = $_POST['nccf'];
                     $cnccf = $_POST['cnccf'];
                     // Opération d'ajout //////////////////////////////////////////////////////////////////////////////////////////////////////////////
                     if(!strcmp(trim($npsd),""))
                     {   mysql_close($Link);
                         $Msg = "Pseudo non saisi!";
                         include("Message.php");
                         die();
                     }
                     else
                     {   if(!strcmp(trim($nccf),""))
                         {    mysql_close($Link);
                              $Msg = "Code confidentiel non saisi!";
                              include("Message.php");
                              die();
                         }
                         else
                         {    if(!strcmp(trim($cnccf),""))
                              {    mysql_close($Link);
                                   $Msg = "Confirmation du code confidentiel non saisi!";
                                   include("Message.php");
                                   die();
                              }
                              else
                              {   if(strcmp(trim($nccf),trim($cnccf)))
                                  {    mysql_close($Link);
                                       $Msg = "Code confidentiel non confirm&eacute;!";
                                       include("Message.php");
                                       die();
                                  }
                                  else
                                  {   if((strrpos(trim($npsd),'<'))||(strrpos(trim($npsd),'>')))
                                      {    mysql_close($Link);
                                           $Msg = "Les symboles '&lt;' et '&gt;' ne sont pas autoris&eacute; !!";
                                           include("Message.php");
                                           die();
                                      }
                                      else
                                      {   // Recherche d'un camarade ayant le même pseudo
                                          $Query = "SELECT 'X' FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".trim($npsd)."')";
                                          $Result = mysql_query(trim($Query),$Link);
                                          if(mysql_num_rows($Result) != 0)
                                          {   mysql_free_result($Result);
                                              mysql_close($Link);
                                              $Msg = "Le camarade <font color=\"#808080\">".stripslashes(trim($npsd))."</font> existe d&eacute;j&agrave;!";
                                              include("Message.php");
                                              die();
                                          }
                                          else
                                          {   // Ajout d'1 Camarade
                                              $Query = "INSERT INTO Camarades (CAM_Pseudo,CAM_CodeConf) VALUES ('".trim($npsd)."','".trim($nccf)."')";
                                              if($Result = mysql_query(trim($Query),$Link))
                                              {   // Suppression du compte admin
                                                  $Query = "UPDATE Camarades SET CAM_Admin = 0 WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                                  $Result = mysql_query(trim($Query),$Link);
                                                  // Info sur le Forum
                                                  $aDate = getdate();
                                                  $WebmasterMsg = "CECI EST UN MESSAGE DU <b>WEBMASTER</b>! STOP!\nAJOUT D\'UN NOUVEAU CAMARADE!";
                                                  $WebmasterMsg .= " STOP!\nPSEUDO DU NOUVEAU CAMARADE: <b>" . trim($npsd) . "</b>! STOP!\nFIN DU MESSAGE!";
                                                  $WebmasterMsg .= " STOP!...STOP! STOP!";
                                                  $Query = "INSERT INTO Forum (FRM_Pseudo,FRM_Message,FRM_Date,FRM_Time) VALUES ('Webmaster',";
                                                  $Query .= "'$WebmasterMsg','".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."',";
                                                  $Query .= "'".trim($aDate["hours"]).":".trim($aDate["minutes"]).":".trim($aDate["seconds"])."')";
                                                  $Result = mysql_query(trim($Query),$Link);
                                                  mysql_close($Link);
                                                  $Msg = "Le camarade <font color=\"#808080\">".stripslashes(trim($npsd))."</font> a &eacute;t&eacute;";
                                                  $Msg .= " cr&eacute;&eacute; avec succ&eacute;s!...Maintenant si son pseudo ne lui convient pas, et";
                                                  $Msg .= " bien...Tant pis pour lui!";
                                                  $Tpe = "1";
                                                  include("Message.php");
                                                  die();
                                              }
                                              else
                                              {   mysql_close($Link);
                                                  $Msg = "Echec de l'ajout du camarade! Contact le <font color=\"#808080\">Webmaster</font>!";
                                                  include("Message.php");
                                                  die();
                                              }
                                          }
                                      }
                                 }
                             }
                         }
                     }
                }
            }
            else
            {   mysql_close($Link);
                $Msg = "Tu n'as plus les droits pour ajouter un camarade!";
                include("Message.php");
                die();
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
<title>Le Classico: Ajout d'1 Camarade</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
form {padding: 0px; margin-bottom: 0px; border: 0px}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Entete {font-size: 12pt; font-family: Impact,Verdana,Lucida}
</style>
<script type="text/javascript">
<!--
// OnValidate ////////////////////////////////////////////////
function OnValidate() {
    // Check invalid characters: '"<>& and space char
    var pattern = new RegExp(/['"<>& ]/i);
    if (pattern.test(document.getElementById("pseudo").value))
        return false;
    return true;
}
-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ******************************************************************************************************************************** AJOUT CAMARADE -->
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
    <td width="100%" bgcolor="#ff0000" nowrap><font ID="BigTitle">&nbsp;<b>Ajout&nbsp;d'un&nbsp;Camarade</b></font></td>
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
<font face="Verdana,Lucida,Courier" size=2>Il s'agit l&agrave; d'un privil&egrave;ge accord&eacute; &agrave; tous les
 camarades et qui permet comme il est indiqu&eacute;, de cr&eacute;er un nouveau camarade pour la personne de ton choix. Mais <b>ATTENTION</b>!
 Une fois ce camarade cr&eacute;&eacute; tu pourras te brosser pour en cr&eacute;er un nouveau. Alors fais le bon choix...Car ce
 dernier héritera de ce privilège, et ainsi de suite...Capito Pepito ?<br><br>
</font>
<form action="AddMember.php?Clf=<?php echo $Clf; ?>" method="post">
<table border=0 width=300 cellspacing=0 cellpadding=0>
<tr>
<td><font ID="Entete">Pseudo:</font></td>
</tr>
<tr>
<td><input type="text" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" ID="pseudo" name="npsd" maxlength=30></td>
</tr>
<tr>
<td><font ID="Entete">Code confidentiel:</font></td>
</tr>
<tr>
<td><input type="password" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="nccf" maxlength=20></td>
</tr>
<tr>
<td><font ID="Entete">Confirmes son code:</font></td>
</tr>
<tr>
<td><input type="password" style="font-size: 10pt; font-family: Verdana,Lucida,Courier" name="cnccf" maxlength=20></td>
</tr>
<tr>
<td><br><input type="hidden" name="ope" value=1><input type="submit" style="font-family: Verdana;font-size: 10pt" onclick="return OnValidate()" value="Ajouter"></td>
</tr>
</table>
</form>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
</body>
</html>
