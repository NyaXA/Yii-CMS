<?php
/**
 *
 */
Yii::import('zii.widgets.jui.CJuiWidget');
class Uploader extends CJuiWidget
{
    public $model;
    public $id;

    public $dataType; //image, sound, video, document

    /*
    *  image: see http://www.verot.net/php_class_upload_samples.htm or comments in Resizer class
     * document: nothing yet
     * sound: nothing yet
     * video: nothing yet
    */
    public $options = array();
    public $params = array();

    public $tag;
    public $maxFileSize;

    public $setWatermark = false;

    public $uploadUrl;
    public $assets;
    private $allowType = array (
        'document'=>'js:/(\.|\/)(svg\+xml|doc|docx|txt|zip|rar|xml)$/i',
        'image'=>'js:/(\.|\/)(gif|jpeg|png|jpg|tiff)$/i',
        'sound'=>'js:/(\.|\/)(mp3|wav)$/i',
        'any'=>'js:/(\.|\/)(.*)$/i',
        'video'=>'js:/(\.|\/)(mp3|wav)$/i'
    );

    private static $isTemplatesRender = false;

    public function init()
    {
        parent::init();
        $this->initVars();
        $this->registerScripts();
    }

    public function initVars()
    {
        if ($this->id === null)
            throw new CException('Параметр id является обязательным');

        if ($this->model === null)
            throw new CException('Параметр model является обязательным');

        if (!in_array($this->dataType, array('image', 'sound','video','document', 'any'), true))
            throw new CException('Параметр dataType является обязательным  и может принемать значения: image, sound, video, document');

        if ($this->tag === null)
            throw new CException('Параметр tag является обязательным');

        $this->assets = Yii::app()->getModule('upload')->assetsUrl();

        //uploadUrl
        $this->uploadUrl = UploadHtml::url('files/upload', array(
            'typeParent' => get_class($this->model),
            'idParent' => $this->model->id,
            'dataType' => $this->dataType,
            'tag' => $this->tag,
            'options' => $this->options
        ));

        //options
        $default = array(
            'dropZone'                  => "js:$('#{$this->id}-drop-zone')",
            'maxFileSize'               => $this->maxFileSize,
            'acceptFileTypes'           => $this->allowType[$this->dataType],
//            'maxChunkSize'              => 1*1000*1000,
            'sortableSaveUrl'           => UploadHtml::url('files/savePriority'),
            'limitConcurrentUploads'    => 4,
            'existFilesUrl'             => UploadHtml::url('files/existFiles', array(
                                                'typeParent'    => get_class($this->model),
                                                'idParent'      => $this->model->id,
                                                'tag'           => $this->tag
                                            )),
        );
        $this->params = CMap::mergeArray($default, $this->params);
    }

    public function registerScripts()
    {
        $plugins = $this->assets.'/js/plugins/';
        Yii::app()->clientScript
            ->registerCoreScript('jquery.ui')
            ->registerScriptFile($plugins.'tmpl/jquery.tmpl.min.js')
            ->registerScriptFile($plugins.'jFileUpload/jquery.iframe-transport.js')
            ->registerScriptFile($plugins.'jFileUpload/jquery.fileupload.js')
            ->registerScriptFile($plugins.'jFileUpload/jquery.fileupload-ui.js')
            ->registerScriptFile($plugins.'jFileUpload/cmsUI.fileupload.js')
            ->registerCssFile   ($plugins.'jFileUpload/jquery.fileupload-ui.css')
            ->registerScriptFile($plugins.'jEditable/jquery.jeditable.js')
            ->registerScriptFile($plugins.'moderniz/moderniz.js');

        $params = CJavaScript::encode($this->params);

        Yii::app()->clientScript
            ->registerScript('uploader_'.$this->id, "$('#{$this->id}').fileupload({$params});");
    }

    public function run()
    {
        if (!self::$isTemplatesRender) {        //only single render
            $this->render('uploaderTemplates');
            self::$isTemplatesRender = true;
        }
        $this->render('uploader', array('uploadUrl'=> $this->uploadUrl));
    }

}

