(function($)
{
    jQuery.fn.sortableCGrid = function(options)
    {
        var defaults = {
            menuBlock:null,
            offsetX:8,
            offsetY:8,
            speed:'fast'
        };
        var options = $.extend(defaults, options);
        var id = options.id;
        var table = this;

        table.sortable({
            axis:"y",
            containment:"parent",
            cursor:"pointer",
            items:'tr',
            delay:100,
            distance:5,
            handle:".positioner",
            forcePlaceholderSize:true,
            forceHelperSize:true,
            tolerance:"pointer",
            update:function(event, ui)
            {
                var arr = [];
                var elems = $("tr .pk", table);
                $.each(elems, function(key)
                {
                    var pk = $(this).attr('id').split('_')[1];
                    arr[key] = pk;
                });
                $.ajax({
                    url:options.url,
                    type:"POST",
                    data:({pk:arr, model:options.model}),
                    dataType:"json",
                    success:function(msg)
                    {
                        table.yiiGridView.settings[id].afterAjaxUpdate = function(id, data)
                        {
                            table.sortableCGrid()
                        };
                        table.yiiGridView.update(id);
                    }
                });
            }
        }).disableSelection();
    };

})(jQuery);
