<?php

class NewsController extends BaseController
{
	public static function actionsTitles() 
	{
	    return array(
	        "View"  => "Просмотр новости",
	        "Index" => "Список новостей"
	    );
	}
	
	
	public function actionView($id) 
	{
		$model = News::model()->active()->findByPk($id);
        if (!$model)
        {
            $this->pageNotFound();
        }

		$this->render('view', array(
			'model' => $model
		));	
	}	

	
	public function actionIndex() 
	{
        $data_provider = new ActiveDataProvider('News');

		$this->render('index', array(
            'data_provider' => $data_provider
		));
	}
}
