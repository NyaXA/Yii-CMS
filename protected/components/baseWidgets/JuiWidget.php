<?php
abstract class JuiWidget extends CJuiWidget
{
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