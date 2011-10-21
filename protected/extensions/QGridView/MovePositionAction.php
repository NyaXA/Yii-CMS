<?php
class MovePositionAction extends CAction
{
    public $modelName;
    
    public function run()
    {
        $model = new $this->modelName;
        $model->model()->swapPosition($_POST['from'], $_POST['to']);
    }
    
}