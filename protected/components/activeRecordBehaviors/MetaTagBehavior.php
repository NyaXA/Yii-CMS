<?php

class MetaTagBehavior extends CActiveRecordBehavior
{
    public $meta_tags;

    public function afterSave($event)
    {
        $model = $this->getOwner();
        $attrs = array(
            'object_id' => $model->id,
            'model_id'  => get_class($model)
        );
        foreach ($model->meta_tags as $key=> $val)
        {
            $attrs['tag'] = $key;

            $meta_tag     = MetaTag::model()->findByAttributes($attrs);

            if (!$meta_tag)
            {
                $meta_tag = new MetaTag;
            }

            $meta_tag->attributes   = $attrs;
            $meta_tag->static_value = $val;
            $meta_tag->save();
        }


        parent::afterSave($event);
    }


}
