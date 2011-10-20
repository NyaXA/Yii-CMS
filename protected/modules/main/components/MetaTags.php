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
        $meta_tag = MetaTag::model()->findByAttributes(array(
            'object_id' => $model->id,
            'model_id'  => get_class($model)
        ));

        if ($meta_tag)
        {
            $tags = MetaTag::$tags;
            foreach ($tags as $tag)
            {
                $attr = 'meta_' . $tag;
                Yii::app()->controller->$attr = $meta_tag->$tag;
            }
        }
    }
}
