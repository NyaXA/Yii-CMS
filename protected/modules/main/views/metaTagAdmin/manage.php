<?php

$this->tabs = array(
    'добавить мета-тег' => $this->createUrl('create')
);

$this->widget('application.components.GridView', array(
	'id' => 'meta-tag-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array('name' => 'object_id'),
		array('name' => 'model_id'),
		array('name' => 'tag'),
		array('name' => 'static_value'),
		array('name' => 'dynamic_value'),
		array('name' => 'date_create'),
		array(
			'class' => 'CButtonColumn',
		),
	),
)); 

