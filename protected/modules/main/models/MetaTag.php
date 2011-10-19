<?php

class MetaTag extends ActiveRecordModel
{
    const PAGE_SIZE = 10;


    public static $tags = array(
        'title',
        'description',
        'keywords'
    );


    public function name()
    {
        return 'Мета-теги';
    }


	public static function model($className=__CLASS__)
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
			array('model_id', 'required'),
            array('title, description, keywords', 'length', 'max' => 300),
			array('object_id', 'length', 'max' => 11),
			array('model_id', 'length', 'max' => 50),

			array('id, object_id, model_id,date_create', 'safe', 'on' => 'search'),
		);
	}


	public function relations()
	{
		return array();
	}


    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['meta_tags'] = 'Мета-теги';
        return $labels;
    }


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('title', $this->title, true);
		$criteria->compare('description', $this->description, true);
        $criteria->compare('keywords', $this->keywords, true);

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

        return $model::model()->findByPk($this->object_id);
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

        $html = "";

        $labels = $this->attributeLabels();

        foreach (self::$tags as $tag)
        {
            $html.= '<b>' . $labels[$tag] .'</b>' . ': '  . $meta_tag->$tag . '<br/><br/>';
        }

        return $html;
    }
}