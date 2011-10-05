<?php
$this->widget('GridView', array(
    'id' => 'fileManager-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'title',
        'name',
        'tag',
        'model_id',
        'object_id',
        'order'
    )
));