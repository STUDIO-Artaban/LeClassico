/******************************************************************************************
FILE: events.js
AUHTOR: Moi
DATE: 02/03/2005
*****************************************************************************************/

// Constantes ////////////////////////////////////////////////////////////////////////////
var sMonth=new Array("JANVIER","FEVRIER", "MARS", "AVRIL", "MAI", "JUIN", "JUILLET", "AOUT", "SEPTEMBRE", "OCTOBRE", "NOVEMBRE", "DECEMBRE");
var sNoPic="<img src=\"/Images/nopic.gif\">";
var sToDay1="<table border=0 width=\"100%\" cellspacing=0 cellpadding=0 height=19><tr><td align=\"center\"><font ID=\"Title\">";
var sSelDay1="<table border=1 bordercolor=\"#ff0000\" width=\"100%\" cellspacing=0 cellpadding=0 style=\"border-style: solid\" height=19><tr><td align=\"center\" style=\"border-style: solid\"><font ID=\"Title\">";
var sToDay2="</font></td></tr></table>";

// Variables /////////////////////////////////////////////////////////////////////////////
var iSelYear=0;
var iSelMnth=0;
var iToYear=0;
var iToMnth=0;
var iToDay=0;
var iViewYear=0;
var iViewMnth=0;
var iViewDay=0;

// GetSelectedDay ////////////////////////////////////////////////////////////////////////
function GetSelectedDay(oTdObj)
{    var iDay=0;
     if(oTdObj.innerHTML.search(/>1<.+/)!=(-1)) iDay=1;
     else if(oTdObj.innerHTML.search(/>2<.+/)!=(-1)) iDay=2;
     else if(oTdObj.innerHTML.search(/>3<.+/)!=(-1)) iDay=3;
     else if(oTdObj.innerHTML.search(/>4<.+/)!=(-1)) iDay=4;
     else if(oTdObj.innerHTML.search(/>5<.+/)!=(-1)) iDay=5;
     else if(oTdObj.innerHTML.search(/>6<.+/)!=(-1)) iDay=6;
     else if(oTdObj.innerHTML.search(/>7<.+/)!=(-1)) iDay=7;
     else if(oTdObj.innerHTML.search(/>8<.+/)!=(-1)) iDay=8;
     else if(oTdObj.innerHTML.search(/>9<.+/)!=(-1)) iDay=9;
     else if(oTdObj.innerHTML.search(/>10<.+/)!=(-1)) iDay=10;
     else if(oTdObj.innerHTML.search(/>11<.+/)!=(-1)) iDay=11;
     else if(oTdObj.innerHTML.search(/>12<.+/)!=(-1)) iDay=12;
     else if(oTdObj.innerHTML.search(/>13<.+/)!=(-1)) iDay=13;
     else if(oTdObj.innerHTML.search(/>14<.+/)!=(-1)) iDay=14;
     else if(oTdObj.innerHTML.search(/>15<.+/)!=(-1)) iDay=15;
     else if(oTdObj.innerHTML.search(/>16<.+/)!=(-1)) iDay=16;
     else if(oTdObj.innerHTML.search(/>17<.+/)!=(-1)) iDay=17;
     else if(oTdObj.innerHTML.search(/>18<.+/)!=(-1)) iDay=18;
     else if(oTdObj.innerHTML.search(/>19<.+/)!=(-1)) iDay=19;
     else if(oTdObj.innerHTML.search(/>20<.+/)!=(-1)) iDay=20;
     else if(oTdObj.innerHTML.search(/>21<.+/)!=(-1)) iDay=21;
     else if(oTdObj.innerHTML.search(/>22<.+/)!=(-1)) iDay=22;
     else if(oTdObj.innerHTML.search(/>23<.+/)!=(-1)) iDay=23;
     else if(oTdObj.innerHTML.search(/>24<.+/)!=(-1)) iDay=24;
     else if(oTdObj.innerHTML.search(/>25<.+/)!=(-1)) iDay=25;
     else if(oTdObj.innerHTML.search(/>26<.+/)!=(-1)) iDay=26;
     else if(oTdObj.innerHTML.search(/>27<.+/)!=(-1)) iDay=27;
     else if(oTdObj.innerHTML.search(/>28<.+/)!=(-1)) iDay=28;
     else if(oTdObj.innerHTML.search(/>29<.+/)!=(-1)) iDay=29;
     else if(oTdObj.innerHTML.search(/>30<.+/)!=(-1)) iDay=30;
     else if(oTdObj.innerHTML.search(/>31<.+/)!=(-1)) iDay=31;
     return iDay;
}

// SetToDay //////////////////////////////////////////////////////////////////////////////
function SetToDay(iYear,iMonth,iDay)
{   iSelYear=iYear;
    iSelMnth=iMonth;
    iToYear=iYear;
    iToMnth=iMonth;
    iToDay=iDay;
    iViewYear=iYear;
    iViewMnth=iMonth;
    iViewDay=iDay;
}

