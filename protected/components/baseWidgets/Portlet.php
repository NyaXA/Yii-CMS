<?php
Yii::import('zii.widgets.CPortlet');

abstract class Portlet extends CPortlet
{
    public $assets;

    public function init()
    {
        $this->attachBehaviors($this->behaviors());
        parent::init();
    }

    public function behaviors()
    {
        return array(
            'Widget' => array(
                'class' => 'application.components.behaviors.WidgetBehavior'
            )
        );
    }

}
