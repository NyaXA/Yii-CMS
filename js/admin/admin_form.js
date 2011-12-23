$(document).ready(function()
{
    $('.back_button').click(function()
    {
        location.href = $(this).attr('url');
    });

    $('.hint').each(function()
    {
        var self = $(this),
            label = self.siblings('label:first'),

            a = $('<a class="hint" href="#">')
                .aToolTip({
                    clickIt:true,
                    tipContent:self.html()
                });
        self.replaceWith(a);
    });
});
