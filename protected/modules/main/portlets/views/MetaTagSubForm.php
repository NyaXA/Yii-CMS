<?php $class = get_class($this->model); ?>

<?php foreach (MetaTag::$tags as $tag): ?>
    <?php $meta_model = MetaTag::getTag($this->model, $tag) ?>
    <?php echo CHtml::activeLabel($meta_model, $tag); ?>
    <?php echo CHtml::activeTextField($meta_model, 'static_value', array('name' => $class . '[meta_tags][' . $tag. ']', 'class' => 'text'));  ?>
    <br/><br/>
<?php endforeach ?>
