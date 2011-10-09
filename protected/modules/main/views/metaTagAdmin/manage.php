<?php

function modelIdName($model)
{
    return $model::name();
}

$this->tabs = array(
    'добавить мета-тег' => $this->createUrl('create')
);

$this->widget('application.components.GridView', array(
	'id' => 'meta-tag-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array(
            'name'  => 'model_id',
            'value' => 'modelIdName($data->model_id);'
        ),
		array(
            'name'   => 'object_id',
            'header' => 'Объект',
            'value'  => '$data->object'
        ),
		array(
            'name'  => 'tag',
            'value' => 'MetaTag::$tags[$data->tag]'
        ),
		array('name' => 'static_value'),
		array('name' => 'dynamic_value'),
		array('name' => 'date_create'),
		array(
			'class' => 'CButtonColumn',
		),
	),
)); 

