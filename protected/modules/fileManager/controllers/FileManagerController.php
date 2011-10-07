<?php

class FileManagerController extends BaseController
{
    public static function actionsTitles()
    {
        return array(
            "DownloadFile" => "Скачать файл"
        );
    }


    public function actionDownloadFile($id)
    {
        $model = FileManager::model()->findByPk($id);
        if (!$model || !file_exists($model->path))
        {
            $this->pageNotFound();
        }

        $this->request->sendFile($model->title, $model->content);
    }
}
