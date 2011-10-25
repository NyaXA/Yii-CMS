<?php

class MetaTagSubForm extends SubForm
{
    public $model;
    public $title = "Мета-теги";

    public function init()
    {
        $class     = 'application.components.activeRecordBehaviors.MetaTagBehavior';

        $behaviors = $this->model->behaviors();
        $classes   = ArrayHelper::extract($behaviors, 'class');
        if (!in_array($class, $classes))
        {
            throw new CException("Модель должна иметь поведение: {$class}");
        }

        if (!isset($this->model->meta_tags))
        {
            throw new CException("Класс {$class} должен иметь поле meta_tags");
        }

        parent::init();
    }


    public function renderContent()
    {
        $this->render('MetaTagSubForm');
    }
}
