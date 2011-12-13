<?php

class Menu extends ActiveRecordModel
{
    const PAGE_SIZE = 10;


    public function name()
    {
        return 'Меню';
    }


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'menu';
	}


	public function rules()
	{
		return array(
			array('name', 'required'),
			array('is_visible', 'numerical', 'integerOnly' => true),
			array('id', 'length', 'max' => 11),
			array('name', 'length', 'max' => 50),
            array('name', 'unique', 'className' => 'Menu', 'attributeName' => 'name'),
            array('id, name, is_visible', 'safe', 'on' => 'search'),
		);
	}


	public function relations()
	{
		return array(
			'links' => array(
			    self::HAS_MANY,
			    'MenuSection',
			    'menu_id',
			    'condition' => "lang = '" . Yii::app()->language . "'"
			),
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('is_visible', $this->is_visible);

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}


	public function getSections()
	{
        $root = MenuSection::model()->roots()->find('menu_id = ' . $this->id);

        $sections = $root->authObject()->children()->findAll();

		foreach ($sections as $i => $section)
		{
			if (!$section->is_visible)
			{
				unset($sections[$i]);
			}

			if ($section->page && !$section->page->is_published)
			{
				unset($sections[$i]);
            }
		}

		return $sections;
	}


    public function getCurrentSection()
    {
        foreach ($this->sections as $section)
        {
            if ($section->isActive())
            {
                return $section;
            }

            $childs = $section->children()->findAll();
            if ($childs)
            {
                foreach ($childs as $child)
                {
                    if ($child->isActive())
                    {
                        return $child;
                    }
                }
            }
        }
    }


    public function getPagePath($page_id)
    {
        $link = MenuSection::model()->findByAttributes(array(
            'page_id' => $page_id,
            'menu_id' => $this->id
        ));

        return $link->getPath();
    }
}
