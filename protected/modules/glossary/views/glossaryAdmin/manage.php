<?php
$this->page_title = 'Управление статьями';

$this->tabs = array(
    "добавить статью" => $this->createUrl("create")
);

$this->widget('GridView', array(
	'id'=>'press-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template' => '{summary}<br/>{pager}<br/>{items}<br/>{pager}',
	'columns'=>array(
		'title',
		array('name' => 'state', 'value' => 'Glossary::$states[$data->state]'),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}'
		),
	),
)); 
?>
