<?php
/////////////////////////////////////////////////////////////////////////////////
// Variables
$bFolder = false; // "/LeClassico" folder?
// Localhost MySql //////////////////////////////////////////////////////////////
$SqlLocalhost = "localhost";
// Database MySql ///////////////////////////////////////////////////////////////
$SqlDB = "";
//$SqlDB = "LeClassico";
// User MySql ///////////////////////////////////////////////////////////////////
$SqlUser = "";
//$SqlUser = "root";
// Password MySql ///////////////////////////////////////////////////////////////
$SqlPassword = "";
//$SqlPassword = "";
// Serveur FTP //////////////////////////////////////////////////////////////////
$FtpLocalhost = "localhost";
// Serveur photos DIR ///////////////////////////////////////////////////////////
$SrvPhtFolder = $_SERVER["DOCUMENT_ROOT"]."/Photos/";
// Serveur flyers DIR ///////////////////////////////////////////////////////////
$SrvFlyFolder = $_SERVER["DOCUMENT_ROOT"]."/Flyers/";
// Serveur music DIR ////////////////////////////////////////////////////////////
$SrvMscFolder = $_SERVER["DOCUMENT_ROOT"]."/Music/";
// Serveur profile DIR //////////////////////////////////////////////////////////
$SrvProFolder = $_SERVER["DOCUMENT_ROOT"]."/Profiles/";
// Adresse pour les images //////////////////////////////////////////////////////
$SrvImgAddr = "http://www.leclassico.fr/Images";
//$SrvImgAddr = "file:///I:/Program Files/EasyPHP/www/LeClassico/Images";
// Adresse pour les sons ////////////////////////////////////////////////////////
$SrvMscAddr = "http://www.leclassico.fr/Music";
//$SrvMscAddr = "file:///I:/Program Files/EasyPHP/www/LeClassico/Music";
// Adresse du serveur local /////////////////////////////////////////////////////
$LocalSrvAddr = "http://www.leclassico.fr/";
//$LocalSrvAddr = "http://vp.magellan.free.fr/LeClassico/";
//$LocalSrvAddr = "http://localhost/LeClassico/";
// Adresse du serveur distant ///////////////////////////////////////////////////
$DistSrvAddr = "http://www.leclassico.fr/";
//$DistSrvAddr = "http://vp.magellan.free.fr/LeClassico/";
//$DistSrvAddr = "http://www.lconseil.com/pascal/";
//$DistSrvAddr = "http://localhost/LeClassico/";
// Webmaster name ///////////////////////////////////////////////////////////////
$WebMaster = "Webmaster";

