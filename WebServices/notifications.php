<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];
$Count = $_GET['Count'];
$Date = $_GET['Date'];

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
                case 3: { ////// Update

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

                    //////
                    $i = 0;
                    $Lenght = count($Keys);
                    for ( ; $i < $Lenght; ++$i) { // Update loop

                        $Query = "UPDATE Notifications SET";
                        $Query .= " NOT_LuFlag=".strval($Updates[$i]['LuFlag']);

                        $Query .= " WHERE";
                        $Query .= " NOT_Pseudo='".trim($Keys[$i]['Pseudo'])."' AND";
                        $Query .= " NOT_Date='".trim($Keys[$i]['Date'])."' AND";
                        $Query .= " NOT_ObjType='".trim($Keys[$i]['ObjType'])."' AND";
                        if (is_null($Keys[$i]['ObjID'])) $Query .= " NOT_ObjID IS NULL AND";
                        else $Query .= " NOT_ObjID=".strval($Keys[$i]['ObjID'])." AND";
                        if (is_null($Keys[$i]['ObjDate'])) $Query .= " NOT_ObjDate IS NULL AND";
                        else $Query .= " NOT_ObjDate='".trim($Keys[$i]['ObjDate'])."' AND";
                        $Query .= " NOT_ObjFrom='".trim($Keys[$i]['ObjFrom'])."' AND";

                        $Query .= " NOT_StatusDate < '".trim($Status[$i]['StatusDate'])."'";
                        if (!mysql_query(trim($Query),$Link)) {

                            echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_QUERY_UPDATE")).'}';
                            break;
                        }
                        if ((is_null($StatusDate)) || (strcmp($StatusDate, $Status[$i]['StatusDate']) < 0))
                            $StatusDate = $Status[$i]['StatusDate'];
                        if ((is_null($Date)) || (strcmp($Date, $Keys[$i]['Date']) < 0))
                            $Date = $Keys[$i]['Date'];
                    }
                    if ($i != $Lenght)
                        break; // Error

                    $StatusDate = str_replace(" ","n",$StatusDate);
                    $Date = str_replace(" ","n",$Date);
                    // Let's reply with updated records
                    //break;
                }
                case 1:
                case 2: { ////// Select
                    $noLimit = false;

                    $Query = "SELECT * FROM Notifications WHERE UPPER(NOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if ($Ope == 2) // Old
                        $Query .= " AND NOT_Date < '".str_replace("n"," ",$Date)."'";
                    else { // New & Update
                        if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),""))) {
                            $Query .= " AND NOT_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                            $Query .= " AND NOT_Date >= '".str_replace("n"," ",$Date)."'";
                            $noLimit = true;
                        }
                    }
                    $Query .= " ORDER BY NOT_Date DESC";
                    if ((!Empty($Count)) && (!$noLimit))
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
