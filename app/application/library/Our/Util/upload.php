<?php

/**
 * @Desc: upload
 * @User: liuhui
 * @Date: 15-5-11 上午11:59
 * @error code:
 *
 */
namespace Our\Util;

class upload
{
    // 上传文件的最大值
    public $maxSize = -1;
    // 是否支持多文件上传
    public $supportMulti = true;
    // 允许上传的文件后缀
    //  留空不作后缀检查
    public $allowExts = array();
    // 允许上传的文件类型
    // 留空不做检查
    public $allowTypes = array();
    // 使用对上传图片进行缩略图处理
    public $thumb = false;
    // 缩略图最大宽度
    public $thumbMaxWidth;
    // 缩略图最大高度
    public $thumbMaxHeight;
    // 缩略图前缀
    public $thumbPrefix = 'thumb_';
    public $thumbSuffix = '';
    // 缩略图保存路径
    public $thumbPath = '';
    // 缩略图文件名
    public $thumbFile = '';
    // 是否移除原图
    public $thumbRemoveOrigin = false;
    // 压缩图片文件上传
    public $zipImages = false;
    // 启用子目录保存文件
    public $autoSub = false;
    // 子目录创建方式 可以使用hash date
    public $subType = 'hash';
    // 时间格式
    public $dateFormat = 'Ymd';
    // hash的目录层次
    public $hashLevel = 1;
    // 上传文件保存路径
    public $savePath = '';
    // 是否自动检查附件
    public $autoCheck = true;
    // 存在同名是否覆盖
    public $uploadReplace = false;

    // 上传文件命名规则
    // 例如可以是 time uniqid com_create_guid 等
    // 必须是一个无需任何参数的函数名 可以使用自定义函数
    public $saveRule = 'time';

    // 上传文件Hash规则函数名
    // 例如可以是 md5_file sha1_file 等
    public $hashType = 'md5_file';

    // 错误信息
    private $error = '';

    // 上传成功的文件信息
    private $uploadFileInfo;

    /**
     * 上传类初始化
     * @param int $maxSize 文件上传的最大文件大小（以字节为单位）默认为-1 不限大小
     * @param mixed $allowExts 允许上传的文件后缀（留空为不限制），使用数组设置，默认为空数组
     * @param mixed $allowTypes 允许上传的文件类型（留空为不限制），使用数组设置，默认为空数组
     * @param mixed $savePath 文件保存路径，如果留空会取UPLOAD_PATH常量定义的路径
     * @param mixed $saveRule 上传文件的保存规则，必须是一个无需任何参数的函数名，例如可以是 time、 uniqid com_create_guid 等，但必须能保证生成的文件名是唯一的，默认是uniqid
     */
    public function __construct($maxSize = '', $allowExts = '', $allowTypes = '', $savePath = '', $saveRule = '')
    {
        if (!empty($maxSize) && is_numeric($maxSize)) {
            $this->maxSize = $maxSize;
        }
        if (!empty($allowExts)) {
            if (is_array($allowExts)) {
                $this->allowExts = array_map('strtolower', $allowExts);
            } else {
                $this->allowExts = explode(',', strtolower($allowExts));
            }
        }
        if (!empty($allowTypes)) {
            if (is_array($allowTypes)) {
                $this->allowTypes = array_map('strtolower', $allowTypes);
            } else {
                $this->allowTypes = explode(',', strtolower($allowTypes));
            }
        }
        if (!empty($saveRule)) {
            $this->saveRule = $saveRule;
        }

        $this->savePath = $savePath;
    }

