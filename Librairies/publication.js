/****************************************************************************************
FILE: publication.js
AUHTOR: Pascal Viguie
DATE: 04/07/2016
*****************************************************************************************/

function strcmp(a, b) {
    if (a.toString() < b.toString()) return -1;
    if (a.toString() > b.toString()) return 1;
    return 0;
}
function remove(message,address) {
    if (!confirm(message)) return;
    var http = new XMLHttpRequest();
    try {
        http.open('GET', address, false);
        http.send();
    }
    catch (e) { console.log('XMLHttpRequest exception!'); }
    if (http.status == 200) {
        var reply;
        try { reply = JSON.parse(xhr.responseText); }
        catch (e) {
            reply = new Object()
            reply.Error = WEBSERVICE_ERROR_JSON;
        }
        if ((xhr.responseText) && (typeof reply.Error != 'undefined')) {
            console.log('ERROR: #' + reply.Error);
            alert('Echec durant la suppression: ' + WEBSERVICE_ERROR_MESSAGES[reply.Error] + '\nSi le probleme persiste, contactes le Webmaster!');
        }
        else setTimeout(function() { location.reload(true); }, 100);
    }
    else console.log('ERROR: Web service not ready!');
}

//////
var LC_WEBSERVICE = 'http://www.leclassico.fr/WebServices/';
var LC_ACTUALITES = 'actualites.php?Clf=';
var LC_COMMENTAIRES = 'commentaires.php?Clf=';

var WEBSERVICE_ERROR_JSON = 0;
var WEBSERVICE_ERROR_SERVER_UNAVAILABLE = 1;
var WEBSERVICE_ERROR_INVALID_TOKEN = 6;
var WEBSERVICE_ERROR_INVALID_USER = 7;
var WEBSERVICE_ERROR_INVALID_PUBLICATION_ID = 18;
var WEBSERVICE_ERROR_REQUEST_PUBLICATION_DELETE = 19;
var WEBSERVICE_ERROR_REQUEST_COMMENT_DELETE = 21;
var WEBSERVICE_ERROR_MESSAGES = [
    'JSON.parse exception!', // JSON.parse exception
    'Serveur indisponible','','','','', // Server unavailable
    'Jeton invalide', // Invalid token
    'Utilisateur invalide','','','','','','','','','','', // Invalid user
    'Publication inconnue', // Invalid publication ID
    'Requete de suppression de publication invalide','', // Delete publication request failed
    'Requete de suppression de commentaire invalide' // Delete comment request failed
];

var clef = '';
var file = '';
var camarade = '';

// OnRemovePublication //////////////////////
function OnRemovePublication(actuID) {
    remove('Es-tu sur de vouloir supprimer cette publication\nainsi que tous les commentaires?',
            LC_WEBSERVICE + LC_ACTUALITES + clef + '&Actu=' + actuID + '&Cmd=1');
}

// OnRemoveCommentaire //////////////////////
function OnRemoveCommentaire(type,actuID,commentDate,clf) {
    remove('Es-tu sur de vouloir supprimer ce commentaire?', LC_WEBSERVICE + LC_COMMENTAIRES + clf + '&Type=' + type +
            '&Cmd=1&Actu=' + actuID + '&Date=' + commentDate.replace(' ',SEPARATOR_DATE_TIME));
}

// OnPublicationChange //////////////////////
function OnPublicationChange(link) {
    if (link) document.getElementById("lnkRadio").checked = true;
    else document.getElementById("imgRadio").checked = true;
}

// StartPubListener /////////////////////////
var DELAY_UPDATE = 3000; // In milliseconds

var SEPARATOR_ACTU_ID = 'n';
var SEPARATOR_DATE_TIME = 'n';

var TABLE_PUBLICATIONS = 'Publications';
var TABLE_COMMENTAIRES = 'Commentaires';

var REQ_NONE = -1;
var REQ_INIT_ACTU = 0;
var REQ_INIT_COMMENT = 1;
var REQ_NEW_ACTU = 2;
var REQ_NEW_COMMENT = 3;

var request = 0;

var HTML_COMMENT_PREV_CAMARDE = '<a href="index.php?Chp=2';
var HTML_COMMENT_PREV_PSEUDO = '" target="_top" style="font-size:10pt">';
var HTML_COMMENT_NO_PREV = ':</a><font class="comment">&nbsp;';

var commDate = '';
var actuDate = '';
var actuId = '';

