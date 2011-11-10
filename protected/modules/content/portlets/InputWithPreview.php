<?php
class InputWithPreview extends InputWidget
{
    public $title = 'Ссылка на страницу: ';
    public $pattern = '/page/{value}';

    public function init()
    {
        parent::init();
        $this->initVars();
        $this->registerScripts();
    }

    public function initVars()
    {
        $this->pattern = Yii::app()->request->hostInfo.Yii::app()->baseUrl.$this->pattern;
    }

    public function registerScripts()
    {
        $plugins = $this->assets.'/js/plugins/';
        $options = CJavaScript::encode(array(
            'title' => $this->title,
            'pattern' => $this->pattern
        ));
        Yii::app()->clientScript
            ->registerScriptFile($plugins.'inputWithPreview/inputWithPreview.js')
            ->registerScript($this->id, "
                $('#{$this->id}').inputWithPreview({$options});
            ");
    }

    public function getModuleId()
    {
        return 'content';
    }
}