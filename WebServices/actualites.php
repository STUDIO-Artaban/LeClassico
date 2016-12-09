<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];
$Count = $_GET['Count'];
$Date = $_GET['Date'];

$Cam = $_GET['Cam'];
$Actu = $_GET['Actu'];
$Cmd = $_GET['Cmd'];
if (!Empty($Cam))
    $Cam = base64_decode(urldecode($Cam));

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
            if (Empty($Ope)) { ////// Web site //////////////////////////////////////////////////////////////////////////////////////////

                // NB: Check from which the web service is consumed coz update, insert or delete
                //     operation management can be different (using JSON request for application)
                if (!Empty($Cmd)) {

                    // Delete
                    if (Empty($Actu))
                        $Reply = '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_PUBLICATION_ID")).'}';
                    else {

                        $Query = "SELECT ACT_Fichier FROM Actualites WHERE ACT_Status <> 2 AND ACT_ActuID = $Actu";
                        $Result = mysql_query(trim($Query),$Link);
                        if($aRow = mysql_fetch_array($Result))
                            $File = $aRow["ACT_Fichier"];
                        mysql_free_result($Result);
                        $Query = "UPDATE Actualites SET ACT_Status = 2, ACT_StatusDate = CURRENT_TIMESTAMP WHERE ACT_ActuID = $Actu AND (UPPER(ACT_Pseudo) = UPPER('".addslashes($Camarade)."') OR UPPER(ACT_Camarade) = UPPER('".addslashes($Camarade)."'))";
                        if (!mysql_query(trim($Query),$Link))
                            $Reply = '{"Error":'.strval(constant("WEBSERVICE_ERROR_REQUEST_PUBLICATION_DELETE")).'}';

                        else { // Remove image file (if any)
                            if (!is_null($File)) {
                                @unlink(GetSrvPhtFolder()."$File");
                                $Query = "UPDATE Photos SET PHT_Status = 2, PHT_StatusDate = CURRENT_TIMESTAMP WHERE PHT_Fichier = '$File'";
                                mysql_query(trim($Query),$Link);
                            }
                            $Reply = '{}'; // Ok...
                        }
                    }
                }
                else { // Select

                    $Query = "SELECT ACT_ActuID,ACT_Pseudo,CAM_Profile,CAM_Sexe,ACT_Camarade,ACT_Date,ACT_Text,ACT_Link,ACT_Fichier,ACT_Status,ACT_StatusDate";
                    $Query .= " FROM Actualites LEFT JOIN Camarades ON ACT_Pseudo = CAM_Pseudo AND CAM_Status <> 2";
                    if ((!Empty($Cam)) && (strcmp($Cam,$Camarde))) {

                        $Query .= " WHERE (UPPER(ACT_Pseudo) = UPPER('".addslashes($Cam)."') OR UPPER(ACT_Camarade) = UPPER('".addslashes($Cam)."'))";
                        if ((!is_null($Date)) && (strcmp(trim($Date),"")))
                            $Query .= " AND ACT_Date > '".str_replace("n"," ",$Date)."'";
                    }
                    else {
                        $Query .= " INNER JOIN Abonnements ON ACT_Pseudo = ABO_Camarade AND ABO_Status <> 2 AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
                        if ((!is_null($Date)) && (strcmp(trim($Date),"")))
                            $Query .= " AND ACT_StatusDate > '".str_replace("n"," ",$Date)."'";
                    }
                    $Query .= " ORDER BY ACT_Date DESC";
                    if (!Empty($Count))
                        $Query .= " LIMIT $Count";
                    $Result = mysql_query(trim($Query),$Link);

                    // Reply
                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Actualites":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            $Profile = $aRow["CAM_Profile"];
                            if (is_null($Profile)) {
                                if ((!is_null($aRow["CAM_Sexe"])) && ($aRow["CAM_Sexe"] == 1))
                                    $Profile = "Images/woman.png";
                                else
                                    $Profile = "Images/man.png";
                            }
                            else
                                $Profile = "Profiles/$Profile";

                            if (strlen($Reply) == 0) $Reply .= '{"Actualites":[';
                            else $Reply .= ',';

                            $Reply .= '{"Status":'.strval($aRow["ACT_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["ACT_StatusDate"]).'",';
                            $Reply .= '"Profile":"'.trim($Profile).'",';
                            $Reply .= '"CamaradeURL":"'.urlencode(base64_encode($aRow["ACT_Camarade"])).'",';
                            $Reply .= '"Camarade":"'.addslashes($aRow["ACT_Camarade"]).'",';
                            $Reply .= '"Pseudo":"'.addslashes($aRow["ACT_Pseudo"]).'",';
                            $Reply .= '"PseudoURL":"'.urlencode(base64_encode($aRow["ACT_Pseudo"])).'",';
                            $Reply .= '"Date":"'.substr($aRow["ACT_Date"],0,10).'",';
                            $Reply .= '"Time":"'.substr($aRow["ACT_Date"],11).'",';
                            $Reply .= '"Text":"'.str_replace('"','\"',str_replace("\n","\\n",str_replace("\r\n","\n",trim($aRow["ACT_Text"])))).'",';
                            $Reply .= '"Link":"'.str_replace('"','\"',trim($aRow["ACT_Link"])).'",';
                            $Reply .= '"Fichier":"'.trim($aRow["ACT_Fichier"]).'",';
                            $Reply .= '"RemoveFlag":'.(((!strcmp($Camarade,$aRow["ACT_Pseudo"]))||(!strcmp($Camarade,$aRow["ACT_Camarade"])))? "true":"false").',';
                            $Reply .= '"ActuID":'.strval($aRow["ACT_ActuID"]).'}';
                        }
                        $Reply .= ']}';
                    }
                }
            }
            else { ////// Application ///////////////////////////////////////////////////////////////////////////////////////////////////

                switch ($Ope) {
                    case 3: { ////// Update

                        break;
                    }
                    case 4: { ////// Insert

                        break;
                    }
                    case 1:
                    case 2: { ////// Select

                        $Query = "SELECT * FROM Actualites";
                        $Query .= " INNER JOIN Abonnements ON ACT_Pseudo = ABO_Camarade AND ABO_Status <> 2 AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
                        // TODO: If a followed member has published on a wall and if the owner of this wall removes him from its
                        //       followed list, that will cause to hide the publication from this result! Should includes the
                        //       query result used to display only user publications (with the WHERE clause above).

                        if ($Ope == 2) // Old
                            $Query .= " AND ACT_Date < '".str_replace("n"," ",$Date)."'";
                        else { // New & Update
                            if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),""))) {
                                $Query .= " AND ACT_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                                $Query .= " AND ACT_Date >= '".str_replace("n"," ",$Date)."'";
                                if ($Ope == 3) // Update
                                    $Query .= " AND ACT_Status = 1";
                            }
                        }
                        $Query .= " ORDER BY ACT_Date DESC";
                        if (!Empty($Count))
                            $Query .= " LIMIT $Count";

                        $Result = mysql_query(trim($Query),$Link);
                        if (mysql_num_rows($Result) == 0)
                            $Reply = '{"Actualites":null}';
                        else {

                            $Reply = '';
                            while ($aRow = mysql_fetch_array($Result)) {

                                if (strlen($Reply) == 0) $Reply .= '{"Actualites":[';
                                else $Reply .= ',';

                                $Reply .= '{"ActuID":'.strval($aRow["ACT_ActuID"]).',';
                                $Reply .= '"Pseudo":"'.trim($aRow["ACT_Pseudo"]).'",';
                                $Reply .= '"Date":"'.trim($aRow["ACT_Date"]).'",';
                                if (!is_null($aRow["ACT_Camarade"])) $Reply .= '"Camarade":"'.trim($aRow["ACT_Camarade"]).'",';
                                else $Reply .= '"Camarade":null,';
                                if (!is_null($aRow["ACT_Text"])) $Reply .= '"Text":"'.trim($aRow["ACT_Text"]).'",';
                                else $Reply .= '"Text":null,';
                                if (!is_null($aRow["ACT_Link"])) $Reply .= '"Link":"'.trim($aRow["ACT_Link"]).'",';
                                else $Reply .= '"Link":null,';
                                if (!is_null($aRow["ACT_Fichier"])) $Reply .= '"Fichier":"'.trim($aRow["ACT_Fichier"]).'",';
                                else $Reply .= '"Fichier":null,';
                                $Reply .= '"Status":'.strval($aRow["ACT_Status"]).',';
                                $Reply .= '"StatusDate":"'.trim($aRow["ACT_StatusDate"]).'"}';
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