var commRow = {};
function AddCommentaires(data) {
    for (var i = 0; i < data.length; i++) {
        if (document.getElementById(TABLE_COMMENTAIRES + data[i].ObjID)) {
            if (data[i].Status != 0) { // == 2 -> Deleted (not managed yet)
                if (strcmp(data[i].StatusDate, commDate) == 1)
                    commDate = data[i].StatusDate;
                continue;
            }
            var htmlComment = data[i].Text.replace('<','&lt;').replace('>','&gt;');
            var htmlRemove = '';
            if (data[i].RemoveFlag)
                htmlRemove = '&nbsp;<img class="remove" src="Images/remove.png" onclick=\'OnRemoveCommentaire("A",' + data[i].ObjID +
                                ',"' + data[i].Date + '","' + clef + '")\'>';

            // Add comment row
            var row = document.getElementById(TABLE_COMMENTAIRES + data[i].ObjID).insertRow(commRow[data[i].ObjID]);
            var cell = row.insertCell(0);
            cell.innerHTML =
                HTML_COMMENT_PREV_CAMARDE + '&Clf=' + clef + '&Cam=' + data[i].PseudoURL +
                HTML_COMMENT_PREV_PSEUDO + data[i].Pseudo +
                HTML_COMMENT_NO_PREV + htmlComment + htmlRemove +
                '</font>';

            commRow[data[i].ObjID] += 1;
            if (strcmp(data[i].StatusDate, commDate) == 1)
                commDate = data[i].StatusDate;
        }
    }
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
var HTML_ACTU_PREV_DATE = '</td>' +
    '<td bgcolor="#ff8000"></td>' +
    '<td></td>' +
    '<td colspan=2><font ID="Date">Le <font color="green">';
var HTML_ACTU_PREV_MESSAGE = '</td>' +
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
        '<table border=0 width="100%" cellspacing=0 cellpadding=0>' +
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
        '<td bgcolor="#d8e1c6"></td>' +
        '<td bgcolor="#d8e1c6">' +
            '<div style="height:54px;overflow:auto">' +
            '<table border=0 width="100%" cellspacing=0 cellpadding=0 ID="';
var HTML_COMMENT_PREV_FILE = '">' +
            '</table>' +
            '</div>' +
        '</td>' +
        '<td bgcolor="#d8e1c6"></td>' +
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
        '<td><input type="hidden" name="ope" value=69><input type="text" name="txt" class="comment"></td>' +
        '</tr>' +
        '<tr>' +
        '<td><input type="hidden" name="act" value=';
var HTML_COMMENT_PREV_NOTHING = '><input type="submit" class="comment" value="Ok"></td>' +
        '</tr>' +
        '</table>' +
    '</td>' +
    '</tr>' +
    '</table>';

function AddActualites(data) {
    if (document.getElementById(TABLE_PUBLICATIONS)) {
        for (var i = (data.length - 1); i >= 0; i--) {
            if (data[i].Status == 2) { // Deleted (not managed yet)
                if (strcmp(data[i].StatusDate, actuDate) == 1)
                    actuDate = data[i].StatusDate;
                continue;
            }
            var htmlImage = '';
            if (data[i].Fichier != '')
                htmlImage = '<img class="pubImage" src="Photos/' + data[i].Fichier + '" width="100%">';
            var htmlRemove = '</font></font>';
            if (data[i].RemoveFlag)
                htmlRemove += '&nbsp;<img class="remove" src="Images/delete.png" onclick="OnRemovePublication(' + data[i].ActuID + ')">';
            var htmlWall = '';
            if ((data[i].CamaradeURL != '') && (strcmp(data[i].CamaradeURL,camarade) != 0))
                htmlWall = '&nbsp;&gt;&nbsp;<a href="index.php?Chp=2&Clf=' + clef + '&Cam=' + data[i].CamaradeURL +
                            '" target="_top" style="font-size:12pt">' + data[i].Camarade + '</a>';

            // Add actu row
            var row = document.getElementById(TABLE_PUBLICATIONS).insertRow(1);
            row.insertCell(0);
            var cell = row.insertCell(1);
            cell.innerHTML =
                HTML_ACTU_PREV_PROFILE + data[i].Profile +
                HTML_ACTU_PREV_CAMARADE + clef + '&Cam=' + data[i].PseudoURL +
                    '" target="_top" style="font-size:12pt">' + data[i].Pseudo + '</a>' + htmlWall +
                HTML_ACTU_PREV_DATE + data[i].Date + '</font> &agrave; <font color="#ff0000">' + data[i].Time + htmlRemove +
                HTML_ACTU_PREV_MESSAGE + data[i].Text.replace('<','&lt;').replace('>','&gt;').replace('\\n','<br>').replace('\n','<br>') +
                HTML_ACTU_PREV_LINK + data[i].Link + '" target="_blank">' + data[i].Link +
                HTML_ACTU_PREV_IMAGE + htmlImage +
                HTML_ACTU_PREV_COMMENTS;

            // Add comments row
            row = document.getElementById(TABLE_PUBLICATIONS).insertRow(2);
            row.insertCell(0);
            cell = row.insertCell(1);
            cell.innerHTML = 
                HTML_COMMENT_PREV_NAME + TABLE_COMMENTAIRES + data[i].ActuID +
                HTML_COMMENT_PREV_FILE + file +
                    '?Clf=' + clef +
                    '&Cam=' + camarade +
                HTML_COMMENT_PREV_ACTUID + data[i].ActuID +
                HTML_COMMENT_PREV_NOTHING;

            row = document.getElementById(TABLE_PUBLICATIONS).insertRow(3);
            row.style.height = '20px';
            row.insertCell(0);
            row.insertCell(1);

            commRow[data[i].ActuID] = 0;
            if (actuId == '') actuId = data[i].ActuID;
            else actuId += SEPARATOR_ACTU_ID + data[i].ActuID;
            if (strcmp(data[i].StatusDate, actuDate) == 1)
                actuDate = data[i].StatusDate;
        }
    }
}

var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
    if (xhr.readyState == 4) {
        if (xhr.status == 200) {
            var reply;
            try { reply = JSON.parse(xhr.responseText); }
            catch (e) {
                reply = new Object()
                reply.Error = WEBSERVICE_ERROR_JSON;
            }
            if (typeof reply.Error != 'undefined') {
                console.log('ERROR: #' + reply.Error);
                request = REQ_NONE;
                return;
            }
            switch (request) {
                case REQ_INIT_ACTU:
                case REQ_NEW_ACTU: {

                    if(reply.Actualites)
                        AddActualites(reply.Actualites);

                    if (request == REQ_INIT_ACTU) request = REQ_INIT_COMMENT;
                    else request = REQ_NEW_COMMENT;
                    break;
                }
                case REQ_INIT_COMMENT:
                case REQ_NEW_COMMENT: {

                    if(reply.Commentaires)
                        AddCommentaires(reply.Commentaires);

                    request = REQ_NEW_ACTU;
                    break;
                }
            }
        }
        else
            console.log('ERROR: Web service not ready!');
    }
};

