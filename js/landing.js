$(document).ready(function(){
	$('.searchField').keypress(function (e) {
		if (e.which == 13) {
			if($(this).val().length > 0) {
				e.preventDefault();
				var $form = $(this).closest('form');
				$form.attr('action', $form.data('baseurl') + $(this).val());
				$(this).val("");
				$form.submit();
			}
		}
	})
});
