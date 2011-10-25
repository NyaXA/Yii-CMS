<?php

function modelIdName($model)
{
    return $model::name();
}

$this->tabs = array(
    'добавить мета-тег' => $this->createUrl('create')
);

$this->widget('GridView', array(
	'id' => 'meta-tag-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array(
            'name'   => 'model_id',
            'value'  => 'modelIdName($data->model_id);',
            'filter' => false
        ),
		array(
            'name'   => 'object_id',
            'header' => 'Объект',
            'value'  => '$data->object',
            'filter' => false
        ),
		array('name' => 'title'),
        array('name' => 'description'),
        array('name' => 'keywords'),
		array(
            'name'   => 'date_create',
            'filter' => false
        ),
		array(
			'class' => 'CButtonColumn',
		),
	),
)); 

