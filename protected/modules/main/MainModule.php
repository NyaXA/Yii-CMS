<?php

class MainModule extends WebModule
{
    public static $base_module = true;


    public static function description()
    {
        return 'Главный модуль';
    }


    public static function version()
    {
        return '1.0';
    }


    public static function name()
    {
        return 'Система';
    }


	public function init()
	{
		$this->setImport(array(
        	'main.models.*',
            'main.components.*',
		));
	}


	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	public static function adminMenu()
	{
        return array(
            'Мета-теги'         => '/main/MetaTagAdmin/manage',
            'Добавить мета-тег' => '/main/MetaTagAdmin/create',
			'Логирование'       => '/main/logAdmin/manage',
			'Действия сайта'    => '/main/SiteActionAdmin/manage',
			'Обратная связь'    => '/main/feedbackAdmin/manage',
			'Языки'             => '/main/LanguageAdmin/manage',
			'Добавить язык'     => '/main/LanguageAdmin/create',
            'Настройки'         => '/main/SettingAdmin/manage',
		);
	}
}
