<?php
/**
 * Содержит в себе часто используемые функции. Такие, как:
 * 1. getAssets - Получение assets родительского модуля
 * 2. TODO: getSettings - Получение необходимых настроек (надо ли?)
 * 3. getModule - Получение родительского модуля
 * 4. url - алиас BaseController::url()
 */
class WidgetBehavior extends CBehavior
{
    private $_assets;

    public function getModule()
    {
        $component = $this->getOwner();
        if (method_exists($component, 'getModuleId')) //эта проверка нужна, что бы просто не упали старые виджеты
        {
            return Yii::app()->getModule($component->getModuleId());
        }
        else
        {
            return Yii::app()->controller->module;
        }
    }

    /**
     *
     * @param string $route
     * @param array  $params
     * @param string $ampersand
     *
     * @return string
     */
    public function url($route, $params = array(), $ampersand = '&')
    {
        return Yii::app()->controller->url($route, $params = array(), $ampersand = '&');
    }

    /**
     * Возвращает URL до директории assets, модуля, которому принадлежит виджет
     *
     * @return mixed
     */
    public function getAssets()
    {
        if ($this->_assets === null)
        {
            $component = $this->getOwner();
            //check settings
            if (method_exists($component, 'getModuleId')) //эта проверка нужна, что бы просто не упали старые виджеты
            {
                $this->_assets = $this->module->assetsUrl();
            }
        }

        return $this->_assets;
    }

}