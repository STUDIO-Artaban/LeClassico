/******************************************************************************************
FILE: publication.js
AUHTOR: Pascal Viguie
DATE: 04/07/2016
*****************************************************************************************/

// OnPublicationChange //////////////////////
function OnPublicationChange(link) {
    if(link) document.getElementById("lnkRadio").checked = true;
    else document.getElementById("imgRadio").checked = true;
}

/*
var table = document.getElementById("myTable");

// Create an empty <tr> element and add it to the 1st position of the table:
var row = table.insertRow(0);

// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);

// Add some text to the new cells:
cell1.innerHTML = "NEW CELL1";
cell2.innerHTML = "NEW CELL2";
*/
