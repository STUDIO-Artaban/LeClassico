<?php
require("Package.php");
$iResult = 0;
$SelFile = "";
$Clf = $_GET['Clf'];
if(!Empty($Clf))
{   $Camarade = DistUserKeyId($Clf);
    if(!Empty($ope))
    {   if($ope == 3)
        {   // 3: Modifier ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if(!Empty($file)) $SelFile = $file;
            $Query = "UPDATE Music SET";
            // Artiste
            if((!Empty($art))&&(strcmp(trim($art),""))) $Query .= " MSC_Artiste = '".trim($art)."'";
            else $Query .= " MSC_Artiste = '???'";
            // Album
            if((!Empty($alb))&&(strcmp(trim($alb),""))) $Query .= ",MSC_Album = '".trim($alb)."'";
            else $Query .= ",MSC_Album = '???'";
            // Morceau
            if((!Empty($mor))&&(strcmp(trim($mor),""))) $Query .= ",MSC_Morceau = '".trim($mor)."'";
            else $Query .= ",MSC_Morceau = '???'";
            $Query .= " WHERE MSC_Fichier LIKE '$SelFile%'";
            //
            $iResult=1; // Ok
        }
        else
        {   // 4: Ajouter ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if(!Empty($mng))
            {   $iMscID = $mng;
                $File = "MSC$iMscID";
                if((!Empty($locfile))&&(!strcmp(strtoupper(substr(trim($locfile),-4)),".WMA"))) $File .= ".wma";
                else $File .= ".mp3";
                // Ajout dans la table Music
                $Query = "INSERT INTO Music (MSC_Fichier,MSC_Pseudo,MSC_Artiste,MSC_Album,MSC_Morceau,MSC_Source) VALUES (";
                // Fichier
                $Query .= "'$File',";
                // Pseudo
                $Query .= "'".addslashes($Camarade)."',";
                // Artiste
                if((!Empty($art))&&(strcmp(trim($art),""))) $Query .= "'".trim($art)."',";
                else $Query .= "'???',";
                // Album
                if((!Empty($alb))&&(strcmp(trim($alb),""))) $Query .= "'".trim($alb)."',";
                else $Query .= "'???',";
                // Morceau
                if((!Empty($mor))&&(strcmp(trim($mor),""))) $Query .= "'".trim($mor)."',";
                else $Query .= "'???',";
                // Source
                if((!Empty($locfile))&&(strcmp(trim($locfile),""))) $Query .= "'".trim($locfile)."')";
                else $Query .= "'???')";
                //
                $SelFile = $File;
                $iResult=12; // Ok
            }
            else $iResult=6; // GenFile
            /*if((!Empty($_FILES["locfile"]["name"]))&&(strlen($_FILES["locfile"]["name"]) > 4))
            {   if((!strcmp(strtoupper(substr($_FILES["locfile"]["name"],-4)),".WMA"))||
                   (!strcmp(strtoupper(substr($_FILES["locfile"]["name"],-4)),".MP3")))
                {   // Contrôle si le fichier existe
                    if(!Empty($_FILES["locfile"]["size"]))
                    {   // Contrôle de la taille du morceau
                        if($_FILES["locfile"]["size"] <= 10000000)
                        {   if(!Empty($mng))
                            {   $iMscID = $mng;
                                $File = "MSC$iMscID";
                                if(!strcmp(strtoupper(substr($_FILES["locfile"]["name"],-4)),".WMA")) $File .= ".wma";
                                else $File .= ".mp3";
                                // Création de la connexion
                                $conn_id = @ftp_connect(GetFtpLocalhost());
                                // Authentification avec nom de compte et mot de passe
                                $login_result = @ftp_login($conn_id,GetMySqlUser(),GetMySqlPassword());
                                // Vérification de la connexion
                                if(($conn_id)&&($login_result))
                                {   // Téléchargement d'un fichier
                                    $upload = @ftp_put($conn_id,GetSrvMscFolder()."$File",$_FILES["locfile"]["tmp_name"],FTP_BINARY);
                                    // Fermeture de la connexion FTP.
                                    @ftp_quit($conn_id);
                                    // Vérification de téléchargement
                                    if($upload)
                                    {   // Ajout dans la table Music
                                        $Query = "INSERT INTO Music (MSC_Fichier,MSC_Pseudo,MSC_Artiste,MSC_Album,MSC_Morceau) VALUES (";
                                        // Fichier
                                        $Query .= "'$File',";
                                        // Pseudo
                                        $Query .= "'".addslashes($Camarade)."',";
                                        // Artiste
                                        if((!Empty($art))&&(strcmp(trim($art),""))) $Query .= "'".trim($art)."',";
                                        else $Query .= "'???',";
                                        // Album
                                        if((!Empty($alb))&&(strcmp(trim($alb),""))) $Query .= "'".trim($alb)."',";
                                        else $Query .= "'???',";
                                        // Morceau
                                        if((!Empty($mor))&&(strcmp(trim($mor),""))) $Query .= "'".trim($mor)."')";
                                        else $Query .= "'???')";
                                        //
                                        $SelFile = $File;
                                        $iResult=12; // Ok
                                    }
                                    else $iResult=9; // Upload
                                }
                                else $iResult=13; // FTP
                            }
                            else $iResult=6; // GenFile
                        }
                        else $iResult=8; // Taille
                    }
                    else $iResult=7; // Exist
                }
                else $iResult=5; // Extension
            }
            else $iResult=4; // File
            */
        }
    }
}
else
{   //$Msg = "Tu n'est pas connect&eacute;!";
    //include("Message.php");
    //die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Music Manager</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<?php
if($iResult != 0)
{   // Résultat de l'opération
?>
<script type="text/javascript">
<!--
// Commandes //////////////////////////////////////////////////
top.MscOpe.location.href="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php
echo $Clf;
if(strcmp($SelFile,"")) echo "&file=$SelFile";
if(!Empty($man)) echo "&man=$man";
?>&resope=<?php
echo $iResult;
if(($iResult == 1)||($iResult == 12)) echo "&qry=".urlencode(base64_encode($Query));
?>";
//-->
</script>
<?php
    // Résultat de l'opération
}
?>
</head>
<body topmargin=0 leftmargin=0 bgcolor="#ffffff">
</body>
</html>
