<?php	
namespace App\lib;

class Image {
	public static $file;
	public static $image;
	public static $width;
	public static $height;
	public static $bits;
	public static $mime;

	/**
	 * Constructor
	 *
	 * @param	string	$file
	 *
 	*/
	 public function __construct() {		
		if (!extension_loaded('gd')) {
			exit('Error: PHP GD is not installed!');
		}	
				
	}
	
	public static function initialize($file) {        
        if (file_exists($file)) {			

			self::$file = $file;

			$info = getimagesize($file);

			self::$width  = $info[0];
			self::$height = $info[1];
			self::$bits = isset($info['bits']) ? $info['bits'] : '';
			self::$mime = isset($info['mime']) ? $info['mime'] : '';

			if (self::$mime == 'image/gif') {
				self::$image = imagecreatefromgif($file);
			} elseif (self::$mime == 'image/png') {
				self::$image = imagecreatefrompng($file);
			} elseif (self::$mime == 'image/jpeg') {
				self::$image = imagecreatefromjpeg($file);
			}
		} else {			
			exit('Error: Could not load image ' . $file . '!');
		}
	}

	/**
     * 
	 * 
	 * @return	string
     */
	public static function getFile() {
		return self::$file;
	}

	/**
     * 
	 * 
	 * @return	array
     */
	public static function getImage() {
		return self::$image;
	}
	
	/**
     * 
	 * 
	 * @return	string
     */
	public static function getWidth() {
		return self::$width;
	}
	
	/**
     * 
	 * 
	 * @return	string
     */
	public static function getHeight() {
		return self::$height;
	}
	
	/**
     * 
	 * 
	 * @return	string
     */
	public static function getBits() {
		return self::$bits;
	}
	
	/**
     * 
	 * 
	 * @return	string
     */
	public static function getMime() {
		return self::$mime;
	}
	
	/**
     * 
     *
     * @param	string	$file
	 * @param	int		$quality
     */
	public static function save($file, $quality = 90) {
		$info = pathinfo($file);

		$extension = strtolower($info['extension']);

		// if (is_resource(self::$image)) {
			if ($extension == 'jpeg' || $extension == 'jpg') {
				imagejpeg(self::$image, $file, $quality);
			} elseif ($extension == 'png') {
				imagepng(self::$image, $file);
			} elseif ($extension == 'gif') {
				imagegif(self::$image, $file);
			}

			imagedestroy(self::$image);
		// }
	}
	
	/**
     * 
     *
     * @param	int	$width
	 * @param	int	$height
	 * @param	string	$default
     */
	public static function resize($width = 0, $height = 0, $default = '') {
		if (!self::$width || !self::$height) {
			return;
		}

		$xpos = 0;
		$ypos = 0;
		$scale = 1;

		$scale_w = $width / self::$width;
		$scale_h = $height / self::$height;

		if ($default == 'w') {
			$scale = $scale_w;
		} elseif ($default == 'h') {
			$scale = $scale_h;
		} else {
			$scale = min($scale_w, $scale_h);
		}

		if ($scale == 1 && $scale_h == $scale_w && self::$mime != 'image/png') {
			return;
		}

		$new_width = (int)(self::$width * $scale);
		$new_height = (int)(self::$height * $scale);
		$xpos = (int)(($width - $new_width) / 2);
		$ypos = (int)(($height - $new_height) / 2);

		$image_old = self::$image;
		self::$image = imagecreatetruecolor($width, $height);

		if (self::$mime == 'image/png') {
			imagealphablending(self::$image, false);
			imagesavealpha(self::$image, true);
			$background = imagecolorallocatealpha(self::$image, 255, 255, 255, 127);
			imagecolortransparent(self::$image, $background);
		} else {
			$background = imagecolorallocate(self::$image, 255, 255, 255);
		}

		imagefilledrectangle(self::$image, 0, 0, $width, $height, $background);

		imagecopyresampled(self::$image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, self::$width, self::$height);
		imagedestroy($image_old);

		self::$width = $width;
		self::$height = $height;
	}
	
