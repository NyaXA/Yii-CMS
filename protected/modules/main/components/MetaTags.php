<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artos
 * Date: 20.10.11
 * Time: 13:57
 * To change this template use File | Settings | File Templates.
 */
 
class MetaTags extends CApplicationComponent
{
    public function set(ActiveRecordModel $model)
    {
        $meta_tag = MetaTag::model()->findAllByAttributes(array(
            'object_id' => $model->id,
            'model_id'  => get_class($model)
        ));

        if ($meta_tag)
        {
            foreach ($meta_tag as $tag)
            {
                $attr = 'meta_'.$tag->tag;
                Yii::app()->controller->$attr = $tag->static_value;
            }
        }
    }
}
