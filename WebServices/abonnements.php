<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];

$Keys = $_POST['Keys'];
$Status = $_POST['Status'];

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

                case 4: ////// Insert
                case 5: { ////// Delete

                    if (Empty($Keys)) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_MISSING_KEYS")).'}';
                        break;
                    }
                    if (Empty($Status)) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_MISSING_STATUS")).'}';
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

                    //////
                    $i = 0;
                    $Lenght = count($Keys);
                    if ($Ope == 4) {

                        for ( ; $i < $Lenght; ++$i) { // Insert loop

                            $Query = "INSERT INTO Abonnements (ABO_Pseudo,ABO_Camarade) VALUES ('";
                            $Query .= addslashes($Keys[$i]['Pseudo'])."','".addslashes($Keys[$i]['Camarade'])."')";
                            if (!mysql_query(trim($Query),$Link)) {
                                
                                $Query = "UPDATE Abonnements SET";
                                $Query .= " ABO_Status = 1,";
                                $Query .= " ABO_StatusDate = CURRENT_TIMESTAMP";

                                $Query .= " WHERE";
                                $Query .= " ABO_Pseudo='".addslashes($Keys[$i]['Pseudo'])."' AND";
                                $Query .= " ABO_Camarade='".addslashes($Keys[$i]['Camarade'])."' AND";
                                $Query .= " ABO_Status=2 AND";
                                $Query .= " ABO_StatusDate < '".trim($Status[$i]['StatusDate'])."'";
                                if (!mysql_query(trim($Query),$Link)) {
                                    echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_QUERY_INSERT")).'}';
                                    break;
                                }
                                if ((mysql_affected_rows() == 0) && ((is_null($StatusDate)) || (strcmp($StatusDate, $Status[$i]['StatusDate']) < 0)))
                                    $StatusDate = $Status[$i]['StatusDate'];
                                    // NB: Needed to return records updated after current request
                            }
                        }

                    } else {
                        for ( ; $i < $Lenght; ++$i) { // Delete loop

                            $Query = "UPDATE Abonnements SET";
                            $Query .= " ABO_Status = 2,";
                            $Query .= " ABO_StatusDate = CURRENT_TIMESTAMP";

                            $Query .= " WHERE";
                            $Query .= " ABO_Pseudo='".addslashes($Keys[$i]['Pseudo'])."' AND";
                            $Query .= " ABO_Camarade='".addslashes($Keys[$i]['Camarade'])."' AND";
                            $Query .= " ABO_StatusDate < '".trim($Status[$i]['StatusDate'])."'";
                            if (!mysql_query(trim($Query),$Link)) {
                                echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_QUERY_DELETE")).'}';
                                break;
                            }
                            if (mysql_affected_rows() == 0) {

                                $Query = "INSERT INTO Abonnements (ABO_Pseudo,ABO_Camarade,ABO_Status) VALUES ('";
                                $Query .= addslashes($Keys[$i]['Pseudo'])."','".addslashes($Keys[$i]['Camarade'])."',2)";
                                if (!mysql_query(trim($Query),$Link)) {

                                    if ((is_null($StatusDate)) || (strcmp($StatusDate, $Status[$i]['StatusDate']) < 0))
                                        $StatusDate = $Status[$i]['StatusDate'];
                                        // NB: Needed to return records updated after current request
                                }
                            }
                        }
                    }
                    if ($i != $Lenght)
                        break; // Error

                    if (is_null($StatusDate))
                        $StatusDate = date("Y-m-d H:i:s", strtotime(getTimeStamp($Link)) - 1);

                    // Let's reply with inserted or deleted records
                    //break;
                }
                case 1: { ////// Select

                    $Query = "SELECT * FROM Abonnements WHERE UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),"")))
                        $Query .= " AND ABO_StatusDate > '".str_replace("n"," ",$StatusDate)."'";

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