/////////////////////////////////////////////////////////////////////////////////
// GetFolder
///////////////////////////////////
function GetFolder()
{   global $bFolder;
    if($bFolder) return "/LeClassico";
    return "";
}
///////////////////////////////////
// GetMySqlLocalhost
///////////////////////////////////
function GetMySqlLocalhost()
{   global $SqlLocalhost;
    return $SqlLocalhost;
}
///////////////////////////////////
// GetMySqlDB
///////////////////////////////////
function GetMySqlDB()
{   global $SqlDB;
    return $SqlDB;
}
///////////////////////////////////
// GetMySqlUser
///////////////////////////////////
function GetMySqlUser()
{   global $SqlUser;
    return $SqlUser;
}
///////////////////////////////////
// GetMySqlPassword
///////////////////////////////////
function GetMySqlPassword()
{   global $SqlPassword;
    return $SqlPassword;
}
///////////////////////////////////
// GetFtpLocalhost
///////////////////////////////////
function GetFtpLocalhost()
{   global $FtpLocalhost;
    return $FtpLocalhost;
}
///////////////////////////////////
// GetSrvPhtFolder
///////////////////////////////////
function GetSrvPhtFolder()
{   global $SrvPhtFolder;
    return $SrvPhtFolder;
}
///////////////////////////////////
// GetSrvFlyFolder
///////////////////////////////////
function GetSrvFlyFolder()
{   global $SrvFlyFolder;
    return $SrvFlyFolder;
}
///////////////////////////////////
// GetSrvMscFolder
///////////////////////////////////
function GetSrvMscFolder()
{   global $SrvMscFolder;
    return $SrvMscFolder;
}
///////////////////////////////////
// GetImgAddr
///////////////////////////////////
function GetImgAddr()
{   global $SrvImgAddr;
    return $SrvImgAddr;
}
///////////////////////////////////
// GetMscAddr
///////////////////////////////////
function GetMscAddr()
{   global $SrvMscAddr;
    return $SrvMscAddr;
}
///////////////////////////////////
// GetLocalSrvAddr
///////////////////////////////////
function GetLocalSrvAddr()
{   global $LocalSrvAddr;
    return $LocalSrvAddr;
}
///////////////////////////////////
// GetDistSrvAddr
///////////////////////////////////
function GetDistSrvAddr()
{   global $DistSrvAddr;
    return $DistSrvAddr;
}
/////////////////////////////////////////////////////////////////////////////////
// GetKeyIdentifier
///////////////////////////////////
function GetKeyIdentifier($User,$Delay)
{   srand((float) microtime()*1000000);
    $NewKey = sprintf("%d_%d_%d_%s",time(),rand(),$Delay,urlencode(base64_encode($User)));
    $NewKey = urlencode(base64_encode($NewKey));
    return $NewKey;
}
///////////////////////////////////
// CompareKeyIdentifier
///////////////////////////////////
function CompareKeyIdentifier($CurKey)
{   list($iTime, $iRand, $iDelay, $sUser) = sscanf(base64_decode(urldecode($CurKey)),"%d_%d_%d_%s");
    if((time()-$iTime) < $iDelay) return TRUE;
    return FALSE;
}
///////////////////////////////////
// UserKeyIdentifier
///////////////////////////////////
function UserKeyIdentifier($CurKey)
{   list($iTime, $iRand, $iDelay, $sUser) = sscanf(base64_decode(urldecode($CurKey)),"%d_%d_%d_%s");
    return stripslashes(base64_decode(urldecode($sUser)));
}
///////////////////////////////////
// GetDistKeyId
///////////////////////////////////
function GetDistKeyId($CurKey)
{   //list($iTime, $iRand, $iDelay, $sUser) = sscanf(base64_decode(urldecode($CurKey)),"%d_%d_%d_%s");
    //$NewKey = sprintf("%d_%d_%d_%s",$iTime,$iRand,$iDelay,urlencode(stripslashes(base64_decode(urldecode($sUser)))));
    //$NewKey = urlencode($NewKey);
    //return $NewKey;
    return $CurKey;
}
///////////////////////////////////
// DistUserKeyId
///////////////////////////////////
function DistUserKeyId($CurKey)
{   //list($iTime, $iRand, $iDelay, $sUser) = sscanf(urldecode($CurKey),"%d_%d_%d_%s");
    //return stripslashes(urldecode($sUser));
    return UserKeyIdentifier($CurKey);
}
///////////////////////////////////
// PrintString
///////////////////////////////////
function PrintString($String)
{   $PrintStr = "";
    $Tok = strtok($String,"\n");
    while($Tok)
    {   $PrintStr = $PrintStr.$Tok."<br>";
        $Tok = strtok("\n");
    }
    return $PrintStr;
}
///////////////////////////////////
// GetComments
///////////////////////////////////
function GetComments($clf,$camarade,$link,$type,$id)
{   $query = "SELECT COM_Date,COM_Pseudo,COM_Text FROM Commentaires WHERE COM_Status <> 2 AND COM_ObjType = '$type' AND COM_ObjID = $id ORDER BY COM_Date";
    $result = mysql_query(trim($query),$link);
    $comments = "";
    $search = array("<",">");
    $replace = array("&lt;","&gt;");
    while($row = mysql_fetch_array($result))
    {   // Tant qu'il y a des commentaires
        if(!Empty($comments)) $comments .= "<br>";
        $text = str_replace($search,$replace,$row["COM_Text"]);
        if(!Empty($clf)) {
            $comments .= "<a href=\"index.php?Chp=2&Cam=".urlencode(base64_encode($row["COM_Pseudo"]))."&Clf=$clf\" target=\"_top\" style=\"font-size:10pt\">".$row["COM_Pseudo"].":</a>&nbsp;$text";
            if(!strcmp($camarade,$row["COM_Pseudo"])) $comments .= "&nbsp;<img class=\"remove\" src=\"Images/remove.png\" onclick='OnRemoveCommentaire(\"P\",$id,\"".trim($row["COM_Date"])."\",\"$clf\")'>";
        }
        else
            $comments .= "<b><u>".$row["COM_Pseudo"].":</u></b>&nbsp;$text";
    }
    mysql_free_result($result);
    return $comments;
}
///////////////////////////////////
// GetWebmaster
///////////////////////////////////
function GetWebmaster()
{   global $WebMaster;
    return $WebMaster;
}
///////////////////////////////////
// GetPhotoID
///////////////////////////////////
function GetPhotoID($photo)
{   // Retourne l'ID du de la photo en fonction du fichier (ex: 'LC0112.jpg' -> 112)
    return intval(substr($photo,2));
}
///////////////////////////////////
// GetSrvProFolder
///////////////////////////////////
function GetSrvProFolder()
{   global $SrvProFolder;
    return $SrvProFolder;
}
///////////////////////////////////
// GetPhotoFile
///////////////////////////////////
function GetPhotoFile($link,$previous)
{   $query = "SELECT PNU_PhotoID FROM PhotoNumber";
    if($result = mysql_query(trim($query),$link))
    {   $row = mysql_fetch_array($result);
        $iPhtID = $row["PNU_PhotoID"];
        // TODO: Remove code below coz possible issue if new photo has been generated
        //       between 'GetPhotoFile(false)' & 'GetPhotoFile(true)' calls
        if($previous) $iPhtID--;
        //
        $file = "$iPhtID";
        switch(strlen($file))
        {   case 1:
            {   $file = "LC000$iPhtID";
                break;
            }
            case 2:
            {   $file = "LC00$iPhtID";
                break;
            }
            case 3:
            {   $file = "LC0$iPhtID";
                break;
            }
            default:
            {   $file = "LC$iPhtID";
                break;
            }
        }
        mysql_free_result($result);
        return $file;
    }
}
///////////////////////////////////
// GetImageExtension
///////////////////////////////////
function GetImageExtension($fileName)
{   if((!Empty($_FILES[$fileName]["name"]))&&(strlen($_FILES[$fileName]["name"]) > 4)) {
        if(!strcmp(strtoupper(substr($_FILES[$fileName]["name"],-4)),".GIF")) return ".gif";
        else if(!strcmp(strtoupper(substr($_FILES[$fileName]["name"],-4)),".JPG")) return ".jpg";
        else if(!strcmp(strtoupper(substr($_FILES[$fileName]["name"],-4)),".PNG")) return ".png";
    }
}
///////////////////////////////////
// GetResult
///////////////////////////////////
function GetResult($res)
{   switch($res)
    {   case 1: // Uploading en cours
            return "Transfert en cours...";
        case 2: // Non connecté
            return "<font color=\"#ff0000\">Non connect&eacute;</font>!";
        case 3: // Echec de la connexion au serveur SQL
            return "<font color=\"#ff0000\">Echec</font> de la connexion au serveur <font color=\"#ff0000\">SQL</font>!";
        case 4: // Pseudo du camarade inconnu
            return "Pseudo du camarade <font color=\"#ff0000\">inconnu</font>!";
        case 5: // Fichier ou extension du fichier non valide
            return "Fichier ou extension du fichier <font color=\"#ff0000\">non valide</font>!";
        case 6: // Echec de la génération du nouveau nom du fichier
            return "<font color=\"#ff0000\">Echec</font> de la <font color=\"#ff0000\">g&eacute;n&eacute;ration</font> du nouveau nom du fichier!";
        case 7: // Echec de la connexion au serveur FTP
            return "<font color=\"#ff0000\">Echec</font> de la connexion au serveur <font color=\"#ff0000\">FTP</font>!";
        case 8: // Login FTP incorrect
            return "Login FTP <font color=\"#ff0000\">incorrect</font>!";
        case 9: // Le fichier source n'existe pas
            return "Le fichier source <font color=\"#ff0000\">n'existe pas</font>!";
        case 10: // Taille de la photo > 200 Ko
            return "Taille de la photo <font color=\"#ff0000\">&gt;</font> &agrave; <font color=\"#ff0000\">200 Ko</font>! Espace disque limit&eacute;!!!";
        case 11: // Espace disque insuffisant
            return "Espace disque <font color=\"#ff0000\">insuffisant</font>!";
        case 12: // Echec de l'upload
            return "Le Transfert a <font color=\"#ff0000\">&eacute;chou&eacute;</font>!";
        case 13: // Echec durant la mise à jour des nouveaux nom de fichier
            return "<font color=\"#ff0000\">Echec</font> durant <font color=\"#ff0000\">la mise &agrave; jour</font> des nouveaux noms de fichier!";
        case 14: // Echec de l'ajout dans l'album
            return "<font color=\"#ff0000\">Echec de l'ajout</font> dans l'album!";
        case 15: // Ajout réussi
            return "Ajout r&eacute;ussi !!!";
        case 16: // Suppression en cours
            return "Suppression en cours...";
        case 17: // Echec suppression: Droits insuffisants
            return "<font color=\"#ff0000\">Echec de la suppression</font>! Tu n'as pas les droits sur cet album!!!!!!";
        case 18: // Echec suppression
            return "<font color=\"#ff0000\">Echec de la suppression</font>! Contact le <font color=\"#8080ff\">Webmaster</font>!";
        case 19: // Suppression réussi
            return "Suppression r&eacute;ussi !!";
        case 20: // Rien a publier
            return "Rien &agrave; publier!!... circulez!";
        case 21: // Echec publication
            return "<font color=\"#ff0000\">Echec durant la publication</font>! Contact le <font color=\"#8080ff\">Webmaster</font>!";
        case 22: // Rien a commenter
            return "No comment!!!";
        case 23: // Echec commentaire
            return "<font color=\"#ff0000\">Echec durant l'ajout du commentaire</font>! Contact le <font color=\"#8080ff\">Webmaster</font>!";
    }
    // Prêt
    return "Pr&ecirc;t...";
}
///////////////////////////////////
// DownloadImageFile
///////////////////////////////////
function DownloadImageFile($link,$folder,$fileName)
{   $ext = GetImageExtension($fileName);
    if(Empty($ext)) return 5; // Wrong extension
    $file = GetPhotoFile($link,false);
    if(Empty($file)) return 6; // Generate file number
    $iPhtID = GetPhotoID($file);
    if(Empty($_FILES[$fileName]["size"])) return 9; // Empty file
    if($_FILES["pht"]["size"] > 200000) return 10; // Wrong file size
    //if((diskfreespace("$folder/") - $_FILES[$fileName]["size"]) < 5000000) return 11; // Not enough memory space
    $file .= $ext;
    if(!@move_uploaded_file($_FILES[$fileName]["tmp_name"],trim($folder)."$file")) return 12; // Failed to download
    // MAJ de la table PhotoNumber
    $iPhtID++;
    $query = "UPDATE PhotoNumber SET PNU_PhotoID = $iPhtID";
    if(!mysql_query(trim($query),$link)) return 13; // Failed to update photo number
    return 14;
}
///////////////////////////////////
// AjoutePublication
///////////////////////////////////
function AjoutePublication($link,$camarade)
{   $msg = trim($_POST["msg"]);
    $to = trim($_POST["to"]);
    $lnk = trim($_POST["lnk"]);
    $join = $_POST["join"];
    if((is_null($join))||(Empty($join))) $join = 0;
    if((!strcmp($msg,""))&&((($join == 0)&&(!strcmp($lnk,"")))||(($join == 1)&&(Empty($_FILES["img"]["name"]))))) return 20; // Nothing to publish
    if(($join == 1)&&(!Empty($_FILES["img"]["name"]))&&(DownloadImageFile($link,GetSrvPhtFolder(),"img") != 14)) return 12; // Download failed
    $file = GetPhotoFile($link,true).GetImageExtension("img");
    // MAJ database
    $query = "INSERT INTO Actualites (ACT_ActuID,ACT_Date,ACT_Pseudo,ACT_Camarade,ACT_Text,ACT_Link,ACT_Fichier,ACT_StatusDate) VALUES (NULL,CURRENT_TIMESTAMP,";
    $query .= "'".addslashes($camarade)."',";
    $query .= ((strcmp($to,""))? "'$to',":"NULL,");
    $query .= ((strcmp($msg,""))? "'".addslashes($msg)."',":"NULL,");
    $journal = false;
    if(($join == 0)&&(strcmp($lnk,""))) $query .= "'$lnk',NULL,CURRENT_TIMESTAMP)";
    else if(($join == 1)&&(!Empty($_FILES["img"]["name"]))) {
        $journal = true;
        $query .= "NULL,'$file',CURRENT_TIMESTAMP)";
    }
    else $query .= "NULL,NULL,CURRENT_TIMESTAMP)";
    if(!mysql_query(trim($query),$link)) return 21; // Failed to publish
    if($journal) {
        $query = "INSERT INTO Photos (PHT_Album,PHT_Pseudo,PHT_Fichier,PHT_FichierID) VALUES ('Journal','".addslashes($camarade)."','$file',".strval(GetPhotoID($file)).")";
        mysql_query(trim($query),$link);
    }
    return 15; // Ok...
}
///////////////////////////////////
// AjouteCommentaire
///////////////////////////////////
function AjouteCommentaire($link,$camarade,$type,$id,$comment)
{   $txt = trim($_POST[$comment]);
    if(!strcmp($txt,"")) return 22; // No comment
    $query = "INSERT INTO Commentaires (COM_ObjType,COM_ObjID,COM_Pseudo,COM_Date,COM_Text) VALUES ('$type',$id,'".addslashes($camarade)."',CURRENT_TIMESTAMP,'".addslashes($txt)."')";
    if(!mysql_query(trim($query),$link)) return 23; // Failed to comment
    return 15; // Ok...
}
?>
