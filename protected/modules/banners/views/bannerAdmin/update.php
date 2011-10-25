<?php

$this->tabs = array(
    'управление банерами' => $this->createUrl('manage'),
    'просмотр'            => $this->createUrl('view', array('id' => $form->model->id))
);

echo $form;