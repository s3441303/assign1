$(function(){
	$("#startSession").click(function(e){
		$(this).text("Session has started");
		$("#searchButton").attr("name","sessionStart");
		$(this).attr("class","pull-right btn btn-small btn-info");
		e.preventDefault();
	});
});