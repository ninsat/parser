/* set global variable i */

var i=0; 

function increment(){
i +=1;                         /* function for automatic increament of feild's "Name" attribute*/                 
}

/* 
---------------------------------------------

function to remove fom elements dynamically
---------------------------------------------

*/

function removeElement(parentDiv, childDiv){
 

     if (childDiv == parentDiv) {
          alert("The parent div cannot be removed.");
     }
     else if (document.getElementById(childDiv)) {     
          var child = document.getElementById(childDiv);
          var parent = document.getElementById(parentDiv);
          parent.removeChild(child);
     }
     else {
          alert("Child div has already been removed or does not exist.");
          return false;
     }
}


 /* 
 ----------------------------------------------------------------------------
 
 functions that will be called upon, when user click on the Name text field
 
 ---------------------------------------------------------------------------
 */
function remoteIdFunction() {

     var r = document.createElement('div');
     var y = document.createElement("input");
          r.setAttribute("class", "input-group newFields");

          y.setAttribute("required", true);
          y.setAttribute("type", "text");
          y.setAttribute("class", "form-control");
          y.setAttribute("placeholder", "jQuery selector");

     var g = document.createElement("button");
          g.setAttribute("class", "btn btn-danger");
          g.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
     increment();
          y.setAttribute("Name", "remoteId_"+i);
          r.appendChild(y);
          g.setAttribute("onclick", "removeElement('creatorForm','id_"+ i +"')");
          r.appendChild(g);
          r.setAttribute("id", "id_"+i);
     $(y).before('<label class="required" for="remoteId_"'+ i +'>Remote ID:</label>');
     $(g).wrap('<span class="input-group-btn" />');
     document.getElementById("creatorForm").appendChild(r);
}


/*
-----------------------------------------------------------------------------

functions  that will be called upon, when user click on the Email text field

------------------------------------------------------------------------------
*/
function emailFunction()
{
var r=document.createElement('span');
var y = document.createElement("INPUT");
y.setAttribute("type", "text");
y.setAttribute("placeholder", "Email");
var g = document.createElement("IMG");
g.setAttribute("src", "delete.png");
increment();
y.setAttribute("Name","textelement_"+i);
r.appendChild(y);
g.setAttribute("onclick", "removeElement('myForm','id_"+ i +"')");
r.appendChild(g);
r.setAttribute("id", "id_"+i);
document.getElementById("myForm").appendChild(r);
}

/*
-----------------------------------------------------------------------------

functions  that will be called upon, when user click on the Contact text field

------------------------------------------------------------------------------
*/

function contactFunction()
{
var r=document.createElement('span');
var y = document.createElement("INPUT");
y.setAttribute("type", "text");
y.setAttribute("placeholder", "Contact");
var g = document.createElement("IMG");
g.setAttribute("src", "delete.png");
increment();
y.setAttribute("Name","textelement_"+i);
r.appendChild(y);
g.setAttribute("onclick", "removeElement('myForm','id_"+ i +"')");
r.appendChild(g);
r.setAttribute("id", "id_"+i);
document.getElementById("myForm").appendChild(r);
}

/*
-----------------------------------------------------------------------------

functions  that will be called upon, when user click on the Messege textarea field

------------------------------------------------------------------------------
*/

function textareaFunction()
{
var r=document.createElement('span');

var y = document.createElement("TEXTAREA");
var g = document.createElement("IMG");
y.setAttribute("cols", "17");
y.setAttribute("placeholder", "message..");
g.setAttribute("src", "delete.png");
increment();
y.setAttribute("Name","textelement_"+i);
r.appendChild(y);
g.setAttribute("onclick", "removeElement('myForm','id_"+ i +"')");
r.appendChild(g);
r.setAttribute("id", "id_"+i);
document.getElementById("myForm").appendChild(r);

}