// SelectDate //////////////////////////////////////////////////////////////////////////////
function SelectDate(iYear,iMonth,iDay)
{   iSelYear=iYear;
    iSelMnth=iMonth;
    iViewYear=iYear;
    iViewMnth=iMonth;
    iViewDay=iDay;
}

// InitCalendrier ////////////////////////////////////////////////////////////////////////
function InitCalendrier()
{   var itmp=0;
    var i=0;
    var SelDate=new Date();
    SelDate.setDate(1);
    SelDate.setMonth(iSelMnth-1);
    SelDate.setFullYear(iSelYear);
    // Affiche le mois et l'année
    document.getElementById("MonthSel").innerHTML=sMonth[iSelMnth-1]+" - "+iSelYear;
    // Affecte les dates et les couleurs
    itmp=SelDate.getDay();
    if(itmp!=0) itmp--;
    else itmp=6;
    // Semaine 1
    while(i!=itmp)
    {   document.getElementById("Week1").cells[i].innerHTML=sNoPic;
        if(i>4) document.getElementById("Week1").cells[i].style.backgroundColor="#bacc9a";
        else document.getElementById("Week1").cells[i].style.backgroundColor="#d8e1c6";
        i++;
    }
    itmp=1;
    while(i!=7)
    {   if((SelDate.getFullYear()!=iToYear)||(SelDate.getMonth()!=(iToMnth-1))||(itmp!=iToDay))
        {   document.getElementById("Week1").cells[i].innerHTML=sToDay1+itmp+sToDay2;
        }
        else
        {   // Sélectionne le jour d'aujourd'hui
            document.getElementById("Week1").cells[i].innerHTML=sSelDay1+itmp+sToDay2;
        }
        if((iViewYear!=iSelYear)||(iViewMnth!=iSelMnth)||(iViewDay!=itmp))
        {   if(i>4) document.getElementById("Week1").cells[i].style.backgroundColor="#bbbbbb";
            else document.getElementById("Week1").cells[i].style.backgroundColor="#dddddd";
        }
        else document.getElementById("Week1").cells[i].style.backgroundColor="#ff8000";
        i++;
        itmp++;
    }
    // Semaine 2
    i=0;
    while(i!=7)
    {   if((SelDate.getFullYear()!=iToYear)||(SelDate.getMonth()!=(iToMnth-1))||(itmp!=iToDay))
        {   document.getElementById("Week2").cells[i].innerHTML=sToDay1+itmp+sToDay2;
        }
        else
        {   // Sélectionne le jour d'aujourd'hui
            document.getElementById("Week2").cells[i].innerHTML=sSelDay1+itmp+sToDay2;
        }
        if((iViewYear!=iSelYear)||(iViewMnth!=iSelMnth)||(iViewDay!=itmp))
        {   if(i>4) document.getElementById("Week2").cells[i].style.backgroundColor="#bbbbbb";
            else document.getElementById("Week2").cells[i].style.backgroundColor="#dddddd";
        }
        else document.getElementById("Week2").cells[i].style.backgroundColor="#ff8000";
        i++;
        itmp++;
    }
    // Semaine 3
    i=0;
    while(i!=7)
    {   if((SelDate.getFullYear()!=iToYear)||(SelDate.getMonth()!=(iToMnth-1))||(itmp!=iToDay))
        {   document.getElementById("Week3").cells[i].innerHTML=sToDay1+itmp+sToDay2;
        }
        else
        {   // Sélectionne le jour d'aujourd'hui
            document.getElementById("Week3").cells[i].innerHTML=sSelDay1+itmp+sToDay2;
        }
        if((iViewYear!=iSelYear)||(iViewMnth!=iSelMnth)||(iViewDay!=itmp))
        {   if(i>4) document.getElementById("Week3").cells[i].style.backgroundColor="#bbbbbb";
            else document.getElementById("Week3").cells[i].style.backgroundColor="#dddddd";
        }
        else document.getElementById("Week3").cells[i].style.backgroundColor="#ff8000";
        i++;
        itmp++;
    }
    // Semaine 4
    i=0;
    while(i!=7)
    {   if((SelDate.getFullYear()!=iToYear)||(SelDate.getMonth()!=(iToMnth-1))||(itmp!=iToDay))
        {   document.getElementById("Week4").cells[i].innerHTML=sToDay1+itmp+sToDay2;
        }
        else
        {   // Sélectionne le jour d'aujourd'hui
            document.getElementById("Week4").cells[i].innerHTML=sSelDay1+itmp+sToDay2;
        }
        if((iViewYear!=iSelYear)||(iViewMnth!=iSelMnth)||(iViewDay!=itmp))
        {   if(i>4) document.getElementById("Week4").cells[i].style.backgroundColor="#bbbbbb";
            else document.getElementById("Week4").cells[i].style.backgroundColor="#dddddd";
        }
        else document.getElementById("Week4").cells[i].style.backgroundColor="#ff8000";
        i++;
        itmp++;
    }
    // Semaine 5
    i=0;
    SelDate.setDate(itmp);
    while((SelDate.getMonth()==(iSelMnth-1))&&(i!=7))
    {   if((SelDate.getFullYear()!=iToYear)||(SelDate.getMonth()!=(iToMnth-1))||(itmp!=iToDay))
        {   document.getElementById("Week5").cells[i].innerHTML=sToDay1+itmp+sToDay2;
        }
        else
        {   // Sélectionne le jour d'aujourd'hui
            document.getElementById("Week5").cells[i].innerHTML=sSelDay1+itmp+sToDay2;
        }
        if((iViewYear!=iSelYear)||(iViewMnth!=iSelMnth)||(iViewDay!=itmp))
        {   if(i>4) document.getElementById("Week5").cells[i].style.backgroundColor="#bbbbbb";
            else document.getElementById("Week5").cells[i].style.backgroundColor="#dddddd";
        }
        else document.getElementById("Week5").cells[i].style.backgroundColor="#ff8000";
        itmp++;
        SelDate.setDate(itmp);
        i++;
    }
    while(i!=7)
    {   document.getElementById("Week5").cells[i].innerHTML=sNoPic;
        if(i>4) document.getElementById("Week5").cells[i].style.backgroundColor="#bacc9a";
        else document.getElementById("Week5").cells[i].style.backgroundColor="#d8e1c6";
        i++;
    }
    // Semaine 6
    i=0;
    SelDate.setDate(itmp);
    while(SelDate.getMonth()==(iSelMnth-1))
    {   if((SelDate.getFullYear()!=iToYear)||(SelDate.getMonth()!=(iToMnth-1))||(itmp!=iToDay))
        {   document.getElementById("Week6").cells[i].innerHTML=sToDay1+itmp+sToDay2;
        }
        else
        {   // Sélectionne le jour d'aujourd'hui
            document.getElementById("Week6").cells[i].innerHTML=sSelDay1+itmp+sToDay2;
        }
        if((iViewYear!=iSelYear)||(iViewMnth!=iSelMnth)||(iViewDay!=itmp))
        {   if(i>4) document.getElementById("Week6").cells[i].style.backgroundColor="#bbbbbb";
            else document.getElementById("Week6").cells[i].style.backgroundColor="#dddddd";
        }
        else document.getElementById("Week6").cells[i].style.backgroundColor="#ff8000";
        itmp++;
        SelDate.setDate(itmp);
        i++;
    }
    while(i!=7)
    {   document.getElementById("Week6").cells[i].innerHTML=sNoPic;
        if(i>4) document.getElementById("Week6").cells[i].style.backgroundColor="#bacc9a";
        else document.getElementById("Week6").cells[i].style.backgroundColor="#d8e1c6";
        i++;
    }
}

