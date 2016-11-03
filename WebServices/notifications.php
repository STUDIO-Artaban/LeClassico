<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$Date = $_GET['Date'];
$Count = $_GET['Count'];

$Keys = $_POST['Keys'];
$Status = $_POST['Status'];
$Updates = $_POST['Updates'];

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
                case 2: { ////// Update









                    if (Empty($Keys)) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_MISSING_KEYS")).'}';
                        break;
                    }
                    if (Empty($Status)) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_MISSING_STATUS")).'}';
                        break;
                    }
                    if (Empty($Updates)) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_MISSING_UPDATES")).'}';
                        break;
                    }
                    $Keys = json_decode($Keys, true);
                    if (json_last_error() != JSON_ERROR_NONE) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_KEYS")).'}';
                        break;
                    }
                    $Status = json_decode($Status, true);
                    if (json_last_error() != JSON_ERROR_NONE) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_STATUS")).'}';
                        break;
                    }
                    $Updates = json_decode($Updates, true);
                    if (json_last_error() != JSON_ERROR_NONE) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_UPDATES")).'}';
                        break;
                    }

                    $Error = false;
                    $UpdateCount = $count($Keys);
                    for ($i = 0; $i < $UpdateCount; ++$i) { // Records loop

                        $Query = "UPDATE Notifications SET ";




                        $Query .= " WHERE ";




                        $Query .= " NOT_StatusDate < '".trim($Status['Status'][$i]['StatusDate'])."'";
                        if (!mysql_query(trim($Query),$Link)) {

                            echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_QUERY_UPDATE")).'}';
                            $Error = true;
                            break;
                        }
                        if ((is_null($Date)) || (strcmp($Date, $Status['Status'][$i]['StatusDate']) < 0))
                            $Date = $Status['Status'][$i]['StatusDate'];
                    }
                    if ($Error)
                        break;











                    // Let's reply with updated records
                    //break;
                }
                case 1: { ////// Select

                    $Query = "SELECT * FROM Notifications WHERE UPPER(NOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if ((!is_null($Date)) && (strcmp(trim($Date),"")))
                        $Query .= " AND NOT_StatusDate > '".str_replace("n"," ",$Date)."'";
                    if (!Empty($Count))
                        $Query .= " LIMIT $Count";
                    $Result = mysql_query(trim($Query),$Link);

                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Notifications":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Notifications":[';
                            else $Reply .= ',';

                            $Reply .= '{"Pseudo":"'.trim($aRow["NOT_Pseudo"]).'",';
                            $Reply .= '"Date":"'.trim($aRow["NOT_Date"]).'",';
                            $Reply .= '"ObjType":"'.trim($aRow["NOT_ObjType"]).'",';
                            if (!is_null($aRow["NOT_ObjID"])) $Reply .= '"ObjID":'.strval($aRow["NOT_ObjID"]).',';
                            else $Reply .= '"ObjID":null,';
                            if (!is_null($aRow["NOT_ObjDate"])) $Reply .= '"ObjDate":"'.trim($aRow["NOT_ObjDate"]).'",';
                            else $Reply .= '"ObjDate":null,';
                            $Reply .= '"ObjFrom":"'.trim($aRow["NOT_ObjFrom"]).'",';
                            $Reply .= '"LuFlag":'.strval($aRow["NOT_LuFlag"]).',';
                            $Reply .= '"Status":'.strval($aRow["NOT_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["NOT_StatusDate"]).'"}';
                        }
                        $Reply .= ']}';
                    }
                    echo $Reply;
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
