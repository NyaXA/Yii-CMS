<?php $class = get_class($this->model); ?>

<?php foreach (MetaTag::$tags as $tag): ?>
    <?php
        $meta_teg = MetaTag::getTag($this->model, $tag);
        $meta_teg = $meta_teg ? $meta_teg : MetaTag::model();
    ?>
    <?php echo CHtml::activeLabel($meta_teg, $tag); ?>
    <?php echo CHtml::activeTextField($meta_teg, 'static_value', array('name' => $class . '[meta_tags][' . $tag. ']', 'class' => 'text'));  ?>
    <br/><br/>
<?php endforeach ?>