// OnSetLastMonth ////////////////////////////////////////////////////////////////////////
function OnSetLastMonth()
{   var SelDate=new Date();
    SelDate.setDate(1);
    SelDate.setMonth(iSelMnth-1);
    SelDate.setFullYear(iSelYear);
    // Mois précédent
    SelDate.setMonth(SelDate.getMonth()-1);
    iSelYear=SelDate.getFullYear();
    iSelMnth=SelDate.getMonth()+1;
    InitCalendrier();
}

// OnSetNextMonth ////////////////////////////////////////////////////////////////////////
function OnSetNextMonth()
{   var SelDate=new Date();
    SelDate.setDate(1);
    SelDate.setMonth(iSelMnth-1);
    SelDate.setFullYear(iSelYear);
    // Mois suivant
    SelDate.setMonth(SelDate.getMonth()+1);
    iSelYear=SelDate.getFullYear();
    iSelMnth=SelDate.getMonth()+1;
    InitCalendrier();
}

// OnSelectDay ///////////////////////////////////////////////////////////////////////////
function OnSelectDay(oTdObj,sKey)
{   var i=0;
    if((oTdObj.style.backgroundColor.toLowerCase()=="#ffffff")||(oTdObj.style.backgroundColor.toLowerCase()=="rgb(255, 255, 255)"))
    {   // Déselectionne les autres jours
        for(i=0;i<7;i++)
        {   if((document.getElementById("Week1").cells[i].style.backgroundColor.toLowerCase()=="#ff8000")||
               (document.getElementById("Week1").cells[i].style.backgroundColor.toLowerCase()=="rgb(255, 128, 0)"))
            {   if(i>4) document.getElementById("Week1").cells[i].style.backgroundColor="#bbbbbb";
                else document.getElementById("Week1").cells[i].style.backgroundColor="#dddddd";
            }
        }
        for(i=0;i<7;i++)
        {   if((document.getElementById("Week2").cells[i].style.backgroundColor.toLowerCase()=="#ff8000")||
               (document.getElementById("Week2").cells[i].style.backgroundColor.toLowerCase()=="rgb(255, 128, 0)"))
            {   if(i>4) document.getElementById("Week2").cells[i].style.backgroundColor="#bbbbbb";
                else document.getElementById("Week2").cells[i].style.backgroundColor="#dddddd";
            }
        }
        for(i=0;i<7;i++)
        {   if((document.getElementById("Week3").cells[i].style.backgroundColor.toLowerCase()=="#ff8000")||
               (document.getElementById("Week3").cells[i].style.backgroundColor.toLowerCase()=="rgb(255, 128, 0)"))
            {   if(i>4) document.getElementById("Week3").cells[i].style.backgroundColor="#bbbbbb";
                else document.getElementById("Week3").cells[i].style.backgroundColor="#dddddd";
            }
        }
        for(i=0;i<7;i++)
        {   if((document.getElementById("Week4").cells[i].style.backgroundColor.toLowerCase()=="#ff8000")||
               (document.getElementById("Week4").cells[i].style.backgroundColor.toLowerCase()=="rgb(255, 128, 0)"))
            {   if(i>4) document.getElementById("Week4").cells[i].style.backgroundColor="#bbbbbb";
                else document.getElementById("Week4").cells[i].style.backgroundColor="#dddddd";
            }
        }
        for(i=0;i<7;i++)
        {   if((document.getElementById("Week5").cells[i].style.backgroundColor.toLowerCase()=="#ff8000")||
               (document.getElementById("Week5").cells[i].style.backgroundColor.toLowerCase()=="rgb(255, 128, 0)"))
            {   if(i>4) document.getElementById("Week5").cells[i].style.backgroundColor="#bbbbbb";
                else document.getElementById("Week5").cells[i].style.backgroundColor="#dddddd";
            }
        }
        for(i=0;i<7;i++)
        {   if((document.getElementById("Week6").cells[i].style.backgroundColor.toLowerCase()=="#ff8000")||
               (document.getElementById("Week6").cells[i].style.backgroundColor.toLowerCase()=="rgb(255, 128, 0)"))
            {   if(i>4) document.getElementById("Week6").cells[i].style.backgroundColor="#bbbbbb";
                else document.getElementById("Week6").cells[i].style.backgroundColor="#dddddd";
            }
        }
        // Sélectionne la date
        oTdObj.style.backgroundColor="#ff8000";
        // Affecte la date sélectionné
        iViewDay=GetSelectedDay(oTdObj);
        iViewMnth=iSelMnth;
        iViewYear=iSelYear;
        top.EvntTitle.location.href="EventTit.php?yr="+iViewYear+"&mn="+iViewMnth+"&dy="+iViewDay;
        top.EvntSelect.location.href="EventSel.php?yr="+iViewYear+"&mn="+iViewMnth+"&dy="+iViewDay+"&Clf="+sKey;
    }
}

