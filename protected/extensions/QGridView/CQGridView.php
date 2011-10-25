<?php
/**
 * CTreeGridView class file.
 *
 * Used:
 * YiiExt - http://code.google.com/p/yiiext/
 * treeTable - http://plugins.jquery.com/project/treeTable
 * jQuery ui - http://jqueryui.com/
 *
 * @author quantum13
 * @link http://quantum13.ru/
 */


Yii::import('zii.widgets.grid.CGridView');


class CQGridView extends GridView
{

    public $baseTreeTableUrl;

    public $assetsUrl;

    public $handle;

    public $movePositionBaseUrl;
    
    public function init()
    {
		parent::init();
        $this->assetsUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.QGridView.assets'));
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript()
    {
        parent::registerClientScript();

        $cs=Yii::app()->getClientScript()
            ->registerScriptFile($this->assetsUrl.'/jui/jquery.ui.core.min.js',CClientScript::POS_END)
            ->registerScriptFile($this->assetsUrl.'/jui/jquery.ui.widget.min.js',CClientScript::POS_END)
            ->registerScriptFile($this->assetsUrl.'/jui/jquery.ui.mouse.min.js',CClientScript::POS_END)
            ->registerScriptFile($this->assetsUrl.'/jui/jquery.ui.droppable.min.js',CClientScript::POS_END)
            ->registerScriptFile($this->assetsUrl.'/jui/jquery.ui.draggable.min.js',CClientScript::POS_END);

        if (!$this->movePositionBaseUrl) {
            $baseUrl = Yii::app()->baseUrl.'/';
            $c = Yii::app()->controller;
            if ($m = $c->module)
                $baseUrl .= $m->id.'/';
            $baseUrl .= $c->id;
        } else {
            $baseUrl = $this->movePositionBaseUrl;
        }

        $cs->registerScript('draganddrop', '
            $(document).ready(function()  {
               $("#'.$this->getId().' tbody tr").live("mouseenter", function() {
	              var $this = $(this);
	              if($this.is(":data(draggable)")) return;
				  $this.draggable({
				      handle: "'.$this->handle.'",
	                  helper: "clone",
	                  opacity: .75,
	                  refreshPositions: true, // Performance?
	                  revert: "invalid",
	                  revertDuration: 300,
	                  scroll: true
	              });
	           });
               $("#'.$this->getId().' tbody tr").live("mouseenter", function() {
               		var $this = $(this);
               		if ($this.is(":data(droppable)")) return;
				    $(this).droppable({
	                    drop: function(e, ui) {
							$("#'.$this->getId().'").addClass("grid-views-loading");
	                    	$.post(
		                    	"'.$baseUrl.'/movePosition",
								{
		                    		from : $(ui.draggable).attr("id").split("_", 2)[1],
		                    		to : $(this).attr("id").split("_", 2)[1]
		                    	},
		                    	function() {
		                    		$.fn.yiiGridView.update("'.$this->getId().'");
		    					}
		                    );
	                    },
	  					hoverClass: "accept",
	               });
               });
            });
		');
    }
    
    public function renderTableRow($row)
    {
		$data=$this->dataProvider->data[$row];
		$pk = $this->getId().'_'.$data->id;
		if ($this->rowCssClassExpression!==null) {
    		echo '<tr class="'.$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data)).'" id="'.$pk.'">';
		} else if (is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
			echo '<tr class="'.$this->rowCssClass[$row%$n].'"  id="'.$pk.'">';
		else
			echo '<tr id="'.$pk.'">';
		foreach ($this->columns as $column)
			$column->renderDataCell($row);
		echo "</tr>\n";
    }
    
}
