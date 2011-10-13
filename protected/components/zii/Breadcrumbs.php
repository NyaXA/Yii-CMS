<?php
Yii::import('zii.widgets.CBreadcrumbs');
class Breadcrumbs extends CBreadcrumbs
{
    public $homeLink = false;
    public $htmlOptions=array('id'=>'breadcrumb_links');
    public $separator=' <span>/</span> ';

    public function run()
    {
        echo '<div id="breadcrumb">';
        parent::run();
        echo '</div>';
    }
}

