<?php
require("../Package.php");
require("constants.php");

$Clf = $_GET['Clf'];
$Ope = $_GET['Ope'];
$StatusDate = $_GET['StatusDate'];

$Best = false;
if (isset($_POST['Best']))
    $Best = true;
if (isset($_POST['Files']))
    $Files = $_POST['Files'];

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
                            break;

                        } else {

                            $PhtFirst = true;
                            while ($aRow = mysql_fetch_array($Result)) {
                                if (!$PhtFirst)
                                {   if (($PhtVot != $aRow["VOT_Pos"]) && ($PhtIndex >= 3)) break;
                                    else $PhtFile .= ",'".trim($aRow["VOT_Fichier"])."'";
                                }
                                else
                                {   $PhtFile = "'".trim($aRow["VOT_Fichier"])."'";
                                    $PhtFirst = false;
                                }
                                $PhtVot = $aRow["VOT_Pos"];
                            }
                            mysql_free_result($Result);








                            if (isset($Files)) {



                            } else
                                $Query = "SELECT * FROM Photos WHERE PHT_Fichier IN ($PhtFile)";

                            






                        }

                    }
                    if (!$Best) { // Select








                        $Query = "SELECT * FROM Photos WHERE ";
                        if ((!is_null($StatusDate)) && (strcmp(trim($StatusDate),"")))
                            $Query .= " AND PHT_StatusDate > '".str_replace("n"," ",$StatusDate)."'";
                        $Query .= " ORDER BY PHT_Fichier ASC";








                    }
                    $Result = mysql_query(trim($Query),$Link);
                    if (mysql_num_rows($Result) == 0)
                        $Reply = '{"Photos":null}';
                    else {

                        $Reply = '';
                        while ($aRow = mysql_fetch_array($Result)) {

                            if (strlen($Reply) == 0) $Reply .= '{"Commentaires":[';
                            else $Reply .= ',';

                            $Reply .= '{"ObjType":"'.trim($aRow["COM_ObjType"]).'",';
                            $Reply .= '"ObjID":'.strval($aRow["COM_ObjID"]).',';
                            $Reply .= '"Pseudo":"'.trim($aRow["COM_Pseudo"]).'",';
                            $Reply .= '"Date":"'.trim($aRow["COM_Date"]).'",';
                            $Reply .= '"Text":"'.str_replace('"','\"',str_replace("\n","\\n",str_replace("\r\n","\n",trim($aRow["COM_Text"])))).'",';
                            $Reply .= '"Status":'.strval($aRow["COM_Status"]).',';
                            $Reply .= '"StatusDate":"'.trim($aRow["COM_StatusDate"]).'"}';
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
