<?php

Yii::import('zii.widgets.CPortlet');

class Portlet extends CPortlet
{
    public function init()
    {
        $this->_checkRequiredFields();
        parent::init();
    }

    /**
     * Проверяет заполненность обязательных полей
     * Можно задавать обязательность заполнения любого поля из группы: поле|поле|поле
     *
     * @throws CException
     */
    private function _checkRequiredFields()
    {
        if (!method_exists($this, 'requiredFields'))
        {
            return;
        }

        $fields = $this->requiredFields();
        foreach ($fields as $field)
        {
            if (strpos($field, '|') === false)
            {
                if ($this->$field === null)
                {
                    throw new CException('Параметр '.$this.' является обязательным для портлета '.get_class($this));
                }
            }
            else
            {
                $variants = explode('|', $field);
                $good     = false;
                foreach ($variants as $variant)
                {
                    if ($this->$variant !== null)
                    {
                        $good = true;
                        break;
                    }
                }
                if (!$good)
                {
                    throw new CException(
                        'Одно из полей '.$variant.' должно быть заполнено для портлета '.get_class($this));
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
