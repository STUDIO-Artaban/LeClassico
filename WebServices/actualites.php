<?php
require("../Package.php");
$Clf = $_GET['Clf'];
$Cam = $_GET['Cam'];
$Count = $_GET['Count'];
if(!Empty($Cam)) $Cam = base64_decode(urldecode($Cam));
header('Content-Type: application/json');
if(!Empty($Clf))
{   // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(Empty($Link)) echo '{"error":"Connection failed!"}';
    else
    {   $Camarade = UserKeyIdentifier($Clf);
        mysql_select_db(GetMySqlDB(),$Link);
        $Query = "SELECT CAM_Pseudo FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            // Request
            $Query = "SELECT ACT_ActuID,ACT_Pseudo,CAM_Profile,CAM_Sexe,ACT_Camarade,ACT_Date,ACT_Text,ACT_Link,ACT_Fichier";
            $Query .= " FROM Actualites LEFT JOIN Camarades ON ACT_Pseudo = CAM_Pseudo";
            if(!Empty($Cam)) {






                // WHERE UPPER(ACT_Pseudo) = UPPER('Pascal') OR UPPER(ACT_Camarade) = UPPER('Pascal')






            }
            else
                $Query .= " INNER JOIN Abonnements ON ACT_Pseudo = ABO_Camarade AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
            $Query .= " ORDER BY ACT_Date DESC";
            if(!Empty($Count)) $Query .= " LIMIT $Count";
            $Result = mysql_query(trim($Query),$Link);
            // Reply
            if(mysql_num_rows($Result) == 0) $Reply = '{"publications":null}';
            else {
                $Reply = '';
                while($aRow = mysql_fetch_array($Result)) {
                    $Profile = $aRow["CAM_Profile"];
                    if(is_null($Profile)) {
                        if((!is_null($aRow["CAM_Sexe"]))&&($aRow["CAM_Sexe"] == 1))
                            $Profile = "Images/woman.png";
                        else
                            $Profile = "Images/man.png";
                    }
                    else
                        $Profile = "Profiles/$Profile";
                    if(strlen($Reply) == 0) $Reply .= '{"publications":[';
                    else $Reply .= ',';
                    $Reply .= '{"profile":"'.trim($Profile).'",';
                    $Reply .= '"token":"'.trim($Clf).'",';
                    $Reply .= '"camarade":"'.urlencode(base64_encode($aRow["ACT_Pseudo"])).'",';
                    $Reply .= '"pseudo":"'.addslashes($aRow["ACT_Pseudo"]).'",';
                    $Reply .= '"date":"'.substr($aRow["ACT_Date"],0,10).'",';
                    $Reply .= '"time":"'.substr($aRow["ACT_Date"],11).'",';
                    $Reply .= '"text":"'.trim($aRow["ACT_Text"]).'",';
                    $Reply .= '"link":"'.trim($aRow["ACT_Link"]).'",';
                    $Reply .= '"image":"'.trim($aRow["ACT_Fichier"]).'",';
                    $Reply .= '"id":'.strval($aRow["ACT_ActuID"]).'}';
                }
                $Reply .= ']}';
            }
            echo $Reply;
        }
        else
        {   mysql_close($Link);
            echo '{"error":"Invalid user!"}';
        }
    }
}
else
    echo '{"error":"No valid token!"}';
?>
