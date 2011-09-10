<?php

return array(
	'activeForm' => array(
		'id'          => 'news-file-form',
		'class'       => 'CActiveForm',
		'htmlOptions' => array('enctype'=>'multipart/form-data'),
	),
	'elements' => array(
		'title'   => array('type' => 'text'),
		'file'    => array('type' => 'file'),
		'news_id' => array('type' => 'hidden')
	),
	'buttons' => array(
		'submit' => array('type' => 'submit', 'value' => $this->model->isNewRecord ? 'Создать' : 'Сохранить')
	)	
);