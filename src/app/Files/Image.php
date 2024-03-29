<?php

/**
 * @author Le Ngoc Doan
 * @copyright 2019
 */

namespace Gomee\Files;

use GdImage;

class Image
{
    use FileType;
    protected $source;
    /**
     * Undocumented variable
     *
     * @var GdImage
     */
    protected $data;
    protected $original;
    protected $type = null;
    protected $mime = null;
    protected $width = 0;
    protected $height = 0;
    protected static $font_path;
    protected static $font_folder;
    protected static $font = 'arial.ttf';
    protected $isImage = false;
    protected $name = null;
    public function __construct($image = null)
    {
        $this->newImage($image);
    }

    public function newImage($image = null)
    {
        if (self::isImageFile($image) || (is_resource($image) && \get_resource_type($image) == 'gd') || (is_object($image) && class_exists('GdImage') && is_a($image, 'GdImage'))) {
            if (self::isImageFile($image)) {
                $i = self::getsity($image);
                $im  = self::create($image);
                $this->data = $im;
                $this->original = $im;
                if ($i) {
                    $this->height = $i['h'];
                    $this->width = $i['w'];
                    $this->type = $i['type'];
                    $this->mime = $i['mime'];
                    $this->isImage = true;
                } else {

                    $this->height = imagesy($this->data);
                    $this->width = imagesx($this->data);
                }
            } else {
                $this->data = $image;
                $this->original = $image;
                $this->height = imagesy($this->data);
                $this->width = imagesx($this->data);
                $this->isImage = true;
            }
        } else {
            $this->height = 768;
            $this->width = 1366;
            $im = self::create(null, $this->height, $this->width, array(255, 255, 255));
            $this->data = $im;
            $this->original = $im;
            $this->type = 'png';
            $this->mime = 'image/png';
        }
    }
    /**
     * lấy dữ liệu ảnh
     *
     * @return GdImage
     */
    public function get()
    {
        return $this->data;
    }
    /**
     * lấy dữ liệu nguyên dạng
     *
     * @return GD
     */
    public function getoriginal()
    {
        return $this->original;
    }
    /**
     * lấy loại tập tin ảnh
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * lấy loại file
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }
    /**
     * lấy chiều cao ảnh
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
    /**
     * lấy chiều rộng ảnh
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
    /**
     * lầy tên file đã được lưu
     *
     * @return string
     */
    public function getStoredFilename()
    {
        return $this->name;
    }
    /**
     * kiểm tra xem phải ảnh hay không
     *
     * @return bool
     */
    public function check()
    {
        return $this->isImage;
    }

    /**
     * lấy duôi file
     *
     * @param string $mime
     * @return string
     */
    public function getExt($mime = null)
    {
        $type = $mime ? $mime : ($this->mime ? $this->mime : 'image/png');
        $mity = $this->getMimeType($type);

        $stt = 'png';
        if (($type == "png" || $type == "image/png")) {
            $stt = 'png';
        } elseif (($type == "jpg" || $type == "jpeg" || $type == "image/jpeg")) {
            $stt = 'jpg';
        } elseif (($type == "gif" || $type == "image/gif")) {
            $stt = 'gif';
        } elseif ($mity) {
            $stt = $mity->extension;
        }
        return $stt;
    }

