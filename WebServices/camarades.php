<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Date = $_GET['Date'];
header('Content-Type: application/json;charset=ISO-8859-1');

if (!Empty($Clf)) {
    // Connexion
    $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
    if (Empty($Link))
        echo '{"error":'.strval(constant("WEBSERVICE_ERROR_SERVER_UNAVAILABLE")).'}';
    else {

        $Camarade = UserKeyIdentifier($Clf);
        mysql_select_db(GetMySqlDB(),$Link);
        $Query = "SELECT CAM_Pseudo FROM Camarades WHERE CAM_Status <> 2 AND UPPER(CAM_Pseudo) = UPPER('".addslashes($Camarade)."')";
        $Result = mysql_query(trim($Query),$Link);
        if (mysql_num_rows($Result) != 0) {

            mysql_free_result($Result);
            $Query = "SELECT * FROM Camarades";
            if ((!is_null($Date)) && (strcmp(trim($Date),"")))
                $Query .= " WHERE CAM_StatusDate > '".str_replace("n"," ",$Date)."'";
            $Result = mysql_query(trim($Query),$Link);

            // Select
            if (mysql_num_rows($Result) == 0)
                $Reply = '{"camarades":null}';
            else {

                $Reply = '';
                while ($aRow = mysql_fetch_array($Result)) {

                    if (strlen($Reply) == 0) $Reply .= '{"camarades":[';
                    else $Reply .= ',';

                    $Reply .= '{"Pseudo":"'.trim($aRow["CAM_Pseudo"]).'",';
                    $Reply .= '"CodeConf":"'.trim($aRow["CAM_CodeConf"]).'",';
                    if (!is_null($aRow["CAM_Nom"])) $Reply .= '"Nom":"'.trim($aRow["CAM_Nom"]).'",';
                    else $Reply .= '"Nom":null,';
                    if (!is_null($aRow["CAM_Prenom"])) $Reply .= '"Prenom":"'.trim($aRow["CAM_Prenom"]).'",';
                    else $Reply .= '"Prenom":null,';
                    if (!is_null($aRow["CAM_Sexe"])) $Reply .= '"Sexe":'.strval($aRow["CAM_Sexe"]).',';
                    else $Reply .= '"Sexe":null,';
                    if (!is_null($aRow["CAM_BornDate"])) $Reply .= '"BornDate":"'.trim($aRow["CAM_BornDate"]).'",';
                    else $Reply .= '"BornDate":null,';
                    if (!is_null($aRow["CAM_Adresse"])) $Reply .= '"Adresse":"'.trim($aRow["CAM_Adresse"]).'",';
                    else $Reply .= '"Adresse":null,';
                    if (!is_null($aRow["CAM_Ville"])) $Reply .= '"Ville":"'.trim($aRow["CAM_Ville"]).'",';
                    else $Reply .= '"Ville":null,';
                    if (!is_null($aRow["CAM_Postal"])) $Reply .= '"Postal":"'.trim($aRow["CAM_Postal"]).'",';
                    else $Reply .= '"Postal":null,';
                    if (!is_null($aRow["CAM_Email"])) $Reply .= '"Email":"'.trim($aRow["CAM_Email"]).'",';
                    else $Reply .= '"Email":null,';
                    if (!is_null($aRow["CAM_Hobbies"])) $Reply .= '"Hobbies":"'.trim($aRow["CAM_Hobbies"]).'",';
                    else $Reply .= '"Hobbies":null,';
                    if (!is_null($aRow["CAM_APropos"])) $Reply .= '"APropos":"'.trim($aRow["CAM_APropos"]).'",';
                    else $Reply .= '"APropos":null,';
                    if (!is_null($aRow["CAM_LogDate"])) $Reply .= '"LogDate":"'.trim($aRow["CAM_LogDate"]).'",';
                    else $Reply .= '"LogDate":null,';
                    $Reply .= '"Admin":'.strval($aRow["CAM_Admin"]).',';
                    if (!is_null($aRow["CAM_Profile"])) $Reply .= '"Profile":"'.trim($aRow["CAM_Profile"]).'",';
                    else $Reply .= '"Profile":null,';
                    if (!is_null($aRow["CAM_Banner"])) $Reply .= '"Banner":"'.trim($aRow["CAM_Banner"]).'",';
                    else $Reply .= '"Banner":null,';
                    $Reply .= '"Located":'.strval($aRow["CAM_Located"]).',';
                    if (!is_null($aRow["CAM_Latitude"])) $Reply .= '"Latitude":'.strval($aRow["CAM_Latitude"]).',';
                    else $Reply .= '"Latitude":null,';
                    if (!is_null($aRow["CAM_Longitude"])) $Reply .= '"Longitude":'.strval($aRow["CAM_Longitude"]).',';
                    else $Reply .= '"Longitude":null,';
                    $Reply .= '"Status":'.strval($aRow["CAM_Status"]).',';
                    $Reply .= '"StatusDate":"'.trim($aRow["CAM_StatusDate"]).'"}';
                }
                $Reply .= ']}';
            }
            echo $Reply;
        }
        else
            echo '{"error":'.strval(constant("WEBSERVICE_ERROR_INVALID_USER")).'}';
        mysql_close($Link);
    }
}
else
    echo '{"error":'.strval(constant("WEBSERVICE_ERROR_INVALID_TOKEN")).'}';
?>
