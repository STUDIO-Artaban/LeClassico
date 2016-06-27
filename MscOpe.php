<?php
require("Package.php");
$Chp = "10";
$Clf = $_GET['Clf'];
$Tri = 0;
$SelFile = "";
$Extension = "";
$bNext = false;
$bPrev = false;
$bAutoPlay = false;
$bLoop = true;
$Status = "<font ID='Status'>Pr&ecirc;t</font>";
//if(!Empty($Clf))
//{
    // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(Empty($Link)) $Status = "<font ID='Status'>Connexion au serveur impossible!</font>";
    else
    {   mysql_select_db(GetMySqlDB(),$Link);
        if(!Empty($Clf))
        {   $Camarade = DistUserKeyId($Clf);
            $Query = "SELECT CAM_Pseudo FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
            //mysql_select_db(GetMySqlDB(),$Link);
            $Result = mysql_query(trim($Query),$Link);
            if(mysql_num_rows($Result) != 0)
            {   $aRow = mysql_fetch_array($Result);
                $Camarade = stripslashes($aRow["CAM_Pseudo"]);
                mysql_free_result($Result);
            }
            else
            {   mysql_close($Link);
                $Status = "<font ID='Status'>Ton pseudo est inconnu!</font>";
            }
        }
        else $Camarade = "";

            $iCurCnt = 0;
            $iLastVote = 0;
            $iVote = 0;
            $iTmp = 0;
            $LastFile = "";
            $LastExt = "";
            $aDate = getdate();
            if(!Empty($trcfg)) $Tri = $trcfg;
            if(!Empty($auto)) $bAutoPlay = true;
            if(!Empty($loop)) $bLoop = false;
            if(!Empty($file))
            {   $SelFile = $file;
                //
                if((Empty($qry))||((!Empty($resope)&&($resope == 1))))
                {   $Query = "SELECT MSC_Fichier FROM Music WHERE MSC_Fichier LIKE '$SelFile%'";
                    $Result = mysql_query(trim($Query),$Link);
                    $aRow = mysql_fetch_array($Result);
                    $Extension = substr($aRow["MSC_Fichier"],strlen($aRow["MSC_Fichier"])-3);
                    mysql_free_result($Result);
                }
                else
                {   $Extension = substr($SelFile,strlen($SelFile)-3);
                    $SelFile = substr($SelFile,0,strlen($SelFile)-4);
                }
                //
            }
            if(!Empty($ope))
            {   if($ope == 1)
                {   // 1: Supprimer /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $Query = "SELECT 'X' FROM Music WHERE MSC_Fichier LIKE '$SelFile%' AND UPPER(MSC_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    $Result = mysql_query(trim($Query),$Link);
                    if(mysql_num_rows($Result) != 0)
                    {   // Supprime la photo de toute la table Photos
                        $Query = "DELETE FROM Music WHERE MSC_Fichier LIKE '$SelFile%'";
                        if(mysql_query(trim($Query),$Link))
                        {   // Supprime la photo de la table Votes
                            $Query = "DELETE FROM Votes WHERE VOT_Fichier LIKE '$SelFile%'";
                            mysql_query(trim($Query),$Link);
                            // Supprime la photo du serveur
                            @unlink(GetSrvMscFolder()."$SelFile.$Extension");
                            $Status = "<font ID='Status'>Morceau supprim&eacute; !!!</font>";
                        }
                        else $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la suppression !!!</font>";
                    }
                    else $Status = "<font ID='Status'>Vous n'avez pas les droits !!!</font>";
                    $SelFile = "";
                }
                else
                {   // 2: Voter /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    if(!Empty($vtmsc))
                    {   switch($vtmsc)
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
                        $Query = "SELECT VOT_Date, VOT_Note, VOT_Total FROM Votes WHERE VOT_Fichier LIKE '$SelFile.$Extension'";
                        $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                        $Result = mysql_query(trim($Query),$Link);
                        if(mysql_num_rows($Result) != 0)
                        {   $aRow = mysql_fetch_array($Result);
                            if((sscanf(substr($aRow["VOT_Date"],0,4),"%d")==sscanf(trim($aDate["year"]),"%d"))&&
                               (sscanf(substr($aRow["VOT_Date"],5,2),"%d")==sscanf(trim($aDate["mon"]),"%d"))&&
                               (sscanf(substr($aRow["VOT_Date"],8),"%d")==sscanf(trim($aDate["mday"]),"%d")))
                            {   mysql_free_result($Result);
                                $Query = "UPDATE Votes SET VOT_Note = $iVote WHERE VOT_Fichier LIKE '$SelFile.$Extension'";
                                $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            }
                            else
                            {   mysql_free_result($Result);
                                $Query = "UPDATE Votes SET VOT_Note = $iVote,";
                                $Query .= " VOT_Total = ".($aRow["VOT_Note"]+$aRow["VOT_Total"]).",";
                                $Query .= " VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."'";
                                $Query .= " WHERE VOT_Fichier LIKE '$SelFile.$Extension'";
                                $Query .= " AND UPPER(VOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            }
                            if(!mysql_query(trim($Query),$Link)) $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant le vote !!!</font>";
                            else $Status = "<font ID='Status'>A Vot&eacute; !!!</font>";
                        }
                        else
                        {   $Query = "INSERT INTO Votes (VOT_Pseudo,VOT_Fichier,VOT_Note,VOT_Type,VOT_Date) VALUES (";
                            // Pseudo
                            $Query .= "'".addslashes($Camarade)."',";
                            // Fichier
                            $Query .= "'$SelFile.$Extension',";
                            // Note
                            $Query .= "$iVote,";
                            // Type
                            $Query .= "1,";
                            // Date
                            $Query .= "'".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."')";
                            if(!mysql_query(trim($Query),$Link)) $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant le vote !!!</font>";
                            else $Status = "<font ID='Status'>A Vot&eacute; !!!</font>";
                        }
                    }
                }
            }
            else if(!Empty($resope))
            {   switch($resope)
                {   case 1: // Modification: Ok
                    {   if(!Empty($qry))
                        {   $Query = "SELECT 'X' FROM Music WHERE MSC_Fichier LIKE '$SelFile%' AND UPPER(MSC_Pseudo) = UPPER('".addslashes($Camarade)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {   $Query = base64_decode(urldecode($qry));
                                if(!mysql_query(trim($Query),$Link)) $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la modification !</font>"; // Bad
                                else $Status = "<font ID='Status'>Modification termin&eacute; !</font>"; // Ok
                            }
                            else $Status = "<font ID='Status'><font color='#ff0000'>Echec</font> ! Vous n'avez pas les droits !</font>"; // Droits
                        }
                        else $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la modification !</font>"; // Bad
                        break;
                    }
                    /*case 2: // Modification: Droit
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Echec</font> ! Vous n'avez pas les droits !</font>";
                        break;
                    }
                    case 3: // Modification: Echec
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la modification !</font>";
                        break;
                    }*/
                    case 4: // Ajout: File
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Pas</font> de fichier source !</font>";
                        break;
                    }
                    case 5: // Ajout: Extension
                    {   $Status = "<font ID='Status'>Extension <font color='#ff0000'>invalide</font> (WMA ou MP3) !</font>";
                        break;
                    }
                    case 6: // Ajout: GenFile
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la g&eacute;n&eacute;ration !</font>";
                        break;
                    }
                    case 7: // Ajout: Exist
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> ! Fichier source inexistant !</font>";
                        break;
                    }
                    case 8: // Ajout: Taille
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> ! Taille du fichier &gt; 10 Mo !</font>";
                        break;
                    }
                    case 9: // Ajout: Upload
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant le t&eacute;l&eacute;chargement !</font>";
                        break;
                    }
                    /*case 10: // Ajout: MAJ
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la mise &agrave; jour !</font>";
                        break;
                    }
                    case 11: // Ajout: Bad
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant l'ajout !</font>";
                        break;
                    }*/
                    case 12: // Ajout: Ok
                    {   if(!Empty($qry))
                        {   // MAJ de la table MusicNumber
                            $Query = "SELECT MNU_MusicID FROM MusicNumber";
                            if($Result = mysql_query(trim($Query),$Link))
                            {   $aRow = mysql_fetch_array($Result);
                                $iMscID = $aRow["MNU_MusicID"];
                                mysql_free_result($Result);
                                $iMscID++;
                                $Query = "UPDATE MusicNumber SET MNU_MusicID = $iMscID";
                                if(!mysql_query(trim($Query),$Link))
                                {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la mise &agrave; jour !</font>"; // MAJ
                                    // Supprime le fichier ainsi transféré du serveur
                                    @unlink(GetSrvMscFolder()."$SelFile"."$Extension");
                                }
                                else
                                {   // Ajout dans la table Music
                                    $Query = base64_decode(urldecode($qry));
                                    if(!mysql_query(trim($Query),$Link))
                                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant l'ajout !</font>"; // Bad
                                        // Supprime le fichier ainsi transféré du serveur
                                        @unlink(GetSrvMscFolder()."$SelFile"."$Extension");
                                    }
                                    else $Status = "<font ID='Status'>Ajout termin&eacute; !</font>"; // Ok
                                }
                            }
                            else
                            {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant la g&eacute;n&eacute;ration !</font>"; // GenFile
                                // Supprime le fichier ainsi transféré du serveur
                                @unlink(GetSrvMscFolder()."$SelFile"."$Extension");
                            }
                        }
                        else
                        {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant l'ajout !</font>"; // Bad
                            // Supprime le fichier ainsi transféré du serveur
                            @unlink(GetSrvMscFolder()."$SelFile"."$Extension");
                        }
                        break;
                    }
                    case 13: // Ajout: FTP
                    {   $Status = "<font ID='Status'><font color='#ff0000'>Erreur</font> durant le connexion FTP !</font>";
                        break;
                    }
                }
            }
            $Query = "SELECT COUNT(*) AS MSC_Count FROM Music";
            $iMscCnt = mysql_result(mysql_query(trim($Query),$Link),0,"MSC_Count");
            $Query = "SELECT SUM(VOT_Note)+SUM(VOT_Total) AS VOT_Pos,VOT_Fichier FROM Votes WHERE VOT_Type = 1 GROUP BY VOT_Fichier ORDER BY VOT_Pos DESC";
            $Result = mysql_query(trim($Query),$Link);
            if(mysql_num_rows($Result) != 0)
            {   while($aRow = mysql_fetch_array($Result))
                {   if($iLastVote != $aRow["VOT_Pos"]) $aClassement[] = $aRow["VOT_Pos"];
                    $iLastVote = $aRow["VOT_Pos"];
                }
                mysql_free_result($Result);
            }
            //
            $Query = "SELECT MSC_Source,MSC_Fichier,MSC_Pseudo,MSC_Artiste,MSC_Album,MSC_Morceau,V1.VOT_Note AS MSC_Note,V1.VOT_Total AS MSC_Total,SUM(V2.VOT_Note) AS MSC_AllNote,SUM(V2.VOT_Total) AS MSC_AllTotal";
            $Query .= " FROM Music LEFT JOIN Votes AS V1 ON MSC_Fichier = V1.VOT_Fichier AND UPPER(V1.VOT_Pseudo) = UPPER('".addslashes($Camarade)."') AND V1.VOT_Date = '".trim($aDate["year"])."-".trim($aDate["mon"])."-".trim($aDate["mday"])."' AND V1.VOT_Type = 1 LEFT JOIN Votes AS V2 ON MSC_Fichier = V2.VOT_Fichier AND V2.VOT_Type = 1";
            $Query .= " GROUP BY MSC_Fichier,MSC_Pseudo,MSC_Artiste,MSC_Album,MSC_Morceau,MSC_Note,MSC_Total";
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
            while(($aRow = mysql_fetch_array($Result))&&(strcmp($SelFile,""))&&(strcmp(substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4),$SelFile)))
            {   $LastFile = substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4);
                $LastExt = substr($aRow["MSC_Fichier"],strlen($aRow["MSC_Fichier"])-3);
                $iCurCnt++;
            }
            if((Empty($iResCnt))&&(Empty($resope)))
            {   $SelFile = "";
                $Status = "<font ID='Status'>Pas de son !?!</font>";
            }
            else
            {   if(!Empty($pos))
                {   if($pos == 1)
                    {   // Previous
                        $SelFile = $LastFile;
                        $Extension = $LastExt;
                        $iCurCnt--;
                        if($iCurCnt == 0) $LastFile = "";
                        mysql_free_result($Result);
                        $Result = mysql_query(trim($Query),$Link);
                        while(($aRow = mysql_fetch_array($Result))&&($iTmp!=$iCurCnt)) $iTmp++;
                    }
                    else
                    {   // Next
                        $LastFile = substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4);
                        $LastExt = substr($aRow["MSC_Fichier"],strlen($aRow["MSC_Fichier"])-3);
                        $iCurCnt++;
                        if($aRow = mysql_fetch_array($Result))
                        {   $SelFile = substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4);
                            $Extension = substr($aRow["MSC_Fichier"],strlen($aRow["MSC_Fichier"])-3);
                        }
                        else $SelFile = "";
                    }
                }
                else
                {   $SelFile = substr($aRow["MSC_Fichier"],0,strlen($aRow["MSC_Fichier"])-4);
                    $Extension = substr($aRow["MSC_Fichier"],strlen($aRow["MSC_Fichier"])-3);
                }
                //
                if(($iCurCnt+1)<$iResCnt) $bNext = true;
                if(strcmp($LastFile,"")) $bPrev = true;
            }
        /*}
        else
        {   mysql_close($Link);
            $Status = "<font ID='Status'>Ton pseudo est inconnu!</font>";
        }*/
    }
