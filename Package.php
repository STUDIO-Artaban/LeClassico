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
function GetComments($link,$fichier)
{   $query = "SELECT COM_Date,COM_Pseudo,COM_Text FROM Commentaires WHERE COM_Fichier LIKE '$fichier' ORDER BY COM_Date";
    $result = mysql_query(trim($query),$link);
    $comments = "";
    while($row = mysql_fetch_array($result))
    {   // Tant qu'il y a des commentaires
        if(!Empty($comments)) $comments .= "<br>";
        $comments .= "<b><u>".$row["COM_Pseudo"].":</u></b>&nbsp;".$row["COM_Text"];
    }
    mysql_free_result($result);
    return $comments;
}
?>
