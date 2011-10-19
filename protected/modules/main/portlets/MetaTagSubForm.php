<?php
 
class MetaTagSubForm extends Portlet
{
    public $model;


    public function init()
    {
        $class = 'application.components.activeRecordBehaviors.MetaTagBehavior';

        $behaviors = $this->model->behaviors();
        $classes   = ArrayHelper::extract($behaviors, 'class');
        if (!in_array($class, $classes))
        {
            throw new CException("Модель должна иметь поведение: {$class}");
        }

        if (!property_exists(get_class($this->model), 'meta_tags'))
        {
            throw new CException("Класс {$class} должен иметь поле meta_tags");
        }

        parent::init();
    }


    public function renderContent()
    {
        $model = MetaTag::model();

        if ($this->model->id)
        {
            $meta_tag = MetaTag::model()->findByAttributes(array(
                'object_id' => $this->model->id,
                'model_id'  => get_class($this->model)
            ));

            if ($meta_tag)
            {
                $model = $meta_tag;
            }
        }

        if (isset($_POST[get_class($this->model)]['meta_tags']))
        {
            foreach ($_POST[get_class($this->model)]['meta_tags'] as $tag => $value)
            {
                $model->$tag = $value;
            }
        }

        $this->render('MetaTagSubForm', array(
            'model' => $model
        ));
    }
}
