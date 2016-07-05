/****************************************************************************************
FILE: publication.js
AUHTOR: Pascal Viguie
DATE: 04/07/2016
*****************************************************************************************/

// OnPublicationChange //////////////////////
function OnPublicationChange(link) {
    if(link) document.getElementById("lnkRadio").checked = true;
    else document.getElementById("imgRadio").checked = true;
}

// StartPubListener /////////////////////////
var LC_WEBSERVICE = 'http://www.leclassico.fr/WebServices/';
var LC_ACTUALITES = 'actualites.php?Clf=';
var LC_COMMENTAIRES = 'commentaires.php?Clf=';

var TABLE_PUBLICATIONS = 'Publications';
var TABLE_COMMENTAIRES = 'Commentaires';

var REQ_NONE = -1;
var REQ_INITIALIZE = 0;
var REQ_NEW_ACTU = 1;
var REQ_NEW_COMMENT = 2;

var request = 0;

var HTML_ACTU_PREV_PROFILE =
    '<table border=0 width="100%" cellspacing=0 cellpadding=0>' +
    '<tr>' +
    '<td><img src="Images/SubOranHG.jpg"></td>' +
    '<td bgcolor="#ff8000" colspan=2></td>' +
    '<td><img src="Images/SubOranHD.jpg"></td>' +
    '<td></td>' +
    '<td colspan=2 width="100%"><div style="width:100%"></div></td>' +
    '</tr>' +
    '<tr>' +
    '<td bgcolor="#ff8000"></td>' +
    '<td bgcolor="#ff8000"><img class="tinyProfile" src="'; // Images/man.jpg
var HTML_ACTU_PREV_CAMARADE = '"></td>' +
    '<td bgcolor="#ff8000">&nbsp;<a href="index.php?Chp=2&Clf='; //  clef +   &Cam=  + camarade + " target="_top" style="font-size:12pt">   camarade (decoded)
var HTML_ACTU_PREV_DATE = '</a></td>' +
    '<td bgcolor="#ff8000"></td>' +
    '<td></td>' +
    '<td colspan=2><font ID="Date">Le <font color="green">'; // 2016-07-05</font> à <font color="#ff0000">15:12:10
var HTML_ACTU_PREV_MESSAGE = '</font></font></td>' +
    '</tr>' +
    '<tr>' +
    '<td bgcolor="#ff8000" colspan=4></td>' +
    '<td><img src="Images/InOranWhiteBG.jpg"></td>' +
    '<td colspan=2></td>' +
    '</tr>' +
    '<tr>' +
    '<td bgcolor="#ff8000" colspan=5></td>' +
    '<td bgcolor="#ff8000" width="100%"><div style="width:100%"></div></td>' +
    '<td><img src="Images/SubOranHD.jpg"></td>' +
    '</tr>' +
    '<tr>' +
    '<td bgcolor="#ff8000"></td>' +
    '<td bgcolor="#ff8000" colspan=5><font ID="Message">';
var HTML_ACTU_PREV_LINK = '</font></td>' +
    '<td bgcolor="#ff8000"></td>' +
    '</tr>' +
    '<tr height=10>' +
    '<td bgcolor="#ff8000" colspan=7></td>' +
    '</tr>' +
    '<tr>' +
    '<td bgcolor="#ff8000"></td>' +
    '<td bgcolor="#ff8000" colspan=5><a class="link" href="'; // http://studio-artaban.com" target="_blank">http://studio-artaban.com
var HTML_ACTU_PREV_COMMENTS = '</a></td>' +
    '<td bgcolor="#ff8000"></td>' +
    '</tr>' +
    '<tr>' +
    '<td><img src="Images/SubOranBG.jpg"></td>' +
    '<td bgcolor="#ff8000" colspan=5></td>' +
    '<td><img src="Images/SubOranBD.jpg"></td>' +
    '</tr>' +
    '</table>';
var HTML_COMMENT_PREV_ACTUID =
    '<table border=0 width="100%" cellspacing=0 cellpadding=0>' +
    '<tr height=5>' +
    '<td colspan=2></td>' +
    '</tr>' +
    '<tr>' +
    '<td width="100%" valign="top">' +
        '<table border=0 width="100%" cellspacing=0 cellpadding=0 ID="Comments'; // ActuID