    /**
     * 上传文件
     * @param string $savePath 上传文件保存路径，格式如下
     * Array
     * (
     * [name] => 130150_8N8b_2306979.jpg
     * [type] => image/jpeg
     * [tmp_name] => /private/var/tmp/phpoXP5d8
     * [error] => 0
     * [size] => 60796
     * )
     * @return string
     */
    public function upload($savePath = '')
    {
        //如果不指定保存文件名，则由系统默认
        if (empty($savePath))
            $savePath = $this->savePath;
        // 检查上传目录
        if (!is_dir($savePath)) {
            // 检查目录是否编码后的
            if (is_dir(base64_decode($savePath))) {
                $savePath = base64_decode($savePath);
            } else {
                // 尝试创建目录
                if (!mkdir($savePath)) {
                    $this->error = '上传目录' . $savePath . '不存在';
                    return false;
                }
            }
        } else {
            if (!is_writeable($savePath)) {
                $this->error = '上传目录' . $savePath . '不可写';
                return false;
            }
        }
        $fileInfo = array();
        $isUpload = false;

        // 获取上传的文件信息
        // 对$_FILES数组信息处理
        $files = $this->dealFiles($_FILES);
        \Our\halo\HaloLogger::DEBUG($_FILES);
        foreach ($files as $key => $file) {
            //过滤无效的上传
            if (!empty($file['name'])) {
                //登记上传文件的扩展信息
                $file['key'] = $key;
                $file['extension'] = $this->getExt($file['name']);
                $file['savepath'] = $savePath;
                $file['savename'] = $this->getSaveName($file);

                // 自动检查附件
                if ($this->autoCheck) {
                    if (!$this->check($file))
                        return false;
                }

                //保存上传文件
                if (!$this->save($file)) return false;
                if (function_exists($this->hashType)) {
                    $fun = $this->hashType;
                    $file['hash'] = $fun($file['savepath'] . $file['savename']);
                }
                //上传成功后保存文件信息，供其他地方调用
                unset($file['tmp_name'], $file['error']);
                $fileInfo[] = $file;
                $isUpload = true;
            }
        }
        if ($isUpload) {
            $this->uploadFileInfo = $fileInfo;
            return true;
        } else {
            $this->error = '没有选择上传文件';
            return false;
        }
    }

    /**
     * 取得上传文件的信息
     * @return array
     */
    public function getUploadFileInfo()
    {
        return $this->uploadFileInfo;
    }

    /**
     * 取得最后一次错误信息
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->error;
    }

    /**
     * 转换上传文件数组变量为正确的方式
     * @param array $files 上传的文件变量$_FILES
     * @return array
     */
    private function dealFiles($files)
    {
        $fileArray = array();
        foreach ($files as $file) {
            if (is_array($file['name'])) {
                $keys = array_keys($file);
                $count = count($file['name']);
                for ($i = 0; $i < $count; $i++) {
                    foreach ($keys as $key)
                        $fileArray[$i][$key] = $file[$key][$i];
                }
            } else {
                $fileArray = $files;
            }
            break;
        }
        return $fileArray;
    }

    /**
     * 保存文件
     * @param array $file 上传的文件
     *
     * @return string
     */
    private function save($file)
    {
        $filename = $file['savepath'] . $file['savename'];
        if (!$this->uploadReplace && is_file($filename)) {
            // 不覆盖同名文件
            $this->error = '文件已经存在！' . $filename;
            return false;
        }
        // 如果是图像文件 检测文件格式
        if (in_array(strtolower($file['extension']), array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf')) && false === getimagesize($file['tmp_name'])) {
            $this->error = '非法图像文件';
            return false;
        }
        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            $this->error = '文件上传保存错误！';
            return false;
        }
        if ($this->thumb && in_array(strtolower($file['extension']), array('gif', 'jpg', 'jpeg', 'bmp', 'png'))) {
            $image = getimagesize($filename);
            if (false !== $image) {
                //是图像文件生成缩略图
                $thumbWidth = explode(',', $this->thumbMaxWidth);
                $thumbHeight = explode(',', $this->thumbMaxHeight);
                $thumbPrefix = explode(',', $this->thumbPrefix);
                $thumbSuffix = explode(',', $this->thumbSuffix);
                $thumbFile = explode(',', $this->thumbFile);
                $thumbPath = $this->thumbPath ? $this->thumbPath : $file['savepath'];
                // 生成图像缩略图

                $realFilename = $this->autoSub ? basename($file['savename']) : $file['savename'];
                for ($i = 0, $len = count($thumbWidth); $i < $len; $i++) {
                    $thumbname = $thumbPath . $thumbPrefix[$i] . substr($realFilename, 0, strrpos($realFilename, '.')) . $thumbSuffix[$i] . '.' . $file['extension'];
                    image::thumb($filename, $thumbname, '', $thumbWidth[$i], $thumbHeight[$i], true);
                }
                if ($this->thumbRemoveOrigin) {
                    // 生成缩略图之后删除原图
                    unlink($filename);
                }
            }
        }
        if ($this->zipImags) {
            // TODO 对图片压缩包在线解压
        }
        return true;
    }

