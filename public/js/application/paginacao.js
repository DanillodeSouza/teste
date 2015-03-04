$(document).ready(function(){
	$(document).on("click", ".linkPaginar", function() {
		var pageToken = $(this).attr('data-token');
		if (pageToken != '') {
			var dados = 'pageToken=' + pageToken
			+ '&query=' + $("#query").val();
			$.ajax({
			 	url: "/paginar",
			 	data: dados,
			 	method: 'post'
			}).done(function(result) {
				$("#videosYouTube").html(result);
			});
		}
	});
});