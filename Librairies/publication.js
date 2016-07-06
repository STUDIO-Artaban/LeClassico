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

var HTML_COMMENT_PREV_CAMARDE = '<a href="index.php?Chp=2&Cam=';
var HTML_COMMENT_PREV_PSEUDO = '" target="_top" style="font-size:10pt">';
var HTML_COMMENT_NO_PREV = ':</a><font class="comment">&nbsp;';

function AddCommentaires(data) {











    // Add comment row
    var row = document.getElementById(TABLE_COMMENTAIRES + '0').insertRow(3);
    var cell = row.insertCell(0);
    cell.style.backgroundColor = "#bacc9a";
    cell = row.insertCell(1);
    cell.style.backgroundColor = "#d8e1c6";
    cell = row.insertCell(2);
    cell.style.backgroundColor = "#d8e1c6";
    cell.innerHTML =
        HTML_COMMENT_PREV_CAMARDE +
            'camcamcam' +
            '&Clf=' + 'clfclfclf' +
        HTML_COMMENT_PREV_PSEUDO + 'Pascal' +
        HTML_COMMENT_NO_PREV +
            'ola bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo #1' +
        '</font>';
    cell = row.insertCell(3);
    cell.style.backgroundColor = "#d8e1c6";
    cell = row.insertCell(4);
    cell.style.backgroundColor = "#bacc9a";


    // Add comment row
    var row = document.getElementById(TABLE_COMMENTAIRES + '0').insertRow(4);
    var cell = row.insertCell(0);
    cell.style.backgroundColor = "#bacc9a";
    cell = row.insertCell(1);
    cell.style.backgroundColor = "#d8e1c6";
    cell = row.insertCell(2);
    cell.style.backgroundColor = "#d8e1c6";
    cell.innerHTML =
        HTML_COMMENT_PREV_CAMARDE +
            'camcamcam' +
            '&Clf=' + 'clfclfclf' +
        HTML_COMMENT_PREV_PSEUDO + 'Seik' +
        HTML_COMMENT_NO_PREV +
            'ola bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo #2' +
        '</font>';
    cell = row.insertCell(3);
    cell.style.backgroundColor = "#d8e1c6";
    cell = row.insertCell(4);
    cell.style.backgroundColor = "#bacc9a";










    // Add comment row
    var row = document.getElementById(TABLE_COMMENTAIRES + '1').insertRow(3);
    var cell = row.insertCell(0);
    cell.style.backgroundColor = "#bacc9a";
    cell = row.insertCell(1);
    cell.style.backgroundColor = "#d8e1c6";
    cell = row.insertCell(2);
    cell.style.backgroundColor = "#d8e1c6";
    cell.innerHTML =
        HTML_COMMENT_PREV_CAMARDE +
            'camcamcam' +
            '&Clf=' + 'clfclfclf' +
        HTML_COMMENT_PREV_PSEUDO + 'JM' +
        HTML_COMMENT_NO_PREV +
            'ola bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo bloblo #3' +
        '</font>';
    cell = row.insertCell(3);
    cell.style.backgroundColor = "#d8e1c6";
    cell = row.insertCell(4);
    cell.style.backgroundColor = "#bacc9a";













}

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
    '<td bgcolor="#ff8000"><img class="tinyProfile" src="';
var HTML_ACTU_PREV_CAMARADE = '"></td>' +
    '<td bgcolor="#ff8000">&nbsp;<a href="index.php?Chp=2&Clf=';
var HTML_ACTU_PREV_DATE = '</a></td>' +
    '<td bgcolor="#ff8000"></td>' +
    '<td></td>' +
    '<td colspan=2><font ID="Date">Le <font color="green">';
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
    '<td bgcolor="#ff8000" colspan=5><a class="link" href="';
var HTML_ACTU_PREV_IMAGE = '</a></td>' +
    '<td bgcolor="#ff8000"></td>' +
    '</tr>' +
    '<tr>' +
    '<td bgcolor="#ff8000"></td>' +
    '<td bgcolor="#ff8000" colspan=5>';
var HTML_ACTU_PREV_COMMENTS = '</td>' +
    '<td bgcolor="#ff8000"></td>' +
    '</tr>' +
    '<tr>' +
    '<td><img src="Images/SubOranBG.jpg"></td>' +
    '<td bgcolor="#ff8000" colspan=5></td>' +
    '<td><img src="Images/SubOranBD.jpg"></td>' +
    '</tr>' +
    '</table>';
