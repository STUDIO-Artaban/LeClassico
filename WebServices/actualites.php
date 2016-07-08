<?php
require("../Package.php");
$Clf = $_GET['Clf'];
$Cam = $_GET['Cam'];
$Count = $_GET['Count'];
$Actu = $_GET['Actu'];
$Cmd = $_GET['Cmd'];
$Date = $_GET['Date'];
if(!Empty($Cam)) $Cam = base64_decode(urldecode($Cam));
header('Content-Type: text/html;charset=ISO-8859-1');
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
            if(!Empty($Cmd)) {
                // Delete
                if(Empty($Actu)) $Reply = '{"error":"ID invalid!"}';
                else {
                    $Query = "SELECT ACT_Fichier FROM Actualites WHERE ACT_ActuID = $Actu";
                    $Result = mysql_query(trim($Query),$Link);
                    if($aRow = mysql_fetch_array($Result)) $File = $aRow["ACT_Fichier"];
                    mysql_free_result($Result);
                    $Query = "DELETE FROM Actualites WHERE ACT_ActuID = $Actu AND (UPPER(ACT_Pseudo) = UPPER('".addslashes($Camarade)."') OR UPPER(ACT_Camarade) = UPPER('".addslashes($Camarade)."'))";
                    if(!mysql_query(trim($Query),$Link)) $Reply = '{"error":"SQL request failed!"}';
                    else {
                        // Remove comments
                        $Query = "DELETE FROM Commentaires WHERE COM_ObjType = 'A' AND COM_ObjID = $Actu";
                        mysql_query(trim($Query),$Link);
                        // Remove image file (if any)
                        if(!is_null($File)) @unlink(GetSrvPhtFolder()."$File");
                        $Reply = '{}'; // Ok...
                    }
                }
            }
            else {
                // Select
                $Query = "SELECT ACT_ActuID,ACT_Pseudo,CAM_Profile,CAM_Sexe,ACT_Camarade,ACT_Date,ACT_Text,ACT_Link,ACT_Fichier";
                $Query .= " FROM Actualites LEFT JOIN Camarades ON ACT_Pseudo = CAM_Pseudo";
                if(!Empty($Cam)) {
                    $Query .= " WHERE (UPPER(ACT_Pseudo) = UPPER('".addslashes($Cam)."') OR UPPER(ACT_Camarade) = UPPER('".addslashes($Cam)."'))";
                    if(!Empty($Date)) $Query .= " AND ACT_Date > '".str_replace("n"," ",$Date)."'";
                }
                else {
                    $Query .= " INNER JOIN Abonnements ON ACT_Pseudo = ABO_Camarade AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if(!Empty($Date)) $Query .= " WHERE ACT_Date > '".str_replace("n"," ",$Date)."'";
                }
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
                        $Reply .= '"camarade":"'.urlencode(base64_encode($aRow["ACT_Pseudo"])).'",';
                        $Reply .= '"pseudo":"'.addslashes($aRow["ACT_Pseudo"]).'",';
                        $Reply .= '"date":"'.substr($aRow["ACT_Date"],0,10).'",';
                        $Reply .= '"time":"'.substr($aRow["ACT_Date"],11).'",';
                        $Reply .= '"text":"'.str_replace('"','\"',trim($aRow["ACT_Text"])).'",';
                        $Reply .= '"link":"'.str_replace('"','\"',trim($aRow["ACT_Link"])).'",';
                        $Reply .= '"image":"'.trim($aRow["ACT_Fichier"]).'",';
                        $Reply .= '"remove":'.(((!strcmp($Camarade,$aRow["ACT_Pseudo"]))||(!strcmp($Camarade,$aRow["ACT_Camarade"])))? "true":"false").',';
                        $Reply .= '"id":'.strval($aRow["ACT_ActuID"]).'}';
                    }
                    $Reply .= ']}';
                }
            }
            mysql_close($Link);
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
