<?php
class SortableBehavior extends CActiveRecordBehavior
{
    //заполняем айдишниками
    public function fillOrderColumn()
    {
        $model = $this->getOwner();
        $c     = Yii::app()->db->commandBuilder->createSqlCommand(
            'UPDATE '.$model->tableName().' AS t SET t.order = t.id');
        $c->execute();
    }

    public function setPositions($ids, $column, $start)
    {
        $model = $this->getOwner();
        $table = $model->tableName();

        $priorities = array();
        foreach ($ids as $id)
        {
            $priorities[$id] = $start--;
        }

        $case = $this->arrToCase('id', $priorities, $model->getTableAlias());

        $c = Yii::app()->db->commandBuilder->createSqlCommand("UPDATE {$table} AS t SET t.{$column} = {$case}");
        $c->execute();
    }

    public function arrToCase($caseParam, $values, $alias)
    {
        $case = "case $alias.$caseParam ";
        foreach ($values as $key => $val)
        {
            $case .= "when $key then ".$val.' ';
        }
        $case .= 'end';

        return $case;
    }

    public function setDefaultPriority()
    {
        $owner           = $this->getOwner();
        $model           = $owner
            ->model()
            ->mostPriority()
            ->find();
        $owner->priority = $model->priority + 1;
    }


}