var HTML_COMMENT_PREV_NAME =
    '<table border=0 width="100%" cellspacing=0 cellpadding=0>' +
    '<tr height=5>' +
    '<td colspan=2></td>' +
    '</tr>' +
    '<tr>' +
    '<td width="100%" valign="top">' +
        '<table border=0 width="100%" cellspacing=0 cellpadding=0 ID="';
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
        '<form action="';
var HTML_COMMENT_PREV_ACTUID = '" method="post">' +
        '<table border=0 cellspacing=0 cellpadding=0>' +
        '<tr>' +
        '<td><font ID="Label">Ton commentaire:</font></td>' +
        '</tr>' +
        '<tr>' +
        '<td><input type="hidden" name="ope" value=69><input type="text" class="comment"></td>' +
        '</tr>' +
        '<tr>' +
        '<td><input type="hidden" name="act" value=';
var HTML_COMMENT_PREV_NOTHING = '><input type="submit" class="comment" value="Ok"></td>' +
        '</tr>' +
        '</table>' +
    '</td>' +
    '</tr>' +
    '</table>';

var camarade = '';
var file = '';

function AddActualites(data) {
    if (document.getElementById(TABLE_PUBLICATIONS)) {
        for (var i = (data.length - 1); i >= 0; i--) {
            var htmlImage = '';
            if(data[i].image != '')
                htmlImage = '<img class="pubImage" src="Photos/' + data[i].image + '" width="100%">';
            // Add actu row
            var row = document.getElementById(TABLE_PUBLICATIONS).insertRow(1);
            row.insertCell(0);
            var cell = row.insertCell(1);
            cell.innerHTML =
                HTML_ACTU_PREV_PROFILE + data[i].profile +
                HTML_ACTU_PREV_CAMARADE + data[i].token +
                    '&Cam=' + data[i].camarade +
                    '" target="_top" style="font-size:12pt">' + data[i].pseudo +
                HTML_ACTU_PREV_DATE + data[i].date + '</font> &agrave; <font color="#ff0000">' + data[i].time +
                HTML_ACTU_PREV_MESSAGE + data[i].text +
                HTML_ACTU_PREV_LINK + data[i].link + '" target="_blank">' + data[i].link +
                HTML_ACTU_PREV_IMAGE + htmlImage +
                HTML_ACTU_PREV_COMMENTS;

            // Add comments row
            row = document.getElementById(TABLE_PUBLICATIONS).insertRow(2);
            row.insertCell(0);
            cell = row.insertCell(1);
            cell.innerHTML = 
                HTML_COMMENT_PREV_NAME + TABLE_COMMENTAIRES + data[i].id +
                HTML_COMMENT_PREV_FILE + file +
                    '?Clf=' + data[i].token +
                    '&Cam=' + camarade +
                HTML_COMMENT_PREV_ACTUID + data[i].id +
                HTML_COMMENT_PREV_NOTHING;
            row = document.getElementById(TABLE_PUBLICATIONS).insertRow(3);
            row.style.height = '20px';
            row.insertCell(0);
            row.insertCell(1);
        }
    }
    //else
    //    console.log('No "Publications" HTML table!');
}

var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
    if (xhr.readyState == 4) {
        if (xhr.status == 200) {
            var reply;
            try { reply = JSON.parse(xhr.responseText); }
            catch (e) {
                reply = new Object()
                reply.error = 'JSON.parse exception!';
            }
            if (typeof reply.error != 'undefined') {
                console.log('ERROR: ' + reply.error);
                request = REQ_NONE;
                return;
            }
            switch (request) {
                case REQ_INITIALIZE: {

                    if(reply.publications)
                        AddActualites(reply.publications);
                    request = REQ_NEW_ACTU;
                    break;
                }
                case REQ_NEW_ACTU: {

                    if(reply.publications)
                        AddActualites(reply.publications);
                    request = REQ_NEW_COMMENT;
                    break;
                }
                case REQ_NEW_COMMENT: {



                    //AddCommentaires(data);



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
var count = 0;

function SendRequests() {
    var reqAddress;
    switch (request) {
        case REQ_NONE: {
            return; // Stop
        }
        case REQ_INITIALIZE: {
            reqAddress = LC_WEBSERVICE + LC_ACTUALITES + clef + '&Count=' + count;
            if (camarade != '')
                reqAddress += '&Cam=' + camarade;
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
    if(cam) camarade = cam;
    count = cnt;
    file = src;

    //console.log('StartPubListener...');
    setTimeout(SendRequests, 10);
}

