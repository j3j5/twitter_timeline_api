$(document).ready(function(){
	// GIF Toggle
	$('#gifToggle').show();
	$('#gifToggle').click(function () {
		var filtered = $('#gifToggle').data('filtered');
		if(filtered == 'off') {
			$('img').filter(function() {
				var source= $(this).attr('src');
				return !source.match(/\.(gif)/i);
			}).parent().parent().parent().hide();
			$('#gifToggle').data('filtered', 'on');
		} else {
			$('img').filter(function() {
				var source= $(this).attr('src');
				return !source.match(/\.(gif)/i);
			}).parent().parent().parent().show();
			$('#gifToggle').data('filtered', 'off');
		}
	});
	var after = $('#next').data('after');
	if(after != '') {
		$('#next').show();
		$('#next').click(function () {
			window.location = $(location).attr('href') + '/' + after;
		});
	}
});
