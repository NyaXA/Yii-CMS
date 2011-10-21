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
        $this->render('view', array(
            'list'  => News::model()->last()->active()->limit(5)->notEqual("id", $id)->findAll(),
            'model' => $this->loadModel($id, array('active'))
        ));
    }

    public function actionIndex()
    {
        $model         = new News;
        $data_provider = new ActiveDataProvider(get_class($model), array(
            'criteria' => $model->active()->ordered()->getDbCriteria()
        ));

        $this->render('index', array(
            'data_provider' => $data_provider
        ));
    }

    public function loadModel($value, $scopes = array(), $attribute = null)
    {
        return parent::loadModel('News', $value, $scopes, $attribute);
    }
}
