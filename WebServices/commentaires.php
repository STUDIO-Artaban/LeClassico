<?php
require("../Package.php");
$Clf = $_GET['Clf'];
$Actu = $_GET['Actu'];
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




            $Query = "SELECT COM_ObjID,COM_Pseudo,COM_Text,COM_Date FROM Commentaires WHERE COM_ObjType = 'A' AND COM_ObjID = $Actu ORDER BY COM_Date";




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
                    $Reply .= '"text":"'.trim($aRow["COM_Text"]).'",';
                    $Reply .= '"date":"'.trim($aRow["COM_Date"]).'"}';
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
