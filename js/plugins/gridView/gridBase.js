(function($)
{
    /**
     * Этот плагин берет на себя работу по синхронизации с плагином Yii,
     * а так же предоставляет простой API для реализации на его основе своих плагинов
     *
     * Плагин изначально вызывает метод _initHandlers
     * используйте его для инициализации инициализации своих скриптов
     * после ajaxUpdate(метод yiiGridView) этот метод будет вызван повторно,
     * поэтому нет необходимости использовать live или delegate
     *
     * На одну таблицу можо вешать неограниченное количество плагинов основанных на CmsUI.gridBase
     * Но, т.к. мы зависим от yiiGridView, то инициализация этих плагинов, должна произойти
     * после инициализации yiiGridView
     * Т.е. если вы хотите проинициализировать плагин из какой-либо колонки,
     * то для этого в компоненте GridView предусмотренно событие onRegisterScript.
     * В методе init колонки, используйте $this->grid->onRegisterScript = array($this, 'registerScript');
     * и в методе registerScript вашего класса регистрируйте любые скрипты.
     */
    $.widget('CmsUI.gridBase', {
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
                self._initHandlers();
            };
            self._initHandlers();
        },
        _initHandlers:function()  //there run functions for initialize some event handlers
        {
        }
    });
})(jQuery);


