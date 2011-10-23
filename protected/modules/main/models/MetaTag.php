<?php

class MetaTag extends ActiveRecordModel
{
    const PAGE_SIZE = 10;

    public $title;
    public $description;
    public $keywords;

    public static $tags = array(
        'title',
        'description',
        'keywords'
    );


    public function name()
    {
        return 'Мета-теги';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'meta_tags';
    }


    public function rules()
    {
        return array(
            array(
                'model_id',
                'required'
            ),
//            array('title, description, keywords', 'length', 'max' => 300),
            array(
                'object_id',
                'length',
                'max' => 11
            ),
            array(
                'model_id',
                'length',
                'max' => 50
            ),
            array(
                'static_value, tag',
                'length',
                'max' => 300
            ),
            array(
                'id, object_id, model_id, date_create',
                'safe',
                'on' => 'search'
            ),
        );
    }


    public function relations()
    {
        return array();
    }


    public function attributeLabels()
    {
        $labels              = parent::attributeLabels();
        $labels['meta_tags'] = 'Мета-теги';
        return $labels;
    }


    public function search()
    {
        $alias    = $this->getTableAlias();
        $criteria = new CDbCriteria;

        //		$criteria->compare($alias.'.title', $this->title, true);
        //		$criteria->compare($alias.'.description', $this->description, true);
        //        $criteria->compare($alias.'.keywords', $this->keywords, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria' => $criteria
        ));
    }


    public function getObject()
    {
        if (!$this->object_id)
        {
            return;
        }

        $model = $this->model_id;
        $model = new $model;
        return $model->model()->findByPk($this->object_id);
    }


    public function html($object_id, $model_id)
    {
        $meta_tag = $this->findByAttributes(array(
            'object_id' => $object_id,
            'model_id'  => $model_id
        ));

        if (!$meta_tag)
        {
            return;
        }

        $html   = "";

        $labels = $this->attributeLabels();

        foreach (self::$tags as $tag)
        {
            $html .= '<b>'.$labels[$tag].'</b>'.': '.$meta_tag->$tag.'<br/><br/>';
        }

        return $html;
    }

    public function parent($model)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.model_id="'.get_class($model).'" AND '.$alias.'.object_id='.$model->id
        ));
        return $this;
    }


    public function tag($tag)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.tag="'.$tag.'"'
        ));
        return $this;
    }

    public static function getTag($model, $tag)
    {
        return self::model()->parent($model)->tag($tag)->find();
    }
}