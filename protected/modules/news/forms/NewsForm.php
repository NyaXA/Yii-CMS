<?php

return array(
    'activeForm' => array(
        'id'         => 'news-form',
        'class'      => 'CActiveForm',
        'htmlOptions'=> array('enctype'=> 'multipart/form-data'),
    ),
    'elements'   => array(
        'title' => array(
            'type'  => 'text',
            'class' => 'big'
        ),
        'text'  => array('type' => 'application.extensions.tiny_mce.TinyMCE'),
        'photo' => array('type' => 'file'),
        'state' => array(
            'type'  => 'dropdownlist',
            'items' => News::$states
        ),
        'date'  => array('type' => 'date'),
        'meta'  => array(
            'type'   => 'widget',
            'widget' => 'MetaTagSubForm'
        ),
        'files' => array('type' => 'file_manager'),
        'roles' => array(
            'type'  => 'multi_select',
            'items' => array('1'=>1, '3'=>4)
        ),

    ),
    'buttons'    => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'Создать' : 'Сохранить'
        )
    )
);
