(function($)
{
    $.fn.tipInput = function()
    {
        var elements = $(this).find('input, textarea');
        elements.each(function()
        {
            var el = $(this), text = el.data('label');

            el.val(text)
                .blur(function()
                {
                    if (el.val() == '')
                    {
                        el.val(text);
                    }
                })
                .focus(function()
                {
                    if (el.val() == text)
                    {
                        el.val('');
                    }
                });
        });

        $(this).submit(function()
        {
            elements.each(function()
            {
                var el = $(this), text = el.data('label');
                if (el.val() == text)
                {
                    el.val('');
                }
            });
        });
    };
})(jQuery);