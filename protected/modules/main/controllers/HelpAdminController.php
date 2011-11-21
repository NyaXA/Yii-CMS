<?php

/*
 * Контроллер для всяких вспомогательных функций,
 * экшены можно добавить в него один раз, вместо того, что бы добавлять их во все контроллеры
 */
class HelpAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'Sortable'      => 'Изменение позиции',
            'SaveAttribute' => 'Сохранение Атрибута'
        );
    }

    public function actions()
    {
        return array(
            'sortable' => array(
                'class' => 'application.components.zii.sortable.SortableAction',
            )
        );
    }

    /**
     * сохраняет 1 атрибут модели, все параметры передаются через POST
     */
    public function actionSaveAttribute()
    {
        $model = call_user_func($_POST['model'].'::model')->findByPk($_POST['id']);
        if (isset($_POST['attributes']))
        {
            $model->attributes = $_POST['attributes'];
        }
        $attribute         = $_POST['attribute'];
        $model->$attribute = $_POST['value'];

        if ($model->save(true, array($attribute)))
        {
            echo $model->$attribute;
        }
        else
        {
            echo $model->getError($attribute);
        }
    }
}
