(function($)
{
    $.fn.clientForm = function()
    {
        var self = $(this);

        self.tipInput();
        $('label').inFieldLabels();
    }

})(jQuery);