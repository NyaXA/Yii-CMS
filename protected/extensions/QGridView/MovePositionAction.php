<?php
class MovePositionAction extends CAction
{
    public function run()
    {
        $id = $this->getController()->getId();
        $modelClass = strtr($id, array('Controller'=>'', 'Admin'=>''));
        $model = new $modelClass;
        $model->model()->swapPosition($_POST['from'], $_POST['to']);
    }
    
}