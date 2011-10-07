<?php

$models = AppManager::getModels();

return array(
    'activeForm' => array(
        'id' => 'meta-tag-form',
		//'enableAjaxValidation' => true,
		//'clientOptions' => array(
		//	'validateOnSubmit' => true,
		//	'validateOnChange' => true
		//)
    ),
    'elements' => array(
        'model_id' => array(
            'type'   => 'dropdownlist',
            'items'  => $models,
            'prompt' => 'не выбрана'
        ),
        'object_id' => array('type' => 'dropdownlist', 'label' => 'Объект'),
        'tag' => array('type' => 'dropdownlist', 'items' => MetaTag::$tags),
        'static_value' => array('type' => 'textarea'),
        'dynamic_value' => array('type' => 'textarea'),

    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'создать' : 'сохранить')
    )
);


