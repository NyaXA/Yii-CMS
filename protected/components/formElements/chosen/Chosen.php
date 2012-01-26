<?php
class Chosen extends InputWidget
{
    public $items;

    public function init()
    {
        parent::init();
        $options = CJavaScript::encode(array());

        Yii::app()->clientScript
            ->registerScriptFile($this->assets.'/chosen.jquery.js')
            ->registerCssFile($this->assets.'/chosen.css')
            ->registerScript($this->id . '_chosen', "$('#{$this->id}').chosen($options);");
    }


    public function run()
    {
        $el = new FormInputElement(array(
            'type' => 'dropdownlist',
            'name' => $this->attribute,
            'attributes' => array(
                'items'=>$this->items
            ),
        ), $this);
        echo $el->renderInput();
    }
}