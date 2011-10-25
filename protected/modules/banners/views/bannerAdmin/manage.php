<?php

$this->tabs = array(
    'добавить' => $this->createUrl('create')
);

$this->widget('GridView', array(
	'id' => 'banner-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array('name' => 'name'),
		array('name' => 'url'),
		array('name' => 'is_active', 'value' => '$data->is_active ? "Да" : "Нет"'),
		array('name' => 'date_start'),
		array('name' => 'date_end'),
		array(
			'class' => 'CButtonColumn',
		),
	),
)); 

