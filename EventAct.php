<?php
require("Package.php");
$Chp = "15";
$Clf = $_GET['Clf'];
$eveid = $_POST['eveid'];
$evenm = $_POST['evenm'];
$evedate = $_POST['evedate'];
$evelieu = $_POST['evelieu'];
$evermk = $_POST['evermk'];
$evefly = $_POST['evefly'];
$ope = $_POST['ope'];
$evechgf = $_POST['evechgf'];
$iStatus = 0;
/* ERROR MESSAGE (iStatus):
   2 - Clef vide (non connect�)
   3 - Echec de la connexion au serveur SQL
   4 - Pseudo du camarade inconnu
   5 - Fichier ou extension du fichier non valide
   6 - Echec de la g�n�ration du nouveau nom du fichier
   7 - Echec de la connexion au serveur FTP
   8 - Echec du login FTP
   9 - Fichier source inexistant
   10 - Taille du flyer > 100 Ko
   11 - Espace disque du serveur insuffisant
   12 - Echec de l'upload
   13 - Echec durant la mise � jour des nouveaux nom de fichier
   14 - Echec durant l'ajout ou modification de l'�v�nement
   15 - Ok...
   16 - Suppression en cours
   17 - Echec de la suppression de l'�v�nement: ???
   18 - Suppression r�ussi
   19 - Modification de l'�v�nement en cours
   20 - Enregistrement du nouvel �v�nement en cours
   21 - Nom de l'�v�nement invalide
   22 - Lieu invalide
   23 - Date non valide
   24 - Nom de l'�v�nement d�j� existant
*/
if(!Empty($Clf))
{   // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(!Empty($Link))
    {   $Camarade = UserKeyIdentifier($Clf);
        $Query = "SELECT CAM_Pseudo,CAM_LogDate FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            if(!Empty($ope))
            {   if($ope == 1)
                {   // Modifie l'�v�nement ////////////////////////////////////////////////////////////////////////////////////////////////
                    if((Empty($evenm))||(!strcmp(trim($evenm),""))) $iStatus = 21; //****** Nom invalide
                    elseif((Empty($evelieu))||(!strcmp(trim($evelieu),""))) $iStatus = 22; //****** Lieu invalide
                    elseif((Empty($evedate))||(!strcmp(trim($evedate),""))||(!strcmp(trim($evedate),"AAAA-MM-JJ hh:mm"))) $iStatus = 23; //****** Date invalide
                    else
                    {   $aDate = sscanf(trim($evedate),"%d-%d-%d");
                        if(!checkdate($aDate[1],$aDate[2],$aDate[0])) $iStatus = 23; //****** Date invalide
                        else
                        {   $Query = "SELECT 'X' FROM Evenements WHERE EVE_Status <> 2 AND EVE_Date = '".trim($evedate).":00' AND UPPER(EVE_Nom) = UPPER('".trim($evenm)."') AND EVE_EventID <> $eveid";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {  mysql_free_result($Result);
                               $iStatus = 24; //****** Event exist
                            }
                        }
                    }
                    if($iStatus == 0)
                    {   // Modifie l'�v�nement
                        $Query = "UPDATE Evenements SET ";
                        // Nom
                        $Query .= "EVE_Nom = '".addslashes($evenm)."',";
                        // Lieu
                        $Query .= "EVE_Lieu = '".addslashes($evelieu)."',";
                        // Date
                        $Query .= "EVE_Date = '".trim($evedate).":00'";
                        // Remark
                        if((!Empty($evermk))&&(strcmp(trim($evermk),""))) $Query .= ",EVE_Remark = '".addslashes($evermk)."'";
                        // Flyer
                        if(!Empty($File)) $Query .= ",EVE_Flyer = '".trim($File)."'";
                        $Query .= " WHERE EVE_EventID = $eveid";
                        if(!mysql_query(trim($Query),$Link))
                        {   $iStatus = 14; //****** Evenement
                            if(!Empty($File))
                            {   // Supprime le flyer ainsi transf�r� du serveur
                                @unlink(GetSrvFlyFolder()."$File");
                            }
                        }
                        else $iStatus = 15; // Ok...!
                    }
                }
                elseif($ope == 2)
                {   // Ajoute l'�v�nement /////////////////////////////////////////////////////////////////////////////////////////////////
                    if((Empty($evenm))||(!strcmp(trim($evenm),""))) $iStatus = 21; //****** Nom invalide
                    elseif((Empty($evelieu))||(!strcmp(trim($evelieu),""))) $iStatus = 22; //****** Lieu invalide
                    elseif((Empty($evedate))||(!strcmp(trim($evedate),""))||(!strcmp(trim($evedate),"AAAA-MM-JJ hh:mm"))) $iStatus = 23; //****** Date invalide
                    else
                    {   $aDate = sscanf(trim($evedate),"%d-%d-%d");
                        if(!checkdate($aDate[1],$aDate[2],$aDate[0])) $iStatus = 23; //****** Date invalide
                        else
                        {   $Query = "SELECT 'X' FROM Evenements WHERE EVE_Status <> 2 AND EVE_Date = '".trim($evedate).":00' AND UPPER(EVE_Nom) = UPPER('".trim($evenm)."')";
                            $Result = mysql_query(trim($Query),$Link);
                            if(mysql_num_rows($Result) != 0)
                            {  mysql_free_result($Result);
                               $iStatus = 24; //****** Event exist
                            }
                        }
                    }
                    if($iStatus == 0)
                    {   // Ajoute l'�v�nement
                        $Query = "SELECT MAX(EVE_EventID) AS EVEMAXID FROM Evenements";
                        $Result = mysql_query(trim($Query),$Link);
                        if(mysql_num_rows($Result) != 0)
                        {   $aRow = mysql_fetch_array($Result);
                            $iNewEveID = $aRow["EVEMAXID"];
                            $iNewEveID++;
                            mysql_free_result($Result);
                            $Query = "INSERT INTO Evenements (EVE_EventID,EVE_Pseudo,EVE_Nom,EVE_Date,EVE_Lieu,EVE_Remark,EVE_Flyer,";
                            $Query .= "EVE_NomUPD,EVE_DateUPD,EVE_LieuUPD,EVE_RemarkUPD,EVE_FlyerUPD) VALUES (";
                            // EventID
                            $Query .= "$iNewEveID,'";
                            // Pseudo
                            $Query .= addslashes($Camarade)."','";
                            // Nom
                            $Query .= addslashes($evenm)."','";
                            // Date
                            $Query .= trim($evedate).":00','";
                            // Lieu
                            $Query .= addslashes($evelieu)."',";
                            // Remark
                            if((!Empty($evermk))&&(strcmp(trim($evermk),""))) $Query .= "'".addslashes($evermk)."',";
                            else $Query .= "NULL,";
                            // Flyer
                            if(!Empty($File)) $Query .= "'".trim($File)."',";
                            else $Query .= "NULL,";
                            $Query .= "CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)";
                            if(!mysql_query(trim($Query),$Link)) $iStatus = 14; //****** Evenement
                            else $iStatus = 15; // Ok...!
                        }
                        else $iStatus = 14; //****** Evenement
                        if(($iStatus != 15)&&(!Empty($File)))
                        {   // Supprime le flyer ainsi transf�r� du serveur
                            @unlink(GetSrvFlyFolder()."$File");
                        }
                    }
                }
                else
                {   // Flyer //////////////////////////////////////////////////////////////////////////////////////////
                    if((!Empty($evechgf))&&($evechgf == 1))
                    {   // Retire le flyer
                        $Query = "SELECT EVE_Flyer FROM Evenements WHERE EVE_Status <> 2 AND EVE_EventID = $eveid";
                        $Result = mysql_query(trim($Query),$Link);
                        if(mysql_num_rows($Result) != 0)
                        {   $aRow = mysql_fetch_array($Result);
                            // Supprime le flyer du serveur
                            @unlink(GetSrvFlyFolder().trim($aRow["EVE_Flyer"]));
                            mysql_free_result($Result);
                        }
                        $Query = "UPDATE Evenements SET EVE_Flyer = NULL WHERE EVE_EventID = $eveid";
                        mysql_query(trim($Query),$Link);
                    }
                    if(($iStatus == 0)&&(!Empty($_FILES["evefly"]["name"])))
                    {   if((strlen($_FILES["evefly"]["name"]) > 4)&&
                           ((!strcmp(strtoupper(substr($_FILES["evefly"]["name"],-4)),".GIF"))||
                            (!strcmp(strtoupper(substr($_FILES["evefly"]["name"],-4)),".JPG"))||
                            (!strcmp(strtoupper(substr($_FILES["evefly"]["name"],-4)),".PNG"))))
                        {   // G�n�ration du nouveau nom du flyer
                            $Query = "SELECT FNU_FlyerID FROM FlyerNumber";
                            if($Result = mysql_query(trim($Query),$Link))
                            {   $aRow = mysql_fetch_array($Result);
                                $iFlyID = $aRow["FNU_FlyerID"];
                                $File = "FL$iFlyID";
                                if(!strcmp(strtoupper(substr($_FILES["evefly"]["name"],-4)),".GIF")) $File .= ".gif";
                                elseif(!strcmp(strtoupper(substr($_FILES["evefly"]["name"],-4)),".JPG")) $File .= ".jpg";
                                else $File .= ".png";
                                // Contr�le si le fichier existe
                                //if($aFileInfo = @stat("$evefly"))
                                if(!Empty($_FILES["evefly"]["size"]))
                                {   // Contr�le de la taille du flyer
                                    //if($aFileInfo[7] <= 100000)
                                    if($_FILES["evefly"]["size"] <= 100000)
                                    {   // Contr�le l'espace disque suffisant
                                        //if((diskfreespace(GetSrvFlyFolder()."/") - $_FILES["evefly"]["size"]) > 5000000)
                                        //{   // Upload du flyer
                                            if(!@move_uploaded_file($_FILES["evefly"]["tmp_name"],GetSrvFlyFolder()."$File")) $iStatus = 12; //****** Upload
                                            else
                                            {   // MAJ de la table FlyerNumber
                                                $iFlyID++;
                                                $Query = "UPDATE FlyerNumber SET FNU_FlyerID = $iFlyID";
                                                if(!mysql_query(trim($Query),$Link)) $iStatus = 13; //****** MAJ
                                            }
                                        //}
                                        //else $iStatus = 11; //****** Espace
                                    }
                                    else $iStatus = 10; //****** Taille
                                }
                                else $iStatus = 9; //****** Exist
                            }
                            else $iStatus = 6; //****** GenFly
                        }
                        else $iStatus = 5; //****** Extension
                    }
                    if($iStatus == 0)
                    {   // Modifie l'�v�nement
                        $Query = "UPDATE Evenements SET";
                        // Flyer
                        if(!Empty($File)) $Query .= " EVE_Flyer = '".trim($File)."'";
                        else $Query .= " EVE_Flyer = NULL";
                        $Query .= " WHERE EVE_EventID = $eveid";
                        if(!mysql_query(trim($Query),$Link))
                        {   $iStatus = 14; //****** Evenement
                            if(!Empty($File))
                            {   // Supprime le flyer ainsi transf�r� du serveur
                                @unlink(GetSrvFlyFolder()."$File");
                            }
                        }
                        else $iStatus = 15; // Ok...!
                    }
                }
            }
        }
        else $iStatus = 4; //****** Camarade
        mysql_close($Link);
    }
    else $iStatus = 3; //****** SQL
}
else $iStatus = 2; //****** Key
?>
<html>
<head>
<title>Le Classico: Event Action</title>
<?php
if($iStatus > 1)
{   // R�sultat de l'op�ration d'ajout
?>
<script type="text/javascript">
<!--
// Commandes /////////////////////////////////////////////////////////////////////////////
top.EvntStatus.location.href="EventStat.php?res=<?php echo $iStatus; ?>&Clf=<?php echo $Clf; ?>";
top.EvntManager.location.href="EventMan.php?Clf=<?php echo $Clf; ?>";
-->
</script>
<?php
    // R�sultat de l'op�ration d'ajout
}
?>
</head>
<body bgcolor="#ffffff">
</body>
</html>
