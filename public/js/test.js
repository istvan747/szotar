var testProperties = {
	testJSON: 'a',
	testJSONObject: null,
	badAnswersJSON: '',
	badAnswersObjectsArray: null,
	tesqQuestionCount: 0
}

var a = 3;

function getTestForm(){
	
	var xhttp = new XMLHttpRequest();
	
	xhttp.open( "POST", "gettest", true );
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	xhttp.onreadystatechange = function(){
		if( this.readyState == XMLHttpRequest.DONE && this.status == 200 ){
			testProperties.testJSON = this.responseText;			
			testProperties.testJSONObject = JSON.parse( testProperties.testJSON );
			if( validateJSONFromGetTest( testProperties.testJSONObject )){
				document.getElementById("include_test_table").innerHTML = createTestTable( testProperties.testJSONObject );
				document.getElementById("include_bad_answers_table").innerHTML = "";
				$("body,html").animate({scrollTop: $("#include_test_table").offset().top},800);
			}
		}

	};
	
	xhttp.send( getValuesToPost() );	
}

function getValuesToPost(){
	var magyarAngolRadio = document.getElementById("magyar_angol");
	var angolMagyarRadio = document.getElementById("angol_magyar");
	var leastFrequentlyAskedRadio = document.getElementById("least_frequently_asked_words");
	var mostOfTimeSpoiledRadio = document.getElementById("most_of_time_spoiled");
	var oldestAskedRadio = document.getElementById("oldest_asked");
	var randomRadio = document.getElementById("random");
	var topicSelectedList = document.getElementById("topic_select_list");
	var numberOfQuestionField = document.getElementById("number_of_questions");
	
	post = '';
	if( magyarAngolRadio.checked ){
		post += "languageDirection=" + magyarAngolRadio.value;
	}else{
		post += "languageDirection=" + angolMagyarRadio.value;
	}
	
	if( leastFrequentlyAskedRadio.checked ){
		post += "&testVariety=" + leastFrequentlyAskedRadio.value;
	}else if( mostOfTimeSpoiledRadio.checked ){
		post += "&testVariety=" + mostOfTimeSpoiledRadio.value;
	}else if( oldestAskedRadio.checked ){
		post += "&testVariety=" + oldestAskedRadio.value; 
	}else{
		post += "&testVariety=" + randomRadio.value;
		selectedIttems = getSelectedIttems(topicSelectedList);
		for( var i = 0; i < selectedIttems.length; i++ ){
			post += "&topics[]=" + selectedIttems[i];
		}
	}
	
	post += "&numberOfQuestion=" + numberOfQuestionField.value;
	
	return post;
}

function getSelectedIttems( selectElement ){
	var result = [];
	var options = selectElement.options;
	
	for( var i = 0; i < options.length; i++ ){
		if( options[i].selected ){
			result.push(options[i].value);
		}
	}
	
	return result;
}

function validateJSONFromGetTest( json ){
	if( json.length != 2 ){
		return false;
	}
	if( json[0].length != 2 || json[1].length < 1 ){
		return false;
	}
	if( !(json[0][0].hasOwnProperty( 'sourceLanguage' ) && json[0][1].hasOwnProperty( 'targetLanguage' )) ){
		return false;
	}
	for( var i = 0; i < json[1].length; i++ ){
		if( !(json[1][i].hasOwnProperty('wordA') &&  json[1][i].hasOwnProperty('wordB') &&  json[1][i].hasOwnProperty('topic') 
				&&  json[1][i].hasOwnProperty('wordClass') &&  json[1][i].hasOwnProperty('id')  ) ){
			return false;
		}
		if( !(json[1][i].wordA.hasOwnProperty('id') && json[1][i].wordA.hasOwnProperty('word') 
				&& json[1][i].wordB.hasOwnProperty('id') && json[1][i].wordB.hasOwnProperty('word')) ){
			return false;
		}
	}
	return true;
}