    /**
     * lấy một bản sao
     *
     * @return Image
     */
    public function copy()
    {
        $b = clone $this;
        return $b;
    }
    /**
     * tao hinh anh tren trinh duyet
     * @param string $mime kieu file anh
     */
    public function output($mime = null)
    {
        $m = $mime ? $mime : ($this->mime ? $this->mime : 'image/png');
        $d = $this->data;
        header('Content-Type: ' . $m);
        self::display($d, null, $m);
    }
    /**
     * tao hinh anh tren trinh duyet
     * @param string $mime kieu file anh
     */
    public function show($mime = null)
    {
        $this->output($mime);
    }
    /**
     * tao file tu du lieu co san
     * @param string $filename ten file hoac duong dan noi bo
     * @param string $mime kieu file anh
     * @return bool
     */
    public function save($filename, $mime = null)
    {
        if (!is_string($filename)) throw new \Exception("filename you gived is not a string", 1);
        elseif ((is_resource($this->data) && get_resource_type($this->data) == 'gd') || (is_object($this->data) && class_exists('GdImage') && is_a($this->data, 'GdImage'))) {
            $m = $mime ? $mime : ($this->mime ? $this->mime : 'image/png');
            $ext = $this->getExt($m);
            if (!preg_match('/\.' . $ext . '$/si', $filename)) {
                $filename .= '.' . $ext;
            }

            $p = explode('/', $filename);
            $fn = array_pop($p);
            $file = new Filemanager();
            $file->makeDir(implode('/', $p), 755, true);
            $this->name = $fn;
            //$quality = ($imi['quality'])?$imi['quality']:100;
            if (($ext == "png") && imagepng($this->data, $filename)) {
                $stt = true;
            } elseif (($ext == "jpg" || $ext == "jpeg") && imagejpeg($this->data, $filename)) {
                $stt = true;
            } elseif (($ext == "gif") && imagegif($this->data, $filename)) {
                $stt = true;
            } elseif (($ext == "webp") && imagewebp($this->data, $filename)) {
                $stt = true;
            } elseif (file_put_contents($filename, $this->data)) {
                $stt = true;
            } else {
                $stt = false;
            }
            return $stt;
        }
        return false;
    }

    /**
     * lam mo anh
     * @param int $int Độ mờ
     */
    public function blur($int = 10)
    {
        $image = $this->data;


        //$image = image::images($image);
        $image_width = imagesx($image);
        $image_height = imagesy($image);

        for ($i = 0; $i < $int; $i++) {
            imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
        }

        $this->data = $image;
        return $this;
    }

    /**
     * chinh kich thuoc
     * @param int $width
     * @param int $height
     * @return Image
     */
    public function resizeAndCrop($width = null, $height = null)
    {
        $image = $this->data;
        $w = imagesx($image);
        $h = imagesy($image);
        $g = $w / $h;

        if (is_string($width) && strtolower($width) == 'auto' && is_string($height) && strtolower($height) == 'auto') {
            return $this;
        }

        if (is_string($width) && strtolower($width) == 'auto') {
            $width = $height * $g;
        } elseif (!$width || !is_numeric($width)) {
            $width = $this->width;
        }

        if (is_string($height) && strtolower($height) == 'auto') {
            $height = $width / $g;
        } elseif (!$height || !is_numeric($height)) {
            $height = $this->height;
        }

        $k = $width / $height;
        if ($g < $k) {
            $s = $width;
            $z = "width";
        } else {
            $s = $height;
            $z = "height";
        }

        $this->zoom($z, $s);
        $this->crop($width, $height);
        $this->refresh();
        return $this;
    }




    /**
     * cat hinh anh
     * @param int $width Độ rộng
     * @param int $height Chiều cao
     * @param int $x Tọa độ x
     * @param int $y Tọa đô y
     * @return Image
     */

    public function crop($width = null, $height = null, $x = null, $y = null, $transparent_bg = true)
    {
        $w = $width;
        $h = $height;
        if (is_array($width)) {
            $i = $width;
            if (isset($i['width'])) $w = $i['width'];
            else $w = null;
            if (isset($i['height'])) $h = $i['height'];
            if (isset($i['x'])) $x = $i['x'];
            if (isset($i['y'])) $y = $i['y'];
        }
        $img = $this->data;
        $x = self::cropX($img, $x, $w);
        $y = self::cropY($img, $y, $h);

        $nWidth = $w;
        $nHeight = $h;
        $newImg = imagecreatetruecolor($nWidth, $nHeight);
        if ($transparent_bg) {
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
        }
        $targ_w = $w;
        $targ_h = $h;
        $jpeg_quality = 90;


        $ix = imagesx($img);
        $iy = imagesy($img);


        imagecopyresampled($newImg, $img, 0, 0, $x, $y, $nWidth, $nHeight, $w, $h);
        //imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$targ_w,$targ_h,$w,$h);
        $this->data = $newImg;
        $this->refresh();
        return $this;
    }


