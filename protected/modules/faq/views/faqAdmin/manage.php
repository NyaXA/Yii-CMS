<?php
$this->page_title = 'Управление вопросами';

$this->tabs = array(
    "добавить" => $this->createUrl("create"),
);

$this->widget('GridView', array(
	'id'=>'faq-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'first_name',
        'last_name',
        'patronymic',
		array('name' => 'section_id', 'value' => '$data->section->name'),
		array('name' => 'question', 'type' => 'raw'),
		'email',
		array('name' => 'is_published', 'value' => '$data->is_published ? \'Да\' : \'Нет\''),
        array('name' => 'lang', 'value' => '$data->language->name'),		
		array(
			'class' => 'CButtonColumn',
		),
	),
));
?>
