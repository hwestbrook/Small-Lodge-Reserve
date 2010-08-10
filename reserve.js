/*
 * reserve.js
 *
 * not sure what this will do yet...
 * 
 *
 * Makes a JSON array for picking dates, puts in jQuery datepicker
 * contacts proxy
 */

// declare a global JSON array for picking dates and for the room for the dates
var roompicked = [ {"room":"Annex 1", "num":1, "datesavail":[]},
				   {"room":"Annex 2", "num":2, "datesavail":[]},
				   {"room":"Annex 3", "num":3, "datesavail":[]},
				   {"room":"Annex 4", "num":4, "datesavail":[]},
				   {"room":"Main 1", "num":5, "datesavail":[]},
				   {"room":"Main 2", "num":6, "datesavail":[]},
				   {"room":"Main 3", "num":7, "datesavail":[]}];

// this is jQuery UI for the datepickers
$(function() {
	$("#datepickerin").datepicker({minDate: 0, maxDate: '+1Y', dateFormat: 'yy-mm-dd'});
	$("#datepickerout").datepicker({minDate: 0, maxDate: '+1Y', dateFormat: 'yy-mm-dd'});
});

/*
 * function proxycontact(datein, dateout)
 *
 * this will send a request to the server for available dates
 * 
 */
function proxycontact(datein, dateout)
{	
	// instantiate XMLHttpRequest object
	try
	{
		xhr = new XMLHttpRequest();
	}
	catch (e)
	{
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// handle old browsers
	if (xhr == null)
	{
		alert("Ajax not supported by your browser!");
		return;
	}
	
	// construct URL
	var url = "availability.php?datein=" + datein + "&dateout=" + dateout;
	
	// get the data back from proxy
	xhr.onreadystatechange = function() {
	
		// only handle requests in "loaded" state
		if (xhr.readyState == 4)
		{
			// embed response in page if possible
			if (xhr.status == 200)
			{
				// hide progress
    			document.getElementById("progress").style.display = "none";
    			
    			// show the beginning of the table
    			// document.getElementById('results').style.display = "";
    
				// to evaluate proxy's JSON response
				// example of how to parse ...
				// document.getElementById("checker").innerHTML = newslocs[1]["articles"][1]["title"];
				var vacancy = eval( xhr.responseText );
				
				var tbllen = document.getElementById('resultsp').rows.length;
				
				// delete rows to make way for incoming
				if ( tbllen > 1) {
					for (var del = 1; del < tbllen; del++) {
							document.getElementById('resultsp').deleteRow(1);
					}
				}
				
				// loop through the dates
				for (var i = 1; i < vacancy[0] + 1; i++) {
					// how to write
					var gid=document.getElementById('resultsp').insertRow(i);
					var gidstyl = document.getElementById('resultsp')
					
					// write the values into the table
					gid.insertCell(0).innerHTML= vacancy[i]["date"];
					gidstyl.rows[i].cells[0].className = 'availtblleft';
					
					// loop through whether occupied or not
					for (var j = 1, k = 0; j < 8; j++, k++) {
						if (vacancy[i]["rooms"][k] == "Vacant") {
							// to put the vacancy in the cell
							gid.insertCell(j).innerHTML= vacancy[i]["rooms"][k];
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
								roompicked[horizontal-1].datesavail[vertical-1] = (this.style.backgroundColor != 'green' ? undefined : vacancy[vertical]["datesql"]);
								
								// slide down the booking
								if (location.pathname != "/reserve/index.php") {
									document.getElementById('booking').style.display = "";
								}
								
								// and put the value into the form at the bottom of the page
								var roompicked_text = JSON.stringify(roompicked);
								document.getElementById("jsondates").value = roompicked_text;
								
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
				alert("Error with Ajax call!");
		}
	
	};
	xhr.open("GET", url, true);
	xhr.send(null);
}