function createTestTable( json ){
	table = '<table id="test_table" >'
	var i = 0;
	for( i = 0; i < json[1].length; i++ ){
		table += '<tr>';
		table += '<td><input type="text" name="' + json[1][i].id + '_wordA" id="' + json[1][i].id + '_wordA" class="" value="' + json[1][i].wordA.word + '" disabled /></td>';
		table += '<td>-</td>';
		table += '<td><input type="text" name="' + json[1][i].id + '_wordB" id="' + json[1][i].id + '_wordB" class="" value="" /></td>';
		table += '</tr>';
	}
	testProperties.tesqQuestionCount = i;
	table += '</table>';
	table += '<div id="div_include_test_Ready_button" ><input type="button" name="test_ready_button" id="test_ready_button" value="Ellenőriz" onclick="sendTestForCheck()" /></div>';
	return table;
}

function sendTestForCheck(){	
	var xhttp = new XMLHttpRequest();
	
	xhttp.open( "POST", "testevaluation", true );
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	xhttp.onreadystatechange = function(){
		if( this.readyState == XMLHttpRequest.DONE && this.status == 200 ){
			testProperties.badAnswersJSON = this.responseText;
			testProperties.badAnswersObjectsArray = JSON.parse( testProperties.badAnswersJSON );
			addBadAnswerClassForInputFields( testProperties.testJSONObject, testProperties.badAnswersObjectsArray );
			document.getElementById("include_bad_answers_table").innerHTML = getBadAnswersTableElement(testProperties.badAnswersObjectsArray);
			$("body,html").animate({scrollTop: $("#include_bad_answers_table").offset().top},800);
		}

	};
	
	answers = getAnswerMeaningsInJSON( testProperties.testJSONObject );
	postParameters = "questions=" + testProperties.testJSON + "&answers=" + answers;
	xhttp.send( postParameters );
	document.getElementById("test_ready_button").disabled = true;
}

function getAnswerMeaningsInJSON( testJSONObject ){
	var json = '';
	var answerMeaningArray = new Array();
	function AnswerMeaningObject( wordA, wordB ){
		function WordA( wordA ){
			this.id = -1;
			this.word = wordA;
		}
		function WordB( wordB ){
			this.id = -1;
			this.word = wordB;
		}
		this.wordA = new WordA( wordA );
		this.wordB = new WordB( wordB );
		this.topic = '';
		this.wordClass = '';
		this.id = -1;
	}
	
	for( var i = 0; i < testProperties.tesqQuestionCount; i++ ){
		var wordAFieldValue = document.getElementById( testJSONObject[1][i].id + "_wordA" ).value;
		var wordBFieldValue = document.getElementById( testJSONObject[1][i].id + "_wordB" ).value;
		answerMeaning = new AnswerMeaningObject( wordAFieldValue, wordBFieldValue );
		answerMeaningArray.push( answerMeaning );
	}
	json = JSON.stringify( answerMeaningArray );
	return json;
}

function addBadAnswerClassForInputFields( questionsArray, badAnswersObjectsArray ){	
	for( var i = 0; i < questionsArray[1].length; i++ ){
		document.getElementById( questionsArray[1][i].id + "_wordB").classList.add("good-answer");
	}
	if( badAnswersObjectsArray.length > 0 && questionsArray.length == 2 && questionsArray[1].length > 0 ){
		for( var i = 0; i < badAnswersObjectsArray.length; i++ ){
			if( badAnswersObjectsArray[i].hasOwnProperty("id") ){
				document.getElementById( badAnswersObjectsArray[i].id + "_wordB").classList.remove("good-answer");
				document.getElementById( badAnswersObjectsArray[i].id + "_wordB").classList.add("bad-answer");
			}
		}
	}
}

function getBadAnswersTableElement( badAnswersObjectsArray ){
	table = '';
	if( badAnswersObjectsArray.length > 0){
		table = '<table id="bad_answers_table" ><tr><th colspan="2" >elrontott szavak</th></tr>';
		for( var i = 0; i < badAnswersObjectsArray.length; i++ ){
			if( badAnswersObjectsArray[i].hasOwnProperty("wordA") && badAnswersObjectsArray[i].hasOwnProperty("wordB")
				&& badAnswersObjectsArray[i].wordA.hasOwnProperty("word") && badAnswersObjectsArray[i].wordB.hasOwnProperty("word") ){
				table += '<tr><td>' + badAnswersObjectsArray[i].wordA.word + '</td><td> - </td><td>' + badAnswersObjectsArray[i].wordB.word + '</td></tr>';
			}
		}
		table += '</table>';
	}
	return table;
}
