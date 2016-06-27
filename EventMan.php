<?php
require("Package.php");
$Chp = "15";
$Clf = $_GET['Clf'];
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
            $aDate = getdate();
            mysql_free_result($Result);
            if(!Empty($ope))
            {   // Suppression /////////////////////////////////////////////////////////////////////////////////////////////////////////
                $Query = "SELECT EVE_Flyer FROM Evenements WHERE EVE_EventID = $eveid";
                $Result = mysql_query(trim($Query),$Link);
                if(mysql_num_rows($Result) != 0)
                {   $aRow = mysql_fetch_array($Result);
                    if(!Empty($aRow["EVE_Flyer"]))
                    {   // Supprime le flyer du serveur
                        @unlink(GetSrvFlyFolder().trim($aRow["EVE_Flyer"]));
                    }
                    mysql_free_result($Result);
                }
                $Query = "DELETE FROM Presents WHERE PRE_EventID = $eveid";
                mysql_query(trim($Query),$Link);
                $Query = "DELETE FROM Evenements WHERE EVE_EventID = $eveid";
                if(mysql_query(trim($Query),$Link))
                {   // Supprime cet événement dans les albums photos liés à ce dernier
                    $Query = "UPDATE Albums SET ALB_EventID = 0 WHERE ALB_EventID = $eveid";
                    mysql_query(trim($Query),$Link);
                    $bSuppRes = true;
                }
                else $bSuppRes = false;
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
<title>Le Classico: Gestion des Ev&eacute;nements</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
textarea {
    width: 259px;
    min-width: 259px;
    max-width: 259px;
    height: 48px;
    min-height: 48px;
    max-height: 48px;
    overflow-y: scroll;
    resize: none;
}
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
#Title {font-size: 22pt; font-family: Impact,Verdana,Lucida; color: yellow}
#InTitle {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: black}
#Content {font-size: 10pt; font-family: Verdana,Lucida,Courier; color: black}
</style>
<script type="text/javascript">
<!--
// Variables //////////////////////////////////////////////////////////////////////////////////
var sNoSave="Tu n'as pas sauvegardé les modifications apportées à\nl'événement sélectionné! Veux-tu continuer malgré tout?";
var sNoFlySave="Tu n'as pas sauvegardé les modifications apportées au flyer\nde l'événement sélectionné! Veux-tu continuer malgré tout?";
var sNoFlyer="Saisi le chemin du flyer à ajouter...";
var sNoDate="AAAA-MM-JJ";
var sNewLine='\n';
var bNewEve=false;
var bLock=false;
var iSelEve=0;
var bFlyMod=false;
// LockEventManager ///////////////////////////////////////////////////////////////////////////
function LockEventManager()
{   document.getElementById("EveNom").disabled="disabled";
    document.getElementById("EveDate").disabled="disabled";
    document.getElementById("EveLieu").disabled="disabled";
    document.getElementById("EveRemark").disabled="disabled";
    document.getElementById("EveFlyer").disabled="disabled";
    document.getElementById("BtnModFlyer").disabled="disabled";
    document.getElementById("BtnVoiFlyer").disabled="disabled";
    document.getElementById("BtnSupp").disabled="disabled";
    bNewEve=false;
    document.getElementById("LockHidden").value=1;
}
// ModifyEveInfo //////////////////////////////////////////////////////////////////////////////
function ModifyEveInfo()
{   if(document.getElementById("EveNom").value!="")
    {   if(document.getElementById("EveLieu").value!="")
        {   if(document.getElementById("EveDate").value!="")
            {   if(document.getElementById("EveDate").value!=sNoDate)
                {   document.getElementById("ModEveID").value=document.getElementById("EveID").value;
                    document.getElementById("ModEveNom").value=document.getElementById("EveNom").value;
                    document.getElementById("ModEveDate").value=document.getElementById("EveDate").value;
                    document.getElementById("ModEveLieu").value=document.getElementById("EveLieu").value;
                    document.getElementById("ModEveRemark").value=document.getElementById("EveRemark").value;
                    if(document.getElementById("FlyHidden").value!=0) document.getElementById("ModEveFlyer").value="";
                    else document.getElementById("ModEveFlyer").value=document.getElementById("EveFlyer").value;
                    //
                    LockEventManager();
                    //
                    //if((document.getElementById("FlyHidden").value!=1)&&(document.getElementById("ModEveFlyer").value!=sNoFlyer))
                    //{   top.EvntStatus.location.href="EventStat.php?res=1&Clf=<?php echo $Clf; ?>";
                    //}
                    //else top.EvntStatus.location.href="EventStat.php?res=19&Clf=<?php echo $Clf; ?>";
                    top.EvntStatus.location.href="EventStat.php?res=19&Clf=<?php echo $Clf; ?>";
                    return true;
                }
                else alert("Date de l'événement non saisi!!!");
            }
            else alert("Date de l'événement vide!!!");
        }
        else alert("Lieu de l'événement vide!!!");
    }
    else alert("Nom de l'événement vide!!!");
    return false;
}
// CreateNewEvent /////////////////////////////////////////////////////////////////////////////
function CreateNewEvent()
{   if(bNewEve)
    {   if(document.getElementById("EveNom").value!="")
        {   if(document.getElementById("EveLieu").value!="")
            {   if(document.getElementById("EveDate").value!="")
                {   if(document.getElementById("EveDate").value!=sNoDate)
                    {   document.getElementById("NewEveNom").value=document.getElementById("EveNom").value;
                        document.getElementById("NewEveDate").value=document.getElementById("EveDate").value;
                        document.getElementById("NewEveLieu").value=document.getElementById("EveLieu").value;
                        document.getElementById("NewEveRemark").value=document.getElementById("EveRemark").value;
                        document.getElementById("NewEveFlyer").value=document.getElementById("EveFlyer").value;
                        //
                        LockEventManager();
                        //
                        //if(document.getElementById("NewEveFlyer").value!=sNoFlyer) top.EvntStatus.location.href="EventStat.php?res=1&Clf=<?php echo $Clf; ?>";
                        //else top.EvntStatus.location.href="EventStat.php?res=20&Clf=<?php echo $Clf; ?>";
                        top.EvntStatus.location.href="EventStat.php?res=20&Clf=<?php echo $Clf; ?>";
                        return true;
                    }
                    else alert("Date de l'événement non saisi!!!");
                }
                else alert("Date de l'événement vide!!!");
            }
            else alert("Lieu de l'événement vide!!!");
        }
        else alert("Nom de l'événement vide!!!");
        return false;
    }
    else
    {   if((document.getElementById("BtnModif").disabled!="")||(confirm(sNoSave)))
        {   document.getElementById("EveID").value=0;
            document.getElementById("EveNom").disabled="";
            document.getElementById("EveNom").value="";
            document.getElementById("EveDate").disabled="";
            document.getElementById("EveDate").value=sNoDate;
            document.getElementById("EveLieu").disabled="";
            document.getElementById("EveLieu").value="";
            document.getElementById("EveRemark").disabled="";
            document.getElementById("EveRemark").value="";
            document.getElementById("EveFlyer").disabled="disabled";
            //document.getElementById("EveFlyer").value=sNoFlyer;
            document.getElementById("BtnModif").disabled="disabled";
            document.getElementById("BtnSupp").value="Annuler";
            document.getElementById("BtnSupp").disabled="";
            document.getElementById("BtnNew").value=" Créer ";
            //
            document.getElementById("BtnVoiFlyer").disabled="disabled";
            document.getElementById("BtnModFlyer").disabled="disabled";
            bNewEve=true;
        }
    }
    return false;
}
// ChgEventList ///////////////////////////////////////////////////////////////////////////////
function ChgEventList()
{   var i=0;
    var bFind=false;
    var sRemark="";
    var bChange=false;
    if(!bNewEve)
    {   if(bFlyMod)
        {   if(confirm(sNoFlySave))
            {   bChange=true;
                bFlyMod=false;
                document.getElementById("BtnNew").disabled="";
            }
        }
        else if((document.getElementById("BtnModif").disabled!="")||(confirm(sNoSave))) bChange=true;
        if(bChange)
        {   bLock=true;
            switch(document.getElementById("EveList").selectedIndex)
            {   <?php
                $CntEve = 0;
                $Query = "SELECT EVE_EventID,EVE_Nom,EVE_Lieu,EVE_Date,EVE_Flyer,EVE_Remark FROM Evenements WHERE EVE_Pseudo = '".addslashes($Camarade)."' ORDER BY EVE_Date DESC";
                $Result = mysql_query(trim($Query),$Link);
                while($aRow = mysql_fetch_array($Result))
                {   // Tant qu'il y a des événements
                ?>case <?php echo $CntEve; ?>:
                {   iSelEve=<?php echo $CntEve; ?>;
                    document.getElementById("EveNom").disabled="";
                    document.getElementById("EveDate").disabled="";
                    document.getElementById("EveLieu").disabled="";
                    document.getElementById("EveRemark").disabled="";
                    document.getElementById("EveFlyer").disabled="";
                    //
                    <?php
                    if(!Empty($aRow["EVE_Flyer"]))
                    {   // Flyer
                    ?>
                    document.getElementById("BtnVoiFlyer").disabled="";
                    if(document.getElementById("LockHidden").value!=1) document.getElementById("BtnModFlyer").disabled="";
                    else document.getElementById("BtnModFlyer").disabled="disabled";
                    document.getElementById("FlyHidden").value=1;
                    <?php
                        // Flyer
                    }
                    else
                    {   // Pas Flyer
                    ?>
                    document.getElementById("BtnVoiFlyer").disabled="disabled";
                    document.getElementById("BtnModFlyer").disabled="disabled";
                    document.getElementById("FlyHidden").value=0;
                    <?php
                        // Pas Flyer
                    }
                    ?>
                    document.getElementById("ModEveChgFly").value=0;
                    //
                    document.getElementById("EveID").value=<?php echo $aRow["EVE_EventID"]; ?>;
                    document.getElementById("EveNom").value="<?php echo str_replace("\'","'",addslashes($aRow["EVE_Nom"])); ?>";
                    document.getElementById("EveDate").value="<?php echo $aRow["EVE_Date"]; ?>";
                    document.getElementById("EveLieu").value="<?php echo str_replace("\'","'",addslashes($aRow["EVE_Lieu"])); ?>";
                    sRemark="<?php
                    if(!Empty($aRow["EVE_Remark"])) echo str_replace("\r\n","\"+sNewLine;\n\t\tsRemark+=\"",str_replace("\'","'",addslashes($aRow["EVE_Remark"])));
                    ?>";
                    //document.getElementById("EveFlyer").value="<?php
                    if(!Empty($aRow["EVE_Flyer"])) echo $aRow["EVE_Flyer"];
                    else echo "Saisi le chemin du flyer à ajouter...";
                    ?>";
                    document.getElementById("EveRemark").value=sRemark;
                    //
                    document.getElementById("FlyFile").value="<?php
                    if(!Empty($aRow["EVE_Flyer"])) echo urlencode(base64_encode($aRow["EVE_Flyer"]));
                    ?>";
                    document.getElementById("FlyEve").value="<?php
                    if(!Empty($aRow["EVE_Flyer"])) echo urlencode(base64_encode($aRow["EVE_Nom"]));
                    ?>";
                    document.getElementById("FlyWidth").value=<?php
                    if(!Empty($aRow["EVE_Flyer"]))
                    {   // Récupère la taille de l'image
                        $aSize = @getimagesize(GetSrvFlyFolder().trim($aRow["EVE_Flyer"]));
                        if((!Empty($aSize[0]))&&(!Empty($aSize[1])))
                        {   if($aSize[0] < 250) echo "250";
                            else echo $aSize[0];
                        }
                        else echo "0";
                    }
                    else echo "0";
                    ?>;
                    document.getElementById("FlyHeight").value=<?php
                    if(!Empty($aRow["EVE_Flyer"]))
                    {   // Récupère la taille de l'image
                        $aSize = @getimagesize(GetSrvFlyFolder().trim($aRow["EVE_Flyer"]));
                        if((!Empty($aSize[0]))&&(!Empty($aSize[1]))) echo $aSize[1];
                        else echo "0";
                    }
                    else echo "0";
                    ?>;
                    document.getElementById("FlyYear").value=<?php echo substr($aRow["EVE_Date"],0,4); ?>;
                    document.getElementById("FlyMonth").value=<?php
                    $aMnDy = sscanf(substr($aRow["EVE_Date"],5,2),"%d");
                    echo $aMnDy[0];
                    ?>;
                    document.getElementById("FlyDay").value=<?php
                    $aMnDy = sscanf(substr($aRow["EVE_Date"],8,10),"%d");
                    echo $aMnDy[0];
                    ?>;
                    //
                    document.getElementById("SuppEveID").value=<?php echo $aRow["EVE_EventID"]; ?>;
                    //
                    document.getElementById("FlyEveID").value=<?php echo $aRow["EVE_EventID"]; ?>;
                    //
                    if(document.getElementById("LockHidden").value!=1)
                    {   document.getElementById("BtnSupp").disabled="";
                        document.getElementById("BtnModif").disabled="disabled";
                        document.getElementById("BtnNew").value="Nouveau";
                        document.getElementById("BtnSupp").value="Supprimer";
                    }
                    break;
                }
                <?php
                    // Tant qu'il y a des événements
                    $CntEve++;
                    if(strlen(trim($aRow["EVE_Nom"])) <= 11) $aEveInfo[] = substr($aRow["EVE_Date"],8,10)."/".substr($aRow["EVE_Date"],5,2)."/".substr($aRow["EVE_Date"],2,2)." - ".str_replace($aSearch,$aReplace,trim($aRow["EVE_Nom"]));
                    else $aEveInfo[] = substr($aRow["EVE_Date"],8,10)."/".substr($aRow["EVE_Date"],5,2)."/".substr($aRow["EVE_Date"],2,2)." - ".str_replace($aSearch,$aReplace,substr(trim($aRow["EVE_Nom"]),0,11))."...";
                }
                mysql_free_result($Result);
                ?>default:
                {   document.getElementById("EveNom").disabled="disabled";
                    document.getElementById("EveDate").disabled="disabled";
                    document.getElementById("EveLieu").disabled="disabled";
                    document.getElementById("EveRemark").disabled="disabled";
                    document.getElementById("EveFlyer").disabled="disabled";
                    //
                    document.getElementById("EveID").value=0;
                    document.getElementById("EveNom").value="";
                    document.getElementById("EveDate").value=sNoDate;
                    document.getElementById("EveLieu").value="";
                    document.getElementById("EveRemark").value="";
                    //document.getElementById("EveFlyer").value=sNoFlyer;
                    document.getElementById("FlyHidden").value=0;
                    //
                    document.getElementById("FlyFile").value="";
                    document.getElementById("FlyEve").value="";
                    document.getElementById("FlyWidth").value=0;
                    document.getElementById("FlyHeight").value=0;
                    document.getElementById("FlyYear").value=0;
                    document.getElementById("FlyMonth").value=0;
                    document.getElementById("FlyDay").value=0;
                    //
                    document.getElementById("SuppEveID").value=0;
                    //
                    document.getElementById("FlyEveID").value=0;
                    //
                    document.getElementById("NewEveNom").value="";
                    document.getElementById("NewEveDate").value="";
                    document.getElementById("NewEveLieu").value="";
                    document.getElementById("NewEveRemark").value="";
                    document.getElementById("NewEveFlyer").value="";
                    //
                    document.getElementById("ModEveID").value=0;
                    document.getElementById("ModEveNom").value="";
                    document.getElementById("ModEveDate").value="";
                    document.getElementById("ModEveLieu").value="";
                    document.getElementById("ModEveRemark").value="";
                    document.getElementById("ModEveFlyer").value="";
                    document.getElementById("ModEveChgFly").value=0;
                    //
                    if(document.getElementById("LockHidden").value!=1)
                    {   document.getElementById("BtnVoiFlyer").disabled="disabled";
                        document.getElementById("BtnModFlyer").disabled="disabled";
                        document.getElementById("BtnModif").disabled="disabled";
                        document.getElementById("BtnSupp").disabled="disabled";
                        document.getElementById("BtnSupp").value="Supprimer";
                        document.getElementById("BtnNew").value="Nouveau";
                    }
                    break;
                }
            }
            bLock=false;
        }
        else document.getElementById("EveList").selectedIndex=iSelEve;
    }
}
// ConfirmSuppEve ///////////////////////////////////////////////////////////////////////////////
function ConfirmSuppEve()
{   if(bNewEve)
    {   bNewEve=false;
        ChgEventList();
        if(document.getElementById("FlyHidden").value==1) document.getElementById("EveFlyer").disabled="";
        return false;
    }
    else if(confirm("Es-tu sûr de vouloir supprimer l'événement sélectionné ?"))
    {   top.EvntStatus.location.href="EventStat.php?res=16&Clf=<?php echo $Clf; ?>";
        return true;
    }
    return false;
}
// ChangeEveInfo ////////////////////////////////////////////////////////////////////////////////
function ChangeEveInfo()
{   if((!bLock)&&(document.getElementById("LockHidden").value!=1))
    {   if(!bNewEve)
        {   document.getElementById("BtnModif").disabled="";
            document.getElementById("EveFlyer").disabled="disabled";
            document.getElementById("BtnModFlyer").disabled="disabled";
        }
        else document.getElementById("BtnSupp").disabled="";
    }
}
// ChangeEveFlyer ///////////////////////////////////////////////////////////////////////////////
function ChangeEveFlyer()
{   if((!bLock)&&(document.getElementById("LockHidden").value!=1))
    {   if(!bNewEve)
        {   document.getElementById("BtnVoiFlyer").disabled="disabled";
            document.getElementById("BtnModFlyer").disabled="";
            document.getElementById("EveNom").disabled="disabled";
            document.getElementById("EveDate").disabled="disabled";
            document.getElementById("EveLieu").disabled="disabled";
            document.getElementById("EveRemark").disabled="disabled";
            document.getElementById("BtnNew").disabled="disabled";
            document.getElementById("BtnSupp").disabled="disabled";
            bFlyMod=true;
        }
    }
}
// OnVoirFlyer //////////////////////////////////////////////////////////////////////////////////
function OnVoirFlyer()
{   var iHigh=0;
    var WndFlyer;
    if((document.getElementById("FlyWidth").value==0)||(document.getElementById("FlyHeight").value==0))
    {   WndFlyer=window.open("Flyer.php?fly="+document.getElementById("FlyFile").value+"&sr="+document.getElementById("FlyEve").value+"&yr="+document.getElementById("FlyYear").value+"&mn="+document.getElementById("FlyMonth").value+"&dy="+document.getElementById("FlyDay").value,"WndFlyer","left=0,top=0,width=400,height=300,scrollbars=1,resizable=1");
        WndFlyer.focus();
    }
    else
    {   iHigh=(document.getElementById("FlyHeight").value*1)+70;
        WndFlyer=window.open("Flyer.php?fly="+document.getElementById("FlyFile").value+"&sr="+document.getElementById("FlyEve").value+"&yr="+document.getElementById("FlyYear").value+"&mn="+document.getElementById("FlyMonth").value+"&dy="+document.getElementById("FlyDay").value,"WndFlyer","left=0,top=0,width="+document.getElementById("FlyWidth").value+",height="+iHigh+",resizable=1");
        WndFlyer.focus();
    }
}
// OnModifyFlyer /////////////////////////////////////////////////////////////////////////////////
function OnModifierFlyer()
{   document.getElementById("ModEveChgFly").value=document.getElementById("FlyHidden").value;
    top.EvntStatus.location.href="EventStat.php?res=1&Clf=<?php echo $Clf; ?>";
    return true;
}
<?php
if(!Empty($ope))
{   // Résultat de la suppression
    if($bSuppRes)
    {   // Réussi
?>
// Commandes //////////////////////////////////////////////////////////////////////////////////////
top.EvntStatus.location.href="EventStat.php?res=18&Clf=<?php echo $Clf; ?>";
<?php
        // Réussi
    }
    else
    {   // Echec
?>
// Commandes //////////////////////////////////////////////////////////////////////////////////////
top.EvntStatus.location.href="EventStat.php?res=17&Clf=<?php echo $Clf; ?>";
<?php
        // Echec
    }
    // Résultat de la suppression
}
?>
// Initialize /////////////////////////////////////////////////
function Initialize()
{
    // Modifie la page si Netscape
    if (navigator.appName!="Microsoft Internet Explorer")
    {   
        // Merci IE!! :p
        document.getElementById("EveNom").style.marginTop="1px";
        document.getElementById("EveDate").style.marginTop="1px";
        document.getElementById("EveLieu").style.marginTop="1px";

        document.getElementById("EveNom").style.marginBottom="1px";
        document.getElementById("EveDate").style.marginBottom="1px";
        document.getElementById("EveLieu").style.marginBottom="1px";
    }
}
-->
</script>
<?php
mysql_close($Link);
?>
</head>
<body bgcolor="#ffffff" style="margin-top: 0px;margin-left: 10px" onload="Initialize()">
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- ******************************************************************************************************************************** EVENT MANAGER -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/PuceLC.gif"></td>
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td>
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/TitConHG.jpg"></td>
        </tr>
        <tr>
        <td bgcolor="#ff0000">
            <table border=0 height=18 cellspacing=0 cellpadding=0>
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
    <td width="100%" bgcolor="#ff0000"><font ID="BigTitle">&nbsp;<b>Gestion des Ev&eacute;nements</b></font></td>
    <td>
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/TitConHD.jpg"></td>
        </tr>
        <tr>
        <td bgcolor="#ff0000">
            <table border=0 height=18 cellspacing=0 cellpadding=0>
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
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table><br>
    <font face="Verdana,Lucida,Courier" size=2>Tu as un &eacute;v&eacute;nement &agrave; annoncer ? Nommes le, dates le, ajoutes y un flyer au format
     <b>JPEG</b> (*<b>.jpg</b>), <b>GIF</b> (*<b>.gif</b>) ou <b>PNG</b> (*<b>.png</b>), si toute fois tu en as un, et c'est tout... Enfin, il te faut
     quand m&ecirc;me l'enregistrer!!<br><br></font>
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ff8000">
<tr>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/DosEvent.jpg"></td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%"><font ID="Title">Tes Ev&eacute;nements</font></td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
<tr>
<td width=2 bgcolor="#ff8000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/InOranHG.jpg"></td>
<td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/InOranHD.jpg"></td>
<td width=2 bgcolor="#ff8000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=2 bgcolor="#ff8000">
    <table border=0 width=2 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#e4e4e4">
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
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=180>
    <table border=0 width=180 cellspacing=0 cellpadding=0>
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
<td width="100%">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
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
<td width=5>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5 bgcolor="#e4e4e4">
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=2 bgcolor="#ff8000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td bgcolor="#ff8000">
    <table border=0 height=20 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td align="center" bgcolor="#bacc9a"><font ID="InTitle">LISTE</font></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td align="center" bgcolor="#bacc9a"><font ID="InTitle">PROPRIETES</font></td>
<td bgcolor="#bacc9a"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td bgcolor="#ff8000">
    <table border=0 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td valign="top" bgcolor="#d8e1c6">
    <table border=0 width=180 cellspacing=0 cellpadding=0>
    <tr>
    <td align="center"><input type="hidden" ID="LockHidden" value=0><select style="font-size: 7pt; font-family: Verdana,Lucida,Courier; color: black" ID="EveList" onchange="javascript:ChgEventList()" size=10>
    <?php
    if(count($aEveInfo) != 0)
    {   foreach($aEveInfo as $EveInfo)
        {   // Tant qu'il y a des événements
    ?><option><?php echo $EveInfo; ?></option>
    <?php
            // Tant qu'il y a des événements
        }
    }
    else echo "<option>Pas d'Evénement...</option>";
    ?><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
    </select></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=24 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td bgcolor="#bacc9a">
        <table border=0 height=46 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6">
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=120>
            <table border=0 width=120 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Nom:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><input type="hidden" ID="EveID"><input type="text" ID="EveNom" onchange="javascript:ChangeEveInfo()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: black" maxlength=50 size=40 disabled></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=120>
            <table border=0 width=120 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Date:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><input type="text" ID="EveDate" onchange="javascript:ChangeEveInfo()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: black" maxlength=10 size=20 value="AAAA-MM-JJ" disabled></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=120>
            <table border=0 width=120 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Lieu:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><input type="text" ID="EveLieu" onchange="javascript:ChangeEveInfo()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: black" maxlength=40 size=40 disabled></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" valign="top" width=120>
            <table border=0 width=120 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Remarques:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="left" width="100%"><textarea ID="EveRemark" onchange="javascript:ChangeEveInfo()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier" disabled></textarea></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td bgcolor="#bacc9a">
        <table border=0 height=2 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 height=3 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width="100%">
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><form action="EventAct.php?Clf=<?php echo $Clf; ?>" target="EvntAction" method="post">
        <input type="hidden" ID="ModEveID" name="eveid">
        <input type="hidden" ID="ModEveNom" name="evenm">
        <input type="hidden" ID="ModEveDate" name="evedate">
        <input type="hidden" ID="ModEveLieu" name="evelieu">
        <input type="hidden" ID="ModEveRemark" name="evermk">
        <input type="hidden" ID="ModEveFlyer" name="evefly">
        <input type="hidden" name="ope" value=1>
        <input type="submit" ID="BtnModif" style="font-family: Verdana;font-size: 8pt" onclick="return ModifyEveInfo()" value="Modifier" disabled>
        </form></td>
        <td width=5>
            <table border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><form action="EventMan.php?Clf=<?php echo $Clf; ?>" method="post">
        <input type="hidden" ID="SuppEveID" name="eveid">
        <input type="hidden" name="ope" value=1>
        <input type="submit" ID="BtnSupp" style="font-family: Verdana;font-size: 8pt" onclick="return ConfirmSuppEve()" value="Supprimer" disabled>
        </form></td>
        <td width=5>
            <table border=0 width=5 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><form action="EventAct.php?Clf=<?php echo $Clf; ?>" target="EvntAction" method="post">
        <input type="hidden" ID="NewEveNom" name="evenm">
        <input type="hidden" ID="NewEveDate" name="evedate">
        <input type="hidden" ID="NewEveLieu" name="evelieu">
        <input type="hidden" ID="NewEveRemark" name="evermk">
        <input type="hidden" ID="NewEveFlyer" name="evefly">
        <input type="hidden" name="ope" value=2>
        <input type="submit" ID="BtnNew" style="font-family: Verdana;font-size: 8pt" onclick="return CreateNewEvent()" value="Nouveau">
        </form></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td>
        <table border=0 height=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td bgcolor="#bacc9a">
        <table border=0 height=3 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td bgcolor="#bacc9a">
        <form action="EventAct.php?Clf=<?php echo $Clf; ?>" enctype="multipart/form-data" target="EvntAction" method="post">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td align="left" width=120>
            <table border=0 width=120 cellspacing=0 cellpadding=0>
            <tr>
            <td><font ID="Content"><b>&bull;&nbsp;Flyer:</b></font></td>
            </tr>
            </table>
        </td>
        <td align="right" width="100%"><input type="hidden" ID="FlyHidden"><input type="file" ID="EveFlyer" name="evefly" onchange="javascript:ChangeEveFlyer()" style="font-size: 9pt; font-family: Verdana,Lucida,Courier; color: black" size=27 disabled></td>
        </tr>
        <tr>
        <td>
            <table border=0 height=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td align="right" width="100%">
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td><input type="hidden" ID="FlyFile">
            <input type="hidden" ID="FlyEve">
            <input type="hidden" ID="FlyWidth">
            <input type="hidden" ID="FlyHeight">
            <input type="hidden" ID="FlyYear">
            <input type="hidden" ID="FlyMonth">
            <input type="hidden" ID="FlyDay">
            <input type="button" ID="BtnVoiFlyer" style="font-family: Verdana;font-size: 8pt" onclick="javascript:OnVoirFlyer()" value="Voir Flyer" disabled></td>
            <td width=5>
                <table border=0 width=5 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td><input type="hidden" ID="ModEveChgFly" name="evechgf" value=0>
            <input type="hidden" ID="FlyEveID" name="eveid">
            <input type="hidden" name="ope" value=3>
            <input type="submit" ID="BtnModFlyer" onclick="return OnModifierFlyer()" style="font-family: Verdana;font-size: 8pt" value="Modifier Flyer" disabled></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
        </form>
    </td>
    </tr>
    </table>
</td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td bgcolor="#ff8000">
    <table border=0 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
<tr>
<td bgcolor="#ff8000">
    <table border=0 height=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#e4e4e4"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td bgcolor="#ff8000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#e4e4e4">
<tr>
<td width=2 bgcolor="#ff8000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/InOranBG.jpg"></td>
<td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td width=5><img src="<?php echo GetFolder(); ?>/Images/InOranBD.jpg"></td>
<td width=2 bgcolor="#ff8000">
    <table border=0 width=2 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table>
<table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ff8000">
<tr>
<td valign="bottom"><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
<td width="100%">
    <table border=0 width="100%" height=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td valign="bottom"><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
</tr>
</table><br>
<font face="Verdana,Lucida,Courier" size=2>Tout type d'&eacute;v&eacute;nement peut &ecirc;tre d&eacute;fini ici, comme par exemple un rendez-vous
 sur le forum de discussion du site, pour ceux qui sont loin de chez eux, perdus sur une &icirc;le au milieu de l'oc&eacute;an indien...
 C'est un exemple!!</font>
<!-- *********************************************************************************************************************************************** -->
</td>
</tr>
</table>
</body>
</html>
