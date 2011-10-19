<?php

class Glossary extends ActiveRecordModel
{
    const STATE_ACTIVE = 'active';
    const STATE_HIDDEN = 'hidden';

    const PHOTOS_DIR = 'uploads/glossary';

    const PHOTO_SMALL_WIDTH = "80";
    const PHOTO_SMALL_HEIGHT = "80";

    const PHOTO_BIG_WIDTH = "450";

    const PAGE_SIZE = 20;

    public static $states = array(
        self::STATE_ACTIVE => 'Активна',
        self::STATE_HIDDEN => 'Скрыта'
    );


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'glossary';
    }

    public function name()
    {
        return 'Глоссарий';
    }

    public function scopes()
    {
        $alias = $this->getTableAlias();
        return CMap::mergeArray(parent::scopes(), array(
            'byTitle' => array('order' => $alias.'.title ASC'),
            'active'  => array('condition' => $alias.".state = '".self::STATE_ACTIVE."'")
        ));
    }


    public function rules()
    {
        return array(
            array(
                'user_id, title, text, state, lang',
                'required'
            ),
            array(
                'title',
                'length',
                'max' => 250
            ),
            array(
                'state',
                'length',
                'max' => 6
            ),
            array(
                'id, user_id, title, text, photo, state, date, date_create',
                'safe',
                'on' => 'search'
            ),
        );
    }

    public function relations()
    {
        return array(
            'user'     => array(
                self::BELONGS_TO,
                'User',
                'user_id'
            ),
            'language' => array(
                self::BELONGS_TO,
                'Language',
                'lang'
            )
        );
    }

    // Add those characters to an array and assign them to activeCharSet
    public static function noEmptyChars($field)
    {
        $model       = new Glossary;
        $chars       = (array)$model->findAll(array(
            'select'=> "DISTINCT(
                        UPPER(
                            LEFT(`$field`, 1)
                        )
                     ) AS `title`"
        ));
        $activeChars = array();
        foreach ($chars as $char)
        {
            $activeChars[] = $char->title;
        }

        return $activeChars;
    }

    public function search()
    {
        $alias = $this->getTableAlias();
        $criteria = new CDbCriteria;

        $criteria->compare($alias.'.id', $this->id, true);
        $criteria->compare($alias.'.user_id', $this->user_id, true);
        $criteria->compare($alias.'.title', $this->title, true);
        $criteria->compare($alias.'.text', $this->text, true);
        $criteria->compare($alias.'.photo', $this->photo);
        $criteria->compare($alias.'.state', $this->state, true);
        $criteria->compare($alias.'.date', $this->date, true);
        $criteria->compare($alias.'.date_create', $this->date_create, true);

        $criteria->order = $alias.'.title ASC';

        $page_size = 10;
        if (isset(Yii::app()->session[get_class($this)."PerPage"]))
        {
            $page_size = Yii::app()->session[get_class($this)."PerPage"];
        }

        return new CActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' => array(
                'pageSize' => $page_size
            )
        ));
    }


    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if (!$this->date)
            {
                $this->date = date("Y-m-d");
            }

            return true;
        }
        return false;
    }

    public function getContent()
    {
        if (Yii::app()->controller->checkAccess('GlossariesAdmin_Update'))
        {
            $this->text .= "<br/> <a href='/glossary/glossariesAdmin/update/id/{$this->id}' class='admin_link'>Редактировать</a>";
        }

        return $this->text;
    }

    public function getUrl()
    {
        return Yii::app()->controller->url("/glossary/glossaries/view/", array('id' => $this->id));
    }

    public static function mainUrl()
    {
        return Yii::app()->controller->url("/glossary/glossaries");
    }


}
