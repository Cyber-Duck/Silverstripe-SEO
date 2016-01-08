(function($) {
    $(document).ready(function(){
    	$('input[name="MetaTitle"]').on('change paste keyup',function(event) {
    		title = $(this).val();
    		$('.serp-title').text(title);
		});

    	$('input[name="MetaDescription"]').on('change paste keyup',function(event) {
    		description = $(this).val();
    		$('.serp-description').text(description);
		});
    })
})(jQuery);