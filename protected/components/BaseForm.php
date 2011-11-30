<?php

class BaseForm extends CForm
{
    private $_clear = false;

    public $side;

    public $cancel_button_show = true;

    public function __construct($config, $model = null, $parent = null)
    {
        if (Yii::app()->controller instanceof AdminController)
        {
            $this->side = 'admin';
        }
        else
        {
            $this->side = 'client';
        }

        if (is_string($config))
        {
            $config = self::getFullAlias($config);
        }

        parent::__construct($config, $model, $parent);

        $this->addAttributesToButtons();
        $this->formatDateAttributes();
    }

    public static function getFullAlias($alias)
    {
        list($module, $form) = explode(".", $alias, 2);
        return "application.modules.{$module}.forms.{$form}";
    }

    public static function getFormConfig($alias)
    {
        if (is_string($alias))
        {
            $alias = self::getFullAlias($alias);
            return require(Yii::getPathOfAlias($alias) . '.php');
        }
        else
        {
            return $alias;
        }
    }

    public function __toString()
    {
        if (!($this->parent instanceof self))
        {
            $id = $this->activeForm['id'];
            if ($this->side == 'client') {
                Yii::app()->clientScript->registerPackage('clientForm')->registerScript(
                    $id . '_baseForm', "$('#{$id}').clientForm()");
            }
        }

        if ($this->_clear)
        {
            Yii::app()->clientScript->registerScript('clearForm', '$(function()
                {
                    $(":input","#' . $this->activeForm['id'] . '")
                        .not(":button, :submit, :reset, :hidden")
                        .val("")
                        .removeAttr("checked")
                        .removeAttr("selected");
                })');
        }

        try
        {
            return parent::__toString();
        } catch (Exception $e)
        {
            //Y::dump($e->getMessage());
            Y::dump($e->getTrace());
        }
    }

    public function renderBody()
    {
        $output = parent::renderBody();

        if (!($this->getParent() instanceof self))
        {
            $id = $this->activeForm['id'];
            if ($this->side == 'admin')
            {
                $this->attributes['class'] = 'admin_form';
                return $this->getParent()->msg('Поля отмеченные * обязательны.', 'info') . $output;
            }
        }

        return $output;
    }

    public function renderElement($element)
    {
        if (is_string($element))
        {
            if (($e = $this[$element]) === null && ($e = $this->getButtons()->itemAt($element)) === null
            )
            {
                return $element;
            }
            else
            {
                $element = $e;
            }
        }

        if ($element->getVisible())
        {
            if ($element instanceof CFormInputElement)
            {
                if ($element->type === 'hidden')
                {
                    return "<div style=\"visibility:hidden\">\n" . $element->render() . "</div>\n";
                }
                else
                {
                    return $this->_renderElement($element);
                }
            }
            else if ($element instanceof CFormButtonElement)
            {
                return $element->render() . "\n";
            }
            else
            {
                return $element->render();
            }
        }
        return '';
    }

    private function _renderElement($element)
    {
        if ($element instanceof self)
        {
            return $element->render();
        }

        if ($this->side == 'admin')
        {
            $tpl = '_adminForm';
        }
        elseif ($this->side = 'client')
        {
            $tpl = '_form';
        }
        else
        {
            $tpl = '_form';
        }


        $class = $element->type;
        if (isset($element->attributes['parentClass']))
        {
            $class .= ' ' . $element->attributes['parentClass'];
        }

        $res = CHtml::openTag('dl', array('class'=> $class));
        $res .= CHtml::openTag('dd');
//        $element->attributesadminForm['data-hint']  = $element->hint;

        $res .= Yii::app()->controller->renderPartial('application.views.layouts.' . $tpl, array(
            'element' => $element,
            'form'    => $this
        ), true);
        $res .= CHtml::closeTag('dd');
        $res .= CHtml::closeTag('dl');

        return $res;
    }

    public function clear()
    {
        $this->_clear = true;
    }

    public function renderButtons()
    {
        if (!($this->getParent() instanceof self) && !$this->buttons->itemAt('back') &&
            $this->cancel_button_show && $this->side == 'admin'
        )
        {
            $this->buttons->add("back", array(
                'type'  => 'button',
                'value' => 'Отмена',
                'url'   => Yii::app()->controller->createUrl('manage'),
                'class' => 'back_button submit small'
            ));
        }

        return parent::renderButtons();
    }


    /***** Функции оформления формы *******/

    function addAttributesToButtons()
    {
        foreach ($this->buttons as $i => $button)
        {
            if ($this->side == 'admin')
            {
                $length = mb_strlen($button->value, 'utf-8');

                $class = isset($button->attributes['class']) ?
                    $button->attributes['class'] . " submit" : "submit";

                if ($length > 11)
                {
                    $class .= ' long';
                }
                elseif ($length > 6)
                {
                    $class .= ' mid';
                }
                else
                {
                    $class .= ' small';
                }

                $button->attributes['class'] = $class;
            }
            else
            {

            }
            $this->buttons[$i] = $button;
        }
    }

    function formatDateAttributes()
    {
        if (!$this->model)
        {
            return false;
        }

        $model = $this->model;

        foreach ($model->attributes as $attr => $value)
        {
            if (Yii::app()->dater->isDbDate($value))
            {
                $model->$attr = Yii::app()->dater->formFormat($value);
            }
        }

        $this->model = $model;
    }

    //недоделанные функции
    function renderHint($element)
    {
        if (isset($element->hint))
        {
            $hint = trim($element->hint);
            if (!empty($hint))
            {
                echo "<span class='form_element_hint'>{$hint}</span>";
            }
        }

    }

    //нужно еще добавить вывод городов

}
