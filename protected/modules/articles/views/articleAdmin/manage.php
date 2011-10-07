<?php
$this->page_title = 'Управление материалами';

$this->tabs = array(
    "добавить" => $this->createUrl("create"),
    "управление разделами" => $this->createUrl("articleSectionAdmin/manage"),
);

$this->widget('GridView', array(
	'id'=>'articles-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template' => '{summary}<br/>{pager}<br/>{items}<br/>{pager}',
	'columns'=>array(
		'title',
        array(
            'name'  => 'section_id',
            'value' => '$data->section->name'
        ),
		'date',
        array('name' => 'lang', 'value' => '$data->language->name'),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); 
?>