//}
//else $Status = "<font ID='Status'>Tu n'est pas connect&eacute;!</font>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Music Operator</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<script type="text/javascript">
<!--
// Variables //////////////////////////////////////////////////
var hauteur;
// GetHauteur /////////////////////////////////////////////////
function GetHauteur()
{   if(window.innerHeight) return window.innerHeight;
    else if(top.window.document.body && top.window.document.body.offsetHeight) return top.window.document.body.offsetHeight;
    else return 0;
}
//-->
</script>
</head>
<body topmargin=0 leftmargin=0 bgcolor="#ffffff">
<script type="text/javascript">
<!--
// Commande ///////////////////////////////////////////////////
<?php
if(!Empty($man))
{   // Mode autonome
?>
hauteur = 610;
<?php
    // Mode autonome
}
else
{   // Mode classique
?>
hauteur = GetHauteur();
<?php
    // Mode classique
}
?>
top.MscMain.location.href="<?php echo GetDistSrvAddr(); ?>MscMain.php?Clf=<?php
echo $Clf;
if(strcmp($SelFile,""))
{   echo "&file=$SelFile.$Extension";
    echo "&art=".urlencode(base64_encode(trim($aRow["MSC_Artiste"])));
    echo "&alb=".urlencode(base64_encode(trim($aRow["MSC_Album"])));
    echo "&mrc=".urlencode(base64_encode(trim($aRow["MSC_Morceau"])));
    echo "&psd=".urlencode(base64_encode(trim($aRow["MSC_Pseudo"])));
    echo "&src=".urlencode(base64_encode(trim($aRow["MSC_Source"])));
    if(count($aClassement) != 0)
    {   $iMscPos = 0;
        $indice = 1;
        foreach($aClassement as $Classement)
        {   if($Classement == ($aRow["MSC_AllNote"]+$aRow["MSC_AllTotal"])) $iMscPos = $indice;
            $indice++;
        }
        if($iMscPos != 0) echo "&cls=".urlencode(base64_encode("$iMscPos/$iMscCnt"));
        else echo "&cls=".urlencode(base64_encode("?/$iMscCnt"));
    }
    else echo "&cls=".urlencode(base64_encode("?/$iMscCnt"));
    if(!Empty($aRow["MSC_Note"])) echo "&nt=".$aRow["MSC_Note"];
    if($bPrev == true) echo "&prv=1";
    if($bNext == true) echo "&nxt=1";
    if($bLoop == true) echo "&lp=1";
    if($bAutoPlay == true) echo "&otp=1";
}
if(!Empty($Tri)) echo "&trcfg=$Tri";
if(!Empty($man)) echo "&man=$man";
$Query = "SELECT MNU_MusicID FROM MusicNumber";
if($ResID = mysql_query(trim($Query),$Link))
{   $aRowID = mysql_fetch_array($ResID);
    echo "&mid=".$aRowID["MNU_MusicID"];
    mysql_free_result($ResID);
}
echo "&stt=".urlencode(base64_encode(trim($Status)));
?>&htr="+hauteur;
//-->
</script>
</body>
<?php
if(strcmp($SelFile,"")) mysql_free_result($Result);
mysql_close($Link);
?>
</html>
