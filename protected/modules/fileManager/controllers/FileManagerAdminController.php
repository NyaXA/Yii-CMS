<?php
class FileManagerAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "Upload"       => "Загрузка файлов",
            "SavePriority" => "Сортировка",
            "Delete"       => "Удаление файла",
            "ExistFiles"   => "Загрузка существующих файлов",
            "Manage"       => "Управление файлами"
        );
    }


    public function actionExistFiles($model_id, $object_id, $tag)
    {
        if ($object_id == 0)
        {
            $object_id = 'tmp_'.Yii::app()->user->id;
        }

        $existFiles = FileManager::model()
            ->parent($model_id, $object_id)
            ->tag($tag)
            ->findAll();
        $this->sendFilesAsJson($existFiles);
    }


    public function actionUpload($model_id, $object_id, $data_type, $tag)
    {
        if ($object_id == 0)
        {
            $object_id = 'tmp_'.Yii::app()->user->id;
        }

        $model            = new FileManager('insert');
        $model->object_id = $object_id;
        $model->model_id  = $model_id;
        $model->tag       = $tag;

        $options = isset($_GET['options']) ? $_GET['options'] : array();

        if ($model->saveFile())
        {
            if ($model->save())
            {
                $this->sendFilesAsJson(array($model));
            }
            else
            {
                echo $model->error;
            }
        }
        else
        {
            echo json_encode(array(
                'textStatus' => $model->error
            ));
        }
    }

    private function sendFilesAsJson($files)
    {
        $res = array();
        foreach ((array)$files as $file)
        {
            $res[] = array(
                'title'         => $file->title,
                'descr'         => $file->descr,
                'size'          => $file->size,
                'url'           => $file->src,
                'thumbnail_url' => $file->src,
                'delete_url'    => $file->deleteUrl,
                'delete_type'   => "GET",
                'edit_link'     => CHtml::link('<img width="16" height="16" src="/images/admin/editable.gif" style="display: inline-block;" />', $this->createUrl('/main/helpAdmin/saveAttribute'), array('class'=> 'thumb-edit')),
                'id'            => 'File_'.$file->id
            );
        }

        //        header('Vary: Accept');
        //        if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false))
        //        {
        //            header('Content-type: application/json');
        //        }
        //        else
        //        {
        //            header('Content-type: text/plain');
        //        }

        echo CJSON::encode($res);
    }


    public function actionSavePriority()
    {
        $ids = array_reverse($_POST['File']);

        $files = new FileManager('sort');

        $case = SqlHelper::arrToCase('id', array_flip($ids), 't');
        $arr  = implode(',', $ids);
        $c    = Yii::app()->db->commandBuilder->createSqlCommand("UPDATE {$files->tableName()} AS t SET t.order = {$case} WHERE t.id IN ({$arr})");
        $c->execute();
    }


    public function actionDelete()
    {
        $this
            ->loadModel()
            ->delete();
    }

    public function loadModel()
    {
        if (isset($_GET['id']))
        {
            $condition = '';

            $model = FileManager::model()
                ->findByPk($_GET['id'], $condition);
        }

        if ($model === null)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }


    public function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']))
        {
            print_r(CActiveForm::validate($model));
            Yii::app()
                ->end();
        }
    }


    public function actionManage()
    {
        $model = new FileManager('search');
        $model->unsetAttributes();
        if (isset($_GET['FileManager']))
        {
            $model->attributes = $_GET['FileManager'];
        }

        $this->render('manage', array('model' => $model));
    }
}