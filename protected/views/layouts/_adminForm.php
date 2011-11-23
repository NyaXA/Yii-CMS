<?php
echo $form->getActiveFormWidget()->labelEx($form->model, $element->name);

if ($element->type == 'date')
{
    echo $form->getActiveFormWidget()->textField($form->model, $element->name, $element->attributes);
    $this->widget('application.extensions.calendar.SCalendar', array(
        'inputField' => "{$model_class}_{$element->name}",
        'ifFormat'   => '%d.%m.%Y',
        'language'   => 'ru-UTF'
    ));
}
elseif ($element->type == 'editor')
{
    echo $form->getActiveFormWidget()->labelEx($form->model, $element->name);
    $this->widget('application.extensions.tiny_mce.TinyMCE', array(
        //                'editorTemplate' => 'full',
        'model'     => $form->model,
        'attribute' => $element->name,
    ));
    echo $form->getActiveFormWidget()->error($form->model, $element->name);
}
elseif ($element->type == 'multi_select')
{
    $this->widget('application.extensions.emultiselect.EMultiSelect');
    echo $form->getActiveFormWidget()->dropdownlist($form->model, $element->name, $element->items, array(
        'multiple' => 'multiple',
        'key'      => $element->name,
        'class'    => 'multiselect'
    ));
}
elseif ($element->type == 'autocomplete')
{
    $this->widget('CAutoComplete', array(
        'name'       => $element->name,
        'attribute'  => $element->name,
        'model'      => $form->model,
        'url'        => array($element->url),
        'minChars'   => 2,
        'delay'      => 500,
        'matchCase'  => false,
        'htmlOptions'=> array(
            'size'  => '40',
            'class' => 'text'
        )
    ));

}
elseif ($element->type == 'file_manager')
{
    ?>

<fieldset>
    <legend>Файлы:</legend>

    <?php
    $this->widget('fileManager.portlets.Uploader', array(
        'model'       => $form->model,
        'id'          => 'uploader',
        'data_type'   => 'any',
        'maxFileSize' => 10 * 1000 * 1000,
        'tag'         => 'files'
    ));
    ?>
</fieldset>

<?php
}
else
{
    echo $element->renderInput();
}
echo $form->getActiveFormWidget()->error($form->model, $element->name);
?>


