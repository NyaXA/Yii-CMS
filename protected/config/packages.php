<?php
return array(
    'debug'      => array(
        'baseUrl' => 'js/packages/debug/',
        'js'      => array(
            'debug.js',
        ),
        'depends' => array('jquery')
    ),
    'clientForm' => array(
        'baseUrl' => 'js/packages/clientForm/',
        'js'      => array(
            'grewForm/grewForm.js',
            'tipInput/tipinput.js',
            'clientForm.js',
        ),
        'depends' => array('jquery')
    ),
    'adminForm'  => array(
        'baseUrl' => 'js/admin/',
        'js'      => array(
            'admin_form.js'
        ),
        'depends' => array('jquery')
    )
);

