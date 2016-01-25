(function($) {
    $.entwine(function($) {
        $('#Form_EditForm_MetaTitle').entwine({
            onmatch: function() {
                changeSerp('title',$(this).val());
            },
            onchange: function() {
                changeSerp('title',$(this).val());
            },
            onkeyup: function() {
                changeSerp('title',$(this).val());
            }
        });
        $('#Form_EditForm_MetaDescription').entwine({
            onmatch: function() {
                changeSerp('text',$(this).val());
            },
            onchange: function() {
                changeSerp('text',$(this).val());
            },
            onkeyup: function() {
                changeSerp('text',$(this).val());
            }
        });
        function changeSerp(name,text){
            $('.serp-' + name).text(text);
        }
    });
})(jQuery);