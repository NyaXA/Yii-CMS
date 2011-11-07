<?php
class FileManagerAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "UpdateAttr"   => "Редактирование файла",
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
            $object_id = 'tmp_' . Yii::app()->user->id;
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
            $object_id = 'tmp_' . Yii::app()->user->id;
        }

        $model = new FileManager('insert');
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
                'name'          => $file->title,
                'size'          => $file->size,
                'url'           => $file->src,
                'thumbnail_url' => $file->src,
                'delete_url'    => $file->deleteUrl,
                'delete_type'   => "GET",
                'edit_link'     => UploadHtml::editableLink('Редактировать', $file, 'title', 'fileManagerAdmin/updateAttr', array('class'=>'thumb-edit')),
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
        $success = false;

        $ids = array_reverse($_POST['File']);

        $files = new FileManager('sort');

        $transaction = $files->dbConnection->beginTransaction();

        try
        {   $i = 0;

            foreach($ids as $id)
            {
                $i++;
                $success = $files->updateAll(array('order'=>$i), 'id='.$id) || $success;
            }
            if (!$success)
            {
                throw new CException('Some error ☺');
            }

            $transaction->commit();
        }
        catch(Exception $e)
        {
            $transaction->rollBack();
        }
    }


    public function actionDelete()
    {
        $model = $this->loadModel()->delete();
    }


    public function actionUpdateAttr()
    {
        $model = $this->loadModel();
        
        $model->scenario = 'update';

        $this->performAjaxValidation($model);

        if (isset($_POST[get_class($model)]))
        {
            $attr = $_GET['attr'];
            $model->$attr = $_POST[get_class($model)][$attr];
            
            if ($model->save())
            {
                echo $model->$_GET['attr'];
            }
        }
    }

    public function loadModel()
    {
        if(isset($_GET['id']))
        {
            $condition = '';

            $model = FileManager::model()->findByPk($_GET['id'], $condition);
        }

        if($model === null)
        {
            throw new CHttpException(404,'The requested page does not exist.');
        }

		return $model;
    }


    public function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']))
        {
            print_r(CActiveForm::validate($model));
            Yii::app()->end();
        }
    }


    public function actionManage()
    {
		$model = new FileManager('search');
		$model->unsetAttributes();
		if(isset($_GET['FileManager']))
        {
            $model->attributes = $_GET['FileManager'];
        }

        $this->render('manage', array('model' => $model));
    }
}