// OnMouseIn /////////////////////////////////////////////////////////////////////////////
function OnMouseIn(oTdObj)
{   if((oTdObj.style.backgroundColor.toLowerCase()!="#d8e1c6")&&(oTdObj.style.backgroundColor.toLowerCase()!="rgb(216, 225, 198)")&&
       (oTdObj.style.backgroundColor.toLowerCase()!="#bacc9a")&&(oTdObj.style.backgroundColor.toLowerCase()!="rgb(186, 204, 154)")&&
       (oTdObj.style.backgroundColor.toLowerCase()!="#ff8000")&&(oTdObj.style.backgroundColor.toLowerCase()!="rgb(255, 128, 0)"))
    {   oTdObj.style.backgroundColor="#ffffff";
    }
}

// OnMouseOut ////////////////////////////////////////////////////////////////////////////
function OnMouseOut(oTdObj, bWeekEnd)
{   if((oTdObj.style.backgroundColor.toLowerCase()!="#d8e1c6")&&(oTdObj.style.backgroundColor.toLowerCase()!="rgb(216, 225, 198)")&&
       (oTdObj.style.backgroundColor.toLowerCase()!="#bacc9a")&&(oTdObj.style.backgroundColor.toLowerCase()!="rgb(186, 204, 154)")&&
       (oTdObj.style.backgroundColor.toLowerCase()!="#ff8000")&&(oTdObj.style.backgroundColor.toLowerCase()!="rgb(255, 128, 0)"))
    {   if(bWeekEnd) oTdObj.style.backgroundColor="#bbbbbb";
        else oTdObj.style.backgroundColor="#dddddd";
    }
}
