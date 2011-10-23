<?php $class = get_class($this->model); ?>

<?php foreach ($meta_tags as $tag): ?>
    <?php echo CHtml::activeLabel($tag, 'title'); ?>
    <?php echo CHtml::activeTextField($tag, 'static_value', array('name' => $class . '[meta_tags][' . $tag->tag. ']', 'class' => 'text'));  ?>
    <br/><br/>
<?php endforeach ?>
