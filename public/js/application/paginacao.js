$(document).ready(function(){

	$(document).on("click", ".linkPaginar", function() {
		var pageToken = $(this).attr('data-token');
		if (pageToken != '') {
			var dados = 'pageToken=' + pageToken
			+ '&query=' + $("#query").val();
			$.ajax({
			 	url: "/paginar-youtube",
			 	data: dados,
			 	method: 'post'
			}).done(function(result) {
				$("#videosYouTube").html(result);
			});
		}
	});

	$(document).on("click", ".linkPaginarVimeo", function() {
		var url = $(this).attr('data-link');
		if (url != '') {
			$.ajax({
			 	url: "/paginar-vimeo",
			 	data: url,
			 	method: 'post'
			}).done(function(result) {
				$("#videosVimeo").html(result);
			});
		}
	});

});