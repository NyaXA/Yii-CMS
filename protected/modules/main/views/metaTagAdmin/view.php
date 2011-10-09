<?php

$this->tabs = array(
    'управление мета-тегами' => $this->createUrl('manage'),
    'редактировать'          => $this->createUrl('update', array('id' => $model->id))
);

$model_id = $model->model_id;

$this->widget('application.components.DetailView', array(
	'data' => $model,
	'attributes' => array(
		array('name' => 'model_id', 'value' => $model_id::name()),
		array(
            'name'  => 'object_id',
            'label' => 'Объект',
            'value' => $model->object ? $model->object : null
        ),
		array('name' => 'tag', 'value' => MetaTag::$tags[$model->tag]),
		array('name' => 'static_value'),
		array('name' => 'dynamic_value'),
		array('name' => 'date_create'),
	),
)); 


