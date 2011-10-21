<?php
Yii::import('ext.QGridView.CQGridView');
class AdminGrid extends CQGridView
{
    public $template = '{summary}<br/>{pager}<br/>{items}<br/>{pager}';

    public $sortable = false;
    
    public function initColumns()
    {
        if ($this->sortable) {
            $this->addColumn(array(
                'class' => 'ext.QGridView.SortableColumn',
                'header'=> 'Сортировка'
            ));
        }

        parent::initColumns();
    }

    /**
     * Добавляет колонки перед последней колонкой
     * @param $configs конфиги для колонок
     */
    public function addColumns($configs)
    {
        $last_column = $this->columns[count($this->columns) - 1];

        foreach ($configs as $config)
        {
            $this->columns[count($this->columns) - 1] = $config;
        }
        $this->columns[] = $last_column;
    }

    /**
     * Добавляет колонку перед последней колонкой
     * @param $config конфиг колонки
     */
    public function addColumn($config)
    {
        $last_column = $this->columns[count($this->columns) - 1];
        $this->columns[count($this->columns) - 1] = $config;
        $this->columns[] = $last_column;
    }


}