var countActu = 0;
var countComm = 0;
var profile = false;

function SendRequests() {
    var reqAddress;
    var delay = DELAY_UPDATE;
    var cam = '';
    if (profile)
        cam = '&Cam=' + camarade;
    switch (request) {
        case REQ_NONE: {
            return; // Stop
        }
        case REQ_INIT_ACTU: {
            delay = 10;
            reqAddress = LC_WEBSERVICE + LC_ACTUALITES + clef + '&Count=' + countActu + cam;
            break;
        }
        case REQ_INIT_COMMENT: {
            reqAddress = LC_WEBSERVICE + LC_COMMENTAIRES + clef + '&Type=A&Actu=' + actuId;
            break;
        }
        case REQ_NEW_ACTU: {
            reqAddress = LC_WEBSERVICE + LC_ACTUALITES + clef + cam + '&Date=' + actuDate.replace(' ',SEPARATOR_DATE_TIME);
            break;
        }
        case REQ_NEW_COMMENT: {
            reqAddress = LC_WEBSERVICE + LC_COMMENTAIRES + clef + '&Type=A&Actu=' + actuId +
                            '&Date=' + commDate.replace(' ',SEPARATOR_DATE_TIME);
            break;
        }
    }
    try {
        xhr.open('GET', reqAddress, false);
        xhr.send();
    }
    catch (e) { console.log('XMLHttpRequest exception!'); }
    setTimeout(SendRequests, delay);
}

function StartPubListener(clf,cam,cntAct,cntCom,src,pro) {
    request = REQ_INIT_ACTU;
    clef = clf;
    camarade = cam;
    countActu = cntAct;
    countComm = cntCom;
    file = src;
    profile = pro;

    //console.log('StartPubListener...');
    setTimeout(SendRequests, 10);
}

