<?php
require("Package.php");
$Chp = "9";
$Clf = $_GET['Clf'];
$albnm = $_POST['albnm'];
$pht = $_POST['pht'];
$ope = $_POST['ope'];
$iStatus = 0;
/* ERROR MESSAGE (iStatus):
   2 - Clef vide (non connecté)
   3 - Echec de la connexion au serveur SQL
   4 - Pseudo du camarade inconnu
   5 - Fichier ou extension du fichier non valide
   6 - Echec de la génération du nouveau nom du fichier
   7 - Echec de la connexion au serveur FTP
   8 - Echec du login FTP
   9 - Fichier source inexistant
   10 - Taille de la photo > 200 ko
   11 - Espace disque du serveur insuffisant
   12 - Echec de l'upload
   13 - Echec durant la mise à jour des nouveaux nom de fichier
   14 - Echec de l'ajout dans l'album
   15 - Ok...
   16 - Suppression en cours
   17 - Echec de la suppression de la photo: Droits insuffisants
   18 - Echec de la suppression de la photo: ???
   19 - Suppression réussi
*/
if(!Empty($Clf))
{   // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(!Empty($Link))
    {   $Camarade = UserKeyIdentifier($Clf);
        $Query = "SELECT CAM_Pseudo,CAM_LogDate FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        mysql_select_db(GetMySqlDB(),$Link);
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            if(!Empty($ope))
            {   // Ajoute une photo dans un album /////////////////////////////////////////////////////////////////////////////////////////////////////////////
                if((!Empty($albnm))&&(strcmp(trim($albnm),""))&&(!Empty($_FILES["pht"]["name"]))&&(strlen($_FILES["pht"]["name"]) > 4)&&
                   ((!strcmp(strtoupper(substr($_FILES["pht"]["name"],-4)),".GIF"))||
                    (!strcmp(strtoupper(substr($_FILES["pht"]["name"],-4)),".JPG"))||
                    (!strcmp(strtoupper(substr($_FILES["pht"]["name"],-4)),".PNG"))))
                {   // Génération du nouveau nom du fichier
                    $Query = "SELECT PNU_PhotoID FROM PhotoNumber";
                    if($Result = mysql_query(trim($Query),$Link))
                    {   $aRow = mysql_fetch_array($Result);
                        $iPhtID = $aRow["PNU_PhotoID"];
                        $File = "$iPhtID";
                        switch(strlen($File))
                        {   case 1:
                            {   $File = "LC000$iPhtID";
                                break;
                            }
                            case 2:
                            {   $File = "LC00$iPhtID";
                                break;
                            }
                            case 3:
                            {   $File = "LC0$iPhtID";
                                break;
                            }
                            default:
                            {   $File = "LC$iPhtID";
                                break;
                            }
                        }
                        if(!strcmp(strtoupper(substr($_FILES["pht"]["name"],-4)),".GIF")) $File .= ".gif";
                        elseif(!strcmp(strtoupper(substr($_FILES["pht"]["name"],-4)),".JPG")) $File .= ".jpg";
                        else $File .= ".png";
                        // Contrôle si le fichier existe
                        //if($aFileInfo = @stat("$pht"))
                        if(!Empty($_FILES["pht"]["size"]))
                        {   // Contrôle de la taille de la photo
                            //if($aFileInfo[7] <= 200000)
                            if($_FILES["pht"]["size"] <= 200000)
                            {   // Contrôle l'espace disque suffisant
                                //if((diskfreespace(GetSrvPhtFolder()."/") - $_FILES["pht"]["size"]) > 5000000)
                                //{   // Upload de la nouvelle photo
                                    //if(!@copy($pht,"I:\\Program Files\\EasyPHP\\www\\LeClassico\\Photos\\$File")) $iStatus = 12; //****** Upload
                                    if(!@move_uploaded_file($_FILES["pht"]["tmp_name"],GetSrvPhtFolder()."$File")) $iStatus = 12; //****** Upload
                                    else
                                    {   // MAJ de la table PhotoNumber
                                        $iPhtID++;
                                        $Query = "UPDATE PhotoNumber SET PNU_PhotoID = $iPhtID";
                                        if(!mysql_query(trim($Query),$Link)) $iStatus = 13; //****** MAJ
                                        else
                                        {   // Ajout dans l'album
                                            $Query = "INSERT INTO Photos (PHT_Album,PHT_Pseudo,PHT_Fichier) VALUES (";
                                            // Album
                                            $Query .= "'".trim($albnm)."',";
                                            // Pseudo
                                            $Query .= "'".addslashes($Camarade)."',";
                                            // Fichier
                                            $Query .= "'$File')";
                                            if(!mysql_query(trim($Query),$Link)) $iStatus = 14; //****** Album
                                            else $iStatus = 15; // Ok...!
                                        }
                                        if($iStatus != 15)
                                        {   // Supprime le fichier ainsi transféré du serveur
                                            @unlink(GetSrvPhtFolder()."$File");
                                        }
                                    }
                                //}
                                //else $iStatus = 11; //****** Espace
                            }
                            else $iStatus = 10; //****** Taille
                        }
                        else $iStatus = 9; //****** Exist
                    }
                    else $iStatus = 6; //****** GenFile
                }
                else $iStatus = 5; //****** Extension
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
<title>Le Classico: New Photo</title>
<?php
if($iStatus > 1)
{   // Résultat de l'opération d'ajout
?>
<script type="text/javascript">
<!--
// Commandes /////////////////////////////////////////////////////////////////////////////
top.PhtStatus.location.href="PhtStatus.php?res=<?php echo $iStatus; ?>&Clf=<?php echo $Clf; ?>";
if(top.PhtManager.document.getElementById("YourList").selectedIndex!=0) top.PhtManager.location.href="PhtMan.php?ope=2&phtpth=<?php echo urlencode(base64_encode(trim($pht))); ?>&albnm=<?php echo urlencode(base64_encode(trim($albnm))); ?>&Clf=<?php echo $Clf; ?>";
else top.PhtManager.location.href="PhtMan.php?ope=2&phtpth=<?php echo urlencode(base64_encode(trim($pht))); ?>&shrd=1&albnm=<?php echo urlencode(base64_encode(trim($albnm))); ?>&Clf=<?php echo $Clf; ?>";
-->
</script>
<?php
    // Résultat de l'opération d'ajout
}
?>
</head>
<body bgcolor="#ffffff">
</body>
</html>
