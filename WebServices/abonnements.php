<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$Date = $_GET['Date'];
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
                case 1: { // Select

                    $Query = "SELECT * FROM Abonnements WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if ((!is_null($Date)) && (strcmp(trim($Date),"")))
                        $Query .= " AND ABO_StatusDate > '".str_replace("n"," ",$Date)."'";
                    $Result = mysql_query(trim($Query),$Link);

                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Abonnements":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Abonnements":[';
                            else $Reply .= ',';

                            $Reply .= '{"Pseudo":"'.trim($aRow["ABO_Pseudo"]).'",';
                            $Reply .= '"Camarade":"'.trim($aRow["ABO_Camarade"]).'",';
                            $Reply .= '"Status":'.strval($aRow["ABO_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["ABO_StatusDate"]).'"}';
                        }
                        $Reply .= ']}';
                    }
                    echo $Reply;
                    break;
                }
                case 2: { // Update

                    break;
                }
                case 3: { // Insert

                    break;
                }
                case 4: { // Delete

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
