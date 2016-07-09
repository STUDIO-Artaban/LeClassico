<?php
require("Package.php");
$Chp = "7";
$Clf = $_GET['Clf'];
$albvwu = $_GET['albvwu'];
$vwpht = $_GET['vwpht'];
$albtri = $_GET['albtri'];
$albnm = $_GET['albnm'];
$vwu = $_GET['vwu'];
if(Empty($albvwu)) $albvwu = $_POST['albvwu'];
if(Empty($albtri)) $albtri = $_POST['albtri'];
if(Empty($albnm)) $albnm = $_POST['albnm'];
if(Empty($vwu)) $vwu = $_POST['vwu'];
if(Empty($vwpht)) $vwpht = $_POST['vwpht'];
$pht = $_POST['pht'];
$ope = $_POST['ope'];
$pht1 = $_POST['pht1'];
$vtpht1 = $_POST['vtpht1'];
$pht2 = $_POST['pht2'];
$vtpht2 = $_POST['vtpht2'];
$pht3 = $_POST['pht3'];
$vtpht3 = $_POST['vtpht3'];
$pht4 = $_POST['pht4'];
$vtpht4 = $_POST['vtpht4'];
$pht5 = $_POST['pht5'];
$vtpht5 = $_POST['vtpht5'];
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
            if(Empty($albnm))
            {   mysql_close($Link);
                $Msg = "Album non s&eacute;lectionn&eacute;!!!!...?";
                include("Message.php");
                die();
            }
            else
            {   if(!Empty($ope))
                {   $aDate = getdate();
                    if($ope != 2)
                    {   // Ajoute un commentaire ///////////////////////////////////////////////////////////////////////////////
                        $iStatus = AjouteCommentaire($Link,$Camarade,'P',GetPhotoID(base64_decode(urldecode($pht))),"cmmt");
                        if($iStatus != 15)
                        {   mysql_close($Link);
                            $Msg = "Une erreur est intervenue durant l'ajout de ton commentaire! Son contenu ne lui a peut-&ecirc;tre pas plus!";
                            $Msg .= " Si le probl&egrave;me persiste contact le <font color=\"#808080\">Webmaster</font>!";
                            include("Message.php");
                            die();
                        }
                    }
                    else
                    {   // Vote ///////////////////////////////////////////////////////////////////////////////////////
                        $iVote = 0;
                        if(!Empty($vtpht1))
                        {   switch($vtpht1)
                            {   case 1:
                                {   $iVote = -2;
                                    break;
                                }
                                case 2:
                                {   $iVote = -1;
                                    break;
                                }
                                case 3:
                                {   $iVote = 1;
                                    break;
                                }
                                case 4:
                                {   $iVote = 2;
                                    break;
                                }
                            }
                            $Query = "SELECT VOT_Date, VOT_Note, VOT_Total FROM Votes WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht1))."'";
                            $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   $aRow = mysql_fetch_array($Result);
                                if((sscanf(substr($aRow["VOT_Date"],0,4),"%d")==sscanf(trim($aDate["year"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],5,2),"%d")==sscanf(trim($aDate["mon"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],8),"%d")==sscanf(trim($aDate["mday"]),"%d")))
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht1))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                else
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote,";
                                    $Query .= " VOT_Total = ".($aRow["VOT_Note"]+$aRow["VOT_Total"]).",";
                                    $Query .= " VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
                                    $Query .= " WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht1))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                            else
                            {   $Query = "INSERT INTO Votes (VOT_Pseudo,VOT_Fichier,VOT_Note,VOT_Date) VALUES (";
                                // Pseudo
                                $Query .= "'".addslashes($Camarade)."',";
                                // Fichier
                                $Query .= "'".base64_decode(urldecode($pht1))."',";
                                // Note
                                $Query .= "$iVote,";
                                // Date
                                $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."')";
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                        }
                        if((!Empty($pht2))&&(strcmp(trim($pht2),""))&&(!Empty($vtpht2)))
                        {   switch($vtpht2)
                            {   case 1:
                                {   $iVote = -2;
                                    break;
                                }
                                case 2:
                                {   $iVote = -1;
                                    break;
                                }
                                case 3:
                                {   $iVote = 1;
                                    break;
                                }
                                case 4:
                                {   $iVote = 2;
                                    break;
                                }
                            }
                            $Query = "SELECT VOT_Date, VOT_Note, VOT_Total FROM Votes WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht2))."'";
                            $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   $aRow = mysql_fetch_array($Result);
                                if((sscanf(substr($aRow["VOT_Date"],0,4),"%d")==sscanf(trim($aDate["year"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],5,2),"%d")==sscanf(trim($aDate["mon"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],8),"%d")==sscanf(trim($aDate["mday"]),"%d")))
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht2))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                else
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote,";
                                    $Query .= " VOT_Total = ".($aRow["VOT_Note"]+$aRow["VOT_Total"]).",";
                                    $Query .= " VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
                                    $Query .= " WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht2))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                            else
                            {   $Query = "INSERT INTO Votes (VOT_Pseudo,VOT_Fichier,VOT_Note,VOT_Date) VALUES (";
                                // Pseudo
                                $Query .= "'".addslashes($Camarade)."',";
                                // Fichier
                                $Query .= "'".base64_decode(urldecode($pht2))."',";
                                // Note
                                $Query .= "$iVote,";
                                // Date
                                $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."')";
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                        }
                        if((!Empty($pht3))&&(strcmp(trim($pht3),""))&&(!Empty($vtpht3)))
                        {   switch($vtpht3)
                            {   case 1:
                                {   $iVote = -2;
                                    break;
                                }
                                case 2:
                                {   $iVote = -1;
                                    break;
                                }
                                case 3:
                                {   $iVote = 1;
                                    break;
                                }
                                case 4:
                                {   $iVote = 2;
                                    break;
                                }
                            }
                            $Query = "SELECT VOT_Date, VOT_Note, VOT_Total FROM Votes WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht3))."'";
                            $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   $aRow = mysql_fetch_array($Result);
                                if((sscanf(substr($aRow["VOT_Date"],0,4),"%d")==sscanf(trim($aDate["year"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],5,2),"%d")==sscanf(trim($aDate["mon"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],8),"%d")==sscanf(trim($aDate["mday"]),"%d")))
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht3))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                else
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote,";
                                    $Query .= " VOT_Total = ".($aRow["VOT_Note"]+$aRow["VOT_Total"]).",";
                                    $Query .= " VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
                                    $Query .= " WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht3))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                            else
                            {   $Query = "INSERT INTO Votes (VOT_Pseudo,VOT_Fichier,VOT_Note,VOT_Date) VALUES (";
                                // Pseudo
                                $Query .= "'".addslashes($Camarade)."',";
                                // Fichier
                                $Query .= "'".base64_decode(urldecode($pht3))."',";
                                // Note
                                $Query .= "$iVote,";
                                // Date
                                $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."')";
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                        }
                        if((!Empty($pht4))&&(strcmp(trim($pht4),""))&&(!Empty($vtpht4)))
                        {   switch($vtpht4)
                            {   case 1:
                                {   $iVote = -2;
                                    break;
                                }
                                case 2:
                                {   $iVote = -1;
                                    break;
                                }
                                case 3:
                                {   $iVote = 1;
                                    break;
                                }
                                case 4:
                                {   $iVote = 2;
                                    break;
                                }
                            }
                            $Query = "SELECT VOT_Date, VOT_Note, VOT_Total FROM Votes WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht4))."'";
                            $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   $aRow = mysql_fetch_array($Result);
                                if((sscanf(substr($aRow["VOT_Date"],0,4),"%d")==sscanf(trim($aDate["year"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],5,2),"%d")==sscanf(trim($aDate["mon"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],8),"%d")==sscanf(trim($aDate["mday"]),"%d")))
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht4))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                else
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote,";
                                    $Query .= " VOT_Total = ".($aRow["VOT_Note"]+$aRow["VOT_Total"]).",";
                                    $Query .= " VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
                                    $Query .= " WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht4))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                            else
                            {   $Query = "INSERT INTO Votes (VOT_Pseudo,VOT_Fichier,VOT_Note,VOT_Date) VALUES (";
                                // Pseudo
                                $Query .= "'".addslashes($Camarade)."',";
                                // Fichier
                                $Query .= "'".base64_decode(urldecode($pht4))."',";
                                // Note
                                $Query .= "$iVote,";
                                // Date
                                $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."')";
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                        }
                        if((!Empty($pht5))&&(strcmp(trim($pht5),""))&&(!Empty($vtpht5)))
                        {   switch($vtpht5)
                            {   case 1:
                                {   $iVote = -2;
                                    break;
                                }
                                case 2:
                                {   $iVote = -1;
                                    break;
                                }
                                case 3:
                                {   $iVote = 1;
                                    break;
                                }
                                case 4:
                                {   $iVote = 2;
                                    break;
                                }
                            }
                            $Query = "SELECT VOT_Date, VOT_Note, VOT_Total FROM Votes WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht5))."'";
                            $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   $aRow = mysql_fetch_array($Result);
                                if((sscanf(substr($aRow["VOT_Date"],0,4),"%d")==sscanf(trim($aDate["year"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],5,2),"%d")==sscanf(trim($aDate["mon"]),"%d"))&&
                                   (sscanf(substr($aRow["VOT_Date"],8),"%d")==sscanf(trim($aDate["mday"]),"%d")))
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht5))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                else
                                {   mysql_free_result($Result);
                                    $Query = "UPDATE Votes SET VOT_Note = $iVote,";
                                    $Query .= " VOT_Total = ".($aRow["VOT_Note"]+$aRow["VOT_Total"]).",";
                                    $Query .= " VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
                                    $Query .= " WHERE VOT_Fichier LIKE '".base64_decode(urldecode($pht5))."'";
                                    $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                                }
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                            else
                            {   $Query = "INSERT INTO Votes (VOT_Pseudo,VOT_Fichier,VOT_Note,VOT_Date) VALUES (";
                                // Pseudo
                                $Query .= "'".addslashes($Camarade)."',";
                                // Fichier
                                $Query .= "'".base64_decode(urldecode($pht5))."',";
                                // Note
                                $Query .= "$iVote,";
                                // Date
                                $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."')";
                                if(!mysql_query(trim($Query),$Link))
                                {   mysql_close($Link);
                                    $Msg = "Une erreur est intervenue durant le vote! Contact le <font color=\"#808080\">Webmaster</font> si le probl&egrave;me persiste...";
                                    include("Message.php");
                                    die();
                                }
                            }
                        }
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
{   // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(Empty($Link))
    {   $Msg = "Connexion au serveur impossible!";
        include("Message.php");
        die();
    }
    mysql_select_db(GetMySqlDB(),$Link);
    //$Msg = "Tu n'est pas connect&eacute;!";
    //include("Message.php");
    //die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Photos</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/publication.css">
<style type="text/css">
a {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: blue}
form {padding: 0px; margin-bottom: 0px; border: 0px}
#Page {font-size: 12pt; font-family: Impact,Verdana,Lucida; color: gray}
#Title {font-size: 22pt; font-family: Impact,Verdana,Lucida; color: yellow}
#Info {font-size: 12pt; font-family: Impact,Verdana,Lucida}
#Comment {font-size: 8pt; font-family: Verdana,Lucida,Courier}
</style>
<script src="Librairies/publication.js"></script>
<script type="text/javascript">
<!--
// VerifyAddComment ////////////////////////////////////////////////////////////////////////////////
function VerifyAddComment(CommentID)
{   if(document.getElementById(CommentID).value=="")
    {   alert("Commentaire vide!!!");
        return false;
    }
    return true;
}
// AffectAllVotes //////////////////////////////////////////////////////////////////////////////////
function AffectAllVotes()
{   document.getElementById("PhtVote1").value=document.getElementById("PhtFile1").value;
    if(document.getElementById("Opt1Pht1").checked) document.getElementById("OptVote1").value=1;
    else if(document.getElementById("Opt2Pht1").checked) document.getElementById("OptVote1").value=2;
    else if(document.getElementById("Opt3Pht1").checked) document.getElementById("OptVote1").value=3;
    else if(document.getElementById("Opt4Pht1").checked) document.getElementById("OptVote1").value=4;
    else document.getElementById("OptVote1").value=0;
    if(document.getElementById("PhtFile2"))
    {   document.getElementById("PhtVote2").value=document.getElementById("PhtFile2").value;
        if(document.getElementById("Opt1Pht2").checked) document.getElementById("OptVote2").value=1;
        else if(document.getElementById("Opt2Pht2").checked) document.getElementById("OptVote2").value=2;
        else if(document.getElementById("Opt3Pht2").checked) document.getElementById("OptVote2").value=3;
        else if(document.getElementById("Opt4Pht2").checked) document.getElementById("OptVote2").value=4;
        else document.getElementById("OptVote2").value=0;
    }
    if(document.getElementById("PhtFile3"))
    {   document.getElementById("PhtVote3").value=document.getElementById("PhtFile3").value;
        if(document.getElementById("Opt1Pht3").checked) document.getElementById("OptVote3").value=1;
        else if(document.getElementById("Opt2Pht3").checked) document.getElementById("OptVote3").value=2;
        else if(document.getElementById("Opt3Pht3").checked) document.getElementById("OptVote3").value=3;
        else if(document.getElementById("Opt4Pht3").checked) document.getElementById("OptVote3").value=4;
        else document.getElementById("OptVote3").value=0;
    }
    if(document.getElementById("PhtFile4"))
    {   document.getElementById("PhtVote4").value=document.getElementById("PhtFile4").value;
        if(document.getElementById("Opt1Pht4").checked) document.getElementById("OptVote4").value=1;
        else if(document.getElementById("Opt2Pht4").checked) document.getElementById("OptVote4").value=2;
        else if(document.getElementById("Opt3Pht4").checked) document.getElementById("OptVote4").value=3;
        else if(document.getElementById("Opt4Pht4").checked) document.getElementById("OptVote4").value=4;
        else document.getElementById("OptVote4").value=0;
    }
    if(document.getElementById("PhtFile5"))
    {   document.getElementById("PhtVote5").value=document.getElementById("PhtFile5").value;
        if(document.getElementById("Opt1Pht5").checked) document.getElementById("OptVote5").value=1;
        else if(document.getElementById("Opt2Pht5").checked) document.getElementById("OptVote5").value=2;
        else if(document.getElementById("Opt3Pht5").checked) document.getElementById("OptVote5").value=3;
        else if(document.getElementById("Opt4Pht5").checked) document.getElementById("OptVote5").value=4;
        else document.getElementById("OptVote5").value=0;
    }
    return true;
}
//-->
</script>
</head>
<body bgcolor="#ffffff" style="margin-top: 0;margin-left: 10px">
<!-- *************************************************************************************************************************************** PHOTOS -->
<?php
$iResCnt = 0;
$iResStart = 1;
$aDate = getdate();
$iPhtCnt = 0;
$Query = "SELECT COUNT(*) AS PHT_Count FROM Photos";
$iPhtCnt = mysql_result(mysql_query(trim($Query),$Link),0,"PHT_Count");
$Query = "SELECT SUM(VOT_Note)+SUM(VOT_Total) AS VOT_Pos,VOT_Fichier FROM Votes WHERE VOT_Type = 0 GROUP BY VOT_Fichier ORDER BY VOT_Pos DESC";
$Result = mysql_query(trim($Query),$Link);
$iLastVote = 0;
while($aRow = mysql_fetch_array($Result))
{   if($iLastVote != $aRow["VOT_Pos"]) $aClassement[] = $aRow["VOT_Pos"];
    $iLastVote = $aRow["VOT_Pos"];
}
mysql_free_result($Result);
$Query = "SELECT PHT_Pseudo,PHT_Fichier,PHT_FichierID,V1.VOT_Note AS PHT_Note,V1.VOT_Total AS PHT_Total,SUM(V2.VOT_Note) AS PHT_AllNote,SUM(V2.VOT_Total) AS PHT_AllTotal";
$Query .= " FROM Photos LEFT JOIN Votes AS V1 ON PHT_Fichier = V1.VOT_Fichier AND UPPER(V1.VOT_Pseudo) = UPPER('".addslashes($Camarade)."') AND V1.VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."' AND V1.VOT_Type = 0 LEFT JOIN Votes AS V2 ON PHT_Fichier = V2.VOT_Fichier AND V2.VOT_Type = 0";
$Query .= " WHERE UPPER(PHT_Album) = UPPER('".addslashes(base64_decode(urldecode(trim($albnm))))."')";
// Gestion de l'affichage d'1 photo
if(!Empty($vwpht)) $Query .= " AND PHT_Fichier LIKE '".base64_decode(urldecode($vwpht))."'";
//
$Query .= " GROUP BY PHT_Pseudo,PHT_Fichier,PHT_FichierID,PHT_Note,PHT_Total ORDER BY PHT_Fichier";
$Result = mysql_query(trim($Query),$Link);
$iResCnt = mysql_num_rows($Result);
$iResEnd = $iResCnt;
if($iResCnt > 5)
{   if(!Empty($vwu))
    {   $iResStart = ($vwu * 5) + 1;
        $iResEnd = $iResStart + 4;
        if($iResEnd > $iResCnt) $iResEnd = $iResStart + ($iResCnt - $iResStart);
    }
    else
    {   $iResStart = 1;
        $iResEnd = 5;
    }
}
$bPass = false;
$iNumPht = $iResStart;
$CntView = 0;
$iPhtPos = 0;
$indice = 1;
$iVoteRef = 1;
while($aRow = mysql_fetch_array($Result))
{   // Tant qu'il y a des photos
    $CntView++;
    if(($CntView >= $iResStart)&&($CntView <= $iResEnd))
    {   // Affiche la photo
        if($bPass)
        {   // Lag
?>
<table border=0 height=10 cellspacing=0 cellpadding=0>
<tr>
<td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<?php
            // Lag
        }
        else $bPass = true;
?>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width="50%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
<td align="left">
    <table border=0 width=65 cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/SubOranHG.jpg"></td>
    <td width=50><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    <td width=5 bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=50><img src="<?php echo GetFolder(); ?>/Images/DosPhoto.gif"></td>
    <td width=10><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td nowrap><font ID="Title"><?php
    if(!Empty($vwpht)) echo $aRow["PHT_Fichier"];
    else echo "N&deg;&nbsp;<font color=\"#000000\">$iNumPht</font>";
    ?></font></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5 valign="bottom" bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/OranInBG.jpg"></td>
    </tr>
    </table>
    <table border=0 width="100%" cellspacing=0 cellpadding=0 bgcolor="#ff8000">
    <tr>
    <td width=5>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td width=5><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranHD.jpg"></td>
    <td width=5 bgcolor="#ffffff">
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHG.jpg"></td>
    <td width=150 bgcolor="#BACC9A">
        <table border=0 width=150 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonHD.jpg"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInHG.jpg"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInHD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td align="center" valign="middle" bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Photos/<?php echo $aRow["PHT_Fichier"]; ?>"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A" valign="top">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHG.jpg"></td>
        <td width="100%" bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td width=5><img src="<?php echo GetFolder(); ?>/Images/FonCadInHD.jpg"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><font ID="Info">Album:&nbsp;&nbsp;&nbsp;<input type="text" ID="AlbumPhoto<?php echo $iNumPht; ?>" style="background-color: #d6d6d6; font-size: 8pt; font-family: Verdana,Lucida,Courier" size=12 readonly></font>
        <script type="text/javascript">
        <!--
        // Commande ////////////////////////////////////////////////////////////////////////////////
        document.getElementById("AlbumPhoto<?php echo $iNumPht; ?>").value="<?php echo str_replace("\'","'",addslashes(base64_decode(urldecode($albnm)))); ?>";
        -->
        </script>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><div style="width: 139px; height: 20px; overflow: hidden">
             <table border=0 cellspacing=0 cellpadding=0>
             <tr>
             <td nowrap><font ID="Info">Par:&nbsp;</font><font ID="Comment"><font style="font-size: 12pt; color: #8000ff"><?php echo stripslashes($aRow["PHT_Pseudo"]); ?></font></font></td>
             </tr>
             </table>
        </div></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
        if(count($aClassement) != 0)
        {   $iPhtPos = 0;
            $indice = 1;
            foreach($aClassement as $Classement)
            {   if($Classement == ($aRow["PHT_AllNote"]+$aRow["PHT_AllTotal"])) $iPhtPos = $indice;
                $indice++;
            }
            if($iPhtPos != 0)
            {   // Affiche le classement
        ?>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><font ID="Info">Classement:&nbsp;&nbsp;<input type="text" style="background-color: #d6d6d6; font-size: 8pt; font-family: Verdana,Lucida,Courier" size=6 value="<?php echo "$iPhtPos/$iPhtCnt"; ?>" readonly></font></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
                // Affiche le classement
            }
        }
        ?>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td nowrap><font ID="Info">Commentaires:</font></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td>
        <div style="width: 139px; height: 182px; overflow: auto"><font ID="Comment"><?php echo GetComments($Clf,$Camarade,$Link,'P',$aRow["PHT_FichierID"]); ?></font></div>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
        if(!Empty($Clf))
        {   // Connecté
        ?>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><font ID="Info">Ton commentaire:</font></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td>
            <font ID="Comment">
            <form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
            <input type="hidden" name="albvwu" value=<?php
            if(!Empty($albvwu)) echo $albvwu;
            else echo "0";
            ?>>
            <input type="hidden" name="albtri" value=<?php
            if(!Empty($albtri)) echo $albtri;
            else echo "0";
            ?>>
            <input type="hidden" name="albnm" value="<?php echo $albnm; ?>">
            <input type="hidden" ID="PhtFile<?php echo $iVoteRef; ?>" name="pht" value="<?php echo urlencode(base64_encode($aRow["PHT_Fichier"])); ?>">
            <?php
            if(!Empty($vwpht))
            {   // Gestion de l'affichage d'1 photo
            ?>
            <input type="hidden" name="vwpht" value="<?php echo urlencode(base64_encode($aRow["PHT_Fichier"])); ?>">
            <?php
                // Gestion de l'affichage d'1 photo
            }
            ?>
            <input type="hidden" name="vwu" value=<?php
            if(!Empty($vwu)) echo $vwu;
            else echo "0";
            ?>>
            <input type="hidden" name="ope" value=1>
            <input type="text" ID="NewComment<?php echo $iNumPht; ?>" style="font-size: 8pt; font-family: Verdana,Lucida,Courier" name="cmmt" size=16>&nbsp;<input type="submit" onclick="return VerifyAddComment('NewComment<?php echo $iNumPht; ?>')" style="font-size: 8pt" value="Ok">
            </form>
            </font>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><font ID="Info">Ton vote:</font></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr bgcolor="#d8e1c6">
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td>
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td width="50%" align="center"><img src="<?php echo GetFolder(); ?>/Images/Unsmile.jpg"></td>
            <td width=20><input type="radio" ID="Opt1Pht<?php echo $iVoteRef; ?>" name="vot<?php echo $iVoteRef; ?>" value=1<?php
            if((!Empty($aRow["PHT_Note"]))&&($aRow["PHT_Note"] == -2)) echo " checked";
            ?>></td>
            <td width=20><input type="radio" ID="Opt2Pht<?php echo $iVoteRef; ?>" name="vot<?php echo $iVoteRef; ?>" value=2<?php
            if((!Empty($aRow["PHT_Note"]))&&($aRow["PHT_Note"] == -1)) echo " checked";
            ?>></td>
            <td width=20><input type="radio" ID="Opt3Pht<?php echo $iVoteRef; ?>" name="vot<?php echo $iVoteRef; ?>" value=3<?php
            if((!Empty($aRow["PHT_Note"]))&&($aRow["PHT_Note"] == 1)) echo " checked";
            ?>></td>
            <td width=20><input type="radio" ID="Opt4Pht<?php echo $iVoteRef; ?>" name="vot<?php echo $iVoteRef; ?>" value=4<?php
            if((!Empty($aRow["PHT_Note"]))&&($aRow["PHT_Note"] == 2)) echo " checked";
            ?>></td>
            <td width="50%" align="center"><img src="<?php echo GetFolder(); ?>/Images/Smile.jpg"></td>
            </tr>
            </table>
        </td>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <?php
            // Connecté
        }
        ?>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonCadInBG.jpg"></td>
        <td bgcolor="#d8e1c6"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        <td><img src="<?php echo GetFolder(); ?>/Images/FonCadInBD.jpg"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInBG.jpg"></td>
    <td bgcolor="#000000"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/OraNoiInBD.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td>
        <table border=0 height=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBG.jpg"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubOranBD.jpg"></td>
    <td bgcolor="#ffffff"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonBG.jpg"></td>
    <td bgcolor="#BACC9A"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    <td><img src="<?php echo GetFolder(); ?>/Images/SubFonBD.jpg"></td>
    </tr>
    </table>
</td>
<td width="50%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
</tr>
</table>
<?php
        // Affiche la photo
        $iNumPht++;
        $iVoteRef++;
    }
    // Tant qu'il y a des photos
}
mysql_free_result($Result);
mysql_close($Link);
?>
<br><hr>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width=90 align="left">
    <table border=0 width=90 cellspacing=0 cellpadding=0>
    <tr>
    <td><?php
    // Gestion de l'affichage d'1 photo
    if(!Empty($vwpht))
    {   // Pas de Retour
    ?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
        // Pas de Retour
    }
    else
    {   // Retour
    ?><a href="index.php?Chp=7&vwu=<?php
    if(!Empty($albvwu)) echo $albvwu;
    else echo "0";
    ?>&trcfg=<?php
    if(!Empty($albtri)) echo $albtri;
    else echo "0";
    ?>&Clf=<?php echo $Clf; ?>" target="_top">Retour</a><?php
        // Retour
    }
    ?></td>
    </tr>
    </table>
</td>
<td width=52><?php
if((!Empty($iResStart))&&($iResStart != 1))
{   // Précédent
?>
<form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="vwu" value=<?php echo ($vwu-1); ?>>
<input type="hidden" name="albvwu" value=<?php
if(!Empty($albvwu)) echo $albvwu;
else echo "0";
?>>
<input type="hidden" name="albtri" value=<?php
if(!Empty($albtri)) echo $albtri;
else echo "0";
?>>
<input type="hidden" name="albnm" value="<?php echo $albnm; ?>">
<input type="image" src="<?php echo GetFolder(); ?>/Images/Previous.jpg">
</form>
<?php
    // Précédent
}
else
{   // Pas Précédent
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas Précédent
}
?></td>
<td width=35><?php
if((!Empty($iResStart))&&($iResStart != 1))
{   // Begin
?>
<form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="vwu" value=<?php echo 0; ?>>
<input type="hidden" name="albvwu" value=<?php
if(!Empty($albvwu)) echo $albvwu;
else echo "0";
?>>
<input type="hidden" name="albtri" value=<?php
if(!Empty($albtri)) echo $albtri;
else echo "0";
?>>
<input type="hidden" name="albnm" value="<?php echo $albnm; ?>">
<input type="image" src="<?php echo GetFolder(); ?>/Images/BeginRes.jpg">
</form>
<?php
    // Begin
}
else
{   // Pas Begin
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas Begin
}
?></td>
<td width="100%" align="center"><?php
$iCntPage = ceil($iResCnt / 5);
if($iCntPage >= 3)
{   // Affiche les liens
?>
    <table border=0 cellspacing=0 cellpadding=0>
    <tr>
<?php
    $iAffCnt = 1;
    $iCurPage = $vwu + 1;
    $iLien = 1;
    if(($iCntPage > 10)&&(($iCurPage - 4) > 1))
    {   if(($iCurPage - 4) > ($iCntPage - 8)) $iLien = $iCntPage - 8;
        else $iLien = $iCurPage - 4;
    }
    $bPass = false;
    while($iLien != ($iCntPage + 1))
    {   if((($iAffCnt <= 10)&&($iCntPage <= 10))||(($iAffCnt <= 9)&&($iCntPage > 10)))
        {   if(($iAffCnt == 1)&&(($iCurPage - 4) > 1)&&($iCntPage > 10))
            {   // Suspension
?>
    <td><font ID="Page">...</font></td>
<?php
                // Suspension
                $bPass = true;
            }
            // Affiche le lien
            if($bPass)
            {   // Lag
?>
    <td>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
<?php
                // Lag
            }
            else $bPass = true;
            if($iLien != $iCurPage)
            {   // Autre page
?>
    <td><a href="Photo.php?albvwu=<?php
    if(!Empty($albvwu)) echo $albvwu;
    else echo "0";
    ?>&albtri=<?php
    if(!Empty($albtri)) echo $albtri;
    else echo "0";
    ?>&albnm=<?php echo $albnm; ?>&vwu=<?php echo ($iLien - 1); ?>&Clf=<?php echo $Clf; ?>"><?php echo $iLien; ?></a></td>
<?php
                // Autre page
            }
            else
            {   // Page courante
?>
    <td><font ID="Page"><?php echo $iLien; ?></font></td>
<?php
                // Page courante
            }
            if(($iAffCnt == 9)&&($iCntPage > 10)&&(($iCurPage + 4) < $iCntPage))
            {   // Suspension
?>
    <td>
        <table border=0 width=5 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td><font ID="Page">...</font></td>
<?php
                // Suspension
            }
            // Affiche le lien
            $iAffCnt++;
        }
        $iLien++;
    }
?>
    </tr>
    </table>
<?php
    // Affiche les liens
}
else
{   // Pas de liens
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas de liens
}
?></td>
<td width=35><?php
if($iResEnd < $iResCnt)
{   // End
?>
<form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="albvwu" value=<?php
if(!Empty($albvwu)) echo $albvwu;
else echo "0";
?>>
<input type="hidden" name="albtri" value=<?php
if(!Empty($albtri)) echo $albtri;
else echo "0";
?>>
<input type="hidden" name="albnm" value="<?php echo $albnm; ?>">
<input type="hidden" name="vwu" value=<?php echo ceil($iResCnt / 5) - 1; ?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/EndRes.jpg">
</form>
<?php
    // End
}
else
{   // Pas End
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas End
}
?></td>
<td width=52><?php
if($iResEnd < $iResCnt)
{   // Suivant
?>
<form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
<input type="hidden" name="albvwu" value=<?php
if(!Empty($albvwu)) echo $albvwu;
else echo "0";
?>>
<input type="hidden" name="albtri" value=<?php
if(!Empty($albtri)) echo $albtri;
else echo "0";
?>>
<input type="hidden" name="albnm" value="<?php echo $albnm; ?>">
<input type="hidden" name="vwu" value=<?php
if(!Empty($vwu)) echo ($vwu+1);
else echo "1";
?>>
<input type="image" src="<?php echo GetFolder(); ?>/Images/Next.jpg">
</form>
<?php
    // Suivant
}
else
{   // Pas Suivant
?><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"><?php
    // Pas Suivant
}
?></td>
<?php
if(!Empty($Clf))
{   // Connecté
?>
<td width=90 align="right">
    <table border=0 width=90 cellspacing=0 cellpadding=0>
    <tr>
    <td width=90 align="right">
    <form action="Photo.php?Clf=<?php echo $Clf; ?>" method="post">
    <input type="hidden" name="albvwu" value=<?php
    if(!Empty($albvwu)) echo $albvwu;
    else echo "0";
    ?>>
    <input type="hidden" name="albtri" value=<?php
    if(!Empty($albtri)) echo $albtri;
    else echo "0";
    ?>>
    <input type="hidden" name="vwu" value=<?php
    if(!Empty($vwu)) echo $vwu;
    else echo "0";
    ?>>
    <input type="hidden" ID="PhtVote1" name="pht1" value="">
    <input type="hidden" ID="OptVote1" name="vtpht1" value=0>
    <input type="hidden" ID="PhtVote2" name="pht2" value="">
    <input type="hidden" ID="OptVote2" name="vtpht2" value=0>
    <input type="hidden" ID="PhtVote3" name="pht3" value="">
    <input type="hidden" ID="OptVote3" name="vtpht3" value=0>
    <input type="hidden" ID="PhtVote4" name="pht4" value="">
    <input type="hidden" ID="OptVote4" name="vtpht4" value=0>
    <input type="hidden" ID="PhtVote5" name="pht5" value="">
    <input type="hidden" ID="OptVote5" name="vtpht5" value=0>
    <input type="hidden" name="albnm" value="<?php echo $albnm; ?>">
    <?php
    if(!Empty($vwpht))
    {   // Gestion de l'affichage d'1 photo
    ?>
    <input type="hidden" name="vwpht" value="<?php echo $vwpht; ?>">
    <?php
        // Gestion de l'affichage d'1 photo
        }
    ?>
    <input type="hidden" name="ope" value=2>
    <input type="submit" onclick="return AffectAllVotes()" value=" Voter ">
    </form>
    </td>
    </tr>
    </table>
</td>
<?php
    // Connecté
}
?>
</tr>
</table>
<!-- ********************************************************************************************************************************************** -->
</body>
</html>
