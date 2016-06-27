<?php
require("Package.php");
$Clf = $_GET['Clf'];
$aSearch = array("<",">","\"","'");
$aReplace = array("&lt;","&gt;","&quot;","&#39;");
//
$SelFile = "";
$Extension = "";
$Tri = 0;
$Artiste = "";
$Album = "";
$Morceau = "";
$Pseudo = "";
$bPrev = false;
$bNext = false;
$MusicID = 0;
$MscClass = "";
$Note = 0;
$bLoop = false;
$Status = "<font ID='Status'>Pr&ecirc;t</font>";
$bAutoPlay = false;
$Hauteur = 0;
$Source = "";
if(!Empty($Clf)) $Camarade = DistUserKeyId($Clf);
else $Camarade = "";
// Paramètres
if(!Empty($file))
{   $Extension = substr($file,strlen($file)-3);
    $SelFile = substr($file,0,strlen($file)-4);
}
if(!Empty($trcfg)) $Tri = $trcfg;
if(!Empty($mid)) $MusicID = $mid;
if(!Empty($nt)) $Note = $nt;
if(!Empty($htr)) $Hauteur = $htr;
if(!Empty($stt))
{   $Status = stripslashes(base64_decode(urldecode($stt)));
    if(!strpos($Status,"</font>")) $Status = "<font ID='Status'>Pr&ecirc;t</font>";
}
if(!Empty($art)) $Artiste = stripslashes(base64_decode(urldecode($art)));
if(!Empty($alb)) $Album = stripslashes(base64_decode(urldecode($alb)));
if(!Empty($mrc)) $Morceau = stripslashes(base64_decode(urldecode($mrc)));
if(!Empty($psd)) $Pseudo = stripslashes(base64_decode(urldecode($psd)));
if(!Empty($src)) $Source = stripslashes(base64_decode(urldecode($src)));
if(!Empty($cls)) $MscClass = stripslashes(base64_decode(urldecode($cls)));
if((!Empty($prv))&&($prv == 1)) $bPrev = true;
if((!Empty($nxt))&&($nxt == 1)) $bNext = true;
if((!Empty($lp))&&($lp == 1)) $bLoop = true;
if((!Empty($otp))&&($otp == 1)) $bAutoPlay = true;
//}
//else $Status = "<font ID='Status'>Tu n'est pas connect&eacute;!</font>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Music Main</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Police {font-family: Impact,Verdana,Lucida; font-size: 12pt; color: #ffff00}
#Status {font-size: 10pt; font-family: Verdana,Lucida,Courier; color: #ffffff}
</style>
<script type="text/javascript">
<!--
// Variables //////////////////////////////////////////////////
var hauteur = 0;
var lDirty = false;
var lNewOpe = false;
var iTimerID = 0;
// ModeAutonome ///////////////////////////////////////////////
function ModeAutonome()
{   var WndMusic;
    if (navigator.appName!="Microsoft Internet Explorer") WndMusic=window.open("MscPlayer.php?Clf=<?php echo $Clf; ?>&man=1","WndMusic","left=0,top=0,width=815,height=307,resizable=0");
    else WndMusic=window.open("MscPlayer.php?Clf=<?php echo $Clf; ?>&man=1","WndMusic","left=0,top=0,width=815,height=305,resizable=0");
    WndMusic.focus();
}
// UpdateControls /////////////////////////////////////////////
function UpdateControls(Ope)
{   document.getElementById("NewModBtn").disabled="disabled";
    document.getElementById("SuppBtn").disabled="disabled";
    //document.getElementById("MscFile").disabled="disabled";
    document.getElementById("MscFile").readOnly=true;
    if(Ope == 1) document.getElementById("MscStatus").innerHTML="<font ID='Status'>Modification en cours...</font>";
    else document.getElementById("MscStatus").innerHTML="<font ID='Status'>T&eacute;l&eacute;chargement en cours...</font>";
    window.clearTimeout(iTimerID);
}
// OnTrier ////////////////////////////////////////////////////
function OnTrier()
{   var Tri=0;
    if(document.getElementById("TriAlb").checked) Tri=1;
    else if(document.getElementById("TriMor").checked) Tri=2;
    else if(document.getElementById("TriCam").checked) Tri=3;
    //if(document.getElementById("MscPlay").value!=0)
    if(top.MscPad.document.getElementById("MscPlay").value!=0)
    {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&auto=1&trcfg="+Tri;
        else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&auto=1&loop=1&trcfg="+Tri;
    }
    else
    {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg="+Tri;
        else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&loop=1&trcfg="+Tri;
    }
}
<?php
if(!Empty($Clf))
{   // Connecté
?>
// OnChanger //////////////////////////////////////////////////
function OnChanger()
{   if(!lNewOpe)
    {   document.getElementById("NewModBtn").value=" Modifier ";
        document.getElementById("SuppBtn").value=" Annuler ";
        document.getElementById("TriBtn").disabled="disabled";
        document.getElementById("VoteBtn").disabled="disabled";
        document.getElementById("MscStatus").innerHTML="<font ID='Status'>Modification...</font>";
        document.getElementById("Lock").value=1;
        //if(document.getElementById("MscPlay").value != 0)
        if(top.MscPad.document.getElementById("MscPlay").value != 0)
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1&auto=1&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1&auto=1";
        }
        else
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1";
        }
        lDirty=true;
    }
}
// OnNewModif /////////////////////////////////////////////////
function OnNewModif()
{   var oFile;
    if(!lNewOpe)
    {   if(lDirty)
        {   // Modifier
            document.getElementById("TypeOpe").value=3;
            document.getElementById("MscArt").readOnly=true;
            document.getElementById("MscAlb").readOnly=true;
            document.getElementById("MscMor").readOnly=true;
            iTimerID = window.setTimeout("UpdateControls(1)",100);
            return true;
        }
        else
        {   // Nouveau
            document.getElementById("NewModBtn").value=" Ajouter ";
            //document.getElementById("MscFile").disabled="";
            document.getElementById("MscFile").value="";
            document.getElementById("MscFile").readOnly=false;
            document.getElementById("SuppBtn").value=" Annuler ";
            document.getElementById("SuppBtn").disabled="";
            document.getElementById("TriBtn").disabled="disabled";
            document.getElementById("VoteBtn").disabled="disabled";
            document.getElementById("Lock").value=1;
            //if(document.getElementById("MscPlay").value != 0)
            if(top.MscPad.document.getElementById("MscPlay").value != 0)
            {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
                echo $Clf;
                if(strcmp($SelFile,"")) echo "&file=$SelFile";
                if(!Empty($man)) echo "&man=$man";
                ?>&trcfg=<?php echo $Tri; ?>&lck=1&auto=1&lp=1";
                else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
                echo $Clf;
                if(strcmp($SelFile,"")) echo "&file=$SelFile";
                if(!Empty($man)) echo "&man=$man";
                ?>&trcfg=<?php echo $Tri; ?>&lck=1&auto=1";
            }
            else
            {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
                echo $Clf;
                if(strcmp($SelFile,"")) echo "&file=$SelFile";
                if(!Empty($man)) echo "&man=$man";
                ?>&trcfg=<?php echo $Tri; ?>&lck=1&lp=1";
                else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
                echo $Clf;
                if(strcmp($SelFile,"")) echo "&file=$SelFile";
                if(!Empty($man)) echo "&man=$man";
                ?>&trcfg=<?php echo $Tri; ?>&lck=1";
            }
            //
            document.getElementById("MscArt").readOnly=false;
            document.getElementById("MscAlb").readOnly=false;
            document.getElementById("MscMor").readOnly=false;
            //
            document.getElementById("MscArt").value="";
            document.getElementById("MscAlb").value="";
            document.getElementById("MscMor").value="";
            document.getElementById("MscCam").value="<?php echo str_replace("\'","'",addslashes($Camarade)); ?>";
            document.getElementById("MscStatus").innerHTML="<font ID='Status'>Cr&eacute;ation...</font>";
            //
            lNewOpe=true;
        }
    }
    else
    {   // Ajouter
        if(document.getElementById("MscFile").value == "")
        {   alert("Source non défini !");
            document.getElementById("MscFile").focus();
            return false;
        }
        document.getElementById("TypeOpe").value=4;
        document.getElementById("MscProgress").src="<?php echo GetImgAddr(); ?>/MscLoad.gif";
        document.getElementById("MscArt").readOnly=true;
        document.getElementById("MscAlb").readOnly=true;
        document.getElementById("MscMor").readOnly=true;
        iTimerID = window.setTimeout("UpdateControls(2)",100);
        return true;
    }
    return false;
}
// OnSupprimer ////////////////////////////////////////////////
function OnSupprimer()
{   if(!lNewOpe)
    {   if(!lDirty)
        {   if(confirm("Es-tu sûr de vouloir supprimer ce morceau ?"))
            {   document.getElementById("MscStatus").innerHTML="<font ID='Status'>Suppression...</font>";
                top.MscPad.document.getElementById("MscPad").innerHTML = "<img src='<?php echo GetImgAddr(); ?>/nopic.gif'>";
                if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
                echo $Clf;
                if(strcmp($SelFile,"")) echo "&file=$SelFile";
                if(!Empty($man)) echo "&man=$man";
                ?>&trcfg=<?php echo $Tri; ?>&ope=1";
                else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
                echo $Clf;
                if(strcmp($SelFile,"")) echo "&file=$SelFile";
                if(!Empty($man)) echo "&man=$man";
                ?>&trcfg=<?php echo $Tri; ?>&loop=1&ope=1";
                return true;
            }
        }
    }
    document.getElementById("MscArt").value="<?php
    if(strcmp($SelFile,"")) echo str_replace("\'","'",addslashes($Artiste));
    ?>";
    document.getElementById("MscAlb").value="<?php
    if(strcmp($SelFile,"")) echo str_replace("\'","'",addslashes($Album));
    ?>";
    document.getElementById("MscMor").value="<?php
    if(strcmp($SelFile,"")) echo str_replace("\'","'",addslashes($Morceau));
    ?>";
    document.getElementById("MscCam").value="<?php
    if(strcmp($SelFile,"")) echo str_replace("\'","'",addslashes($Pseudo));
    ?>";
    document.getElementById("MscFile").value="<?php
    if(strcmp($SelFile,"")) echo str_replace("\'","'",addslashes($Source));
    ?>";
    document.getElementById("NewModBtn").value="Nouveau";
    document.getElementById("SuppBtn").value="Supprimer";
    document.getElementById("MscStatus").innerHTML="<font ID='Status'>Pr&ecirc;t</font>";
    document.getElementById("TriBtn").disabled="";
    document.getElementById("VoteBtn").disabled="<?php
    if(!strcmp($SelFile,"")) echo "disabled";
    ?>";
    //document.getElementById("MscFile").disabled="disabled";
    document.getElementById("MscFile").readOnly=true;
    document.getElementById("Lock").value=0;
    //if(document.getElementById("MscPlay").value != 0)
    if(top.MscPad.document.getElementById("MscPlay").value != 0)
    {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>&auto=1&lp=1";
        else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>&auto=1";
    }
    else
    {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>&lp=1";
        else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>";
    }
    <?php
    if((!strcmp($SelFile,""))||(strcmp(strtoupper($Camarade),strtoupper($Pseudo))))
    {
    ?>
    document.getElementById("SuppBtn").disabled="disabled";
    document.getElementById("MscArt").readOnly=true;
    document.getElementById("MscAlb").readOnly=true;
    document.getElementById("MscMor").readOnly=true;
    <?php
    }
    ?>
    lNewOpe=false;
    lDirty=false;
    return true;
}
<?php
if(strcmp($SelFile,""))
{   // Voter
?>
// OnVoter ////////////////////////////////////////////////////
function OnVoter()
{   var iVote=0;
    if(document.getElementById("Opt1Msc").checked) iVote=1;
    else if(document.getElementById("Opt2Msc").checked) iVote=2;
    else if(document.getElementById("Opt3Msc").checked) iVote=3;
    else if(document.getElementById("Opt4Msc").checked) iVote=4;
    if(iVote!=0)
    {   //if(document.getElementById("MscPlay").value!=0)
        if(top.MscPad.document.getElementById("MscPlay").value!=0)
        {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&auto=1&trcfg=<?php echo $Tri; ?>&vtmsc="+iVote+"&ope=2";
            else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&auto=1&trcfg=<?php echo $Tri; ?>&vtmsc="+iVote+"&loop=1&ope=2";
        }
        else
        {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&vtmsc="+iVote+"&ope=2";
            else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&vtmsc="+iVote+"&loop=1&ope=2";
        }
    }
}
<?php
    // Voter
}
    // Connecté
}
?>
// OnMusicPlay ////////////////////////////////////////////////
function OnMusicPlay()
{   <?php
    if(strcmp($SelFile,""))
    {   // Lecture
    ?>
    //document.getElementById("MscPlay").value=1;
    top.MscPad.document.getElementById("MscPlay").value=1;
    top.MscPad.document.getElementById("MscFile").value="<?php echo "$SelFile"; ?>";
    document.getElementById("MscEqu").src="<?php echo GetImgAddr(); ?>/Equalizer.gif";
    top.MscPad.document.getElementById("MscPad").innerHTML = "<embed ID='MscPlayer' width=0 height=0 hidden='true' autostart='true' loop='"+document.getElementById("LoopOpt").value+"'>";
    top.MscPad.document.getElementById("MscPlayer").src="<?php
    //echo GetFolder(); >/Music/<php echo "$SelFile.$Extension"; >'>";
    echo $Source; ?>";
    document.getElementById("MscView").innerHTML="<marquee width=234><font style='font-family: Impact,Verdana,Lucida; font-size: 60pt; color: #bacc9a'><?php
    echo str_replace($aSearch,$aReplace,trim($Artiste)); ?><font color='#d8e1c6'>&nbsp;-&nbsp;</font><?php
    echo str_replace($aSearch,$aReplace,trim($Album)); ?><font color='#d8e1c6'>&nbsp;-&nbsp;</font><?php
    echo str_replace($aSearch,$aReplace,trim($Morceau)); ?><font color='#d8e1c6'>&nbsp;-&nbsp;</font><?php
    echo str_replace($aSearch,$aReplace,trim($Pseudo)); ?>&nbsp;<font color='#d8e1c6'>(</font><?php
    //echo trim("$SelFile.$Extension");
    echo str_replace($aSearch,$aReplace,trim($Source)); ?><font color='#d8e1c6'>)</font></font><marquee>";
    <?php
        // Lecture
    }
    else
    {   // Vider
    ?>
    top.MscPad.document.getElementById("MscPad").innerHTML = "<img src='<?php echo GetImgAddr(); ?>/nopic.gif'>";
    top.MscPad.document.getElementById("MscFile").value="";
    document.getElementById("MscView").innerHTML="";
    <?php
        // Vider
    }
    ?>
    if(document.getElementById("Lock").value != 0)
    {   //if(document.getElementById("MscPlay").value != 0)
        if(top.MscPad.document.getElementById("MscPlay").value != 0)
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1&auto=1&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1&auto=1";
        }
        else
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1";
        }
    }
    else
    {   //if(document.getElementById("MscPlay").value != 0)
        if(top.MscPad.document.getElementById("MscPlay").value != 0)
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&auto=1&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&auto=1";
        }
        else
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>";
        }
    }
}
// OnMusicStop ////////////////////////////////////////////////
function OnMusicStop()
{   //document.getElementById("MscPlay").value=0;
    top.MscPad.document.getElementById("MscPlay").value=0;
    document.getElementById("MscEqu").src="<?php echo GetImgAddr(); ?>/Equalizer.jpg";
    top.MscPad.document.getElementById("MscPad").innerHTML = "<img src='<?php echo GetImgAddr(); ?>/nopic.gif'>";
    top.MscPad.document.getElementById("MscFile").value="";
    document.getElementById("MscView").innerHTML="";
    if(document.getElementById("Lock").value != 0)
    {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>&lck=1&lp=1";
        else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>&lck=1";
    }
    else
    {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>&lp=1";
        else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
        echo $Clf;
        if(strcmp($SelFile,"")) echo "&file=$SelFile";
        if(!Empty($man)) echo "&man=$man";
        ?>&trcfg=<?php echo $Tri; ?>";
    }
}
// OnMusicPause ///////////////////////////////////////////////
function OnMusicPause()
{}
// OnMusicPrev ////////////////////////////////////////////////
function OnMusicPrev()
{   if(document.getElementById("Lock").value != 1)
    {   <?php
        if($bPrev == True)
        {   // Prev
        ?>
        //if(document.getElementById("MscPlay").value!=0)
        if(top.MscPad.document.getElementById("MscPlay").value!=0)
        {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&pos=1&auto=1";
            else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&loop=1&pos=1&auto=1";
        }
        else
        {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&pos=1";
            else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&loop=1&pos=1";
        }
        <?php
            // Prev
        }
        ?>
    }
}
// OnMusicNext ////////////////////////////////////////////////
function OnMusicNext()
{   if(document.getElementById("Lock").value != 1)
    {   <?php
        if($bNext == True)
        {   // Next
        ?>
        //if(document.getElementById("MscPlay").value!=0)
        if(top.MscPad.document.getElementById("MscPlay").value!=0)
        {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&pos=2&auto=1";
            else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&loop=1&pos=2&auto=1";
        }
        else
        {   if(document.getElementById("LoopOpt").value == "true") top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&pos=2";
            else top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
            echo $Clf;
            if(!Empty($man)) echo "&man=$man";
            ?>&file=<?php echo $SelFile; ?>&trcfg=<?php echo $Tri; ?>&loop=1&pos=2";
        }
        <?php
            // Next
        }
        ?>
    }
}
// OnMusicLoop ////////////////////////////////////////////////
function OnMusicLoop()
{   //if(document.getElementById("MscPlay").value!=1)
    if(top.MscPad.document.getElementById("MscPlay").value!=1)
    {   if(document.getElementById("LoopOpt").value == "true")
        {   document.getElementById("LoopOpt").value="false";
            document.getElementById("MscLoop").src="<?php echo GetImgAddr(); ?>/NoLoop.jpg";
        }
        else
        {   document.getElementById("LoopOpt").value="true";
            document.getElementById("MscLoop").src="<?php echo GetImgAddr(); ?>/Loop.jpg";
        }
        if(document.getElementById("Lock").value != 0)
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lck=1";
        }
        else
        {   if(document.getElementById("LoopOpt").value == "true") document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>&lp=1";
            else document.getElementById("MusicList").src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
            echo $Clf;
            if(strcmp($SelFile,"")) echo "&file=$SelFile";
            if(!Empty($man)) echo "&man=$man";
            ?>&trcfg=<?php echo $Tri; ?>";
        }
    }
}
// GetHauteur /////////////////////////////////////////////////
function GetHauteur()
{   if(window.innerHeight) return window.innerHeight;
    else if(top.window.document.body && top.window.document.body.offsetHeight) return top.window.document.body.offsetHeight;
    else return 0;
}
// Refresh ////////////////////////////////////////////////////
function Refresh()
{   if(hauteur != GetHauteur()) window.history.go(0);
}
// Initialize /////////////////////////////////////////////////
function Initialize()
{
    // Modifie la page si Netscape
    if (navigator.appName!="Microsoft Internet Explorer")
    {   
        // Merci IE!! :p
        document.getElementById("Ranking").style.marginTop="1px";
        document.getElementById("MscArt").style.marginTop="1px";
        document.getElementById("MscAlb").style.marginTop="1px";
        document.getElementById("MscMor").style.marginTop="1px";
        document.getElementById("MscCam").style.marginTop="1px";
        document.getElementById("MscFile").style.marginTop="1px";
        document.getElementById("TriArt").style.marginTop="1px";
        document.getElementById("TriAlb").style.marginTop="1px";
        document.getElementById("TriMor").style.marginTop="1px";
        document.getElementById("TriCam").style.marginTop="1px";
        document.getElementById("Opt1Msc").style.marginTop="1px";
        document.getElementById("Opt2Msc").style.marginTop="1px";
        document.getElementById("Opt3Msc").style.marginTop="1px";
        document.getElementById("Opt4Msc").style.marginTop="1px";

        document.getElementById("Ranking").style.marginBottom="1px";
        document.getElementById("MscArt").style.marginBottom="1px";
        document.getElementById("MscAlb").style.marginBottom="1px";
        document.getElementById("MscMor").style.marginBottom="1px";
        document.getElementById("MscCam").style.marginBottom="1px";
        document.getElementById("MscFile").style.marginBottom="1px";
        document.getElementById("TriArt").style.marginBottom="1px";
        document.getElementById("TriAlb").style.marginBottom="1px";
        document.getElementById("TriMor").style.marginBottom="1px";
        document.getElementById("TriCam").style.marginBottom="1px";
        document.getElementById("Opt1Msc").style.marginBottom="1px";
        document.getElementById("Opt2Msc").style.marginBottom="1px";
        document.getElementById("Opt3Msc").style.marginBottom="1px";
        document.getElementById("Opt4Msc").style.marginBottom="1px";

        document.getElementById("MscFile").style.width="176px";

        document.getElementById("TabMsc").width=245;
        document.getElementById("TabEqu").width=256;
    }
}
// Commande //////////////////////////////////////////////////
/* Initialiser la surveillance par Netscape */
if(!window.hauteur && window.innerHeight)
{   window.onresize = Refresh;
    hauteur = GetHauteur();
}
-->
</script>
</head>
<body bgcolor="#000000" style="margin-top: 0;margin-bottom: 0;margin-left: 0;margin-right: 0" onload="Initialize()">
<script type="text/javascript">
<!--
// Commande ///////////////////////////////////////////////////
/* Initialiser la surveillance par Internet Explorer */
if(!window.hauteur && document.body && document.body.offsetWidth)
{   window.onresize = Refresh;
    hauteur = GetHauteur();
}
-->
</script>
<!-- ************************************************************************************************************************************ MAIN MUSIC -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td bgcolor="#ffff00">
    <table border=0 width=1 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
    </tr>
    </table>
