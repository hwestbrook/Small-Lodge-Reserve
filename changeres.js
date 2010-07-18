

/*
 * changeres.js
 *
 * not sure what this will do yet...
 * 
 *
 * Makes a contacts proxy for data, puts out changeres table
 * 
 */

// declare a global JSON array for changing dates and room for the dates
var roomchange = [ {"room":"Annex 1", "num":1, "datesavail":[]},
				   {"room":"Annex 2", "num":2, "datesavail":[]},
				   {"room":"Annex 3", "num":3, "datesavail":[]},
				   {"room":"Annex 4", "num":4, "datesavail":[]},
				   {"room":"Main 1", "num":5, "datesavail":[]},
				   {"room":"Main 2", "num":6, "datesavail":[]},
				   {"room":"Main 3", "num":7, "datesavail":[]}];

function alertDel(transid)
{
	var answer = confirm("Are you sure you want to delete transactions #" + transid + "?");
	if (answer) {
		$.post("deletetrans.php", { deltrans: transid } );
		window.location.reload();
	}
}


/*
 * function changeres(datein, dateout, transactionid)
 *
 * this will send a request to the server for available dates
 * 
 */
function changeres(datein, dateout, transactionid)
{
	// instantiate XMLHttpRequest object
	try
	{
		abc = new XMLHttpRequest();
	}
	catch (e)
	{
		abc = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// handle old browsers
	if (abc == null)
	{
		alert("Ajax not supported by your browser!");
		return;
	}
	
	// construct URL
	var url = "availability.php?datein=" + datein + "&dateout=" + dateout;
	
	// fill the changetransid form with the one you just clicked.
	document.getElementById("changetransid").value = transactionid;
	
	// get data back from availability
	abc.onreadystatechange = function() {
	
		// check for loaded state
		if (abc.readyState == 4) {
			
			// check if this page is ready for new data
			if (abc.status == 200) {
			
				// show the div where data is going
    			document.getElementById('changereserve').style.display = "";
    			
    			// evaluate the data
    			var reschange = eval( abc.responseText );
    			
    			// determine table length to decide if it needs to get erased,
    			// erase if needed
    			var tbllen = document.getElementById('changereservep').rows.length;
				if ( tbllen > 1) {
					for (var del = 1; del < tbllen; del++) {
							document.getElementById('changereservep').deleteRow(1);
					}
				}
				
				// loop through the dates
				for (var i = 1; i < reschange[0] + 1; i++) {
					// how to write
					var gid=document.getElementById('changereservep').insertRow(i);
					var gidstyl = document.getElementById('changereservep')
					
					// write the date into the table
					gid.insertCell(0).innerHTML= reschange[i]["date"];
					gidstyl.rows[i].cells[0].className = 'availtblleft';
					
					// write the vacant or non-vacant into the table
					// also write listeners and other complex stuff
					for (var j = 1, k = 0; j < 8; j++, k++) {
						if (reschange[i]["rooms"][k] == "Vacant") {
							// to put the vacancy in the cell
							gid.insertCell(j).innerHTML= reschange[i]["rooms"][k];
							// to make the cell pretty
							gidstyl.rows[i].cells[j].className = 'availtblcontvac';
							
							// EVENT LISTENER: to select the days we would like to stay
							gidstyl.rows[i].cells[j].addEventListener('click', function(event) {
								
								// to make it pretty
								this.style.backgroundColor = (this.style.backgroundColor != 'green' ? 'green' : 'white');
								this.style.color = (this.style.color != 'white' ? 'white' : 'black');
								
								// to collect the x and y value for where user clicked
								var row = $(this).parent('tr');
								var horizontal = $(this).siblings().andSelf().index(this);
								var vertical = row.siblings().andSelf().index(row);

								// put date values into JSON, or remove them if background switched to white
								roomchange[horizontal-1].datesavail[vertical-1] = (this.style.backgroundColor != 'green' ? undefined : reschange[vertical]["datesql"]);
								
								// and put the value into the form at the bottom of the page
								var roompicked_text = JSON.stringify(roomchange);
								document.getElementById("jsonchangedates").value = roompicked_text;								
								
							},false)
						}
						else if (reschange[i]["rooms"][k] != "Vacant" && reschange[i]["rooms"][k] == transactionid) {
							// to put the current reservation in the cell
							gid.insertCell(j).innerHTML= "Current Res";
							// to make the cell pretty
							gidstyl.rows[i].cells[j].className = 'availtblcontres';
							
							// EVENT LISTENER: to select the days we would like to stay
							gidstyl.rows[i].cells[j].addEventListener('click', function(event) {
								
								// to make it pretty
								this.style.backgroundColor = (this.style.backgroundColor != 'green' ? 'green' : 'orange');
								this.style.color = (this.style.color != 'white' ? 'white' : 'black');
								
								// to collect the x and y value for where user clicked
								var row = $(this).parent('tr');
								var horizontal = $(this).siblings().andSelf().index(this);
								var vertical = row.siblings().andSelf().index(row);

								// put date values into JSON, or remove them if background switched to white
								roomchange[horizontal-1].datesavail[vertical-1] = (this.style.backgroundColor != 'green' ? undefined : reschange[vertical]["datesql"]);
								
								// and put the value into the form at the bottom of the page
								var roompicked_text = JSON.stringify(roomchange);
								document.getElementById("jsonchangedates").value = roompicked_text;
								
							},false)
						}
						else {
							// to put the occupado in the cell
							gid.insertCell(j).innerHTML= "Occupied";
							// to make the cell pretty
							gidstyl.rows[i].cells[j].className = 'availtblcontocc';
						}
					}	
					
			}
			}
			else
				alert("Error with Ajax Call!");
		}
	
	};
	
	abc.open("GET", url, true);
	abc.send(null);
	
// END OF FUNCTION
}