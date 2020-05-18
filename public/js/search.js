function loadMeanings(){
	var xhttp = new XMLHttpRequest();
	var searchField = document.getElementById("search_field");
	xhttp.onreadystatechange = function(){
		if( this.readyState == XMLHttpRequest.DONE && this.status == 200 ){
			let element = document.getElementById("search_result");
			if( this.responseText.length > 2 ){				
				let table = "<table>";
				var meaningArray = JSON.parse(this.responseText);
				for( let i = 0; i < meaningArray.length; i++ ){
					table += getMeaningTableRow( meaningArray[i] );
				}
				table += "</table>";
				element.innerHTML = table;
			}else{
				element.innerHTML = '<table></table>';
			}
		}
	};
	
	xhttp.open( "GET", "search?word=" + searchField.value, true );
	xhttp.send();
}

function getMeaningTableRow( meaning ){
	return "<tr><td>" + meaning.wordB.word + "</td><td>&nbsp;&nbsp;-&nbsp;&nbsp;</td><td>" + meaning.wordA.word + "</td></tr>";
}