    /**
     * 获取错误代码信息
     * @param string $errorNo 错误号码
     * @return void
     * // UPLOAD_ERR_OK         Value: 0
     * // There is no error, the file uploaded with success.
     * // UPLOAD_ERR_INI_SIZE   Value: 1
     * // The uploaded file exceeds the upload_max_filesize directive in php.ini.
     * // UPLOAD_ERR_FORM_SIZE  Value: 2
     * // The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
     * // UPLOAD_ERR_PARTIAL    Value: 3
     * // The uploaded file was only partially uploaded.
     * // UPLOAD_ERR_NO_FILE    Value: 4
     * // No file was uploaded.
     * // UPLOAD_ERR_NO_TMP_DIR Value: 6
     * // Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.
     * // UPLOAD_ERR_CANT_WRITE Value: 7
     * // Failed to write file to disk. Introduced in PHP 5.1.0.
     * // UPLOAD_ERR_EXTENSION  Value: 8
     * // A PHP extension stopped the file upload. Introduced in PHP 5.2.0.
     */
    protected function error($errorNo)
    {
        $errorMsg = array(
            0 => '上传成功',
            1 => '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值',
            2 => '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值',
            3 => '文件只有部分被上传',
            4 => '没有文件被上传',

            6 => '找不到临时文件夹',
            7 => '文件写入失败',
            8 => '未知上传错误'
        );
        if (!empty($errorMsg[$errorNo])) {
            $this->error = $errorMsg[$errorNo];
        } else {
            throw new LogicException($errorMsg[8], -9999);
        }
        return;
    }

    /**
     * 根据上传文件命名规则取得保存文件名
     * @param string $filename 数据
     * @return string
     */
    private function getSaveName($filename)
    {
        $rule = $this->saveRule;
        if (empty($rule)) {//没有定义命名规则，则保持文件名不变
            $saveName = $filename['name'];
        } else {
            if (function_exists($rule)) {
                //使用函数生成一个唯一文件标识号
                $saveName = $rule() . "." . $filename['extension'];
            } else {
                //使用给定的文件名作为标识号
                $saveName = $rule . "." . $filename['extension'];
            }
        }
        if ($this->autoSub) {
            // 使用子目录保存文件
            $saveName = $this->getSubName($filename) . '/' . $saveName;
        }
        return $saveName;
    }

    /**
     * 获取子目录的名称
     * @param array $file 上传的文件信息
     * @return string
     */
    private function getSubName($file)
    {
        switch ($this->subType) {
            case 'date':
                $dir = date($this->dateFormat, time());
                break;
            case 'hash':
            default:
                $name = md5($file['savename']);
                $dir = '';
                for ($i = 0; $i < $this->hashLevel; $i++) {
                    $dir .= $name{0} . '/';
                }
                break;
        }
        if (!is_dir($file['savepath'] . $dir)) {
            mkdir($file['savepath'] . $dir);
        }
        return $dir;
    }

    /**
     * 检查上传的文件
     * @param array $file 文件信息
     * @return boolean
     */
    private function check($file)
    {
        if ($file['error'] !== 0) {
            //文件上传失败
            //捕获错误代码
            $this->error($file['error']);
            return false;
        }
        //文件上传成功，进行自定义规则检查
        //检查文件大小
        if (!$this->checkSize($file['size'])) {
            $this->error = '上传文件大小不符！';
            return false;
        }

        //检查文件Mime类型
        if (!$this->checkType($file['type'])) {
            $this->error = '上传文件MIME类型不允许！';
            return false;
        }
        //检查文件类型
        if (!$this->checkExt($file['extension'])) {
            $this->error = '上传文件类型不允许';
            return false;
        }

        //检查是否合法上传
        if (!$this->checkUpload($file['tmp_name'])) {
            $this->error = '非法上传文件！';
            return false;
        }
        return true;
    }

    /**
     * 检查上传的文件类型是否合法
     * @param string $type 数据
     * @return boolean
     */
    private function checkType($type)
    {
        if (!empty($this->allowTypes))
            return in_array(strtolower($type), $this->allowTypes);
        return true;
    }


    /**
     * 检查上传的文件后缀是否合法
     * @param string $ext 后缀名
     * @return boolean
     */
    private function checkExt($ext)
    {
        if (!empty($this->allowExts))
            return in_array(strtolower($ext), $this->allowExts, true);
        return true;
    }

    /**
     * 检查文件大小是否合法
     * @param integer $size 数据
     * @return boolean
     */
    private function checkSize($size)
    {
        return !($size > $this->maxSize) || (-1 == $this->maxSize);
    }

    /**
     * 检查文件是否非法提交
     * @param string $filename 文件名
     * @return boolean
     */
    private function checkUpload($filename)
    {
        return is_uploaded_file($filename);
    }

    /**
     * 取得上传文件的后缀
     * @param string $filename 文件名
     * @return boolean
     */
    private function getExt($filename)
    {
        $pathinfo = pathinfo($filename);
        return $pathinfo['extension'];
    }
}