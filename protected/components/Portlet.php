<?php

Yii::import('zii.widgets.CPortlet');

class Portlet extends CPortlet 
{
    public function init()
    {
        $this->_checkRequiredFields();
        parent::init();
    }

    private function _checkRequiredFields()
    {
        if (method_exists($this, 'requiredFields'))
        {
            $fields = $this->requiredFields();
            foreach ($fields as $field)
            {
                if ($this->$field === null)
                {
                    throw new CException('Параметр '.$this.' является обязательным для портлета '.get_class($this));
                }
            }
        }
    }
    
    public function url($route, $params = array(), $ampersand = '&')
    {
        return Yii::app()->controller->url($route, $params = array(), $ampersand = '&');
    }


    public function getModule()
    {
        return Yii::app()->controller->module;
    }
}