var HTML_COMMENT_PREV_FILE = '">' +
        '<tr>' +
        '<td><img src="Images/SubFonHG.jpg"></td>' +
        '<td bgcolor="#bacc9a" colspan=3></td>' +
        '<td><img src="Images/SubFonHD.jpg"></td>' +
        '</tr>' +
        '<tr>' +
        '<td bgcolor="#bacc9a" colspan=2></td>' +
        '<td bgcolor="#bacc9a" width="100%"><div style="width:100%"><font ID="Label">Commentaires:</font></div></td>' +
        '<td bgcolor="#bacc9a" colspan=2></td>' +
        '</tr>' +
        '<tr>' +
        '<td bgcolor="#bacc9a"></td>' +
        '<td><img src="Images/FonCadInHG.jpg"></td>' +
        '<td bgcolor="#d8e1c6"></td>' +
        '<td><img src="Images/FonCadInHD.jpg"></td>' +
        '<td bgcolor="#bacc9a"></td>' +
        '</tr>' +
        '<tr>' +
        '<td bgcolor="#bacc9a"></td>' +
        '<td><img src="Images/FonCadInBG.jpg"></td>' +
        '<td bgcolor="#d8e1c6"></td>' +
        '<td><img src="Images/FonCadInBD.jpg"></td>' +
        '<td bgcolor="#bacc9a"></td>' +
        '</tr>' +
        '<tr>' +
        '<td><img src="Images/SubFonBG.jpg"></td>' +
        '<td bgcolor="#bacc9a" colspan=3></td>' +
        '<td><img src="Images/SubFonBD.jpg"></td>' +
        '</tr>' +
        '</table>' +
    '</td>' +
    '<td width=10><div style="width:10px"></div></td>' +
    '<td valign="bottom">' +
        '<form action="'; // file / InfoPerso.php  ?Clf=  +  clef +   &Cam=   + camarade
var HTML_COMMENT_PREV_NOTHING = '" method="post">' +
        '<table border=0 cellspacing=0 cellpadding=0>' +
        '<tr>' +
        '<td><font ID="Label">Ton commentaire:</font></td>' +
        '</tr>' +
        '<tr>' +
        '<td><input type="hidden" name="ope" value=69><input type="text" class="comment"></td>' +
        '</tr>' +
        '<tr>' +
        '<td><input type="submit" class="comment" value="Ok"></td>' +
        '</tr>' +
        '</table>' +
    '</td>' +
    '</tr>' +
    '</table>';

function AddActualites(data) {
    if (document.getElementById(TABLE_PUBLICATIONS)) {









        // Add actu row
        var row = document.getElementById(TABLE_PUBLICATIONS).insertRow(1);
        row.insertCell(0);
        var cell = row.insertCell(1);
        cell.innerHTML =
            HTML_ACTU_PREV_PROFILE + 'Images/man.png' +
            HTML_ACTU_PREV_CAMARADE +
                'clfclfclfclfclf' +
                '&Cam=' + 'camcamcam' +
                '" target="_top" style="font-size:12pt">' + 'Seik' +
            HTML_ACTU_PREV_DATE + '2016-07-05</font> &agrave; <font color="#ff0000">15:12:10' +
            HTML_ACTU_PREV_MESSAGE + 'Ola blabla blabla blabla blabla blabla blabla blabla blabla blabla blabla blabla blabla' +
            HTML_ACTU_PREV_LINK + 'http://studio-artaban.com" target="_blank">http://studio-artaban.com' +
            HTML_ACTU_PREV_COMMENTS;

        // Add comments row
        row = document.getElementById(TABLE_PUBLICATIONS).insertRow(2);
        row.insertCell(0);
        cell = row.insertCell(1);
        cell.innerHTML = 
            HTML_COMMENT_PREV_ACTUID + '0' +
            HTML_COMMENT_PREV_FILE +
                'InfoPerso.php' +
                '?Clf=' + 'clfclfclfclfclf' +
                '&Cam=' + 'camcamcam' +
            HTML_COMMENT_PREV_NOTHING;









    }
    //else
    //    console.log('No "Publications" HTML table!');
}

var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
    if (xhr.readyState == 4) {
        if (xhr.status == 200) {
            var reply = JSON.parse(xhr.responseText);
            if (typeof reply.error != 'undefined') {
                console.log('ERROR: ' + reply.error);
                request = REQ_NONE;
                return;
            }
            switch (request) {
                case REQ_INITIALIZE: {

                    AddActualites(reply);
                    request = REQ_NEW_ACTU;
                    break;
                }
                case REQ_NEW_ACTU: {
                    





                    request = REQ_NEW_COMMENT;
                    break;
                }
                case REQ_NEW_COMMENT: {







                    request = REQ_NEW_ACTU;
                    break;
                }
            }
        }
        //else
        //    console.log('Web service not ready!');
    }
};

var clef = '';
var camarade = '';
var count = 0;
var file = '';

function SendRequests() {
    var reqAddress;
    switch (request) {
        case REQ_NONE: {
            return; // Stop
        }
        case REQ_INITIALIZE: {
            reqAddress = LC_WEBSERVICE + LC_ACTUALITES + clef;
            break;
        }
        case REQ_NEW_ACTU: {
            return;
        }
        case REQ_NEW_COMMENT: {
            return;
        }
    }
    try {
        //console.log('SendRequests: ' + reqAddress);
        xhr.open('GET', reqAddress, false);
        xhr.send();
    }
    catch (e) { }
    setTimeout(SendRequests, 2000);
}

