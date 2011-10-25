<?php
class FileList extends ListView
{
    public $model;
    public $tag;
    public $header = 'Файлы для скачивания';

    public $items;
    public $template = "{header}\n{items}";

    public $enablePagination = false;
    public $itemView ='fileManager.portlets.views.fileListItem';

    public $htmlOptions = array(
        'class' => 'file-list'
    );

    private $assets;

    public function init()
    {
        $this->dataProvider = new CActiveDataProvider('FileManager', array(
            'criteria' => FileManager::model()->parent(get_class($this->model), $this->model->id)
                ->tag($this->tag)->dbCriteria
        ));

        $this->assets  = Yii::app()->getModule('fileManager')->assetsUrl();
        $this->cssFile = $this->assets.'/css/filesList.css';

        parent::init();
    }

    public function renderHeader()
    {
        echo CHtml::tag('div', array('class' => 'header'), $this->header);
    }
}