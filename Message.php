<?php
//require("Package.php");
$Chp = $_GET['Chp'];
$Clf = $_GET['Clf'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
<title>Le Classico: Message</title>
<meta name="Description" content="Site officiel du Classico">
<meta name="Keywords" content="classico; music; deep; country; nashville; amis; amies">
<link rel="stylesheet" type="text/css" href="http://www.leclassico.fr/font-family.css">
<style type="text/css">
a {font-size: 14pt; font-family: Impact,Verdana,Lucida; color: blue}
#BigTitle {font-size: 24pt; font-family: Cursive,Verdana,Lucida; color: white}
</style>
</head>
<body style="margin-top: 0; margin-left: <?php
if(!strcmp($Chp,"6")) echo "0\"";
else echo "10px\"";
?> bgcolor="#ffffff">
<?php
if(strcmp($Chp,"6"))
{   // Pas chapitre Mail
?>
<table border=0 width="100%" height="100%" cellspacing=0 cellpadding=0>
<tr height="100%">
<td width="100%" valign="top">
<!-- *************************************************************************************************************************************** MESSAGE -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConHG.jpg";
        else echo "MsgConHG.jpg";
        ?>"></td>
        </tr>
        <tr>
        <td bgcolor="<?php
        if(Empty($Tpe)) echo "#000000";
        else echo "#0080ff";
        ?>">
            <table border=0 height=28 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConBG.jpg";
        else echo "MsgConBG.jpg";
        ?>"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="<?php
    if(Empty($Tpe)) echo "#000000";
    else echo "#0080ff";
    ?>">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "Warning.jpg";
        else echo "Info.jpg";
        ?>"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width=10 bgcolor="<?php
    if(Empty($Tpe)) echo "#000000";
    else echo "#0080ff";
    ?>">
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td width="100%" bgcolor="<?php
    if(Empty($Tpe)) echo "#000000";
    else echo "#0080ff";
    ?>"><font ID="BigTitle"><b><?php
    if(Empty($Tpe)) echo "Erreur";
    else echo "Information";
    ?></b></font></td>
    <td>
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConHD.jpg";
        else echo "MsgConHD.jpg";
        ?>"></td>
        </tr>
        <tr>
        <td bgcolor="<?php
        if(Empty($Tpe)) echo "#000000";
        else echo "#0080ff";
        ?>">
            <table border=0 height=28 cellspacing=0 cellpadding=0>
            <tr>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConBD.jpg";
        else echo "MsgConBD.jpg";
        ?>"></td>
        </tr>
        </table>
    </td>
    <td width=15>
        <table border=0 width=15 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
</table><br>
<font face="Impact,Verdana,Lucida" size=3 color="#000000"><?php echo $Msg; ?></font>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td width="100%">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td><hr></td>
        </tr>
        <tr>
        <td>
                <table border=0 width="100%" cellspacing=0 cellpadding=0>
                <tr>
                <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                <td width=15>
                        <table border=0 width=15 cellspacing=0 cellpadding=0>
                        <tr>
                        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                        </tr>
                        </table>
                </td>
                <td><a href="<?php
                ////////////////////////////////
                if(!Empty($Chp))
                {       if(!strcmp($Chp,"1")) echo "Home.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"2")) echo "InfoPerso.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"3"))
                        {    if((!Empty($Tpe))&&(!strcmp($Tpe,"1"))) echo "index.php?Clf=$Clf\" target=\"_top\">";
                             else echo "AddMember.php?Clf=$Clf\">";
                        }
                        else if(!strcmp($Chp,"4")) echo "FindMember.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"5")) echo "Forum.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"7")) echo "Album.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"8")) echo "AlbMan.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"9")) echo "PhtMan.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"10")) echo "Compils.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"11")) echo "AddCompil.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"12")) echo "AddMusic.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"13")) echo "Vote.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"14")) echo "Events.php?Clf=$Clf\">";
                        else if(!strcmp($Chp,"15")) echo "AddEvent.php?Clf=$Clf\">";
                        else echo "Accueil.php?Clf=$Clf\">";
                }
                else echo "Home.php?Clf=$Clf\">";
                ////////////////////////////////
                ?>Retour</a></td>
                </tr>
                </table>
        </td>
        </tr>
        </table>
</td>
<td width=15>
        <table border=0 width=15 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
</td>
</tr>
</table>
<!-- *********************************************************************************************************************************************** -->
</td>
<td valign="top"><img src="<?php echo GetFolder(); ?>/Images/Projo.jpg"></td>
</tr>
</table>
<?php
    // Pas chapitre Mail
}
else
{   // Chapitre Mail
?>
<!-- *************************************************************************************************************************************** MESSAGE -->
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td valign="top">
    <table border=0 height=28 cellspacing=0 cellpadding=0>
    <tr>
    <td>
        <table border=0 height=28 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConHG.jpg";
        else echo "MsgConHG.jpg";
        ?>"></td>
        </tr>
        <tr>
        <td bgcolor="<?php
        if(Empty($Tpe)) echo "#000000";
        else echo "#0080ff";
        ?>">
                <table border=0 height=28 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
        </td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConBG.jpg";
        else echo "MsgConBG.jpg";
        ?>"></td>
        </tr>
        </table>
    </td>
    <td bgcolor="<?php
    if(Empty($Tpe)) echo "#000000";
    else echo "#0080ff";
    ?>">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "Warning.jpg";
        else echo "Info.jpg";
        ?>"></td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    <td>
        <table border=0 height=28 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConHD.jpg";
        else echo "MsgConHD.jpg";
        ?>"></td>
        </tr>
        <tr>
        <td bgcolor="<?php
        if(Empty($Tpe)) echo "#000000";
        else echo "#0080ff";
        ?>">
                <table border=0 height=28 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
        </td>
        </tr>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/<?php
        if(Empty($Tpe)) echo "ErrConBD.jpg";
        else echo "MsgConBD.jpg";
        ?>"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
<td width=10>
    <table border=0 width=10 cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
    </tr>
    </table>
</td>
<td width="100%">
    <font face="Impact,Verdana,Lucida" size=3 color="#000000"><?php echo $Msg; ?></font>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td width="100%">
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td><hr></td>
        </tr>
        <tr>
        <td>
            <table border=0 width="100%" cellspacing=0 cellpadding=0>
            <tr>
            <td width="100%"><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
            <td width=15>
                <table border=0 width=15 cellspacing=0 cellpadding=0>
                <tr>
                <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
                </tr>
                </table>
            </td>
            <td><a href="index.php?Chp=6&Clf=<?php echo $Clf; ?>" target="_top">Retour</a></td>
            </tr>
            </table>
        </td>
        </tr>
        </table>
    </td>
    <td width=10>
        <table border=0 width=10 cellspacing=0 cellpadding=0>
        <tr>
        <td><img src="<?php echo GetFolder(); ?>/Images/nopic.gif"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
</table>
<!-- *********************************************************************************************************************************************** -->
<?php
    // Chapitre Mail
}
?>
</body>
</html>
