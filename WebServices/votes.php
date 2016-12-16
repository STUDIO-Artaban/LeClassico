<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$Count = $_GET['Count'];
$StatusDate = $_GET['StatusDate'];
$Date = $_GET['Date'];

$Type = $_POST['Type'];

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

            $aRow = mysql_fetch_array($Result);
            $Camarade = stripslashes($aRow["CAM_Pseudo"]);
            mysql_free_result($Result);
            switch ($Ope) {
                case 3: { ////// Update

                    break;
                }
                case 4: { ////// Insert

                    break;
                }
                case 1:
                case 2: { ////// Select









                    $Query = "SELECT * FROM Commentaires WHERE COM_ObjType = '$Type' AND COM_ObjID IN (".str_replace("n",",",$Ids).")";
                    if ($Ope == 2) // Old
                        $Query .= " AND COM_Date < '".str_replace("n"," ",$Date)."'";
                    else { // New & Update
                        if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),""))) {
                            $Query .= " AND COM_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                            $Query .= " AND COM_Date >= '".str_replace("n"," ",$Date)."'";
                            if ($Ope == 3) // Update
                                $Query .= " AND COM_Status = 1";
                        }
                    }
                    $Query .= " ORDER BY COM_Date DESC";
                    if (!Empty($Count))
                        $Query .= " LIMIT $Count";

                    $Result = mysql_query(trim($Query),$Link);
                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Commentaires":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Commentaires":[';
                            else $Reply .= ',';

                            $Reply .= '{"ObjType":"'.trim($aRow["COM_ObjType"]).'",';
                            $Reply .= '"ObjID":'.strval($aRow["COM_ObjID"]).',';
                            $Reply .= '"Pseudo":"'.trim($aRow["COM_Pseudo"]).'",';
                            $Reply .= '"Date":"'.trim($aRow["COM_Date"]).'",';
                            $Reply .= '"Text":"'.str_replace('"','\"',str_replace("\n","\\n",str_replace("\r\n","\n",trim($aRow["COM_Text"])))).'",';
                            $Reply .= '"Status":'.strval($aRow["COM_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["COM_StatusDate"]).'"}';
                        }
                        $Reply .= ']}';
                    }










                    break;
                }
                case 5: { ////// Delete

                    break;
                }
                default: {
                    $Reply = '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_OPERATION")).'}';
                    break;
                }
            }
            echo $Reply;
        }
        else
            echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_USER")).'}';
        mysql_close($Link);
    }
}
else
    echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_TOKEN")).'}';
?>
