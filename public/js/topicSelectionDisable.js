function initRadio(){
	
	var leastFrequentlyAskedWordsRadio = document.getElementById("least_frequently_asked_words");
	var mostOfTimeSpoiled = document.getElementById("most_of_time_spoiled");
	var oldestAsked = document.getElementById("oldest_asked");
	var randomRadio = document.getElementById("random");
	var topicSelectionList = document.getElementById("topic_select_list");
	
	leastFrequentlyAskedWordsRadio.addEventListener("change", function(){
		if( this.checked ){
			topicSelectionList.disabled = true;
		}
	});	
	
	mostOfTimeSpoiled.addEventListener("change", function(){
		if( this.checked ){
			topicSelectionList.disabled = true;
		}
	});
	
	oldestAsked.addEventListener("change", function(){
		if( this.checked ){
			topicSelectionList.disabled = true;
		}
	});
	
	randomRadio.addEventListener("change", function(){
		if( this.checked ){
			topicSelectionList.disabled = false;
		}
	});
}
