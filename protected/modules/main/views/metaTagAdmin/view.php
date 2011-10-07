<?php

$this->tabs = array(
    'управление'    => $this->createUrl('manage'),
    'редактировать' => $this->createUrl('update', array('id' => $model->id))
);

$this->widget('application.components.DetailView', array(
	'data' => $model,
	'attributes' => array(
		array('name' => 'object_id'),
		array('name' => 'model_id'),
		array('name' => 'tag'),
		array('name' => 'static_value'),
		array('name' => 'dynamic_value'),
		array('name' => 'date_create'),
	),
)); 


