<?php
/**
 * Add disabled field to form, automatic filling from $source field, but filing by tranliteration text.
 * Spaces change on $divider. Other trash will deleting
 * In $source field you can set simple name of soure attribute
 *
 * Добавляет disabled поле в форму, автоматически заполняемую текстом из поля $source, транслитерируя
 * перед этим текст из поля $source. Нетекстовые символы и прочий мусор удаляются
 * В поле $source можно указать просто имя атрибута источника
 *
 * Render input and input[type=hidden], because jquery.serialize ignore disabled inputs
 *
 * Скрытое поле выводится для того, что бы сохранить валидацию, т.к. jquery.serialize игнорирует disabled поля
 *
 */
class AliasField extends InputWidget
{
    public $source = 'checkbox';
    public $divider = '-';


    public function init()
    {
        parent::init();
        $attrs = array();
        CHtml::resolveNameID($this->model, $this->source, $attrs);

        $options = CJavaScript::encode(array(
            'destination'  => $this->id,
            'urlSeparator' => $this->divider,
        ));
        $id      = $attrs['id'];
        Yii::app()->clientScript->registerScriptFile('/js/plugins/adminForm/alias/jquery.synctranslit.js')
            ->registerScript($this->id . '_iphone_checkbox', "
                var title = $('#{$id}'),
                    alias = $('#{$this->id}');
                title.syncTranslit($options);
                alias.change(function() {
                    $('#{$this->id}_hidden').val($('#{$this->id}').val());
                });
                alias.siblings('.change_alias').click(function(){
                    $(this).hide();
                    alias.removeAttr('disabled');
                    return false;
                });
            ");
    }


    public function run()
    {
        echo CHtml::hiddenField($this->name, '', array(
            'id'=> $this->id . '_hidden'
        )); //because disabled elements no serialize
        echo CHtml::activeTextField($this->model, $this->attribute, array(
            'class'   => $this->class,
            'disabled'=> 'disabled'
        ));
        echo CHtml::link(CHtml::image('/img/admin/editable.gif', '', array('height'=> 16)), '#', array('class'=> 'change_alias'));
    }
}