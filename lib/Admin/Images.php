<?php
/**
 * Project pop-lin.com
 *
 * @author      Mehmet Ali Ergut <mim@taximact.com>
 * @copyright   Mehmet Ali Ergut
 * @version     0.1
 * @date        01/02/14 03:34
 * @package     03:34
 */

namespace Admin;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

/**
 * Class Images
 *
 * @package Admin
 */
class Images {

	/**
	 * @var \Imagine\Imagick\Imagine
	 */
	private $imagine;
	/**
	 * @var int
	 */
	private $quality = 80;

	/**
	 *
	 */
	function __construct()
	{
		$this->imagine = new Imagine();
	}

	/**
	 * @param $image
	 * @param $save
	 * @param $width
	 * @param $height
	 * @param int $quality
	 */
	public function resize($image,$save,$width,$height,$quality=75)
	{
		$this->imagine->open($image)
			->resize(new Box($width,$height))
			->save($save, array('quality' => $this->quality));
	}

	/**
	 * @param $image
	 * @param $save
	 * @param $width
	 * @param $height
	 */
	public function crop($image,$save,$width,$height)
	{
		$this->imagine->open($image)
			->crop(new Point(0, 0), new Box($width, $height))
			->save($save, array('quality' => $this->quality));
	}
} 