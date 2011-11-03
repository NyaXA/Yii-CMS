<?php
$this->model->roles = array_keys(CHtml::listData($this->model->roles, 'name', 'description'));

return array(
    'activeForm' => array(
        'id' => 'banner-form',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
		//'enableAjaxValidation' => true,
		//'clientOptions' => array(
		//	'validateOnSubmit' => true,
		//	'validateOnChange' => true
		//)
    ),
    'elements' => array(
        'name' => array('type' => 'text'),
        'url' => array('type' => 'text'),
        'image' => array('type' => 'file'),
        'roles' => array(
            'type'  => 'application.extensions.emultiselect.EMultiSelect',
            'items' => CHtml::listData(AuthItem::model()->roles, 'name', 'description')
        ),
        'is_active' => array('type' => 'checkbox'),
        'date_start' => array('type' => 'date'),
        'date_end' => array('type' => 'date'),

    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'создать' : 'сохранить')
    )
);