function StartPubListener(clf,cam,cnt,src) {
    request = REQ_INITIALIZE;
    clef = clf;
    camarade = cam;
    count = cnt;
    file = src;

    //console.log('StartPubListener...');
    setTimeout(SendRequests, 10);
}






/*
<tr>
<td></td>
<td>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
    <td><img src="Images/SubOranHG.jpg"></td>
    <td bgcolor="#ff8000" colspan=2></td>
    <td><img src="Images/SubOranHD.jpg"></td>
    <td></td>
    <td colspan=2 width="100%"><div style="width:100%"></div></td>
    </tr>
    <tr>
    <td bgcolor="#ff8000"></td>
    <td bgcolor="#ff8000"><img class="tinyProfile" src="Images/man.png"></td>
    <td bgcolor="#ff8000">&nbsp;<a href="index.php?Chp=2&Clf=<?php echo "$Clf&Cam=".urlencode(base64_encode("Seik")); ?>" target="_top" style="font-size:12pt">Seik</a></td>
    <td bgcolor="#ff8000"></td>
    <td></td>
    <td colspan=2><font ID="Date">Le <font color="green">2016-07-05</font> à <font color="#ff0000">15:12:10</font></font></td>
    </tr>
    <tr>
    <td bgcolor="#ff8000" colspan=4></td>
    <td><img src="Images/InOranWhiteBG.jpg"></td>
    <td colspan=2></td>
    </tr>
    <tr>
    <td bgcolor="#ff8000" colspan=5></td>
    <td bgcolor="#ff8000" width="100%"><div style="width:100%"></div></td>
    <td><img src="Images/SubOranHD.jpg"></td>
    </tr>
    <tr>
    <td bgcolor="#ff8000"></td>
    <td bgcolor="#ff8000" colspan=5><font ID="Message">Ola blabla blalba blabla blalba blabla blalba blabla blalba blabla blalba blabla blalba blabla blalba</font></td>
    <td bgcolor="#ff8000"></td>
    </tr>
    <tr height=10>
    <td bgcolor="#ff8000" colspan=7></td>
    </tr>
    <tr>
    <td bgcolor="#ff8000"></td>
    <td bgcolor="#ff8000" colspan=5><a class="link" href="http://studio-artaban.com" target="_blank">http://studio-artaban.com</a></td>
    <td bgcolor="#ff8000"></td>
    </tr>
    <tr>
    <td><img src="Images/SubOranBG.jpg"></td>
    <td bgcolor="#ff8000" colspan=5></td>
    <td><img src="Images/SubOranBD.jpg"></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td></td>
<td>
    <table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr height=5>
    <td colspan=2></td>
    </tr>
    <tr>
    <td width="100%" valign="top">
        <table border=0 width="100%" cellspacing=0 cellpadding=0 ID="Comments">
        <tr>
        <td><img src="Images/SubFonHG.jpg"></td>
        <td bgcolor="#bacc9a" colspan=3></td>
        <td><img src="Images/SubFonHD.jpg"></td>
        </tr>
        <tr>
        <td bgcolor="#bacc9a" colspan=2></td>
        <td bgcolor="#bacc9a" width="100%"><div style="width:100%"><font ID="Label">Commentaires:</font></div></td>
        <td bgcolor="#bacc9a" colspan=2></td>
        </tr>
        <tr>
        <td bgcolor="#bacc9a"></td>
        <td><img src="Images/FonCadInHG.jpg"></td>
        <td bgcolor="#d8e1c6"></td>
        <td><img src="Images/FonCadInHD.jpg"></td>
        <td bgcolor="#bacc9a"></td>
        </tr>




        <!-- PUBLICATIONS ######################################################################################################### -->
        <tr>
        <td bgcolor="#bacc9a"></td>
        <td bgcolor="#d8e1c6"></td>
        <td bgcolor="#d8e1c6">commentaires...</td>
        <td bgcolor="#d8e1c6"></td>
        <td bgcolor="#bacc9a"></td>
        </tr>
        <!-- ###################################################################################################################### -->




        <tr>
        <td bgcolor="#bacc9a"></td>
        <td><img src="Images/FonCadInBG.jpg"></td>
        <td bgcolor="#d8e1c6"></td>
        <td><img src="Images/FonCadInBD.jpg"></td>
        <td bgcolor="#bacc9a"></td>
        </tr>
        <tr>
        <td><img src="Images/SubFonBG.jpg"></td>
        <td bgcolor="#bacc9a" colspan=3></td>
        <td><img src="Images/SubFonBD.jpg"></td>
        </tr>
        </table>
    </td>
    <td width=10><div style="width:10px"></div></td>
    <td valign="bottom">
        <form action="InfoPerso.php?Clf=" method="post">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td><font ID="Label">Ton commentaire:</font></td>
        </tr>
        <tr>
        <td><input type="hidden" name="ope" value=69><input type="text" class="comment"></td>
        </tr>
        <tr>
        <td><input type="submit" class="comment" value="Ok"></td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
*/
