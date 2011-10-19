<?php

class MetaTagBehavior extends CActiveRecordBehavior
{
    public function afterSave($event)
    {
        $model = $this->getOwner();

        $attrs = array(
            'object_id' => $model->id,
            'model_id'  => get_class($model)
        );

        $meta_tag = MetaTag::model()->findByAttributes($attrs);

        if (!$meta_tag)
        {
            $meta_tag = new MetaTag;
        }

        $meta_tag->attributes = array_merge($attrs, $model->meta_tags);
        $meta_tag->save();

        parent::afterSave($event);
    }



}
