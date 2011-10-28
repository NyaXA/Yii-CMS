(function($)
{
    $.fn.tipInput = function(text)
    {
        $(this)
            .blur(function()
            {
                if ($(this).val() == '')
                {
                    $(this).val(text);
                }
            })
            .focus(function()
            {
                if ($(this).val() == text)
                {
                    $(this).val('');
                }
            });
    };
})(jQuery);