</td>
<td>
    <table border=0 width=5 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
    </tr>
    </table>
</td>
<td valign="top">
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
    <td> <!------------------------------------------------------------------------------------------------------------------------------- COMMANDES -->
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td>
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td>
               <table border=0 cellspacing=0 cellpadding=0> <!----------------------------------------------------------------------------- DOWNLOAD -->
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/DosMusic.jpg"></td>
               </tr>
               <tr>
               <td>
                   <table border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td bgcolor="#d8e1c6">
                       <table border=0 width=2 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   <td><img src="<?php echo GetImgAddr(); ?>/CtrMscHG.jpg"></td>
                   <td>
                       <table border=0 width="100%" cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td>
                   <table border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td bgcolor="#d8e1c6">
                       <table border=0 width=2 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   <td>
                       <table border=0 width=5 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   <td><img ID="MscProgress" src="<?php echo GetImgAddr(); ?>/MscLoad.jpg"></td>
                   </tr>
                   </table>
               </td>
               </tr>
               </table>
           </td>
           <td valign="top">
               <table border=0 cellspacing=0 cellpadding=0>
               <tr>
               <td bgcolor="#d8e1c6">
                   <table border=0 height=2 cellspacing=0 cellpadding=0>
                   <tr>
                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/CtrMscHG.jpg"></td>
               </tr>
               </table>
           </td>
           <td valign="top">
               <form action="<?php echo GetDistSrvAddr(); ?>MscMan.php?Clf=<?php
               echo $Clf;
               if(!Empty($man)) echo "&man=$man";
               ?>" enctype="multipart/form-data" target="MscMan" method="post">
               <table border=0 cellspacing=0 cellpadding=0> <!------------------------------------------------------------------------------ CONTROL -->
               <tr>
               <td bgcolor="#d8e1c6">
                   <table border=0 height=2 cellspacing=0 cellpadding=0>
                   <tr>
                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td>
                   <table border=0 height=4 cellspacing=0 cellpadding=0>
                   <tr>
                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td>
                   <table border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td width=70>
                       <table border=0 width=70 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><font ID="Police">Artiste:</font></td>
                       </tr>
                       </table>
                   </td>
                   <td><input type="text" ID="MscArt" name="art" style="border-style: solid; border-color: #00ff00; border-width: 1px; background-color: #000000; color: #ffffff; font-size: 10pt; font-family: Verdana,Lucida,Courier" size=20 maxlength=30<?php
                   if(strcmp($SelFile,"")) echo " value=\"".str_replace("\"","&quot;",$Artiste)."\"";
                   if((!strcmp($SelFile,""))||(strcmp(strtoupper($Camarade),strtoupper($Pseudo)))) echo " readonly";
                   else echo " OnKeyPress=\"javascript:OnChanger()\" OnChange=\"javascript:OnChanger()\"";
                   ?>></td>
                   <td><input type="radio" ID="TriArt" name="tri" value=1<?php
                   if($Tri==0) echo " checked";
                   ?>></td>
                   </tr>
                   <tr>
                   <td><font ID="Police">Album:</font></td>
                   <td><input type="text" ID="MscAlb" name="alb" style="border-style: solid; border-color: #00ff00; border-width: 1px; background-color: #000000; color: #ffffff; font-size: 10pt; font-family: Verdana,Lucida,Courier" size=20 maxlength=40<?php
                   if(strcmp($SelFile,"")) echo " value=\"".str_replace("\"","&quot;",$Album)."\"";
                   if((!strcmp($SelFile,""))||(strcmp(strtoupper($Camarade),strtoupper($Pseudo)))) echo " readonly";
                   else echo " OnKeyPress=\"javascript:OnChanger()\" OnChange=\"javascript:OnChanger()\"";
                   ?>></td>
                   <td><input type="radio" ID="TriAlb" name="tri" value=2<?php
                   if($Tri==1) echo " checked";
                   ?>></td>
                   </tr>
                   <tr>
                   <td><font ID="Police">Morceau:</font></td>
                   <td><input type="text" ID="MscMor" name="mor" style="border-style: solid; border-color: #00ff00; border-width: 1px; background-color: #000000; color: #ffffff; font-size: 10pt; font-family: Verdana,Lucida,Courier" size=20 maxlength=40<?php
                   if(strcmp($SelFile,"")) echo " value=\"".str_replace("\"","&quot;",$Morceau)."\"";
                   if((!strcmp($SelFile,""))||(strcmp(strtoupper($Camarade),strtoupper($Pseudo)))) echo " readonly";
                   else echo " OnKeyPress=\"javascript:OnChanger()\" OnChange=\"javascript:OnChanger()\"";
                   ?>></td>
                   <td><input type="radio" ID="TriMor" name="tri" value=3<?php
                   if($Tri==2) echo " checked";
                   ?>></td>
                   </tr>
                   <tr>
                   <td><font ID="Police">Pseudo:</font></td>
                   <td><input type="text" ID="MscCam" style="border-style: solid; border-color: #00ff00; border-width: 1px; background-color: #000000; color: #ffffff; font-size: 10pt; font-family: Verdana,Lucida,Courier" size=20 readonly<?php
                   if(strcmp($SelFile,"")) echo " value=\"".str_replace("\"","&quot;",$Pseudo)."\"";
                   ?>></td>
                   <td><input type="radio" ID="TriCam" name="tri" value=4<?php
                   if($Tri==3) echo " checked";
                   ?>></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td>
                   <table border=0 height=5 cellspacing=0 cellpadding=0>
                   <tr>
                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td>
                   <table width=245 border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td align="left">
                       <table width=64 border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <?php
                       if(!Empty($Clf))
                       {   // Connecté
                           ?><td><input type="submit" ID="NewModBtn" style="font-family: Verdana;font-size: 8pt;border-style: solid; border-color: #00ff00; border-width: 1px" onclick="return OnNewModif()" value="Nouveau"></td><?php
                           // Connecté
                       }
                       else
                       {   // Non Connecté
                           ?><td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td><?php
                           // Non Connecté
                       }
                       ?>
                       </tr>
                       </table>
                   </td>
                   <td>
                       <table border=0 width=6 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   <td align="left">
                       <table width=137 border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <?php
                       if(!Empty($Clf))
                       {   // Connecté
                           ?><td><input type="button" ID="SuppBtn" style="font-family: Verdana;font-size: 8pt;border-style: solid; border-color: #00ff00; border-width: 1px" OnClick="javascript:OnSupprimer()" value="Supprimer"<?php
                           if((!strcmp($SelFile,""))||(strcmp(strtoupper($Camarade),strtoupper($Pseudo)))) echo " disabled";
                           ?>></td><?php
                           // Connecté
                       }
                       else
                       {   // Non Connecté
                           ?><td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td><?php
                           // Non Connecté
                       }
                       ?>
                       </tr>
                       </table>
                   </td>
                   <td><input type="button" ID="TriBtn" OnClick="javascript:OnTrier()" style="font-family: Verdana;font-size: 8pt;border-style: solid; border-color: #00ff00; border-width: 1px" value=" Trier "></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td>
                   <table border=0 height=5 cellspacing=0 cellpadding=0>
                   <tr>
                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
