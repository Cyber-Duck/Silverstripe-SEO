(function($) {
    $.entwine('ss', function($) {

        title = 'input[name="MetaTitle"]';
        description = 'textarea[name="MetaDescription"]';

        $('input[name="MetaTitle"]').entwine({
            onmatch: function() {
                createCountInput('title',title);
                changeSerpCount('title',title);
                if($(this).val() != ''){
                    changeSerp('title',$(this).val());
                }
            },
            onchange: function() {
                changeSerp('title',$(this).val());
                changeSerpCount('title',title);
            },
            onkeyup: function() {
                changeSerp('title',$(this).val());
                changeSerpCount('title',title);
            }
        });
        $('textarea[name="MetaDescription"]').entwine({
            onmatch: function() {
                createCountInput('description',description);
                changeSerpCount('description',description);
                if($(this).val() != ''){
                    changeSerp('description',$(this).val());
                }
            },
            onchange: function() {
                changeSerp('description',$(this).val());
                changeSerpCount('description',description);
            },
            onkeyup: function() {
                changeSerp('description',$(this).val());
                changeSerpCount('description',description);
            }
        });
        function createCountInput(name,input){
            $('<input>').attr({
                type: 'text',
                class: 'text seo-serp-count seo-serp-count-' + name,
            }).insertAfter(input);
        }
        function changeSerpCount(name,input){
            $('.seo-serp-count-' + name).val($(input).val().length);
        }
        function changeSerp(name,text){
            $('.seo-serp-' + name).text(text);
        }
    });
})(jQuery);