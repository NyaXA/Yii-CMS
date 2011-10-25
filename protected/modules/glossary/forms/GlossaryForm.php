<?php

return array(
	'activeForm' => array(
		'id'         => 'glossary-form',
		'class'      => 'CActiveForm',
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	),
	'elements' => array(
		'title' => array('type' => 'text', 'class' => 'big'),
		'text'  => array('type' => 'editor'),
		'state' => array('type' => 'dropdownlist', 'items' => Glossary::$states),
		'date'  => array('type' => 'date'),
	),
	'buttons' => array(
		'submit' => array('type' => 'submit', 'value' => $this->model->isNewRecord ? 'Создать' : 'Сохранить')
	)
);