    /**
     * chỉnh kích thước
     *
     * @param int $width
     * @param int $height
     * @return Image
     */
    public function resize($width = null, $height = null)
    {
        $image = $this->data;
        if ($image) {
            $bg = self::create(null, $width, $height);
            if (self::isImageFile($image)) {
                $img = self::create($image);
            } else {
                $img = $image;
            }
            imagecopy($bg, $img, 0, 0, $width, $height, imagesx($image), imagesy($image));
            $afterresize = $bg;
        } else {
            $afterresize = $image;
        }
        return $afterresize;
        $this->refresh();
        return $this;
    }

    /**
     * thu phong hinh anh
     * @param string $type chieu thu phong height | width | d
     * @param int $size do lon hinh anh
     * @param string $p don vi thu phong % | px
     * @return Image
     */
    public function zoom($type = 'width', $size = 100, $p = 'px')
    {
        $img = $this->data;
        $width = imagesx($img);
        $height = imagesy($img);
        $tt = strtolower($type);
        $n = $size;
        if ($p == "%") {
            $zk = $n / 100;
            $new_height = $height * $zk;
            $new_width = $width * $zk;
        } else {
            $k = $width / $height;
            if ($tt == "h" || $tt == 'height') {
                $new_height = $n;
                $new_width = $new_height * $k;
            } elseif ($tt == 'd' || $tt == 'diagonal') {
                $d1 = sqrt(($height * $height) + ($width * $width));
                $d2 = $n;
                $k2 = $d2 / $d1;
                $new_height = $k2 * $height;
                $new_width = $k2 * $width;
            } else {
                $new_width = $n;
                $new_height = $new_width / $k;
            }
        }
        $newImg = imagecreatetruecolor($new_width, $new_height);
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $new_width, $new_height, $transparent);

        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $this->data = $newImg;
        $this->refresh();
        return $this;
    }

    /**
     * xoay ảnh
     *
     * @param integer $angle
     * @return Image
     */
    public function rotate($angle = 0)
    {
        if (is_numeric($angle)) {
            $img = $this->data;

            $img = imagerotate($img, $angle, -1);
            imagealphablending($img, true);
            imagesavealpha($img, true);
            $this->data = $img;
            $this->refresh();
        }
        return $this;
    }


    /**
     * đưa về trạng thái ban đầu
     *
     * @return void
     */
    public function restore()
    {
        $this->data = $this->original;
        $this->refresh();
        return $this;
    }

    /**
     * làm mới
     *
     * @return void
     */
    public function refresh()
    {
        $this->width = imagesx($this->data);
        $this->height = imagesy($this->data);
        return $this;
    }


    // các phương thức static

    /**
     * @param string
     */
    public static function isImageFile($url)
    {
        if (!is_string($url))
            return false;
        $stt = (preg_match('/(^http|\.jpg|\.gif|\.png|tmp|\.jpeg)/si', $url) || is_file($url)) ? true : false;
        return $stt;
    }





    public static function getsity($image_url)
    {
        $pex = '';
        $mime = '';
        $w = 0;
        $h = 0;
        if (self::isImageFile($image_url)) {
            $source = getimagesize($image_url);
            if ($source) {
                $mime = $source['mime'];
                $w = $source[0];
                $h = $source[1];
                $typ = explode('/', $mime);
                if ($typ[0] == 'image' && isset($typ[1])) {
                    $t = $typ[1];
                    switch ($t) {
                        case 'png':
                            $pex = $t;
                            break;
                        case 'jpeg':
                            $pex = 'jpg';
                            break;
                        case 'gif':
                            $pex = $t;
                            break;
                        default:
                            $pex = $t;
                            break;
                    }
                }
            }
        } elseif ($image_url) {
            $w = imagesx($image_url);
            $h = imagesy($image_url);
        } else {
            $w = 0;
            $h = 0;
        }
        $img_inf = array(
            'type' => $pex,
            'mime' => $mime,
            'w' => $w,
            'h' => $h
        );
        return ($w) ? $img_inf : null;
    }

    /**
     * @param resource or string
     * @param string
     * @param string
     */
    public static function images($image = null)
    {
        if (self::isImageFile($image)) {
            $source_im = self::create($image);
        } elseif (get_resource_type($image) == 'gd') {
            $source_im = $image;
        } else {
            $source_im = static::create(null, 480, 360, array(255, 255, 255));
        }
        return $source_im;
    }
    public static function display($image_src, $img_filename = null, $img_type = null)
    {
        $imi = $image_src;
        if (is_array($imi)) {
            $type = ($imi['type']) ? $imi['type'] : $img_type;
            $image = $imi['image'];
            $filename = ($imi['filename']) ? $imi['filename'] : (($img_filename) ? $img_filename : null);
        } elseif (self::isImageFile($imi)) {
            $image = self::create($imi);
            $ii = self::getsity($imi);
            $filename = ($img_filename) ? $img_filename : null;
            $type = ($img_type) ? $img_type : $ii['type'];
        } else {
            $image = $imi;
            $filename = ($img_filename) ? $img_filename : null;
            $type = $img_type;
        }
        $p = explode('/', $filename);
        $fn = array_pop($p);
        $file = new Filemanager();
        $file->makeDir(implode('/', $p), 777, true);
        //$quality = ($imi['quality'])?$imi['quality']:100;
        if (($type == "png" || $type == "image/png") && imagepng($image, $filename)) {
            $stt = true;
        } elseif (($type == "jpg" || $type == "jpeg" || $type == "image/jpeg") && imagejpeg($image, $filename)) {
            $stt = true;
        } elseif (($type == "gif" || $type == "image/gif") && imagegif($image, $filename)) {
            $stt = true;
        } elseif (isset($ii) && copy($imi, $img_filename)) {
            $stt = true;
        } else {
            $stt = false;
        }
        return $stt;
    }

    /**
     * @param resource or string
     * @param int
     * @param int
     * @param array or string
     */

    public static function create($image_url = null, $image_w = 100, $image_h = 100, $color = null)
    {
        if (is_string($image_url) && self::isImageFile($image_url)) {
            $img = self::getsity($image_url);
            $type = $img['type'];
            if ($type == "png") {
                $image = \imagecreatefrompng($image_url);
            } elseif ($type == "jpg") {
                $image = \imagecreatefromjpeg($image_url);
            } elseif ($type == "gif") {
                $image = \imagecreatefromgif($image_url);
            } else {
                $image = file_get_contents($image_url);
            }
        } else {
            if (is_array($color) && isset($color[0]) && isset($color[1]) && isset($color[2])) {
                $image = \imagecreatetruecolor($image_w, $image_h);
                imagecolorallocate($image, $color[0], $color[1], $color[2]);
            } elseif (is_string($color)) {
                $color = explode(',', $color);
                if (is_array($color) && isset($color[0]) && isset($color[1]) && isset($color[2])) {
                    $image = \imagecreatetruecolor($image_w, $image_h);
                    imagecolorallocate($image, $color[0], $color[1], $color[2]);
                } else {
                    $image = \imagecreatetruecolor($image_w, $image_h);
                }
            } else
                $image = \imagecreatetruecolor($image_w, $image_h);
            //$txtColor = imagecolorallocate($image, 245, 250, 254);

        }
        return $image;
    }

    protected static function cropX($img, $x = null, $width = null)
    {
        $r = 0;
        $w = imagesx($img);
        $h = imagesy($img);
        if (!is_numeric($width)) $width = $w;
        if (is_numeric($x)) {
            $r = $x;
        } elseif (is_string($x)) {
            $s = strtolower($x);
            if ($s == 'left' || $s == 'l' || $s == 'trai' || $s == 't') {
                $e = 0;
            } elseif ($s == 'right' || $s == 'r' || $s == 'phai' || $s == 'p') {
                $r = $w - $width;
            } else {
                $r = ($w - $width) / 2;
            }
        } else {
            $r = ($w - $width) / 2;
        }
        return $r;
    }

    protected static function cropY($img, $y = null, $height = null)
    {
        $r = 0;
        $w = imagesx($img);
        $h = imagesy($img);
        if (!is_numeric($height)) $height = $h;
        if (is_numeric($y)) {
            $r = $y;
        } elseif (is_string($y)) {
            $s = strtolower($y);
            if ($s == 'top' || $s == 't' || $s == 'tren') {
                $e = 0;
            } elseif ($s == 'bottom' || $s == 'b' || $s == 'duoi' || $s == 'd') {
                $r = $h - $height;
            } else {
                $r = ($h - $height) / 2;
            }
        } else {
            $r = ($h - $height) / 2;
        }
        return $r;
    }
}
