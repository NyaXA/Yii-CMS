<?php

class FileManager extends ActiveRecordModel
{
    const UPLOAD_PATH = './upload/fileManager/';
    const FILE_POSTFIX = '';

    public $extension;

    public $size;

    public $error;


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'file_manager';
    }


    public function primaryKey()
    {
        return 'id';
    }

    public function rules()
    {
        return array(
            array(
                'nameWithoutExt',
                'length', 'min' => 1, 'max' => 900, 'tooShort' => 'Название файла должно быть меньше 1 сим.', 'tooLong' => 'Пожалуйста, сократите наименование файла до 900 сим.'
            )
        );
    }

    public function behaviors()
    {
        return array(
            'sortable' => array(
                'class' => 'application.components.activeRecordBehaviors.SortableBehavior'
            )
        );
    }

    public function parent($type, $id)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias . '.model_id="' . $_GET['model_id'] . '" AND ' . $alias . '.object_id=' . $_GET['object_id'],
            'order'     => $alias . '.order DESC'
        ));
        return $this;
    }

    public function tag($tag)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
                                               'condition' => $alias . '.tag="' . $tag . '"'
                                          ));
        return $this;
    }

    public function getDeleteUrl()
    {
        return Yii::app()->controller->url('fileManagerAdmin/delete', array('id' => $this->id));
    }


    public function setExt($val)
    {
        $this->extension = $val;
        $this->mimeType = $this->mimeByExt($val);
    }


    public function getIsImage()
    {
        return in_array($this->extension, array('png', 'jpeg', 'jpg', 'tiff', 'ief', 'gif'));
    }


    public function getIsSound()
    {
        return in_array($this->extension, array('wma', 'mp3'));
    }


    public function getIsExcel()
    {
        return in_array($this->extension, array('xl', 'xla', 'xlb', 'xlc', 'xld', 'xlk', 'xll', 'xlm', 'xls', 'xlt', 'xlv', 'xlw'));
    }


    public function getIsWord()
    {
        return in_array($this->extension, array('doc', 'dot', 'docx'));
    }


    public function getIsArchive()
    {
        return in_array($this->extension, array('zip', 'rar', 'tar', 'gz'));
    }


    public function getUrl()
    {
        return "/fileManager/";
    }


    public function getIcon()
    {
        $folder = Yii::app()->getModule('fileManager')->assetsUrl() . '/img/fileIcons/';
        if ($this->isImage)
            $name = 'image';
        elseif ($this->isSound)
            $name = 'sound';
        elseif ($this->isExcel)
            $name = 'excel';
        elseif ($this->isWord)
            $name = 'word';
        elseif ($this->isArchive)
            $name = 'archive';
        elseif (is_file('.' . $folder . $this->extension . '.png'))
            $name = $this->extension;
        else
            $name = 'any';
        return CHtml::image($folder . $name . '.png', '', array('height' => 24));
    }


    public static function mimeByExt($ext)
    {
        switch ($ext) {
            case 'bin':
            case 'zoo':
            case 'dump':
                return 'application/octet-stream';
            case 'oda':
            case 'pdf':
            case 'rtf':
            case 'zip':
            case 'xml':
            case 'dxf':
                return 'application/' . $ext;
            case 'ai':
            case 'eps':
            case 'ps':
                return 'application/postscript';
            case 'bcpio' :
            case 'cpio':
            case 'csh':
            case 'dvi':
            case 'tar':
            case 'gtar':
            case 'hdf':
            case 'latex':
            case 'mif':
            case 'sh':
            case 'shar':
            case 'sv4cpio':
            case 'sv4crc':
            case 'tar':
            case 'tcl':
            case 'tex':
            case 'ustar':
            case 'wav':
            case 'dvi':
                return 'application/x-' . $ext;
            case 'nc':
            case 'cdf':
                return 'application/x-netcdf';
            case 'texinfo':
            case 'texi':
                return 'application/x-texinfo';
            case 'man':
            case 'me':
            case 'ms':
                return 'application/x-troff-' . $ext;
            case 't':
            case 'tr':
            case 'roff':
                return 'applicatlon/x-troff';
            case 'src':
                return 'application/x-wais-source';
            case 'au':
            case 'snd':
                return 'application/basic';
            case 'gif':
            case 'ief':
            case 'png':
            case 'x-png':
                return 'image/' . $ext;
            case 'jpg';
            case 'jpeg';
            case 'jpe':
                return 'image/jpeg';
            case 'tiff':
            case 'tif':
                return 'image/tiff';
            case 'ras':
                return 'image/x-cmu-raster';
            case 'rpnm':
                return 'image/x-portable-anymap';
            case 'pbm':
                return 'image/x-portable-bitmap';
            case 'pgm':
                return 'image/x-portable-graymap';
            case 'ppm':
                return 'image/x-portable-pixmap';
            case 'rgb':
            case 'dwg':
            case 'xwd':
                return 'image/x-' . $ext;
            case 'xbm':
                return 'image/x-xbitmap';
            case 'xpm':
                return 'imaqe/x-xpixrnap';
            case 'xwd':
                return 'image/x-xwindowdump';
            case 'html':
            case 'htm':
                return 'text/html';
            case 'txt':
            case 'cxx':
            case 'def':
                return 'text/plain';
            case 'rtx':
                return 'text/richtext';
            case 'tsv':
                return 'text/tab-separated-values';
            case 'etx':
                return 'text/x-setext';
            case 'mpeg':
            case 'mpg':
            case 'mpe':
            case 'mp3':
                return 'video/mpeg';
            case 'mp3':
                'audio/mpeg3';
            case 'qt':
            case 'mov':
                return 'video/quicktime';
            case 'qvi':
                return 'video/x-msvideo';
            case 'movie':
                return 'video/x-sgi-movie';
            case 'xdr':
                return 'video/x-amt-demorun';
            case 'xgz':
                return 'xgl/drawing';
            case 'xif':
                return 'image/vnd.xiff';
            case 'xl':
            case 'xla':
            case 'xlb':
            case 'xlc':
            case 'xld':
            case 'xlk':
            case 'xll':
            case 'xlm':
            case 'xls':
            case 'xlt':
            case 'xlv':
            case 'xlw':
                return 'application/excel';
            case 'xm':
                return 'audio/xm';
            case 'xmz':
                return 'xgl/movie';
            case 'xpix':
                return 'application/x-vnd.ls-xpix';
            case 'xsr':
                return 'video/x-amt-showrun';
            case 'xyz':
                return 'chemical/x-pdb';
            case 'z':
                return 'application/x-compress';
            case 'zsh':
                return 'text/x-script.zsh';
            case 'css':
                return 'text/css';
            case 'dcr':
                return 'application/x-director';
            case 'deepv':
                return 'application/x-deepv';
            case 'der':
                return 'application/x-x509-ca-cert';
            case 'dif':
            case 'dv':
                return 'video/x-dv';
            case 'dir':
                return 'application/x-director';
            case 'dl':
                return 'video/dl';
            case 'doc':
            case 'dot':
                return 'application/msword';
            case 'dp':
                return 'application/commonground';
            case 'drw':
                return 'application/drafting';
            case 'dwf':
                return 'model/vnd.dwf';
            case 'gz':
                return 'application/x-gzip';
        }
    }


    public static function rus2translit($string)
    {
        $converter = array(

            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',

            ' ' => '_'
        );

        return strtr($string, $converter);
    }

    public function getHandler($field = false)
    {
        Yii::import('upload.extensions.upload.Upload');
        $param = $field ? $_FILES[$field] : self::UPLOAD_PATH . $this->name;
        return new Upload($param);
    }

    public function save()
    {
        if (!parent::save()) {
            $this->error = Yii::t('FileManagerModule.main', 'Не удалось сохранить изменения');
            return false;
        }
        return true;
    }


    public static function str2url($str)
    {
        $str = self::rus2translit($str); // переводим в транслит
        $str = mb_strtolower($str); // в нижний регистр
        $str = preg_replace('~[^-a-z0-9_\s]+~u', '-', $str); // заменям все ненужное нам на "-"
        $str = trim($str, "-"); // удаляем начальные и конечные '-'
        return $str;
    }


    public function setExtraProperties($field, &$handler, $options)
    {
        $info = getimagesize($_FILES[$field]['tmp_name']);

        if (isset($options['save_y']) && $options['save_y']) {
            $size = isset($options['min_y']) ? $options['min_y'] : 0;
            $handler->image_y = ($info[1] > $size) ? $info[1] : $size;
        }

        if (isset($options['save_x']) && $options['save_x']) {
            $size = isset($options['min_x']) ? $options['min_x'] : 0;
            $handler->image_x = ($info[0] > $size) ? $info[0] : $size;
        }
    }

    private function beforeSaveOnServer()
    {
        $this->title = $_FILES['file']['name'];

        if (!is_dir(self::UPLOAD_PATH))
        {
            mkdir(self::UPLOAD_PATH, 0777);
        }
    }


    public function saveFile()
    {
        $this->beforeSaveOnServer();

        $file = CUploadedFile::getInstanceByName('file');

        $file_name = FileSystem::getUniqFileName($file->name, self::UPLOAD_PATH);

        if ($file->saveAs(self::UPLOAD_PATH . $file_name))
        {
            $this->name = $file_name;
            $this->fill();
            return true;
        }
        else
        {
            $this->error = $file->getError();
            return false;
        }
    }
    
    /**
     * @return string formatted file size
     */
    public function getFormatSize()
    {
        $file = self::UPLOAD_PATH . $this->name;
        $size = $this->size;

        $metrics[0] = 'bytes';
        $metrics[1] = 'KB';
        $metrics[2] = 'MB';
        $metrics[3] = 'GB';
        $metric = 0;

        while (floor($size / 1024) > 0)
        {
            ++$metric;
            $size /= 1024;
        }

        $ret = round($size, 1) . " " . (isset($metrics[$metric]) ? $metrics[$metric] : '??');
        return $ret;
    }


    public function getSrc($realFile = false)
    {
        $src = Yii::app()->baseUrl;
        if ($this->isImage)
            $src .= substr(self::UPLOAD_PATH . $this->name, 1);
        elseif ($this->isSound) {
            if ($realFile)
                $src .= Yii::app()->getModule('fileManager')->assetsUrl() . '/img/mp3.png';
            else
                $src .= substr(self::UPLOAD_PATH . $this->name, 1);
        }

        return $src;
    }


    public function afterFind()
    {
        parent::afterFind();
        $this->fill();
    }


    public function fill()
    {
        $file = self::UPLOAD_PATH . $this->name;
        $this->size = ($file && is_file($file)) ? filesize($file) : NULL; //$this->formatSize($this->basePath.$this->name);
        $this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
    }


    public function getNameWithoutExt()
    {
        $name = pathinfo($this->name, PATHINFO_FILENAME);
        $params = array(' ' => '');
        if (self::FILE_POSTFIX)
        {
            $params[self::FILE_POSTFIX] = '';
        }
        return strtr($name, $params);
    }


    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $model = FileManager::model()->parent($this->model_id, $this->object_id)->limit(1)->find();
                $this->order = $model ? $model->order + 1 : 1;
                $this->title;
            }

            return true;
        }
        else
        {
            return false;
        }
    }


    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            if (is_file(self::UPLOAD_PATH . $this->name))
            {
                unlink(self::UPLOAD_PATH . $this->name);
            }

            return true;
        }

        return false;
    }


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('object_id', $this->object_id, true);
		$criteria->compare('model_id', $this->model_id, true);
		$criteria->compare('tag', $this->tag, true);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('order', $this->order);

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}
}
