<?php

class GlossariesController extends BaseController
{
    public static function actionsTitles()
    {
        return array(
            "View"  => "Просмотр определения",
            "Index" => "Список определений"
        );
    }

    public function actionView($id)
    {
        $this->render('view', array(
            'model'=> $this->loadModel($id)
        ));
    }

    public function actionIndex()
    {
        $config = array(
            'criteria'        => Glossary::model()->active()->byTitle()->dbCriteria,
            'alphapagination' => array(
                'attribute'     => 'title',
                'pagination'    => array(
                    'pageSize' => Glossary::PAGE_SIZE,
                ),
                'charSet' => CMap::mergeArray(ApPagination::$alphabet['ru'], ApPagination::$alphabet['en']),
                'activeCharSet' => Glossary::noEmptyChars('title')
            )
        );

        $this->render('index', array(
            'dp' => new ApActiveDataProvider('Glossary', $config)
        ));
    }

    public function loadModel($id)
    {
        $model = Glossary::model()->findByPk((int) $id);
        if($model === null)
        {
            $this->pageNotFound();
        }

        return $model;
    }

}

