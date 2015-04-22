<?php
/**
 * Index Controller
 *
 * @package    Admin
 * @subpackage None
 * @file       Admin/IndexControler.php
 * @desc       Used interface for base objects implementations
 * @author     Avi Aialon
 * @license    BSD/GPLv2
 *
 * copyright (c) Avi Aialon (DeviantLogic)
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */

namespace Spf\Mvc\Controller;

/**
 * Base controller for admin section
 *
 * @package    Admin
 * @subpackage None
 * @file       Admin/IndexControler.php
 * @desc       Used interface for base objects implementations
 * @author     Avi Aialon
 * @license    BSD/GPLv2
 *
 * copyright (c) Avi Aialon (DeviantLogic)
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */

class IndexController
    extends \Core\Net\HttpRequest
{
   /**
    * Admin index action
	*
	* @return void
	*/
   public final function indexAction(array $requestDispatchData)
   {
	   //$test = \Core\Hybernate\GeoLocation\GeoLocation::getInstance($_SERVER['REMOTE_ADDR']);
	   //var_dump($test->get()); die;
		//$this->setViewData('products', array());
   }
}