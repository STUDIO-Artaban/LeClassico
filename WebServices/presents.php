<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];

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
                case 1: { ////// Select

                    $Query = "SELECT * FROM Presents";

                    // TODO: Return Presents entries for events that only follows N month B4 and after current
                    //       day (keep date criteria defining the lastest remote DB event to get updates)

                    if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),""))) {
                        $Query .= " WHERE PRE_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                        if ($Ope == 3) // Update
                            $Query .= " AND PRE_Status = 1";
                    }
                    $Query .= " ORDER BY PRE_EventID,PRE_Pseudo ASC";

                    $Result = mysql_query(trim($Query),$Link);
                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Presents":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Presents":[';
                            else $Reply .= ',';

                            $Reply .= '{"EventID":'.strval($aRow["PRE_EventID"]).',';
                            $Reply .= '"Pseudo":"'.trim($aRow["PRE_Pseudo"]).'",';
                            $Reply .= '"Status":'.strval($aRow["PRE_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["PRE_StatusDate"]).'"}';
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
