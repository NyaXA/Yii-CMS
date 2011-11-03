<?php
class SortableBehavior extends CActiveRecordBehavior
{

    //заполняем айдишниками
    public function fillOrderColumn()
    {
        $model = $this->getOwner();
        $c     = Yii::app()->db->commandBuilder->createSqlCommand(
            'update '.$model->tableName().' as t set t.order = t.id');
        $c->execute();
    }

    public function setPositions($ids, $table, $criteria = null)
    {
        $criteria = $criteria ? $criteria : new CDbCriteria();
        $owner    = $this->getOwner();
        $pk       = $owner->primaryKey();

        //last id have 0 priority => revers => first id have 0 priority => flip => every id have their priority
        $priorities = array_flip(array_reverse($ids));
        $data       = array('priority' => Sql::arrToCase($pk, $priorities));

        $c = Yii::app()->db->commandBuilder->createUpdateCommand($table, $data, $criteria);

        Y::dump($c->execute());
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