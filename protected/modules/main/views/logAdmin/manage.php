<?php $this->page_title = 'Просмотр логов'; ?>

<?php
$this->widget('GridView', array(
	'id' => 'grid',
	'dataProvider' => $model->search(),
    'template' => '{summary}<br/>{pager}<br/>{items}<br/>{pager}',
    'filter' => $model,
	'columns' => array(
		array('name' => 'message'),
		'level',
        array('name' => 'logtime', 'filter' => false),
        array(
            'class'    => 'CButtonColumn',
            'template' => ''
        )
	),
));
?>