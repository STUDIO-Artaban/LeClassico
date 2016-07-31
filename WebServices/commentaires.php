<?php
require("../Package.php");
$Clf = $_GET['Clf'];
$Type = $_GET['Type'];
$Actu = $_GET['Actu'];
$Count = $_GET['Count'];
$Cmd = $_GET['Cmd'];
$Date = $_GET['Date'];
if(!Empty($Cam)) $Cam = base64_decode(urldecode($Cam));
header('Content-Type: application/json;charset=ISO-8859-1');
if(!Empty($Clf))
{   // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if(Empty($Link)) echo '{"error":"Connection failed!"}';
    else
    {   $Camarade = UserKeyIdentifier($Clf);
        mysql_select_db(GetMySqlDB(),$Link);
        $Query = "SELECT CAM_Pseudo FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        $Result = mysql_query(trim($Query),$Link);
        if(mysql_num_rows($Result) != 0)
        {   $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            if(!Empty($Cmd)) {
                $Query = "UPDATE Commentaires SET COM_Status = 2, COM_StatusDate = CURRENT_TIMESTAMP WHERE COM_ObjType = '$Type' AND COM_ObjID = $Actu AND COM_Date = '".str_replace("n"," ",$Date)."'";
                if(!mysql_query(trim($Query),$Link)) $Reply = '{"error":"SQL request failed!"}';
                else $Reply = '{}'; // Ok...
            }
            else {
                // Select
                $Query = "SELECT COM_ObjID,COM_Pseudo,COM_Text,COM_Date FROM Commentaires WHERE COM_Status <> 2 AND COM_ObjType = '$Type' AND COM_ObjID IN (";
                $ActuIDs = explode('n', $Actu);
                $PrevID = false;
                foreach($ActuIDs as &$ActuId) {
                    if($PrevID) $Query .= ",$ActuId";
                    else $Query .= "$ActuId";
                    $PrevID = true;
                }
                $Query .= ")";
                if((!is_null($Date))&&(strcmp(trim($Date),""))) $Query .= " AND COM_Date > '".str_replace("n"," ",$Date)."'";
                $Query .= " ORDER BY COM_Date";
                if(!Empty($Count)) $Query .= " LIMIT $Count";
                $Result = mysql_query(trim($Query),$Link);
                // Reply
                if(mysql_num_rows($Result) == 0) $Reply = '{"commentaires":null}';
                else {
                    $Reply = '';
                    while($aRow = mysql_fetch_array($Result)) {
                        if(strlen($Reply) == 0) $Reply .= '{"commentaires":[';
                        else $Reply .= ',';
                        $Reply .= '{"id":'.strval($aRow["COM_ObjID"]).',';
                        $Reply .= '"pseudo":"'.addslashes($aRow["COM_Pseudo"]).'",';
                        $Reply .= '"from":"'.urlencode(base64_encode($aRow["COM_Pseudo"])).'",';
                        $Reply .= '"text":"'.str_replace('"','\"',trim($aRow["COM_Text"])).'",';
                        $Reply .= '"remove":'.((!strcmp($Camarade,$aRow["COM_Pseudo"]))? "true":"false").',';
                        $Reply .= '"date":"'.trim($aRow["COM_Date"]).'"}';
                    }
                    $Reply .= ']}';
                }
            }
            echo $Reply;
        }
        else
            echo '{"error":"Invalid user!"}';
        mysql_close($Link);
    }
}
else
    echo '{"error":"No valid token!"}';
?>
