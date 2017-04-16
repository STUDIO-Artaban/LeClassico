<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];

$Keys = $_POST['Keys'];
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

                case 3: ////// Update
                case 4: { ////// Insert

                    if (Empty($Keys)) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_MISSING_KEYS")).'}';
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
                    $Updates = json_decode($Updates, true);
                    if (json_last_error() != JSON_ERROR_NONE) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_UPDATES")).'}';
                        break;
                    }

                    //////
                    $i = 0;
                    $Lenght = count($Keys);
                    for ( ; $i < $Lenght; ++$i) { // Entries loop

                        if ($Ope == 3) { // Update
                            $Query = "UPDATE Locations SET";
                            $Query .= " LOC_Latitude=".strval($Updates[$i]['Latitude']).",";
                            $Query .= " LOC_Longitude=".strval($Updates[$i]['Longitude']);
                            $Query .= " WHERE";
                            $Query .= " LOC_Pseudo='".trim($Keys[$i]['Pseudo'])."'";

                            if (!mysql_query(trim($Query),$Link)) {
                                echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_QUERY_UPDATE")).'}';
                                break;
                            }
                            
                        } else { // Insert
                            $Query = "INSERT INTO Locations (LOC_Pseudo,LOC_Latitude,LOC_Longitude) VALUES (";
                            $Query .= "'".addslashes($Keys[$i]['Pseudo'])."',";
                            $Query .= strval($Updates[$i]['Latitude']).",";
                            $Query .= strval($Updates[$i]['Longitude']).")";

                            if (!mysql_query(trim($Query),$Link)) {
                                echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_QUERY_INSERT")).'}';
                                break;
                            }
                        }
                    }
                    if ($i != $Lenght)
                        break; // Error

                    // Let's reply with inserted or updated records
                    //break;
                }
                case 1: { ////// Select

                    $Query = "SELECT * FROM Locations";
                    if (($Ope == 3) || ($Ope == 4)) { // Update or insert
                        $Query .= " WHERE LOC_Pseudo = UPPER('".addslashes($Camarade)."')";

                    } else { // Select
                        $Query .= " INNER JOIN Abonnements ON ABO_Pseudo = UPPER('".addslashes($Camarade)."') AND ABO_Camarade = LOC_Pseudo";
                        if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),"")))
                            $Query .= " WHERE LOC_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                    }
                    $Result = mysql_query(trim($Query),$Link);
                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Locations":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Locations":[';
                            else $Reply .= ',';

                            $Reply .= '{"Pseudo":"'.trim($aRow["LOC_Pseudo"]).'",';
                            $Reply .= '"Latitude":'.strval($aRow["LOC_Latitude"]).',';
                            $Reply .= '"Longitude":'.strval($aRow["LOC_Longitude"]).',';
                            $Reply .= '"Status":'.strval($aRow["LOC_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["LOC_StatusDate"]).'"}';
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
