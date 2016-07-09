<?php
require("Package.php");
$Chp = $_GET['Chp'];
$Clf = $_GET['Clf'];
$Cam = $_GET['Cam'];
$psd = $_POST['psd'];
$ccf = $_POST['ccf'];
$bRes = true;
if(Empty($Clf))
{  if((Empty($psd))||(Empty($ccf)))
   {   //include("Connexion.php");
       //die();
   }
   else
   {   // Connexion
       $Link = @mysql_connect(GetMySqlLocalhost(),GetMySqlUser(),GetMySqlPassword());
       if(Empty($Link))
       {   $NoBack = true;
           $Msg = "Connexion au serveur Impossible!";
           include("Message.php");
           die();
       }
       else
       {   $Query = "SELECT CAM_Pseudo FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".trim($psd)."')";
           mysql_select_db(GetMySqlDB(),$Link);
           $Result = mysql_query(trim($Query),$Link);
           if(!mysql_num_rows($Result))
           {   $Msg = "Pseudo Inconnu!";
               $bRes = false;
           }
           else
           {   $aRow = mysql_fetch_array($Result);
               $Camarade = stripslashes($aRow["CAM_Pseudo"]);
               mysql_free_result($Result);
               $Query = "SELECT 'X' FROM Camarades WHERE UPPER(CAM_Pseudo) = UPPER('".trim($psd)."') AND UPPER(CAM_CodeConf) = UPPER('".trim($ccf)."')";
               $Result = mysql_query(trim($Query),$Link);
               if(!mysql_num_rows($Result))
               {   $Msg = "Code Erron&eacute;!";
                   $bRes = false;
               }
               else
               {   mysql_free_result($Result);
                   // Optimisation des tables MySQL
                   $Query = "OPTIMIZE TABLE Camarades, Evenements, Presents, Albums, Photos, Messagerie, Votes, Abonnements, Commentaires, Actualites";
                   mysql_query(trim($Query),$Link);
               }
           }
           mysql_close($Link);
       }
       if(!$bRes)
       {   //include("Connexion.php");
           //die();
           $Chp = 11;
       }
       else $Clf = GetKeyIdentifier($Camarade,3600); // 600 -> 10 min, 3600 -> 1 heure
   }
}
else
{   if(CompareKeyIdentifier($Clf)) $Clf = GetKeyIdentifier(UserKeyIdentifier($Clf),3600); // 600 -> 10 min, 3600 -> 1 heure
    else
    {   $Msg = "Connexion Expir&eacute;e!";
        $Clf = "";
        $Chp = 11;
        //include("Connexion.php");
        //die();
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<?php
////////////////////////////////
if(!Empty($Chp))
{   if(!strcmp($Chp,"1"))
    {   echo "<title>Le Classico: Accueil</title>\n";
        $Mnu = "0";
    }
    else if(!strcmp($Chp,"2"))
    {   echo "<title>Le Classico: Profile</title>\n";
        $Mnu = "1";
    }
    else if(!strcmp($Chp,"3"))
    {   echo "<title>Le Classico: Ajout d'1 Camarade</title>\n";
        $Mnu = "1";
    }
    else if(!strcmp($Chp,"4"))
    {   echo "<title>Le Classico: Recherche 1 Camarade</title>\n";
        $Mnu = "1";
    }
    else if(!strcmp($Chp,"5"))
    {   echo "<title>Le Classico: Fil d'actualit&eacute;</title>\n";
        $Mnu = "1";
    }
    else if(!strcmp($Chp,"6"))
    {   echo "<title>Le Classico: La Messagerie</title>\n";
        $Mnu = "1";
    }
    else if(!strcmp($Chp,"7"))
    {   echo "<title>Le Classico: Les Albums Photos</title>\n";
        if((!Empty($Clf))&&(strcmp($Clf,""))) $Mnu = "2";
        else $Mnu = "0";
    }
    else if(!strcmp($Chp,"8"))
    {   echo "<title>Le Classico: Gestionnaire des Albums</title>\n";
        $Mnu = "2";
    }
    else if(!strcmp($Chp,"9"))
    {   echo "<title>Le Classico: Gestionnaire des Photos</title>\n";
        $Mnu = "2";
    }
    else if(!strcmp($Chp,"10"))
    {   echo "<title>Le Classico: Musique</title>\n";
        //$Mnu = "3"; // Chantier
        $Mnu = "0";
    }
    else if(!strcmp($Chp,"11"))
    {   echo "<title>Le Classico: Login</title>\n";
        $Mnu = "0";
    }
    else if(!strcmp($Chp,"12"))
    {   echo "<title>Le Classico: Ajout/Suppression d'1 Morceau</title>\n";
        $Mnu = "3";
    }
    else if(!strcmp($Chp,"13"))
    {   echo "<title>Le Classico: Le Classement</title>\n";
        $Mnu = "3";
    }
    else if(!strcmp($Chp,"14"))
    {   echo "<title>Le Classico: Les Ev&eacute;nements</title>\n";
        if((!Empty($Clf))&&(strcmp($Clf,""))) $Mnu = "4";
        else $Mnu = "0";
    }
    else if(!strcmp($Chp,"15"))
    {   echo "<title>Le Classico: Gestionnaire des Ev&eacute;nements</title>\n";
        $Mnu = "4";
    }
    else
    {   echo "<title>Le Classico: Accueil</title>\n";
        $Mnu = "0";
    }
}
else
{   $Chp = "1";
    $Mnu = "0";
    echo "<title>Le Classico: Accueil</title>\n";
}
////////////////////////////////
?>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="icon" href="http://www.leclassico.fr/Images/LeClassico.ico" />
<frameset border=0 rows="174,*,131">
        <frameset border=0 cols="180,31,574,*">
                <frame src="Ball.html" frameborder="no" scrolling="no" noresize>
                <frame src="Between.html" frameborder="no" scrolling="no" noresize>
                <frameset border=0 rows="137,37">
                        <frame src="Title.php<?php
                        if((!Empty($Clf))&&(strcmp($Clf,""))) echo "?Clf=$Clf";
                        ?>" frameborder="no" scrolling="no" noresize>
                        <frame src="ClouthS.html" frameborder="no" scrolling="no" noresize>
                </frameset>
                <frameset border=0 rows="73,101">
                        <frame src="Orange.html" frameborder="no" scrolling="no" noresize>
                        <frame src="ClouthB.html" frameborder="no" scrolling="no" noresize>
                </frameset>
        </frameset>
        <?php
        if(!strcmp($Chp,"6"))
        {   // Mail
        ?>
        <frameset border=0 cols="225,10,*">
                <frame src="Menu.php?Mnu=<?php echo $Mnu; ?>&Chp=<?php echo $Chp; ?>&Clf=<?php echo $Clf; ?>" frameborder="no">
                <frame src="White.html" frameborder="no" scrolling="no" noresize>
                <frameset border=0 rows="47,121,20,*,20">
                        <frame src="MailTop.html" frameborder="no" scrolling="no" noresize>
                        <frameset border=0 cols="2,*,2,16">
                                  <frame src="Red.html" frameborder="no" scrolling="no" noresize>
                                  <frame src="MailMain.php?Clf=<?php echo $Clf; ?>" frameborder="no">
                                  <frame src="Red.html" frameborder="no" scrolling="no" noresize>
                                  <frame src="White.html" frameborder="no" scrolling="no" noresize>
                        </frameset>
                        <frame src="MailBot.html" frameborder="no" scrolling="no" noresize>
                        <frameset border=0 cols="300,*">
                                  <frameset border=0 rows="37,*">
                                            <frame name="MailPadTop" src="MailPadTop.php" frameborder="no">
                                            <frameset border=0 cols="2,298">
                                                      <frame src="Red.html" frameborder="no" scrolling="no" noresize>
                                                      <frame name="MailPad" src="MailPad.php?Clf=<?php echo $Clf; ?>" frameborder="no">
                                            </frameset>
                                  </frameset>
                                  <frameset border=0 rows="15,*">
                                            <frame src="MailCntTop.html" frameborder="no" scrolling="no" noresize>
                                            <frameset border=0 cols="*,2,16">
                                                      <frame name="MailContent" src="Mail.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="yes" style="overflow-x: auto">
                                                      <frame src="Red.html" frameborder="no" scrolling="no" noresize>
                                                      <frame src="White.html" frameborder="no" scrolling="no" noresize>
                                            </frameset>
                                  </frameset>
                        </frameset>
                        <frame src="MailBot.html" frameborder="no" scrolling="no" noresize>
                </frameset>
        </frameset>
        <?php
            // Mail
        }
        else
        {   // Pas Mail
        ?>
        <frameset border=0 cols="225,*">
                <frame src="Menu.php?Mnu=<?php echo $Mnu; ?>&Chp=<?php
                echo $Chp;
                if((!Empty($Clf))&&(strcmp($Clf,""))) echo "&Clf=$Clf";
                ?>" frameborder="no">
                <?php
                if(!strcmp($Chp,"5"))
                {   // Forum
                ?>
                <frameset border=0 rows="15,32,*<?php if((!Empty($Clf))&&(strcmp($Clf,""))) echo ",81"; ?>,10">
                        <frame src="ScrollTop.html" frameborder="no" scrolling="no" noresize>
                        <frame src="FrmTitle.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize>
                        <frame src="FrmMsg.php?Clf=<?php echo $Clf; ?>#EndMsg" frameborder="no" scrolling="yes" style="overflow-x: auto">
                        <?php
                        if((!Empty($Clf))&&(strcmp($Clf,"")))
                        {    // Connecté
                             ?><frame src="FrmSend.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize><?php
                             // Connecté
                        }
                        ?>
                        <frame src="ScrollBot.html" frameborder="no" scrolling="no" noresize>
                </frameset>
                <?php
                }
                else
                {   // Pas Forum
                    if(!strcmp($Chp,"9"))
                    {   // Gestion des Photos
                ?>
                <frameset border=0 rows="15,51,10,180,*">
                        <frame src="ScrollTop.html" frameborder="no" scrolling="no" noresize>
                        <frameset border=0 cols="*,16">
                                <frame src="PhtTitle.php?Clf=<?php echo $Clf; ?>" frameborder="no">
                                <frame src="White.html" frameborder="no" scrolling="no" noresize>
                        </frameset>
                        <frame src="ScrollBot.html" frameborder="no" scrolling="no" noresize>
                        <frame name="PhtManager" src="PhtMan.php?Clf=<?php echo $Clf; ?>" frameborder="no">
                        <frameset border=0 cols="60%,40%">
                                <frameset border=0 rows="10,*,10">
                                        <frame src="PhtTop.html" frameborder="no" scrolling="no" noresize>
                                        <frameset border=0 cols="1,*">
                                                <frame name="PhtNewFile" src="PhtNew.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize>
                                                <frame name="PhtStatus" src="PhtStatus.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="yes" style="overflow-x: auto">
                                        </frameset>
                                        <frame src="Bottom.html" frameborder="no" scrolling="no" noresize>
                                </frameset>
                                <frame src="White.html" frameborder="no" scrolling="no" noresize>
                        </frameset>
                </frameset>
                <?php
                        // Gestion des Photos
                    }
                    else
                    {   // Pas Gestion des Photos
                        if(!strcmp($Chp,"14"))
                        {   // Affichage des Evènements
                ?>
                <frameset border=0 rows="15,199,*,10">
                        <frame src="Top.html" frameborder="no" scrolling="no" noresize>
                        <frameset border=0 cols="232,*">
                                <frame name="EvntCal" src="EventCal.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize>
                                <frameset border=0 rows="39,140,25">
                                        <frame name="EvntTitle" src="EventTit.php" frameborder="no" scrolling="no" noresize>
                                        <frameset border=0 cols="7,*,17">
                                                <frame src="EventLef.html" frameborder="no" scrolling="no" noresize>
                                                <frame name="EvntSelect" src="EventSel.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="yes" style="overflow-x: auto">
                                                <frame src="EventRig.html" frameborder="no" scrolling="no" noresize>
                                        </frameset>
                                        <frame src="EventBot.html" frameborder="no" scrolling="no" noresize>
                                </frameset>
                        </frameset>
                        <frame src="EventLst.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="yes" style="overflow-x: auto">
                        <frame src="Bottom.html" frameborder="no" scrolling="no" noresize>
                </frameset>
                <?php
                            // Affichage des Evènements
                        }
                        else
                        {   // Pas Affichage des Evènements
                            if(!strcmp($Chp,"15"))
                            {   // Gestion des Evènements
                ?>
                <frameset border=0 rows="15,*,100">
                        <frame src="Top.html" frameborder="no" scrolling="no" noresize>
                        <frame name="EvntManager" src="EventMan.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="yes" style="overflow-x: auto">
                        <frameset border=0 cols="58%,42%">
                                <frameset border=0 rows="100,10">
                                        <frameset border=0 cols="1,*">
                                                <frame name="EvntAction" src="EventAct.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize>
                                                <frame name="EvntStatus" src="EventStat.php?Clf=<?php echo $Clf; ?>" frameborder="no" scrolling="no" noresize>
                                        </frameset>
                                        <frame src="BotEvent.html" frameborder="no" scrolling="no" noresize>
                                </frameset>
                                <frameset border=0 rows="20,*">
                                        <frame src="EventLag.html" frameborder="no" scrolling="no" noresize>
                                        <frame src="White.html" frameborder="no" scrolling="no" noresize>
                                </frameset>
                        </frameset>
                </frameset>
                <?php
                                // Gestion des Evènements
                            }
                            else
                            {   // Pas Gestion des Evènements
                                if(!strcmp($Chp,"10"))
                                {   // Gestion des Musics
                ?>
                <frameset border=0 rows="17,*,12">
                        <frame src="MscTop.html" frameborder="no" scrolling="no" noresize>
                        <frameset border=0 cols="4,1,*">
<!--                                <frame src="<?php echo GetLocalSrvAddr(); ?>Black.html" frameborder="no" scrolling="no" noresize> -->
                                <frame name="MscPad" src="MscPad.html" frameborder="no" scrolling="no" noresize>
                                <frameset border=0 rows="*,*">
                                        <frame name="MscMan" src="<?php echo GetDistSrvAddr(); ?>MscMan.php?Clf=<?php echo GetDistKeyId($Clf); ?>" frameborder="no" scrolling="no" noresize>
                                        <frame name="MscOpe" src="<?php echo GetLocalSrvAddr(); ?>MscOpe.php?Clf=<?php echo GetDistKeyId($Clf); ?>" frameborder="no" scrolling="no" noresize>
                                </frameset>
                                <frame name="MscMain" src="<?php echo GetDistSrvAddr(); ?>MscMain.php?Clf=<?php echo GetDistKeyId($Clf); ?>" frameborder="no" scrolling="yes" style="overflow-x: auto">
                        </frameset>
                        <frame src="MscBot.html" frameborder="no" scrolling="no" noresize>
                </frameset>
                <?php
                                    // Gestion des Musics
                                }
                                else
                                {   // Pas Gestion des Musics
                ?>
                <frameset border=0 rows="15,*,10">
                        <frame src="Top.html" frameborder="no" scrolling="no" noresize>
                        <frame src="<?php
                        /*else if(!strcmp($Chp,"10")) echo "Compil.php?Clf=$Clf";
                        else if(!strcmp($Chp,"11")) echo "AddCompil.php?Clf=$Clf";
                        else if(!strcmp($Chp,"12")) echo "AddMusic.php?Clf=$Clf";
                        else if(!strcmp($Chp,"13")) echo "Vote.php?Clf=$Clf";*/
                        ////////////////////////////////
                        if(!Empty($Chp))
                        {   if(!strcmp($Chp,"1")) echo "Home.php?Clf=$Clf";
                            else if(!strcmp($Chp,"2"))
                            {   echo "InfoPerso.php?Clf=$Clf";
                                if(!Empty($Cam)) echo "&Cam=$Cam";
                            }
                            else if(!strcmp($Chp,"3")) echo "AddMember.php?Clf=$Clf";
                            else if(!strcmp($Chp,"4")) echo "FindMember.php?Clf=$Clf";
                            else if(!strcmp($Chp,"7"))
                            {   echo "Album.php?Clf=$Clf";
                                if(!Empty($vwu)) echo "&vwu=$vwu";
                                if(!Empty($trcfg)) echo "&trcfg=$trcfg";
                            }
                            else if(!strcmp($Chp,"8")) echo "AlbMan.php?Clf=$Clf";
                            else if(!strcmp($Chp,"9")) echo "PhtMan.php?Clf=$Clf";
                            else if(!strcmp($Chp,"11"))
                            {   echo "Login.php";
                                if((!Empty($Msg))&&(strcmp($Msg,""))) echo "?Msg=$Msg";
                            }
                            else echo "Accueil.php?Clf=$Clf";
                        }
                        else echo "Accueil.php?Clf=$Clf";
                        ////////////////////////////////
                        ?>" frameborder="no" scrolling="yes" style="overflow-x: auto">
                        <frame src="Bottom.html" frameborder="no" scrolling="no" noresize>
                </frameset>
                <?php
                                    // Pas Gestion des Musics
                                }
                                // Pas Gestion des Evènements
                            }
                            // Pas Affichage des Evènements
                        }
                        // Pas Gestion des Photos
                    }
                    // Pas Forum
                }
                ?>
        </frameset>
        <?php
            // Pas Mail
        }
        ?>
        <frameset border=0 cols="*,400">
                <frameset border=0 rows="35,96">
                        <frame src="White.html" frameborder="no" scrolling="no" noresize>
                        <frame src="Fog.html" frameborder="no" scrolling="no" noresize>
                </frameset>
                <frame src="Media.html" frameborder="no" scrolling="no" noresize>
        </frameset>
</framset>
</head>
</html>
