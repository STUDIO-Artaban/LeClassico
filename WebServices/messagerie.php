<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];
$Count = $_GET['Count'];

header('Content-Type: application/json;charset=ISO-8859-1');

if (!Empty($Clf)) {
    // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if (Empty($Link))
        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_SERVER_UNAVAILABLE")).'}';
    else {

        $Camarade = UserKeyIdentifier($Clf);
        mysql_select_db(GetMySqlDB(),$Link);
        $Query = "SELECT CAM_Pseudo FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        $Result = mysql_query(trim($Query),$Link);
        if (mysql_num_rows($Result) != 0) {

            mysql_free_result($Result);
            switch ($Ope) {
                case 1:
                case 2: { ////// Select

                    $Query = "SELECT * FROM Messagerie WHERE UPPER(MSG_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),"")))
                        $Query .= " AND MSG_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                    $Query .= " ORDER BY MSG_Date DESC, MSG_Time DESC";
                    if (!Empty($Count))
                        $Query .= " LIMIT $Count";

                    $Result = mysql_query(trim($Query),$Link);
                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Messagerie":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Messagerie":[';
                            else $Reply .= ',';

                            $Reply .= '{"Pseudo":"'.trim($aRow["MSG_Pseudo"]).'",';
                            $Reply .= '"From":"'.trim($aRow["MSG_From"]).'",';
                            $Reply .= '"Message":"'.str_replace('"','\"',str_replace("\n","\\n",str_replace("\r\n","\n",trim($aRow["MSG_Message"])))).'",';
                            $Reply .= '"Date":"'.trim($aRow["MSG_Date"]).'",';
                            $Reply .= '"Time":"'.trim($aRow["MSG_Time"]).'",';
                            $Reply .= '"LuFlag":'.strval($aRow["MSG_LuFlag"]).',';
                            $Reply .= '"ReadStk":'.strval($aRow["MSG_ReadStk"]).',';
                            $Reply .= '"WriteStk":'.strval($aRow["MSG_WriteStk"]).',';
                            $Reply .= '"Objet":"'.str_replace('"','\"',trim($aRow["MSG_Objet"])).'",';
                            $Reply .= '"Status":'.strval($aRow["MSG_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["MSG_StatusDate"]).'"}';
                        }
                        $Reply .= ']}';
                    }
                    echo $Reply;
                    break;
                }
                case 3: { ////// Update

                    break;
                }
                case 4: { ////// Insert

                    break;
                }
                case 5: { ////// Delete

                    break;
                }
                default: {
                    echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_OPERATION")).'}';
                    break;
                }
            }
        }
        else
            echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_USER")).'}';
        mysql_close($Link);
    }
}
else
    echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_TOKEN")).'}';
?>
