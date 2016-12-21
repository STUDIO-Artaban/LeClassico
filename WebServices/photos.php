<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];

$Best = false;
if (isset($_POST['Best']))
    $Best = true;
if (isset($_POST['Ids']))
    $Ids = $_POST['Ids'];

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

                    if ($Best) { // Best photos

                        $Query = "SELECT SUM(VOT_Note)+SUM(VOT_Total) AS VOT_Pos,VOT_Fichier FROM Votes";
                        $Query .= " WHERE VOT_Type=0";
                        $Query .= " GROUP BY VOT_Fichier ORDER BY VOT_Pos DESC";
                        $Result = mysql_query(trim($Query),$Link);
                        if (mysql_num_rows($Result) == 0) {
                            $Reply = '{"Photos":null}';
                            break; // No vote
                        }
                        $PhtIndex = 0;
                        $PhtFirst = true;
                        while ($aRow = mysql_fetch_array($Result)) {
                            if (!$PhtFirst)
                            {   if (($PhtVot != $aRow["VOT_Pos"]) && ($PhtIndex >= 3))
                                    break;
                                $aPht[] = GetPhotoID($aRow["VOT_Fichier"]);
                                $PhtIndex++;
                            }
                            else
                            {   $aPht[] = GetPhotoID($aRow["VOT_Fichier"]);
                                $PhtIndex++;
                                $PhtFirst = false;
                            }
                            $PhtVot = $aRow["VOT_Pos"];
                        }
                        mysql_free_result($Result);
                        if ((isset($Ids)) && (explode('n', $Ids) == $aPht)) {
                            $Reply = '{"Photos":null}';
                            break; // Same best photos (update)
                        }
                        $PhtFirst = true;
                        foreach ($aPht as &$Pht) {

                            if ($PhtFirst) $PhtFiles = strval($Pht);
                            else $PhtFiles .= ",$Pht";
                            $PhtFirst = false;
                        }
                        $Query = "SELECT * FROM Photos WHERE PHT_FichierID IN ($PhtFiles)";
                    }
                    if (!$Best) { // Select

                        $Query = "SELECT * FROM Photos LEFT JOIN Albums ON PHT_Album = ALB_Nom";
                        $Query .= " WHERE UPPER(ALB_Pseudo) = UPPER('".addslashes($Camarade)."') AND PHT_Album <> 'Journal'";
                        if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),"")))
                            $Query .= " AND PHT_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                    }
                    $Query .= " ORDER BY PHT_FichierID ASC";

                    $Result = mysql_query(trim($Query),$Link);
                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Photos":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Photos":[';
                            else $Reply .= ',';

                            $Reply .= '{"Album":"'.trim($aRow["PHT_Album"]).'",';
                            $Reply .= '"Pseudo":"'.trim($aRow["PHT_Pseudo"]).'",';
                            $Reply .= '"Fichier":"'.trim($aRow["PHT_Fichier"]).'",';
                            $Reply .= '"FichierID":'.strval($aRow["PHT_FichierID"]).',';
                            if ($Best) $Reply .= '"Best":1,';
                            else $Reply .= '"Best":null,';
                            $Reply .= '"Status":'.strval($aRow["PHT_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["PHT_StatusDate"]).'"}';
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
