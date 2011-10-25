<?php
class SortableColumn extends CDataColumn {
    public $cssClass = null;
    public $headerText = null;
    public $value = 3;
    public $assetsUrl;

    public function init()
    {
        parent::init();

        $this->assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.QGridView.assets'));
    }

    protected function renderHeaderCellContent()
    {
        if ($this->headerText != null){
            echo $this->headerText;
        } else {
            parent::renderHeaderCellContent();
        }
    }

    public function renderDataCellContent()
    {
        echo "<div class='positioner'><img src='" . $this->assetsUrl . "/img/hand.png' width='16'></div>";
    }
}