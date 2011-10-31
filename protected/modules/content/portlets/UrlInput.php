<?php
class UrlInput extends InputWidget
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
            ->registerScriptFile($plugins.'activityInput/activityInput.js')
            ->registerScript($this->id, "
                $('#{$this->id}').activityInput({$options});
            ");
    }

    public function renderContent()
    {
        $this->render('ActivityInput');
    }
    
    public function getModuleId()
    {
        return 'content';
    }
}