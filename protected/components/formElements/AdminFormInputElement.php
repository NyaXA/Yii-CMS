<?php
class AdminFormInputElement extends CFormInputElement
{
    public static $widgets = array(
        'alias'             => 'AliasField',
        'all_in_one_input'  => 'AllInOneInput',
        'multi_select'      => 'EMultiSelect',
        'date'              => 'FJuiDatePicker',
        'checkbox'          => 'IphoneCheckbox',
        'multi_autocomplete'=> 'MultiAutocomplete',
        'editor'            => 'TinyMCE',
        'autocomplete'      => 'zii.widgets.jui.CAutoComplete',
    );

    public static $default_widget_settings = array(
        'alias'             => array('class' => 'text'),
        'date'              => array(
            'options'  => array('dateFormat'=> 'd.m.yy'),
            'language' => 'ru'
        ),
        'autocomplete'      => array(
            'minChars'   => 2,
            'delay'      => 500,
            'matchCase'  => false,
            'htmlOptions'=> array(
                'size'  => '40',
                'class' => 'text'
            )
        )
    );

    public $widgets_folder = 'application.components.formElements';


    public function renderInput()
    {
        //set default settings
        $this->attributes = CMap::mergeArray(self::$default_widget_settings[$this->type], $this->attributes);

        if (isset(self::$widgets[$this->type]))
        {
            $this->type = $this->widgets_folder . str_repeat('.' . self::$widgets[$this->type], 2);
        }
        parent::renderInput();
    }
}