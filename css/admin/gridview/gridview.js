(function($)
{
    $.widget('CmsUI.grid', {
        _version:0.1,

        version:function()
        {
            return this._version
        },

        // default options
        options:{
        },
        _create:function()
        {
            var self = this,
                id = self.element.attr('id');

            var func = self.element.yiiGridView.settings[id].afterAjaxUpdate;
            self.element.yiiGridView.settings[id].afterAjaxUpdate = function(id, data)
            {
                if (func != undefined)
                {
                    func(id, data);
                }
                self.element = $('#' + id); //because yiiGridView make replaceWith on our element
                self._initEvents();
            };
            self._initEvents();
        },
        _initEvents:function()
        {

            var self = this;
            self._initSwitchPageSize();
            self._initFilters();

            if ($('.grid-view table').attr('mass_removal'))
            {
                self._initMassRemoval();
            }
        },
        _initMassRemoval:function()
        {
            var self = this;
            $('#mass_remove_button').on('click', function()
            {
                var $checkboxes = $('.object_checkbox:checked', self.element);

                if ($checkboxes.length > 0)
                {
                    if (!confirm('Вы уверены, что хотите удалить отмеченные элементы?'))
                    {
                        return false;
                    }

                    blockUI();
                    var grid_id = self.element.attr('id');

                    $checkboxes.each(function()
                    {
                        var action = $(this).closest('tr').find('.delete').attr('href');

                        $.fn.yiiGridView.update(grid_id, {
                            type:'POST',
                            url:action,
                            success:null
                        });
                    });

                    $(document).ajaxStop(function()
                    {
                        unblockUI();
                        $.fn.yiiGridView.update(grid_id);
                        $(this).unbind('ajaxStop');
                    });
                }
                else
                {
                    showMsg('Отметьте элементы!');
                }
            });
        },
        _initSwitchPageSize:function()
        {
            var self = this;
            $('.pager_select', self.element).on('change', self.element, function()
            {
                var params = '/model/' + $(this).attr('model') + '/per_page/' + $(this).val() + '/back_url/' + $("#back_url").val();
                location.href = '/main/mainAdmin/SessionPerPage' + params;
            });

        },
        _initFilters:function()
        {
            var self = this;
            var inputs = $('.filters input', self.element), //TODO: what with dropdownlist???
                inputs_count = inputs.length;

            if (inputs_count == 0)
            {
                return false;
            }

            var show_filters = false;
            inputs.each(function()
            {
                if ($(this).val())
                {
                    show_filters = true;
                }
            });

            if (show_filters)
            {
                $('.filters:first', self.element).slideToggle();
            }

            $('th', self.element).each(function()
            {
                if ($(this).html() == '&nbsp;')
                {
                    $(this).html("<a href='' class='filters_link'>фильтры</a>");
                }
            });

            $('.filters_link', self.element).click(function()
            {
                $('.filters', self.element).slideToggle();
                return false;
            });
        }
    });
})(jQuery);


