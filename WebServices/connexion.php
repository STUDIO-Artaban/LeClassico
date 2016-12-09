<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$psd = $_POST['psd'];
$ccf = $_POST['ccf'];
$odt = $_POST['odt']; // OS date & time (YYYY-MM-DD HH:MM:SS)

header('Content-Type: application/json;charset=ISO-8859-1');

// Connection
$Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
if (Empty($Link))
    echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_SERVER_UNAVAILABLE")).'}';
else {

    mysql_select_db(GetMySqlDB(),$Link);
    $Query = "SELECT NOW() AS SYS_DateTime";
    $Result = mysql_query(trim($Query),$Link);
    if (!mysql_num_rows($Result))
        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_SYSTEM_DATE")).'}';
    else {

        $aRow = mysql_fetch_array($Result);
        $remoteTime  = strtotime($odt);
        $localTime = strtotime($aRow["SYS_DateTime"]); // YYYY-MM-DD HH:MM:SS
        mysql_free_result($Result);
        if (Empty($Clf)) {
            if ((Empty($psd)) || (Empty($ccf)))
                echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_LOGIN")).'}';
            else {

                $Query = "SELECT CAM_Pseudo FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".trim($psd)."') AND UPPER(CAM_CodeConf) = UPPER('".trim($ccf)."')";
                $Result = mysql_query(trim($Query),$Link);
                if (!mysql_num_rows($Result))
                    echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_LOGIN_FAILED")).'}';
                else {

                    $aRow = mysql_fetch_array($Result);
                    $Camarade = stripslashes($aRow["CAM_Pseudo"]);
                    $Clf = GetKeyIdentifier($Camarade, 10800); // 600 -> 10 min, 3600 -> 1 hour, 10800 -> 3 hours
                    echo '{"Logged":{';
                    echo '"Pseudo":"'.trim($Camarade).'",';
                    echo '"TimeLag":'.strval($localTime - $remoteTime).',';
                    echo '"Token":"'.trim($Clf).'"}}';
                }
            }
        }
        else {
            if (CompareKeyIdentifier($Clf)) {

                $Camarade = UserKeyIdentifier($Clf);
                $Clf = GetKeyIdentifier(UserKeyIdentifier($Clf), 10800);
                echo '{"Logged":{';
                echo '"Pseudo":"'.trim($Camarade).'",';
                echo '"TimeLag":'.strval($localTime - $remoteTime).',';
                echo '"Token":"'.trim($Clf).'"}}';
            }
            else
                echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_TOKEN_EXPIRED")).'}';
        }
    }
    mysql_close($Link);
}
?>