	/**
     * 
     *
     * @param	string	$watermark
	 * @param	string	$position
     */
	public static function watermark($watermark, $position = 'bottomright') {
		switch($position) {
			case 'topleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = 0;
				break;
			case 'topcenter':
				$watermark_pos_x = intval((self::$width - $watermark->getWidth()) / 2);
				$watermark_pos_y = 0;
				break;
			case 'topright':
				$watermark_pos_x = self::$width - $watermark->getWidth();
				$watermark_pos_y = 0;
				break;
			case 'middleleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = intval((self::$height - $watermark->getHeight()) / 2);
				break;
			case 'middlecenter':
				$watermark_pos_x = intval((self::$width - $watermark->getWidth()) / 2);
				$watermark_pos_y = intval((self::$height - $watermark->getHeight()) / 2);
				break;
			case 'middleright':
				$watermark_pos_x = self::$width - $watermark->getWidth();
				$watermark_pos_y = intval((self::$height - $watermark->getHeight()) / 2);
				break;
			case 'bottomleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = self::$height - $watermark->getHeight();
				break;
			case 'bottomcenter':
				$watermark_pos_x = intval((self::$width - $watermark->getWidth()) / 2);
				$watermark_pos_y = self::$height - $watermark->getHeight();
				break;
			case 'bottomright':
				$watermark_pos_x = self::$width - $watermark->getWidth();
				$watermark_pos_y = self::$height - $watermark->getHeight();
				break;
		}
		
		imagealphablending( self::$image, true );
		imagesavealpha( self::$image, true );
		imagecopy(self::$image, $watermark->getImage(), $watermark_pos_x, $watermark_pos_y, 0, 0, $watermark->getWidth(), $watermark->getHeight());

		imagedestroy($watermark->getImage());
	}
	
	/**
     * 
     *
     * @param	int		$top_x
	 * @param	int		$top_y
	 * @param	int		$bottom_x
	 * @param	int		$bottom_y
     */
	public static function crop($top_x, $top_y, $bottom_x, $bottom_y) {
		$image_old = self::$image;
		self::$image = imagecreatetruecolor($bottom_x - $top_x, $bottom_y - $top_y);

		imagecopy(self::$image, $image_old, 0, 0, $top_x, $top_y, self::$width, self::$height);
		imagedestroy($image_old);

		self::$width = $bottom_x - $top_x;
		self::$height = $bottom_y - $top_y;
	}
	
	/**
     * 
     *
     * @param	int		$degree
	 * @param	string	$color
     */
	public static function rotate($degree, $color = 'FFFFFF') {
		$rgb = self::$html2rgb($color);

		self::$image = imagerotate(self::$image, $degree, imagecolorallocate(self::$image, $rgb[0], $rgb[1], $rgb[2]));

		self::$width = imagesx(self::$image);
		self::$height = imagesy(self::$image);
	}
	
	/**
     * 
     *
     */
	private static function filter() {
        $args = func_get_args();

        call_user_func_array('imagefilter', $args);
	}
	
	/**
     * 
     *
     * @param	string	$text
	 * @param	int		$x
	 * @param	int		$y 
	 * @param	int		$size
	 * @param	string	$color
     */
	private static function text($text, $x = 0, $y = 0, $size = 5, $color = '000000') {
		$rgb = self::$html2rgb($color);

		imagestring(self::$image, $size, $x, $y, $text, imagecolorallocate(self::$image, $rgb[0], $rgb[1], $rgb[2]));
	}
	
	/**
     * 
     *
     * @param	object	$merge
	 * @param	object	$x
	 * @param	object	$y
	 * @param	object	$opacity
     */
	private static function merge($merge, $x = 0, $y = 0, $opacity = 100) {
		imagecopymerge(self::$image, $merge->getImage(), $x, $y, 0, 0, $merge->getWidth(), $merge->getHeight(), $opacity);
	}
	
	/**
     * 
     *
     * @param	string	$color
	 * 
	 * @return	array
     */
	private static function html2rgb($color) {
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}

		if (strlen($color) == 6) {
			list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
		} else {
			return false;
		}

		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);

		return array($r, $g, $b);
	}
}