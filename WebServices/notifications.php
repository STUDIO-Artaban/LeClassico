<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Date = $_GET['Date'];
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
            $Query = "SELECT * FROM Notifications WHERE UPPER(NOT_Pseudo) = UPPER('".addslashes($Camarade)."')";
            if ((!is_null($Date)) && (strcmp(trim($Date),"")))
                $Query .= " AND NOT_StatusDate > '".str_replace("n"," ",$Date)."'";
            $Result = mysql_query(trim($Query),$Link);
            if (!Empty($Count))
                $Query .= " LIMIT $Count";

            // Select
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
        }
        else
            echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_USER")).'}';
        mysql_close($Link);
    }
}
else
    echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_TOKEN")).'}';
?>
