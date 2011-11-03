<?php
class SortableAction extends CAction
{
    public function run()
    {
        $model = new $_POST['model'];
        $field = 'order'; //потом надо будет передавать еще и поле сортировки, если их будет несколько

        if (isset($_POST['pk']) && is_array($_POST['pk']))
        {
            //это что бы не париться со страницами
            $i = $model
                ->in('id', $_POST['pk'])
                ->max($field);

            if ($i == 0 || !is_numeric($i)) //если битое значение заполняем все айдишниками
            {
                $model->fillOrderColumn();
            }

            //надо оптимизировать этот цикл
            foreach ($_POST['pk'] as $item)
            {
                $obj         = $model->findByPk($item);
                $obj->$field = $i--;
                $obj->save();
            }
        }
    }
}