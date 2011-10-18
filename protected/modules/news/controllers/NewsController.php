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
		$model = News::model(); 
		
		$news = $model->active()->findByAttributes(array(
			'id'    => $id
		));

		$news_list = $model->last()->active()->limit(5)->notEqual("id", $id)->findAll();

		$this->render('view', array(
			'list' => $news_list,
			'model'      => $news
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