<!--           <td><input type="file" ID="MscFile" name="locfile" style="border-style: solid; border-color: #00ff00; border-width: 1px; background-color: #000000; color: #ffffff; font-size: 10pt; font-family: Verdana,Lucida,Courier" size=19 disabled></td> -->
               <td>
                   <table border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td><font ID="Police">Source:</font></td>
                   <td>
                       <table width=21 border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   <td><input type="text" ID="MscFile" name="locfile" style="border-style: solid; border-color: #00ff00; border-width: 1px; background-color: #000000; width: 171px; color: #ffffff; font-size: 10pt; font-family: Verdana,Lucida,Courier" maxlength=250<?php
                   if(strcmp($SelFile,"")) echo " value=\"".str_replace("\"","&quot;",$Source)."\""; ?> readonly></td>
                   </tr>
                   </table>
               </td>
               </tr>
               </table>
               <input type="hidden" name="file" value="<?php
               if(strcmp($SelFile,"")) echo $SelFile;
               ?>">
               <input type="hidden" ID="TypeOpe" name="ope" value=0>
               <input type="hidden" ID="MscGen" name="mng" value=<?php echo $MusicID; ?>>
               </form>
           </td>
           <td valign="top">
               <table border=0 cellspacing=0 cellpadding=0>
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/ExtMscHD.jpg"></td>
               </tr>
               <tr>
               <td>
                   <table border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td>
                       <table border=0 height=139 width=5 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   <td bgcolor="#d8e1c6">
                       <table border=0 width=2 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   </tr>
                   </table>
               </td>
               </tr>
               </table>
           </td>
           <td>
               <table border=0 width=3 cellspacing=0 cellpadding=0>
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
               </tr>
               </table>
           </td>
           <td valign="top">
               <table border=0 cellspacing=0 cellpadding=0> <!--------------------------------------------------------------------------- CLASSEMENT -->
               <tr>
               <td>
                   <table border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td>
                       <table border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/ExtMscHG.jpg"></td>
                       </tr>
                       <tr>
                       <td>
                           <table border=0 height=20 cellspacing=0 cellpadding=0>
                           <tr>
                           <td bgcolor="#d8e1c6">
                               <table border=0 width=2 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           <td>
                               <table border=0 width=5 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td>
                           <table border=0 cellspacing=0 cellpadding=0>
                           <tr>
                           <td bgcolor="#d8e1c6">
                               <table border=0 width=2 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           <td><img src="<?php echo GetImgAddr(); ?>/CtrMscBG.jpg"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td bgcolor="d8e1c6">
                           <table border=0 height=26 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       </table>
                   </td>
                   <td valign="top">
                       <table border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <td bgcolor="d8e1c6">
                           <table border=0 height=2 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td>
                           <table border=0 height=5 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td>
                           <table border=0 cellspacing=0 cellpadding=0>
                           <tr>
                           <td width=70>
                               <table border=0 width=90 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><font ID="Police">Classement:</font></td>
                               </tr>
                               </table>
                           </td>
                           <td><input ID="Ranking" type="text" style="border-style: solid; border-color: #00ff00; border-width: 1px; background-color: #000000; color: #ffffff; font-size: 10pt; font-family: Verdana,Lucida,Courier" size=3 value="<?php
                           if(strcmp($SelFile,"")) echo $MscClass;
                           ?>" readonly></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td>
                           <table border=0 height=3 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td bgcolor="#d8e1c6">
                           <table border=0 height=2 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td bgcolor="#d8e1c6">
                           <table border=0 width="100%" cellspacing=0 cellpadding=0>
                           <tr>
                           <td width="50%" align="center"><img src="<?php echo GetImgAddr(); ?>/Unsmile.jpg"></td>
                           <td width=20><input type="radio" ID="Opt1Msc" name="vot" value=1<?php
                           if((strcmp($SelFile,""))&&(!Empty($Note))&&($Note == -2)) echo " checked";
                           if(Empty($Clf)) echo " disabled";
                           ?>></td>
                           <td width=20><input type="radio" ID="Opt2Msc" name="vot" value=2<?php
                           if((strcmp($SelFile,""))&&(!Empty($Note))&&($Note == -1)) echo " checked";
                           if(Empty($Clf)) echo " disabled";
                           ?>></td>
                           <td width=20><input type="radio" ID="Opt3Msc" name="vot" value=3<?php
                           if((strcmp($SelFile,""))&&(!Empty($Note))&&($Note == 1)) echo " checked";
                           if(Empty($Clf)) echo " disabled";
                           ?>></td>
                           <td width=20><input type="radio" ID="Opt4Msc" name="vot" value=4<?php
                           if((strcmp($SelFile,""))&&(!Empty($Note))&&($Note == 2)) echo " checked";
                           if(Empty($Clf)) echo " disabled";
                           ?>></td>
                           <td width="50%" align="center"><img src="<?php echo GetImgAddr(); ?>/Smile.jpg"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td bgcolor="#d8e1c6">
                           <table border=0 height=3 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       </table>
                   </td>
                   <td>
                       <table border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/ExtMscHD.jpg"></td>
                       </tr>
                       <tr>
                       <td>
                           <table border=0 height=20 cellspacing=0 cellpadding=0>
                           <tr>
                           <td>
                               <table border=0 width=5 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           <td bgcolor="#d8e1c6">
                               <table border=0 width=2 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td>
                           <table border=0 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/CtrMscBD.jpg"></td>
                           <td bgcolor="#d8e1c6">
                               <table border=0 width=2 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td bgcolor="d8e1c6">
                           <table border=0 height=21 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       <tr>
                       <td bgcolor="d8e1c6">
                           <table border=0 cellspacing=0 cellpadding=0>
                           <tr>
                           <td>
                               <table border=0 width=2 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           <td><img src="<?php echo GetImgAddr(); ?>/ClsMscBD.jpg"></td>
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
                   <table border=0 cellspacing=0 cellpadding=0>
                   <tr>
                   <td>
                       <table border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <td bgcolor="#d8e1c6">
                           <table border=0 width=5 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       <td><input type="button" ID="VoteBtn" style="font-family: Verdana;font-size: 8pt;border-style: solid; border-color: #0000ff; border-width: 1px; width: 48px" value="Voter"<?php
                       if(strcmp($SelFile,"")) echo " OnClick=\"javascript:OnVoter()\"";
                       if(Empty($Clf)) echo " disabled";
                       ?>></td>
                       <td bgcolor="#d8e1c6">
                           <table border=0 width=5 cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       <td valign="top"><img src="<?php echo GetImgAddr(); ?>/CtrMscHG.jpg"></td>
                       <td>
                           <table border=0 width="100%" cellspacing=0 cellpadding=0>
                           <tr>
                           <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                           </tr>
                           </table>
                       </td>
                       </tr>
                       </table>
                   </td>
                   </tr>
                   <tr>
                   <td>
                       <table border=0 cellspacing=0 cellpadding=0>
                       <tr>
                       <td>
                           <table border=0 cellspacing=0 cellpadding=0>
                           <tr>
                           <td>
                               <table border=0 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/ClsMscBG.jpg"></td>
                               <td bgcolor="#d8e1c6">
                                   <table border=0 width=48 cellspacing=0 cellpadding=0>
                                   <tr>
                                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                                   </tr>
                                   </table>
                               </td>
                               <td><img src="<?php echo GetImgAddr(); ?>/ClsMscBD.jpg"></td>
                               <td>
                                   <table border=0 width=37 cellspacing=0 cellpadding=0>
                                   <tr>
                                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                                   </tr>
                                   </table>
                               </td>
                               </tr>
                               </table>
                           </td>
                           </tr>
                           <tr>
                           <td>
                               <table border=0 height=6 cellspacing=0 cellpadding=0>
                               <tr>
                               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                               </tr>
                               </table>
                           </td>
                           </tr>
                           <tr>
                           <td><font ID="Police">T&eacute;l&eacute;charger :</font></td>
                           </tr>
                           </table>
                       </td>
                       <td><?php
                       if(strcmp($SelFile,""))
                       {   // Download
                       ?><a href="<?php
                       //echo GetMscAddr()."/$SelFile.$Extension";
                       echo $Source;
                       ?>" target="_blank"><?php
                           // Download
                       }
                       ?><img src="<?php echo GetImgAddr(); ?>/download.gif" border=0><?php
                       if(strcmp($SelFile,""))
                       {   // Download
                       ?></a><?php
                           // Download
                       }
                       ?></td>
                       </tr>
                       <tr>
                       <td><font ID="Police"><font color="#ffffff" size=1>(bouton droit, menu &#39;Enr<br>la cible sous...&#39;)</font></font></td>
                       <td valign="top"><font ID="Police"><font color="#ffffff" size=1>egistrer</font></font></td>
                       </tr>
                       </table>
                   </td>
                   </tr>
                   </table>
               </td>
               </tr>
               </table>
           </td>
           <td>
               <table border=0 width=3 cellspacing=0 cellpadding=0>
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
               </tr>
               </table>
           </td>
           <td valign="top">
               <table border=0 cellspacing=0 cellpadding=0>
               <tr>
               <td>
                   <table border=0 height=9 cellspacing=0 cellpadding=0>
                   <tr>
                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                   </tr>
                   </table>
               </td>
               </tr>
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/SubMscHG.jpg"></td>
               </tr>
               <tr>
               <td>
                   <table border=0 height=129 cellspacing=0 cellpadding=0>
                   <tr>
                   <td bgcolor="#ffff00">
                       <table border=0 width=2 cellspacing=0 cellpadding=0>
                       <tr>
                       <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                       </tr>
                       </table>
                   </td>
                   <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
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
        <tr> <!------------------------------------------------------------------------------------------------------------------------------ STATUT -->
        <td>
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td>
                <table border=0 cellspacing=0 cellpadding=0>
                <tr>
                <td>
                    <table border=0 height=19 cellspacing=0 cellpadding=0>
                    <tr>
                    <td bgcolor="#d8e1c6">
                        <table border=0 width=2 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                        </tr>
                        </table>
                    </td>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/ExtMscBG.jpg"></td>
                </tr>
                </table>
            </td>
            <td>
                <table border=0 width=50 cellspacing=0 cellpadding=0>
                <tr>
                <td>
                    <table border=0 height=24 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><font ID="Police">Statut:</font></td>
                    </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td bgcolor="#d8e1c6">
                    <table border=0 height=2 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                </tr>
                </table>
            </td>
            <td>
                <table ID="TabMsc" border=0 width=242 cellspacing=0 cellpadding=0>
                <tr>
                <td>
                    <table border=0 height=24 cellspacing=0 cellpadding=0>
                    <tr>
                    <td ID="MscStatus"></td>
                    </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td bgcolor="#d8e1c6">
                    <table border=0 height=2 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                </tr>
                </table>
            </td>
            <td>
                <table border=0 cellspacing=0 cellpadding=0>
                <tr>
                <td>
                    <table border=0 height=19 cellspacing=0 cellpadding=0>
                    <tr>
                    <td>
                        <table border=0 width=5 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                        </tr>
                        </table>
                    </td>
                    <td bgcolor="#d8e1c6">
                        <table border=0 width=2 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/ExtMscBD.jpg"></td>
                </tr>
                </table>
            </td>
            <td width="100%" valign="bottom">
               <table border=0 width="100%" cellspacing=0 cellpadding=0>
               <tr align="center">
               <?php
               if(!Empty($man))
               {   // No Mode autonome
               ?>
               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
               <?php
                   // No Mode autonome
               }
               else
               {   // Mode autonome
               ?>
               <td><font ID="Police"><a href="#" OnClick="javascript:ModeAutonome()">= ThePlayer v1.0 =</a></font></td>
               <?php
                   // Mode autonome
               }
               ?>
               </tr>
               </table>
            </td>
            <td bgcolor="#ffff00">
               <table border=0 width=2 cellspacing=0 cellpadding=0>
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
               </tr>
               </table>
            </td>
            <td>
               <table border=0 width=6 cellspacing=0 cellpadding=0>
               <tr>
               <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
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
    <td> <!------------------------------------------------------------------------------------------------------------------------------- EQUALIZER -->
        <table ID="MusicCadreG" height=<?php
        if(($Hauteur-604)>6) echo $Hauteur-604;
        else echo "6";
        ?> border=0 cellspacing=0 cellpadding=0>
        <tr height="100%">
        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
        <td bgcolor="#ffff00">
            <table border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
        </tr>
        <tr>
        <td>
            <table ID="TabEqu" border=0 width=251 cellspacing=0 cellpadding=0>
            <tr>
            <td ID="MscView"></td>
            </tr>
            </table>
        </td>
        <td>
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td>
                <table border=0 height=6 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/SubMscHG.jpg"></td>
            </tr>
            <tr>
            <td>
                <table border=0 cellspacing=0 cellpadding=0>
                <tr>
                <td bgcolor="#ffff00">
                    <table border=0 width=2 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td>
                    <table border=0 width=6 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td>
                <table border=0 height=57 cellspacing=0 cellpadding=0>
                <tr>
                <td bgcolor="#ffff00">
                    <table border=0 width=2 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td valign="bottom"><img src="<?php echo GetImgAddr(); ?>/EquMscBG.jpg"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 height=19 width=8 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr bgcolor="#ffff00">
            <td><img src="<?php echo GetImgAddr(); ?>/CmdMscBG.jpg"></td>
            </tr>
            </table>
        </td>
        <td>
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td>
                <table border=0 height=6 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td>
                <table border=0 height=6 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td><img ID="MscEqu" src="<?php echo GetImgAddr(); ?>/Equalizer.jpg"></td>
            </tr>
            <tr>
            <td>
                <table border=0 height=6 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 height=3 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 cellspacing=0 cellpadding=0>
                <tr>
                <td><a href="#" onclick="javascript:OnMusicPlay()"><img src="<?php echo GetImgAddr(); ?>/Play.jpg" border=0></a></td>
                <td bgcolor="#ffff00">
                    <table border=0 width=3 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td><a href="#" onclick="javascript:OnMusicStop()"><img src="<?php echo GetImgAddr(); ?>/Stop.jpg" border=0></a></td>
                <td bgcolor="#ffff00">
                    <table border=0 width=3 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td><a href="#" onclick="javascript:OnMusicPause()"><img src="<?php echo GetImgAddr(); ?>/Pause.jpg" border=0></a></td>
                <td bgcolor="#ffff00">
                    <table border=0 width=3 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td><a href="#" onclick="javascript:OnMusicPrev()"><img src="<?php echo GetImgAddr(); ?>/PrevMsc.jpg" border=0></a></td>
                <td bgcolor="#ffff00">
                    <table border=0 width=3 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td><a href="#" onclick="javascript:OnMusicNext()"><img src="<?php echo GetImgAddr(); ?>/NextMsc.jpg" border=0></a></td>
                <td bgcolor="#ffff00">
                    <table border=0 width=40 cellspacing=0 cellpadding=0>
                    <tr>
                    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                    </tr>
                    </table>
                </td>
                <td><a href="#" onclick="javascript:OnMusicLoop()"><img ID="MscLoop" src="<?php echo GetImgAddr(); ?>/<?php
                if($bLoop == True) echo "Loop";
                else echo "NoLoop";
                ?>.jpg" border=0></a></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 height=4 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            </table>
        </td>
        <td bgcolor="#ffff00" valign="top">
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/EquMscBD.jpg"></td>
            </tr>
            <tr>
            <td>
                <table border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/EquMscHD.jpg"></td>
            </tr>
            <tr>
            <td bgcolor="#000000">
                <table border=0 height=52 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/EquMscBD.jpg"></td>
            </tr>
            </table>
        </td>
        <td bgcolor="#ffff00">
            <table border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td valign="bottom">
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/EquMscBG.jpg"></td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
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
<td width="100%"> <!-------------------------------------------------------------------------------------------------------------------------- LISTE -->
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td>
            <table border=0 width=10 cellspacing=0 cellpadding=0>
            <tr>
            <td>
                <table border=0 height=9 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td>
                <table border=0 height=9 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            </table>
        </td>
        <td nowrap><font ID="Police">&nbsp;Tous les sons du Cl@ssico:&nbsp;</font></td>
        <td width="100%">
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td>
                <table border=0 height=9 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffff00">
                <table border=0 height=2 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td>
                <table border=0 height=9 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
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
    <td width="100%"><iframe src="<?php echo GetLocalSrvAddr(); ?>MscList.php?Clf=<?php
    echo $Clf;
    if(strcmp($SelFile,"")) echo "&file=$SelFile";
    if(!Empty($man)) echo "&man=$man";
    ?>&trcfg=<?php
    echo $Tri;
    if($bAutoPlay == true) echo "&auto=1";
    if($bLoop == true) echo "&lp=1";
    ?>" ID="MusicList" width="100%" height=<?php
    if(($Hauteur-362)>248) echo $Hauteur-362;
    else echo "248";
    ?> scrolling="yes" frameborder=0 style="overflow-x: hidden;overflow-y: auto"></iframe></td>
    </tr>
    <tr>
    <td>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td>
            <table border=0 height=6 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffff00">
            <table border=0 height=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td valign="top">
    <table border=0 width=8 cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 height=9 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetImgAddr(); ?>/SubMscHD.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td>
            <table ID="MusicCadreD" border=0 height=<?php
            if(($Hauteur-359)>241) echo $Hauteur-359;
            else echo "241";
            ?> width=6 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
            </tr>
            </table>
        </td>
        <td bgcolor="#ffff00">
            <table border=0 width=2 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><img src="<?php echo GetImgAddr(); ?>/SubMscBD.jpg"></td>
    </tr>
    </table>
</td>
<td>
    <table border=0 width=6 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
    </tr>
    </table>
</td>
<td bgcolor="#ffff00">
    <table border=0 width=1 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
    </tr>
    </table>
</td>
<td bgcolor="#ffffff">
    <table border=0 width=1 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
    </tr>
    </table>
</td>
<td>
    <table border=0 width=4 cellspacing=0 cellpadding=0>
    <tr>
    <td ID="MscPad"><img src="<?php echo GetImgAddr(); ?>/nopic.gif"></td>
    </tr>
    </table>
</td>
</tr>
</table><input type="hidden" ID="Lock" value=0><input type="hidden" ID="LoopOpt" value="<?php
if($bLoop == True) echo "true";
else echo "false";
?>">
<!-- *********************************************************************************************************************************************** -->
<script type="text/javascript">
<!--
// Commande ///////////////////////////////////////////////////
document.getElementById("MscStatus").innerHTML = "<?php echo $Status; ?>";
<?php
if((strcmp($SelFile,""))&&($bAutoPlay == True))
{   // Play new if needed
?>
//document.getElementById("MscPlay").value=1;
top.MscPad.document.getElementById("MscPlay").value=1;
<?php
    // Play new if needed
}
if(!strcmp($SelFile,""))
{   // Pas de vote
?>
document.getElementById("VoteBtn").disabled="disabled";
<?php
    // Pas de vote
}
if(!Empty($man))
{   // Mode Autonome
    switch($man)
    {    case 1:
         {    // From Classico
?>
if (navigator.appName!="Microsoft Internet Explorer")
{   document.getElementById("MusicCadreG").style.height="104px";
}
else
{   document.getElementById("MusicCadreG").height=6;
    document.getElementById("MusicCadreD").height=251;
    document.getElementById("MusicList").height=248;
}
<?php
              // From Classico
              break;
         }
         case 2:
         {    // From Anywhere
?>
if((hauteur - 299) > 6) document.getElementById("MusicCadreG").height=(hauteur - 299);
else document.getElementById("MusicCadreG").height=6;
if((hauteur - 54) > 241) document.getElementById("MusicCadreD").height=(hauteur - 54);
else document.getElementById("MusicCadreD").height=241;
if((hauteur - 57) > 248) document.getElementById("MusicList").height=(hauteur - 57);
else document.getElementById("MusicList").height=248;
<?php
              // From Anywhere
              break;
         }
    }
}
else
{   // Pas Mode Autonome
?>
if (navigator.appName!="Microsoft Internet Explorer")
{   if((hauteur - 172) > 6) document.getElementById("MusicCadreG").style.height=(hauteur - 172)+"px";
    else document.getElementById("MusicCadreG").height=6;
    if((hauteur - 25) > 241) document.getElementById("MusicCadreD").style.height=(hauteur - 25)+"px";
    else document.getElementById("MusicCadreD").height=241;
    if((hauteur - 28) > 248) document.getElementById("MusicList").height=(hauteur - 28);
    else document.getElementById("MusicList").height=248;
}
else
{   if((hauteur - 604) > 6) document.getElementById("MusicCadreG").height=(hauteur - 604);
    else document.getElementById("MusicCadreG").height=6;
    if((hauteur - 359) > 241) document.getElementById("MusicCadreD").height=(hauteur - 359);
    else document.getElementById("MusicCadreD").height=241;
    if((hauteur - 362) > 248) document.getElementById("MusicList").height=(hauteur - 362);
    else document.getElementById("MusicList").height=248;
}
<?php
}
?>
if((top.MscPad.document.getElementById("MscPlay").value != 0)&&(top.MscPad.document.getElementById("MscFile").value != ""))
{   document.getElementById("MscEqu").src="<?php echo GetImgAddr(); ?>/Equalizer.gif";
    document.getElementById("MscView").innerHTML="<marquee width=234><font style='font-family: Impact,Verdana,Lucida; font-size: 60pt; color: #bacc9a'><?php
    echo str_replace($aSearch,$aReplace,trim($Artiste)); ?><font color='#d8e1c6'>&nbsp;-&nbsp;</font><?php
    echo str_replace($aSearch,$aReplace,trim($Album)); ?><font color='#d8e1c6'>&nbsp;-&nbsp;</font><?php
    echo str_replace($aSearch,$aReplace,trim($Morceau)); ?><font color='#d8e1c6'>&nbsp;-&nbsp;</font><?php
    echo str_replace($aSearch,$aReplace,trim($Pseudo)); ?>&nbsp;<font color='#d8e1c6'>(</font><?php
    //echo trim("$SelFile.$Extension");
    echo $Source;
    ?><font color='#d8e1c6'>)</font></font><marquee>";
    if(top.MscPad.document.getElementById("MscFile").value != "<?php echo "$SelFile"; ?>")
    {   top.MscPad.document.getElementById("MscPad").innerHTML = "<embed ID='MscPlayer' width=0 height=0 hidden='true' autostart='true' loop='"+document.getElementById("LoopOpt").value+"'>";
        top.MscPad.document.getElementById("MscPlayer").src="<?php
        //echo GetFolder(); >/Music/<php echo "$SelFile.$Extension"; >'>";
        echo $Source; ?>";
        top.MscPad.document.getElementById("MscFile").value = "<?php echo "$SelFile"; ?>";
    }
}
//-->
</script>
</body>
</html>
