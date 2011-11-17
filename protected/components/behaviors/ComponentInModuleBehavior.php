<?php
/**
 * Содержит в себе часто используемые функции. Такие, как:
 * 1. getAssets - Получение assets родительского модуля
 * 2. getModule - Получение родительского модуля
 * 3. url - алиас BaseController::url()
 */
class ComponentInModuleBehavior extends CBehavior
{
    private $_assets;
    private $_module_id;

    public function getModule()
    {
        $component = $this->getOwner();

        //модуль находится в modules и имеет id такой же как название директории
        if ($this->_module_id == null)
        {
            $c = new ReflectionClass($component);
            $dir = pathinfo($c->getFileName(), PATHINFO_DIRNAME); //путь до нашего виджета

            $arr = explode(DIRECTORY_SEPARATOR, $dir);
            while(end($arr) != 'modules')   //получаем название директории с нашим модулем
            {
                $module_id = array_pop($arr);
            }
            $this->_module_id = $module_id;
        }

        return Yii::app()
            ->getModule($this->_module_id);
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
            $this->_assets = $this->module->assetsUrl();
        }

        return $this->_assets;
    }

}