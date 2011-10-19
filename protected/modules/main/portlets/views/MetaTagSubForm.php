<?php $class = get_class($this->model); ?>

<fieldset>
    <legend>Мета-теги:</legend>

    <?php foreach (MetaTag::$tags as $attr): ?>
        <?php echo CHtml::activeLabel($model, $attr); ?>
        <?php echo CHtml::activeTextField($model, $attr, array('name' => $class . '[meta_tags][' . $attr . ']', 'class' => 'text'));  ?>
        <br/><br/>
    <?php endforeach ?>

</fieldset>
