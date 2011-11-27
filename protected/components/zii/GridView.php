<?php
Yii::import("zii.widgets.grid.CGridView");
class GridView extends CGridView
{
    public $cssFile = null;

    public $filters;

    public $order_links = false;

    public $pager = array('class'=> 'LinkPager');

    public $buttons = null;

    public $sortable = false;

    public $mass_removal = false;

    public $jsPlugin = 'grid';

    public $template = '{pagerSelect}{summary}<br/>{pager}<br/>{items}<br/>{pager}';


    public function init()
    {
        $this->attachBehaviors($this->behaviors());
        parent::init();
        $this->formatDateValues();
    }

    public function behaviors()
    {
        return array();
    }

    public function formatDateValues()
    {
        $data = $this->dataProvider->data;
        foreach ($data as $item)
        {
            foreach ($item as $attr => $value)
            {
                if (Yii::app()->dater->isDbDate($value))
                {
                    if (in_array($value, array(
                        '0000-00-00 00:00:00',
                        '0000-00-00'
                    ))
                    )
                    {
                        $item->$attr = null;
                    }
                    else
                    {
                        $item->$attr = Yii::app()->dater->readableFormat($value);
                    }

                }
            }
        }

        $this->dataProvider->setData($data);
    }

    /**
     * Добавляет колонки перед последней колонкой
     *
     * @param $configs конфиги для колонок
     */
    public function addColumns($configs, $pos = 0)
    {
        $last_index = $pos >= 0 ? $pos : count($this->columns) + $pos;
        $configs[]  = $this->columns[$last_index];
        array_splice($this->columns, $last_index, 1, $configs);
    }

    /**
     * Добавляет колонку перед последней колонкой
     * @param $config конфиг колонки
     */
    public function addColumn($config, $pos = 0)
    {
        $last_index = $pos >= 0 ? $pos : count($this->columns) + $pos;
        $configs    = array(
            $config,
            $this->columns[$last_index]
        );
        array_splice($this->columns, $last_index, 1, $configs);
    }

    public function initColumns()
    {
        if ($this->sortable)
        {
            $this->addColumn(array(
                'class' => 'ext.QGridView.SortableColumn',
                'header'=> 'Сортировка'
            ), -1);
        }

        if ($this->order_links)
        {
            $this->addColumn(array(
                'class' => 'CDataColumn',
                'header'=> 'Порядок',
                'value' => 'GridView::orderLinks($data)',
                'type'  => 'raw'
            ), -1);
        }

        if ($this->mass_removal)
        {
            $this->addColumn(array(
                'class'               => 'CCheckBoxColumn',
                'header'              => "<input type='checkbox' class='object_checkboxes'>",
                'selectableRows'      => 2,
                'checkBoxHtmlOptions' => array(
                    'class'    => 'object_checkbox'
                )
            ));
        }
        parent::initColumns();
    }

    public static function orderLinks($data)
    {
        $class = get_class($data);

        return "<a href='/main/mainAdmin/changeOrder/id/{$data->id}/order/up/class/{$class}/from/" .
            base64_encode($_SERVER["REQUEST_URI"]) . "' />
                    <img src='/images/admin/icons/arrow_up.png' border='0' />
                </a>
                &nbsp;
                <a href='/main/mainAdmin/changeOrder/id/{$data->id}/order/down/class/{$class}/from/" .
            base64_encode($_SERVER["REQUEST_URI"]) . "' />
                    <img src='/images/admin/icons/arrow_down.png' border='0'  />
                </a>";
    }

    public function renderItems()
    {
        if ($this->dataProvider->getItemCount() > 0 || $this->showTableOnEmpty)
        {
            echo "<table class='{$this->itemsCssClass}' cellpadding='0' cellspacing='0' width='100%'>\n";
            $this->renderTableHeader();
            $this->renderTableBody();
            $this->renderTableFooter();
            echo "</table>";

            if ($this->mass_removal)
            {
                echo "<input type='submit' class='submit tiny red' value='удалить' id='mass_remove_button'>";
            }
        }
        else
        {
            $this->renderEmptyText();
        }
    }

    public function renderTableRow($row)
    {
        $data = $this->dataProvider->data[$row];

        if ($this->rowCssClassExpression !== null)
        {
            echo '<tr class="' . $this->evaluateExpression($this->rowCssClassExpression, array(
                'row' => $row,
                'data'=> $data
            )) . '">';
        }
        else if (is_array($this->rowCssClass) && ($n = count($this->rowCssClass)) > 0)
        {
            echo '<tr class="' . $this->rowCssClass[$row % $n] . '">';
        }
        else
        {
            echo '<tr>';
        }

        foreach ($this->columns as $column)
        {
            $column->renderDataCell($row);
        }

        echo "</tr>\n";
    }

    public function renderPagerSelect()
    {
        echo '<div class="pager-select">';
        $value = null;
        if (isset(Yii::app()->session[get_class($this->filter) . "PerPage"]))
        {
            $value = Yii::app()->session[get_class($this->filter) . "PerPage"];
        }

        $select = CHtml::dropDownList("pager_pages", $value, array_combine(range(10, 500, 5), range(10, 500, 5)), array(
            'class' => 'pager_select',
            'model' => get_class($this->filter)
        ));

        echo "&nbsp; &nbsp;Показывать на странице: {$select}";
        echo '</div>';
    }

    /**
     * Изначально регистрируются 2 плагина gridBase и grid
     * Если установить значение свойства jsPlugin, то подключится так же плагин /css/admin/gridview/{$this->jsPlugin}.js
     * И на сам grid будет
     * инициализироват плагин с названием из jsPlugin
     */
    public function registerClientScript()
    {
        parent::registerClientScript();
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('/js/plugins/gridview/gridBase.js');
        $cs->registerScriptFile("/js/plugins/gridview/grid.js");

        if ($this->jsPlugin != 'grid')
        {
            $cs->registerScriptFile("/js/plugins/gridview/{$this->jsPlugin}.js");
        }
        $options = CJavaScript::encode(array(
            'mass_removal' => $this->mass_removal
        ));
        $cs->registerScript($this->getId() . 'CmsUI', "
            $('#{$this->getId()}').{$this->jsPlugin}({$options});
        ");
    }
}
