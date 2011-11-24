
<?php
echo $form->renderBegin();

$model_class = get_class($form->model);
$elements    = $form->getElements();
?>

<?php foreach ($elements as $element): ?>
<?php
    $class = isset($element->attributes['class']) ? $element->attributes['class'] : '';

    $ext_class = '';

    switch ($element->type)
    {
        case 'text':
        case 'password':
            $ext_class = 'text';
            break;

        case 'date':
            $ext_class                       = 'text date_picker';
            $element->attributes['readonly'] = true;
            break;
    }

    if ($ext_class)
    {
        $class .= ' '.$ext_class;
    }

    $element->attributes['class'] = $class;

    $error = $element->renderError();
    ?>

<?php if ($element->type == 'hidden'): ?>
    <?php echo $element->renderInput(); ?>
    <?php else: ?>
    <?php
        $label = $element->label;
        if ($element->required)
        {
            $label .= '('.Yii::t('main', 'обязательное поле').')';
        }
        $element->attribute['data-label'] = $label;
        ?>

    <dl>
        <dd class="<?php echo $element->type ?>">
            <?php
            if ($element->type == 'date')
            {
                echo $form
                    ->getActiveFormWidget()
                    ->textField($form->model, $element->name, $element->attributes);
                $this->widget('application.extensions.calendar.SCalendar', array(
                    'inputField' => "{$model_class}_{$element->name}",
                    'ifFormat'   => '%d.%m.%Y',
                    'language'   => 'ru-UTF'
                ));
                echo $form
                    ->getActiveFormWidget()
                    ->error($form->model, $element->name);
            }
            elseif ($element->name == 'captcha')
            {
                $this->widget('application.extensions.recaptcha.EReCaptcha', array(
                    'model'      => $form->model,
                    'attribute'  => 'captcha',
                    'theme'      => 'red',
                    'language'   => 'ru_Ru',
                    'publicKey'  => '6LcsjsMSAAAAAG5GLiFpNi5R80_tg6v3NndjyuVh'
                ));
                echo $form
                    ->getActiveFormWidget()
                    ->error($form->model, 'captcha');
            }
            else
            {
                if ($error)
                {
                    echo $error;
                }
                echo $element->renderInput();

            }
            ?>
        </dd>
    </dl>
    <?php endif; ?>

<?php endforeach ?>

<dl>
    <dd>
        <?php echo $form->renderButtons(); ?>
    </dd>
</dl>

<?php echo $form->renderEnd(); ?>

<?php Yii::app()->clientScript->registerPackage('clientForm'); ?>

