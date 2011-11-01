<?php
class SortableColumn extends CDataColumn
{
    public $cssClass = null;
    public $headerText = null;
    public $value = 3;
    public $assetsUrl;
    public $movePositionBaseUrl;

    public function init()
    {
        parent::init();

        $this->assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.QGridView.assets'));
        $this->registerClientScript();
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript()
    {
        $cs = Yii::app()->getClientScript()->registerScriptFile(
            $this->assetsUrl.'/jui/jquery.ui.core.min.js', CClientScript::POS_END)->registerScriptFile(
            $this->assetsUrl.'/jui/jquery.ui.widget.min.js', CClientScript::POS_END)->registerScriptFile(
            $this->assetsUrl.'/jui/jquery.ui.mouse.min.js', CClientScript::POS_END)->registerScriptFile(
            $this->assetsUrl.'/jui/jquery.ui.droppable.min.js', CClientScript::POS_END)->registerScriptFile(
            $this->assetsUrl.'/jui/jquery.ui.draggable.min.js', CClientScript::POS_END);

        if (!$this->movePositionBaseUrl)
        {
            $baseUrl = Yii::app()->baseUrl.'/';
            $c       = Yii::app()->controller;
            if ($m = $c->module)
            {
                $baseUrl .= $m->id.'/';
            }
            $baseUrl .= $c->id;
        }
        else
        {
            $baseUrl = $this->movePositionBaseUrl;
        }
        $id = $this->grid->getId();

        $cs->registerScript('draganddrop', '
            $(document).ready(function()  {
               $("#'.$id.' tbody tr").live("mouseenter", function() {
	              var $this = $(this);
	              if($this.is(":data(draggable)")) return;
				  $this.draggable({
				      handle: "'.$id.'",
	                  helper: "clone",
	                  opacity: .75,
	                  refreshPositions: true, // Performance?
	                  revert: "invalid",
	                  revertDuration: 300,
	                  scroll: true
	              });
	           });
               $("#'.$id.' tbody tr").live("mouseenter", function() {
               		var $this = $(this);
               		if ($this.is(":data(droppable)")) return;
				    $(this).droppable({
	                    drop: function(e, ui) {
							$("#'.$id.'").addClass("grid-views-loading");
	                    	$.post(
		                    	"'.$baseUrl.'/movePosition",
								{
		                    		from : $(ui.draggable).attr("id").split("_", 2)[1],
		                    		to : $(this).attr("id").split("_", 2)[1]
		                    	},
		                    	function() {
		                    		$.fn.yiiGridView.update("'.$id.'");
		    					}
		                    );
	                    },
	  					hoverClass: "accept",
	               });
               });
            });
		');
    }


    protected function renderHeaderCellContent()
    {
        if ($this->headerText != null)
        {
            echo $this->headerText;
        }
        else
        {
            parent::renderHeaderCellContent();
        }
    }

    public function renderDataCellContent()
    {
        echo "<div class='positioner'><img src='".$this->assetsUrl."/img/hand.png' width='16'></div>";
    }
}