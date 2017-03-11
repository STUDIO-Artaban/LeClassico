<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];

$Keys = $_POST['Keys'];
$Updates = $_POST['Updates'];
$Status = $_POST['Status'];

if (isset($_POST['Pseudos']))
    $Pseudos = $_POST['Pseudos'];

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
                    if (Empty($Updates)) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_MISSING_UPDATES")).'}';
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
                    $Updates = json_decode($Updates, true);
                    if (json_last_error() != JSON_ERROR_NONE) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_UPDATES")).'}';
                        break;
                    }
                    $Status = json_decode($Status, true);
                    if (json_last_error() != JSON_ERROR_NONE) {
                        echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_INVALID_STATUS")).'}';
                        break;
                    }

                    //////
                    $updatesKeys = array_keys($Updates);
                    $updatesValues = array_values($Updates);
                    $statusKeys = array_keys($Status);
                    $statusValues = array_values($Status);

                    $i = 0;
                    $Lenght = count($Keys);
                    for ( ; $i < $Lenght; ++$i) { // Keys loop

                        $j = 0;
                        $fieldsCount = count($updatesKeys);
                        for ( ; $j < $fieldsCount; ++$j) { // update loop

                            $Query = "UPDATE Camarades SET";
                            $Query .= " CAM_".trim($updatesKeys[$j])."=";
                            if ((!strcmp($updatesKeys[$j], "Sexe")) ||
                                (!strcmp($updatesKeys[$j], "Located")) ||
                                (!strcmp($updatesKeys[$j], "Admin")) ||
                                (!strcmp($updatesKeys[$j], "Latitude")) ||
                                (!strcmp($updatesKeys[$j], "Longitude")))
                                $Query .= strval($updatesValues[$j]);
                            else
                                $Query .= "'".trim($updatesValues[$j])."'";

                            $Query .= " WHERE";
                            $Query .= " CAM_Pseudo='".trim($Keys[$i]['Pseudo'])."' AND";
                            $Query .= " CAM_".trim($statusKeys[$j])."<'".trim($statusValues[$j])."'";

                            if (!mysql_query(trim($Query),$Link)) {
                                echo '{"Error":'.strval(constant("WEBSERVICE_ERROR_QUERY_UPDATE")).'}';
                                break;
                            }
                            if ((mysql_affected_rows() == 0) && ((is_null($StatusDate)) || (strcmp($StatusDate, $statusValues[$j]) < 0)))
                                $StatusDate = $statusValues[$j];
                                // NB: Needed to return records updated after current request
                        }
                        if ($j != $fieldsCount)
                            break; // Error
                    }
                    if ($i != $Lenght)
                        break; // Error

                    // Let's reply with updated records
                    //break;
                }
                case 4: { ////// Insert

                    if ($Ope == 4) {








                    }
                    // Let's reply with inserted/updated records
                    //break;
                }
                case 1: { ////// Select

                    $Query = "SELECT Camarades.* FROM Camarades";
                    
                    //$Query .= " INNER JOIN Abonnements ON CAM_Pseudo = ABO_Camarade AND UPPER(ABO_Pseudo) = UPPER('".addslashes($Camarade)."')";
                    // TODO: Remove comment above when more than a hundred members will be available

                    if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),"")))
                        $Query .= " WHERE CAM_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                    if ((!is_null($Pseudos)) && (strcmp(trim($Pseudos),""))) // Request
                        $Query .= " AND CAM_Pseudo IN ('".str_replace("&","','",$Pseudos)."')";
                    $Result = mysql_query(trim($Query),$Link);

                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Camarades":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Camarades":[';
                            else $Reply .= ',';

                            $Reply .= '{"Pseudo":"'.trim($aRow["CAM_Pseudo"]).'",';
                            if (!strcmp(strtoupper($Camarade),strtoupper($aRow["CAM_Pseudo"]))) {
                                $Reply .= '"CodeConf":"'.str_replace('"','\"',trim($aRow["CAM_CodeConf"])).'",';
                                $Reply .= '"CodeConfUPD":"'.trim($aRow["CAM_CodeConfUPD"]).'",';
                            } else // Do not return PWD if not the connected user (security reasons)
                                $Reply .= '"CodeConf":null,';
                            if (!is_null($aRow["CAM_Nom"])) $Reply .= '"Nom":"'.str_replace('"','\"',trim($aRow["CAM_Nom"])).'",';
                            else $Reply .= '"Nom":null,';
                            $Reply .= '"NomUPD":"'.trim($aRow["CAM_NomUPD"]).'",';
                            if (!is_null($aRow["CAM_Prenom"])) $Reply .= '"Prenom":"'.str_replace('"','\"',trim($aRow["CAM_Prenom"])).'",';
                            else $Reply .= '"Prenom":null,';
                            $Reply .= '"PrenomUPD":"'.trim($aRow["CAM_PrenomUPD"]).'",';
                            if (!is_null($aRow["CAM_Sexe"])) $Reply .= '"Sexe":'.strval($aRow["CAM_Sexe"]).',';
                            else $Reply .= '"Sexe":null,';
                            $Reply .= '"SexeUPD":"'.trim($aRow["CAM_SexeUPD"]).'",';
                            if (!is_null($aRow["CAM_BornDate"])) $Reply .= '"BornDate":"'.trim($aRow["CAM_BornDate"]).'",';
                            else $Reply .= '"BornDate":null,';
                            $Reply .= '"BornDateUPD":"'.trim($aRow["CAM_BornDateUPD"]).'",';
                            if (!is_null($aRow["CAM_Adresse"])) $Reply .= '"Adresse":"'.str_replace('"','\"',trim($aRow["CAM_Adresse"])).'",';
                            else $Reply .= '"Adresse":null,';
                            $Reply .= '"AdresseUPD":"'.trim($aRow["CAM_AdresseUPD"]).'",';
                            if (!is_null($aRow["CAM_Ville"])) $Reply .= '"Ville":"'.trim($aRow["CAM_Ville"]).'",';
                            else $Reply .= '"Ville":null,';
                            $Reply .= '"VilleUPD":"'.trim($aRow["CAM_VilleUPD"]).'",';
                            if (!is_null($aRow["CAM_Postal"])) $Reply .= '"Postal":"'.str_replace('"','\"',trim($aRow["CAM_Postal"])).'",';
                            else $Reply .= '"Postal":null,';
                            $Reply .= '"PostalUPD":"'.trim($aRow["CAM_PostalUPD"]).'",';
                            if (!is_null($aRow["CAM_Phone"])) $Reply .= '"Phone":"'.str_replace('"','\"',trim($aRow["CAM_Phone"])).'",';
                            else $Reply .= '"Phone":null,';
                            $Reply .= '"PhoneUPD":"'.trim($aRow["CAM_PhoneUPD"]).'",';
                            if (!is_null($aRow["CAM_Email"])) $Reply .= '"Email":"'.str_replace('"','\"',trim($aRow["CAM_Email"])).'",';
                            else $Reply .= '"Email":null,';
                            $Reply .= '"EmailUPD":"'.trim($aRow["CAM_EmailUPD"]).'",';
                            if (!is_null($aRow["CAM_Hobbies"])) $Reply .= '"Hobbies":"'.str_replace('"','\"',str_replace("\n","\\n",str_replace("\r\n","\n",trim($aRow["CAM_Hobbies"])))).'",';
                            else $Reply .= '"Hobbies":null,';
                            $Reply .= '"HobbiesUPD":"'.trim($aRow["CAM_HobbiesUPD"]).'",';
                            if (!is_null($aRow["CAM_APropos"])) $Reply .= '"APropos":"'.str_replace('"','\"',str_replace("\n","\\n",str_replace("\r\n","\n",trim($aRow["CAM_APropos"])))).'",';
                            else $Reply .= '"APropos":null,';
                            $Reply .= '"AProposUPD":"'.trim($aRow["CAM_AProposUPD"]).'",';
                            if (!is_null($aRow["CAM_LogDate"])) $Reply .= '"LogDate":"'.trim($aRow["CAM_LogDate"]).'",';
                            else $Reply .= '"LogDate":null,';
                            $Reply .= '"LogDateUPD":"'.trim($aRow["CAM_LogDateUPD"]).'",';
                            $Reply .= '"Admin":'.strval($aRow["CAM_Admin"]).',';
                            $Reply .= '"AdminUPD":"'.trim($aRow["CAM_AdminUPD"]).'",';
                            if (!is_null($aRow["CAM_Profile"])) $Reply .= '"Profile":"'.trim($aRow["CAM_Profile"]).'",';
                            else $Reply .= '"Profile":null,';
                            $Reply .= '"ProfileUPD":"'.trim($aRow["CAM_ProfileUPD"]).'",';
                            if (!is_null($aRow["CAM_Banner"])) $Reply .= '"Banner":"'.trim($aRow["CAM_Banner"]).'",';
                            else $Reply .= '"Banner":null,';
                            $Reply .= '"BannerUPD":"'.trim($aRow["CAM_BannerUPD"]).'",';
                            $Reply .= '"Located":'.strval($aRow["CAM_Located"]).',';
                            $Reply .= '"LocatedUPD":"'.trim($aRow["CAM_LocatedUPD"]).'",';
                            if (!is_null($aRow["CAM_Latitude"])) $Reply .= '"Latitude":'.strval($aRow["CAM_Latitude"]).',';
                            else $Reply .= '"Latitude":null,';
                            $Reply .= '"LatitudeUPD":"'.trim($aRow["CAM_LatitudeUPD"]).'",';
                            if (!is_null($aRow["CAM_Longitude"])) $Reply .= '"Longitude":'.strval($aRow["CAM_Longitude"]).',';
                            else $Reply .= '"Longitude":null,';
                            $Reply .= '"LongitudeUPD":"'.trim($aRow["CAM_LongitudeUPD"]).'",';
                            $Reply .= '"Status":'.strval($aRow["CAM_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["CAM_StatusDate"]).'"}';
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
