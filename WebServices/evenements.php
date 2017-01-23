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

                    $Query = "SELECT * FROM Evenements";

                    // TODO: Return events that only follows N month B4 and after current day (keep date
                    //       criteria defining the lastest remote DB event to get updates)

                    if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),""))) {
                        $Query .= " WHERE EVE_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                        if ($Ope == 3) // Update
                            $Query .= " AND EVE_Status = 1";
                    }
                    $Query .= " ORDER BY EVE_Date DESC";

                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Evenements":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Evenements":[';
                            else $Reply .= ',';

                            $Reply .= '{"EventID":'.strval($aRow["EVE_EventID"]).',';
                            $Reply .= '"Pseudo":"'.trim($aRow["EVE_Pseudo"]).'",';
                            $Reply .= '"Nom":"'.str_replace('"','\"',trim($aRow["EVE_Nom"])).'",';
                            $Reply .= '"NomUPD":"'.trim($aRow["EVE_NomUPD"]).'",';
                            $Reply .= '"Lieu":"'.str_replace('"','\"',trim($aRow["EVE_Lieu"])).'",';
                            $Reply .= '"LieuUPD":"'.trim($aRow["EVE_LieuUPD"]).'",';
                            $Reply .= '"Date":"'.str_replace('"','\"',trim($aRow["EVE_Date"])).'",';
                            $Reply .= '"DateUPD":"'.trim($aRow["EVE_DateUPD"]).'",';
                            $Reply .= '"DateEnd":"'.str_replace('"','\"',trim($aRow["EVE_DateEnd"])).'",';
                            $Reply .= '"DateEndUPD":"'.trim($aRow["EVE_DateEndUPD"]).'",';
                            if (!is_null($aRow["EVE_Flyer"])) $Reply .= '"Flyer":"'.str_replace('"','\"',trim($aRow["EVE_Flyer"])).'",';
                            else $Reply .= '"Flyer":null,';
                            $Reply .= '"FlyerUPD":"'.trim($aRow["EVE_FlyerUPD"]).'",';
                            if (!is_null($aRow["EVE_Remark"])) $Reply .= '"Remark":"'.str_replace('"','\"',str_replace("\n","\\n",str_replace("\r\n","\n",trim($aRow["EVE_Remark"])))).'",';
                            else $Reply .= '"Remark":null,';
                            $Reply .= '"RemarkUPD":"'.trim($aRow["EVE_RemarkUPD"]).'",';
                            $Reply .= '"Status":'.strval($aRow["EVE_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["EVE_StatusDate"]).'"}';
                        }
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
