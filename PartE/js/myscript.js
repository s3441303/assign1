// user click to start session
$(function(){
	$("#startSession").click(function(e){
		$(this).text("Session has started");
		$("#search").attr("name","sessionStart");
		$(this).attr("class","pull-right btn btn-small btn-info");
		e.preventDefault();
	});
});

//user select previous search
$('#previousSearch').on('change',function(){
	var index = $(this).val();
	$.ajax({
	  url: "viewedWine.php",
	  type: "GET",
	  data: {id : index}
	}).done(function(res) {
		$('#viewedWine').html(res);
	});
});