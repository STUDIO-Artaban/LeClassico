<?php
require("Package.php");
$Chp = "10";
$Clf = $_GET['Clf'];
$man = $_GET['man'];
$trcfg = $_GET['trcfg'];
$file = $_GET['file'];
$auto = $_GET['auto'];
$lp = $_GET['lp'];
$lck = $_GET['lck'];
$Tri = 0;
$SelFile = "";
$SelLock = false;
$SelPlay = false;
$SelLoop = false;
$iResCnt = 0;
$aSearch = array("<",">");
$aReplace = array("&lt;","&gt;");
//if(!Empty($Clf))
//{
    // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(Empty($Link))
    {   $Msg = "Connexion au serveur impossible!";
        include("Message.php");
        die();
    }
    else
    {   mysql_select_db(GetMySqlDB(),$Link);
        if(!Empty($Clf))
        {   $Camarade = DistUserKeyId($Clf);
            $Query = "SELECT 'X' FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
            mysql_select_db(GetMySqlDB(),$Link);
            $Result = mysql_query(trim($Query),$Link);
            if(mysql_num_rows($Result) != 0) mysql_free_result($Result);
            else
            {   mysql_close($Link);
                $Msg = "Ton pseudo est inconnu!";
                include("Message.php");
                die();
            }
        }
        if(!Empty($trcfg)) $Tri = $trcfg;
        if(!Empty($file)) $SelFile = $file;
        if((!Empty($lck))&&($lck == 1)) $SelLock = true;
        if((!Empty($auto))&&($auto == 1)) $SelPlay = true;
        if((!Empty($lp))&&($lp == 1)) $SelLoop = true;
        $Query = "SELECT * FROM Music WHERE MSC_Status <> 2";
        switch($Tri)
        {   case 0: // Artiste
            {   $Query .= " ORDER BY MSC_Artiste, MSC_Album, MSC_Morceau, MSC_Pseudo";
                break;
            }
            case 1: // Album
            {   $Query .= " ORDER BY MSC_Album, MSC_Artiste, MSC_Morceau, MSC_Pseudo";
                break;
            }
            case 2: // Morceau
            {   $Query .= " ORDER BY MSC_Morceau, MSC_Artiste, MSC_Album, MSC_Pseudo";
                break;
            }
            case 3: // Pseudo
            {   $Query .= " ORDER BY MSC_Pseudo, MSC_Artiste, MSC_Album, MSC_Morceau";
                break;
            }
            default:
            {   $Query .= " ORDER BY MSC_Artiste, MSC_Album, MSC_Morceau, MSC_Pseudo";
                break;
            }
        }
        $Result = mysql_query(trim($Query),$Link);
        $iResCnt = mysql_num_rows($Result);
    }
//    }
//}
//else
//{   $Msg = "Tu n'est pas connect&eacute;!";
//    include("Message.php");
//    die();
//}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Music List</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<style type="text/css">
p { margin-top: 0}
#Title {font-size: 10pt; font-family: Impact,Verdana,Lucida}
</style>
</head>
<body bgcolor="#000000" style="margin-top: 0;margin-left: 0;margin-right: 0">
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<?php
$BckColor = "#BACC9A";
$FntColor = "#000000";
while($aRow = mysql_fetch_array($Result))
{   $FntColor = "#000000";
    if(!strcmp($BckColor,"#D8E1C6")) $BckColor = "#BACC9A";
    else $BckColor = "#D8E1C6";
    if(Empty($SelFile))
    {   $SelFile = substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4);
        $FntColor = "#0000FF";
    }
    elseif(!strcmp(substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4),$SelFile)) $FntColor = "#0000FF";
    ?>
    <tr bgcolor="<?php echo $BckColor; ?>">
    <td>
        <table width="100%" height=17 cellspacing=0 cellpadding=0>
        <tr>
        <td valign="top"><div style="position: absolute; width: 100%; height: 17px; overflow: hidden" align="left">
            <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td nowrap><font ID="Title" color="<?php echo $FntColor; ?>"><p>&nbsp;<img src="<?php echo GetFolder(); ?>/Images/Puce.gif">&nbsp;&nbsp;<?php
            if(!strcmp($FntColor,"#0000FF")) echo "<u>";
            else
            {   // Lien
                ?><a href="#" OnClick="javascript:OnSelectMusic('<?php echo substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4); ?>')" style="color: <?php echo $FntColor; ?>"><?php
                // Lien
            }
            switch($Tri)
            {   case 0: // Artiste
                {   echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Artiste"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Album"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Morceau"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Pseudo"]));
                    break;
                }
                case 1: // Album
                {   echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Album"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Artiste"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Morceau"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Pseudo"]));
                    break;
                }
                case 2: // Morceau
                {   echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Morceau"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Artiste"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Album"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Pseudo"]));
                    break;
                }
                case 3: // Pseudo
                {   echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Pseudo"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Artiste"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Album"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Morceau"]));
                    break;
                }
                default:
                {   echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Artiste"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Album"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Morceau"]));
                    echo " - ";
                    echo str_replace($aSearch,$aReplace,stripslashes($aRow["MSC_Pseudo"]));
                    break;
                }
            }
            //echo " (".$aRow["MSC_Fichier"].")";
            echo " (".$aRow["MSC_Source"].")";
            if(!strcmp($FntColor,"#0000FF")) echo "</u>";
            else
            {   // Lien
                ?></a><?php
                // Lien
            }
            ?></p></font></td>
            </tr>
            </table>
        </div></td>
        </tr>
        </table>
    </td>
    </tr>
    <?php
}
if($iResCnt!=0) mysql_free_result($Result);
mysql_close($Link);
?>
</table>
</body>
<script type="text/javascript">
<!--
// OnSelectMusic /////////////////////////////////////////////////////////////////////////
function OnSelectMusic(mscFile)
{   // Sélection
    <?php
    if($SelLock == false)
    {   // Permission de changer de son
        if($SelPlay == true)
        {   // AutoPlay
            if($SelLoop == true)
            {   // NoLoop
        ?>
        top.MscOpe.location.href="MscOpe.php?Clf=<?php
        echo $Clf;
        if(!Empty($man)) echo "&man=$man";
        ?>&file="+mscFile+"&trcfg=<?php echo $Tri; ?>&auto=1";
        <?php
                // NoLoop
            }
            else
            {   // Loop
        ?>
        top.MscOpe.location.href="MscOpe.php?Clf=<?php
        echo $Clf;
        if(!Empty($man)) echo "&man=$man";
        ?>&file="+mscFile+"&trcfg=<?php echo $Tri; ?>&loop=1&auto=1";
        <?php
                // Loop
            }
            // AutoPlay
        }
        else
        {   // NoAutoPlay
            if($SelLoop == true)
            {   // NoLoop
        ?>
        top.MscOpe.location.href="MscOpe.php?Clf=<?php
        echo $Clf;
        if(!Empty($man)) echo "&man=$man";
        ?>&file="+mscFile+"&trcfg=<?php echo $Tri; ?>";
        <?php
                // NoLoop
            }
            else
            {   // Loop
        ?>
        top.MscOpe.location.href="MscOpe.php?Clf=<?php
        echo $Clf;
        if(!Empty($man)) echo "&man=$man";
        ?>&file="+mscFile+"&trcfg=<?php echo $Tri; ?>&loop=1";
        <?php
                // Loop
            }
            // AutoPlay
        }
        // Permission de changer de son
    }
    ?>
}
//-->
</script>
</html>
