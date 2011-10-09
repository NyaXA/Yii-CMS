<?php

class MetaTag extends ActiveRecordModel
{
    const PAGE_SIZE = 10;

    const TAG_TITLE       = 'title';
    const TAG_KEYWORDS    = 'keywords';
    const TAG_DESCRIPTION = 'description';


    public static $tags = array(
        self::TAG_TITLE       => 'заголовок (title)',
        self::TAG_KEYWORDS    => 'ключевые слова (keywords)',
        self::TAG_DESCRIPTION => 'Описание (description)'
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
			array('model_id, tag', 'required'),
			array('object_id, tag', 'length', 'max' => 11),
			array('model_id', 'length', 'max' => 50),
			array('static_value, dynamic_value', 'length', 'max' => 500),

			array('id, object_id, model_id, tag, static_value, dynamic_value, date_create', 'safe', 'on' => 'search'),
		);
	}


	public function relations()
	{
		return array();
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('object_id', $this->object_id, true);
		$criteria->compare('model_id', $this->model_id, true);
		$criteria->compare('tag', $this->tag, true);
		$criteria->compare('static_value', $this->static_value, true);
		$criteria->compare('dynamic_value', $this->dynamic_value, true);
		$criteria->compare('date_create', $this->date_create, true);

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
}