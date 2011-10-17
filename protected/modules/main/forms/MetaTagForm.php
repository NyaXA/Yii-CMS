<?php

$models = AppManager::getModels(array('meta_tags' => true));

if ($this->model->isNewRecord)
{
    $objects = array();
}
else
{
    $objects = array();
}

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
        'object_id' => array(
            'type'  => 'dropdownlist',
            'label' => 'Объект',
            'items' => $objects
        ),
        'tag' => array(
            'type'   => 'dropdownlist',
            'items'  => MetaTag::$tags,
            'prompt' => 'не выбрано'
        ),
        'static_value' => array('type' => 'textarea'),
        'dynamic_value' => array('type' => 'textarea'),
        'id' => array('type' => 'hidden')

    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'создать' : 'сохранить',
            'id'    => 'meta_tag_submit'
        )
    )
);


