<?php
namespace Core\Hybernate\Listings;
/**
 * Listings management used with Hybernate loader
 *
 * @package    Core
 * @subpackage Interfaces
 * @file       Core/Interfaces/Base/HybernateBaseInterface.php
 * @desc       Used interface for ORM implementations
 * @author     Avi Aialon
 * @license    BSD/GPLv2
 *
 * copyright (c) Avi Aialon (DeviantLogic)
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt. 
 */
class Listings extends \Core\Interfaces\HybernateInterface
{
	/**
	 * This method returns the objects's friendly URL value.
	 *
	 * @return string
	 */
	public static function createFriendlyUrl(array $arrItemData) 
	{
		$Application = \Core\Application::getInstance();
		$arrItemData = array_change_key_case($arrItemData, CASE_LOWER);
		$strTitle    = $arrItemData['addresstext'];	
		
		$search  = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
		$replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
		$strTitle = str_replace($search, $replace, $strTitle);
		//$strTitle = iconv('UTF-8','ASCII//TRANSLIT', $strTitle);
		
		$strTitle = preg_replace('/[^A-Za-z0-9 ]/', ' ', ucwords($strTitle));
		$strTitle = preg_replace('/[\s]{1,}/', ' ', ucwords($strTitle));
		$strTitle = preg_replace('/[\s]/', '-', $strTitle);
		$strTitle .= '-' . (int) $arrItemData['id'];
		
		return \Core\Net\HttpRequest::getInstance()->route('listing-detail', $strTitle, array());
	}
	
	/**
	 * Extension to self::createFriendlyUrl(...)
	 *
	 * @return string
	 */
	public final function getFriendlyUrl()
	{
		return  \Core\Hybernate\Listings\Listings::createFriendlyUrl($this->get());
	}
	 
	/**
	 * This method enables a listings
	 *
	 * @return void
	 */ 
	public function enable() 
	{ 
		$this->setActiveStatus(1);
		if ((int) $this->getVariable('id') > 0) 
		{
			$this->save();
		}
	}
	
	/**
	 * This method diables a listings
	 *
	 * @return void
	 */ 
	public function disable() 
	{
		$this->setActiveStatus(0);
		if ((int) $this->getVariable('id') > 0) 
		{
			$this->save();
		}
	}
	
	/**
	 * This method returns the closest or default lat/lng coordinates (defaults to Montreal)
	 *
	 * @access public, static
	 * @params array $arrView - The array used to build the class view <see>SHARED_OBJECT::getObjectClassView()</see>
	 * @return array
	 */
	public static function getClosestLatLngCoords()
	{
		$Application 	  = APPLICATION::getInstance();
		$arrCoords		  = array(
			'lat' => '45.508669900000000000',
			'lng' => '-73.553992499999990000',
			'igp' => false // <-- If its a geo position coordinate
		);
		
		if (true === (strtolower($Application->getGeoLocator()->getCountryCode()) === 'ca')) {
			$arrCoords = array(
				'lat' => $Application->getGeoLocator()->getLatitude(),
				'lng' => $Application->getGeoLocator()->getLongitude(),
				'igp' => true
			);
		}
		return ($arrCoords);
	}
	
	/**
	 * This method returns the centermost point of an array of lat/lng
	 *
	 * @access public, static
	 * @params array $arrView - The array used to build the class view <see>SHARED_OBJECT::getObjectClassView()</see>
	 * @return array
	 */
	public static function calculateCenter(array $array_locations) 
	{
		$minlat = false;
		$minlng = false;
		$maxlat = false;
		$maxlng = false;
	
		foreach ($array_locations as $geolocation) {
			 if ($minlat === false) { $minlat = $geolocation['latitude']; } else { $minlat = ($geolocation['latitude'] < $minlat) ? $geolocation['latitude'] : $minlat; }
			 if ($maxlat === false) { $maxlat = $geolocation['latitude']; } else { $maxlat = ($geolocation['latitude'] > $maxlat) ? $geolocation['latitude'] : $maxlat; }
			 if ($minlng === false) { $minlng = $geolocation['longitude']; } else { $minlng = ($geolocation['longitude'] < $minlng) ? $geolocation['longitude'] : $minlng; }
			 if ($maxlng === false) { $maxlng = $geolocation['longitude']; } else { $maxlng = ($geolocation['longitude'] > $maxlng) ? $geolocation['longitude'] : $maxlng; }
		}
	
		// Calculate the center
		$lat = $maxlat - (($maxlat - $minlat) / 2);
		$lng = $maxlng - (($maxlng - $minlng) / 2);
	
		return (array('lat' => $lat, 'lng' => $lng));
	}
	
	/**
	 * This method returns the min/max + radius according to lat long point
	 *
	 * @access public, static
	 * @params array $arrView - The array used to build the class view <see>SHARED_OBJECT::getObjectClassView()</see>
	 * @return array
	 */
	public static function getMinMaxCoordinates(array $coords) 
	{
		$blnContinue = (
			(false === empty($coords['lat'])) && 
			(false === empty($coords['lng'])) &&
			(false === empty($coords['rad'])) 
		);
		
		$arrRetData  = ($blnContinue ? array(
			'minLat' => 0,
			'maxLat' => 0, 
			'minLon' => 0,
			'minLon' => 0,
			'lat'	 => 0,
			'lng'	 => 0,
			'rad'  	 => 0
		) : $coords);
		
		if (true === $blnContinue) {
			$lat = number_format ((float) $coords['lat'], 7);  // latitude of centre of bounding circle in degrees
			$lon = number_format ((float) $coords['lng'], 7);  // longitude of centre of bounding circle in degrees
			$rad = number_format ((float) $coords['rad'], 2);  // radius of bounding circle in kilometers - If you prefer to work in miles, set the earth’s radius $R to 3959.
			//$src = $arrInput['src'];  							 // Search keyword
			$R 	 = 6371; 									 	 // earth's radius, km
			
			// first-cut bounding box (in degrees)
			$maxLat = $lat + rad2deg($rad/$R);
			$minLat = $lat - rad2deg($rad/$R);
			
			// compensate for degrees longitude getting smaller with increasing latitude
			$maxLon = $lon + rad2deg($rad/$R/cos(deg2rad($lat)));
			$minLon = $lon - rad2deg($rad/$R/cos(deg2rad($lat)));
			
			// convert origin of filter circle to radians
			$lat = deg2rad($lat);
			$lon = deg2rad($lon);
			
			$arrRetData  = array(
				'minLat' => $minLat,
				'maxLat' => $maxLat, 
				'maxLon' => $maxLon,
				'minLon' => $minLon,
				'lat'	 => $lat,
				'lng'	 => $lon,
				'rad'    => $rad
			);
		}
		
		
		return ($arrRetData);
	}
	
	/**
	 * This method returns the class view for LISTINGS with item images
	 *
	 * @access public, static
	 * @params array $arrView - The array used to build the class view <see>SHARED_OBJECT::getObjectClassView()</see>
	 * @return array
	 */
	public static function getItemObjectClassView($arrView = array(), $blnUseCache = false, $strCacheKey = false, $blnReturnArray = false, $blnGetIndividual = true) 
	{
		$objApplication = \Core\Application::getInstance();
		$objDb          = $objApplication->getDatabase();
		//$objDb->setEncoding();
		// Define a default set of view arguments
		$arrDefaultView = array(
				'columns'	=>	'a.*, IFNULL(d.description, d2.description) as description, IFNULL(d.linkUrl, d2.linkUrl) as link_url, ' .
								(true === $blnGetIndividual ? 'i.name individual_name, i.lastName individual_last_name, i.fullName individual_full_name, ' .
								'i.position individual_position, i.photo individual_photo, i.website individual_website, o.name as org_name, o.address as org_address, ' .
								'o.website as org_website, o.logo as org_logo,' : '') . ' IFNULL(l.display_name, "' . 
								$objApplication->translate('Other', 'Autre', '其他') . '") as listingsBuildingTypeName ',
				'filter'	=>	array(),
				'operator'	=>	array(),
				'radius'	=> 	array(), 	// Search by radius with one set of lat/lng coords + max amount of kilometers
				'center_point' => 	array(), // The centerpoint location
				'filtered_radius' => array(), // Radius already filtered with min lat/lng
				'between'   => array(), 
				'origin'	=> 	array(), 
				'limit'		=>	0,
				'orderBy'	=> 	'a.id',
				'direction'	=> 	'DESC',
				'groupBy'	=>	'a.id',
				'escapeData'=>	true,
				'inner_join'=>	array(),
				'imagePositionId' => array(),
				'debug'		=> false,
				'keyword'	=> NULL
		);
	
		// Merge the arguments
		$arrViewParams = array_merge(
				(array) $arrDefaultView,
				(array) $arrView
		);

		//
		// Latitude and longitude filter..
		//
		
		// Radius
		$arrRadiusData      = (array) $arrViewParams['radius'];
		$arrOriginData      = (array) $arrViewParams['origin'];
		$arrCenterPoint     = (array) $arrViewParams['center_point'];
		$intUseDistance     = (false === empty($arrViewParams['origin']['radius']) ? (int) $arrViewParams['origin']['radius'] : false);
		
		if (
			(false === empty($arrRadiusData))	&&
			(false === empty($arrRadiusData['lat']))	&&
			(false === empty($arrRadiusData['lng']))
		) {
			
			$arrRadiusData  = self::getMinMaxCoordinates(array(
				'lat'	=> 	(float) $arrRadiusData['lat'],
				'lng'	=> 	(float) $arrRadiusData['lng'],
				'rad'	=> 	(false === empty($arrRadiusData['radius']) ? (int) $arrRadiusData['radius'] : 20)
			));
			unset ($arrViewParams['radius']);
		}
		
		// Filtered radius distance..
		// Add the distance according to the center point of the filtered radius.
		// So distance is calculated from the center out. (to get more accurate results)
		if (false === empty($arrCenterPoint)) {
			$arrViewParams['columns'] .= (false === empty($arrViewParams['columns']) ? ', ' : '');
			
			$arrViewParams['columns'] .= '(((acos(sin((' . $arrCenterPoint['lat'] . ' * pi()/180)) * 
				sin((`a`.`latitude` * pi() / 180))+cos((' . $arrCenterPoint['lat'] . ' *pi()/180)) * 
				cos((`a`.`latitude` *pi()/180)) * cos(((' . $arrCenterPoint['lng'] . ' - `a`.`longitude`)* 
				pi()/180))))*180/pi())*60*1.1515
			) AS distance_from_centerpoint';
		} else {
			$arrViewParams['columns'] .= (false === empty($arrViewParams['columns']) ? ', ' : '') . '0 as distance_from_centerpoint';
		}
		
		
		// Origin
		// This is the current location of the user
		// Calculate the distance from the current user's location to the listing
		if (
			(false === empty($arrOriginData))	&&
			(false === empty($arrOriginData['lat']))	&&
			(false === empty($arrOriginData['lng']))
		) {
			$arrOriginData  = self::getMinMaxCoordinates(array(
				'lat'	=> 	(float) $arrOriginData['lat'],
				'lng'	=> 	(float) $arrOriginData['lng'],
				'rad'	=> 	(false === empty($arrOriginData['radius']) ? (int) $arrOriginData['radius'] : 20)
			));
			$arrViewParams['columns'] .= (false === empty($arrViewParams['columns']) ? ', ' : '');
			$arrViewParams['columns'] .= 'ACOS(
					(SIN(' . $arrOriginData['lat'] . ') * SIN(RADIANS(a.latitude))) + 
					(COS(' . $arrOriginData['lat'] . ') * COS(RADIANS(a.latitude)) * COS(RADIANS(a.longitude) - ' . $arrOriginData['lng']. '))
				) * 6371 AS distance '; // The '6371' is earth's radius...
			
			$arrViewParams['orderBy'] = 'distance ASC, a.id';
			unset ($arrViewParams['origin']);
		}
		
		// Add the item image table
		if (sizeof($arrViewParams['imagePositionId'])) {
			$arrViewParams['left_join'][] 	= 	'listings_image im ON im.listingsId = a.id AND im.id IS NOT NULL';
		}
	
		// Set manditory joins
		$arrViewParams['left_join'][] 	= 'listings_description d ON d.listingsId = a.id AND d.langId = ' . $objApplication->translate(1,2,3);
		$arrViewParams['inner_join'][] 	= 'listings_description d2 ON d2.listingsId = a.id AND d2.langId = 1';
		
		if (true === $blnGetIndividual) {
			$arrViewParams['left_join'][] 	= 'individual i ON i.id = a.individualId AND i.id IS NOT NULL';
			$arrViewParams['left_join'][] 	= 'organization o ON o.id = a.organizationId AND o.id IS NOT NULL';
		}
		$arrViewParams['left_join'][] 	= 'listings_building_type_description l ON l.listingsBuildingTypeId = a.listingsBuildingTypeId AND l.langId = ' . 
											$objApplication->translate(1,2,3) .  ' AND l.listingsBuildingTypeId IS NOT NULL';
	
		// Set manditory filters
		$arrViewParams['filter']['a.activeStatus'] = 1;
		
		
		// Set filter operator
		$arrViewParams['operator'][]  = "=";
		$arrViewParams['operator'][]  = "=";
		$arrViewParams['operator'][]  = "=";
		$arrViewParams['operator'][]  = "=";
			
		if (false === empty($arrRadiusData)) {
			/*
			$arrViewParams['filter_inline']['a.latitude']  = ' BETWEEN ' . (float) $arrRadiusData['minLat'] . ' AND ' . (float) $arrRadiusData['maxLat'];
			$arrViewParams['filter_inline']['a.longitude'] = ' BETWEEN ' . (float) $arrRadiusData['minLon'] . ' AND ' . (float) $arrRadiusData['maxLon'];
			*/
			
			$arrViewParams['between']['a.latitude']  =  array((float) $arrRadiusData['minLat'], (float) $arrRadiusData['maxLat']);
			$arrViewParams['between']['a.longitude'] = array((float) $arrRadiusData['minLon'], (float) $arrRadiusData['maxLon']);
		}
		
		if (false === empty($arrViewParams['filtered_radius'])) {
			/*
			$arrViewParams['filter_inline']['a.latitude']  = ' BETWEEN ' . (float) $arrViewParams['filtered_radius']['minLat'] . ' AND ' . (float) $arrViewParams['filtered_radius']['maxLat'];
			$arrViewParams['filter_inline']['a.longitude'] = ' BETWEEN ' . (float) $arrViewParams['filtered_radius']['minLon'] . ' AND ' . (float) $arrViewParams['filtered_radius']['maxLon'];
			*/
			
			$arrViewParams['between']['a.latitude']  =  array((float) $arrViewParams['filtered_radius']['minLat'], (float) $arrViewParams['filtered_radius']['maxLat']);
			$arrViewParams['between']['a.longitude'] = array((float) $arrViewParams['filtered_radius']['minLon'], (float) $arrViewParams['filtered_radius']['maxLon']);
		}
		
		if ($intUseDistance) {
			//$arrViewParams['having'][] = 'distance <=' . $intUseDistance;
			/*
				$arrViewParams['filter_inline_unescaped']['(ACOS(
					(SIN(' . $arrCenterPoint['lat'] . ') * SIN(RADIANS(a.latitude))) + 
					(COS(' . $arrCenterPoint['lat'] . ') * COS(RADIANS(a.latitude)) * COS(RADIANS(a.longitude) - ' . $arrCenterPoint['lng'] . '))
				) * 6371)'] = "<=" . (int) $intUseDistance;
			*/
				
			
			$arrViewParams['filter_inline_unescaped']['((((acos(sin((' . $arrCenterPoint['lat'] . ' * pi()/180)) * 
				sin((`a`.`latitude` * pi() / 180))+cos((' . $arrCenterPoint['lat'] . ' *pi()/180)) * 
				cos((`a`.`latitude` *pi()/180)) * cos(((' . $arrCenterPoint['lng'] . ' - `a`.`longitude`)* 
				pi()/180))))*180/pi())*60*1.1515
			)'] = "<=" . (int) $intUseDistance . ')';
				
			$arrViewParams['filter_inline_unescaped']['a.latitude']  .= ' != 0';
			$arrViewParams['filter_inline_unescaped']['a.longitude'] .= ' != 0';
		}
	
		// Keyword search
		if (
			(! is_null($arrViewParams['keyword']))	 &&
			(strlen($arrViewParams['keyword']))
		) {
			$arrViewParams['escapeData'] = false;
			$arrViewParams['operator'][]  = "";
			$arrViewParams['operator'][]  = "";
			$arrViewParams['operator'][]  = "IN";
			$arrViewParams['filter']['(d.description'] = "REGEXP '" . $objDb->escape(trim($arrViewParams['keyword'])) .
			"' OR a.addressText REGEXP '" . $objDb->escape(trim($arrViewParams['keyword'])) . "') ";
		}
	
		foreach($arrViewParams['imagePositionId'] as $intIndex => $intImagePositionId) {
			// Set Column Selection
			$intImagePositionId = (int) $intImagePositionId;
			$strOriginalColumns = $arrViewParams['columns'];
			$arrViewParams['columns'] = 'imp' . $intImagePositionId . '.imageUrl as imagePosition' . $intImagePositionId . ", " . $strOriginalColumns;
	
			// Set Inner Join Selection
			$arrViewParams['left_join'][] 	= 	'listings_image imp' . $intImagePositionId . 
					' ON imp' . $intImagePositionId . '.listingsId = a.id ' .
					' AND imp' . $intImagePositionId . '.listingsImageTypeId = ' . $intImagePositionId .
					' AND imp' . $intImagePositionId . '.id IS NOT NULL';
		}
	
		// Set the group by
		$arrViewParams['groupBy'] = (strlen($arrViewParams['groupBy']) ? $arrViewParams['groupBy'] : 'a.id');
		$arrViewParams['groupBy'] = rtrim($arrViewParams['groupBy'], ',');
		
		// Return the class view
		return((true === $blnReturnArray) ? $arrViewParams : 
			\Core\Hybernate\Listings\Listings::getClassView($arrViewParams, $blnUseCache, $strCacheKey));
	}
	
	/**
	 * Method used to search through listings
	 *
	 * @access public, static
	 * @param  boolean $blnUsePagination - Wheather or not to use pagination
	 * @return \Core\Util\Pagination\Pagination
	 */
	public static final function search($blnUsePagination = true)
	{
		// http://stackoverflow.com/questions/6919661/select-within-20-kilometers-based-on-latitude-longitude
		// http://zcentric.com/2010/03/11/calculate-distance-in-mysql-with-latitude-and-longitude/comment-page-1/#comment-783
		// http://www.scribd.com/doc/2569355/Geo-Distance-Search-with-MySQL
		// http://stackoverflow.com/questions/1006654/fastest-way-to-find-distance-between-two-lat-long-points
		// http://stackoverflow.com/questions/1006654/fastest-way-to-find-distance-between-two-lat-long-points
		// http://stackoverflow.com/questions/574691/mysql-great-circle-distance-haversine-formula
		
		// Variables...
		$Application	 = \Core\Application::getInstance();
		$reqDispatcher   = \Core\Net\HttpRequest::getInstance();
		$arrFilter		 = array();
		$arrFilteredRad	 = array();
		$arrCenterPoint	 = array();
		
		$fltMinLat 	 	 = (float) $reqDispatcher->getRequestParam('minLat');
		$fltMaxLat 	 	 = (float) $reqDispatcher->getRequestParam('maxLat');
		$fltMinLng 	 	 = (float) $reqDispatcher->getRequestParam('minLng');
		$fltMaxLng 	 	 = (float) $reqDispatcher->getRequestParam('maxLng');
		$fltLat 	 	 = (float) $reqDispatcher->getRequestParam('lat');
		$fltLng 	 	 = (float) $reqDispatcher->getRequestParam('lng');
		
		$strLocation 	 = $reqDispatcher->getRequestParam('location');
		$blnRent   	 	 = $reqDispatcher->getRequestParam('rent');
		$blnSale   	 	 = $reqDispatcher->getRequestParam('sale');
		$order   	 	 = $reqDispatcher->getRequestParam('order');
		$sortOrder 	 	 = (int) $reqDispatcher->getRequestParam('sort');
		$fltPriceFrom 	 = (int) $reqDispatcher->getRequestParam('inputPriceFrom');
		$fltPriceTo      = (int) $reqDispatcher->getRequestParam('inputPriceTo');
		$intBuildingType = (int) $reqDispatcher->getRequestParam('buildingType');
		$intBedsAmt   	 = (int) $reqDispatcher->getRequestParam('beds');
		$intBathsAmt   	 = (int) $reqDispatcher->getRequestParam('baths');
		$closestList   	 = (int) $reqDispatcher->getRequestParam('closest');
		$limit      	 = (int) $reqDispatcher->getRequestParam('limit');
		
		$geoLocation = null;
		//$geoLocation     = $Application->getGeoLocator()->getGeoCoordinates();
		
		
		// Location settings
		// Try to get a more accurate lat/lng bounds
		if (
			(true === empty($fltMinLat)) ||
			(true === empty($fltMinLng))
		) {
			$objJsonResponse = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . urlencode($strLocation));	
			$objGeometry  	 = json_decode($objJsonResponse);
			
			if (false === empty($objGeometry->status) && ($objGeometry->status === 'OK')) {
					$objGeometryData = array_shift($objGeometry->results);
					if (
						(false === empty($objGeometryData->geometry->location->lat)) &&
						(false === empty($objGeometryData->geometry->location->lng))
					) {
						$arrFilteredRad['minLat'] = $objGeometryData->geometry->bounds->southwest->lat;
						$arrFilteredRad['minLon'] = $objGeometryData->geometry->bounds->southwest->lng;
						$arrFilteredRad['maxLat'] = $objGeometryData->geometry->bounds->northeast->lat;
						$arrFilteredRad['maxLon'] = $objGeometryData->geometry->bounds->northeast->lng;
						
						$arrCenterPoint['lat'] = $objGeometryData->geometry->location->lat;
						$arrCenterPoint['lng'] = $objGeometryData->geometry->location->lng;
						
						$reqDispatcher->setRequestParam('location', $objGeometryData->formatted_address);
					}
			}
		}
		else {
			$arrFilteredRad	= array(
				'minLat' => $fltMinLat,
				'maxLat' => $fltMaxLat,
				'minLon' => $fltMinLng,
				'maxLon' => $fltMaxLng						
			);
			
			$arrCenterPoint = array(
				'lat' => $fltLat,
				'lng' => $fltLng
			);
		}
		
		
		// Rent or Sale
		$blnRent   	 	 = $reqDispatcher->getRequestParam('rent');
		$order   	 	 = $reqDispatcher->getRequestParam('order');
		
		switch (true) {
			case (false === empty($blnSale) && true === empty($blnRent)) : {
				// Sale only
				// Rent or Sale	
				$arrInlineFilter = array('(a.price > ' => '0)');
				// Price details
				if (false === empty($fltPriceTo)) {
					$arrInlineFilter = array('(a.price BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ')' => '');
				}
				break;	
			}
			
			case (false === empty($blnRent) && true === empty($blnSale)) : {
				// Rent only
				// Rent or Sale	
				$arrInlineFilter = array('(a.leaseRent >' => '0)');
				// Price details
				if (false === empty($fltPriceTo)) {
					
					$arrInlineFilter = array('(a.leaseRent BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ')' => '');
				}
				break;	
			}
			
			default: {
				// Rent or Sale	
				$arrInlineFilter = array('(a.price > 0 OR a.leaseRent >' => '0)');
				// Price details
				if (false === empty($fltPriceTo)) {
					$arrInlineFilter = array('((a.price BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ') OR (' . 
											'a.leaseRent BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . '))' => '');
				}
			}
		}
		
		// Building Type...
		if (false === empty($intBuildingType)) {
			$arrFilter['a.listingsBuildingTypeId'] = $intBuildingType;
		}
		
		// Beds
		if (false === empty($intBedsAmt)) {
			$arrInlineFilter['a.bedrooms >='] = $intBedsAmt;
		}
		
		// bathrooms
		if (false === empty($intBathsAmt)) {
			$arrInlineFilter['a.bathrooms >='] = $intBathsAmt;
		}
		
		// Order by
		$__order = (false === empty($order) && (true === in_array(strtolower($order), array('asc', 'desc'))) ? $order : 'asc');
		//$__sortOrder = 'IF(distance_from_centerpoint IS NOT NULL, distance_from_centerpoint, a.price)';
		$__sortOrder = 'distance_from_centerpoint asc, a.price';
		
		if (false === empty($sortOrder)) {
			switch ($sortOrder) {
				case 1 : { $__sortOrder = 'a.price ' . $__order . ', a.leaseRent'; break; }	
				case 2 : { $__sortOrder = 'a.id'; break; }	
				case 3 : { $__sortOrder = (false === empty($geoLocation) ? 'distance' : 'a.id'); break; }	
			}
		}
		
		// Find by closes listings from centerpoint
		if ($closestList > 0) {
			$geoLocation['radius'] = 10;
			$arrFilteredRad = array();
		}
				
		// Begin class object view
		// array_merge($this->getApplication()->getGeoLocator()->getGeoCoordinates(), array('radius' => '16332'))
		
		$returnObject = false;
		if (true === $blnUsePagination) 
		{
			$objPagination = \Core\Util\Pagination\Pagination::getInstance();
			$objPagination->setDefaultItemsPerPage(15);
			$objPagination->setMidRange(6);
			$objPagination->setIsFriendlyUrl(true);
			$objPagination->setBaseUrl(\Core\Net\Url::getCanonicalUrl(NULL, false, true, true, array('page')));
			$objPagination->paginateFromClassObjectView('\Core\Hybernate\Listings\Listings', 
				\Core\Hybernate\Listings\Listings::getItemObjectClassView(array(
					'cacheQuery'		=> false,
					'sql_no_cache'		=> false,
					'center_point'		=> $arrCenterPoint,
					'filtered_radius'	=> $arrFilteredRad,
					'filter'			=> $arrFilter,
					'origin'			=> $geoLocation,
					'filter_inline_unescaped' => $arrInlineFilter,
					'imagePositionId' 	=> array(1),
					'orderBy'			=> $__sortOrder,
					'direction'			=> $__order,
					'debug' 			=> false
				), true, false, true, false));	
			
			$returnObject = $objPagination;
		} 
		else 
		{
			$returnObject = \Core\Hybernate\Listings\Listings::getItemObjectClassView(array(
				'cacheQuery'		=> false,
				'sql_no_cache'		=> false,
				'limit'				=> $limit,	
				'center_point'		=> $arrCenterPoint,
				'filtered_radius'	=> $arrFilteredRad,
				'filter'			=> $arrFilter,
				'origin'			=> $geoLocation,
				'filter_inline_unescaped' => $arrInlineFilter,
				'imagePositionId' 	=> array(1),
				'orderBy'			=> $__sortOrder,
				'direction'			=> $__order,
				'debug' 			=> false
			));
		}
		
		return ($returnObject);
	}
	
	/**
	 * Method used to search through listings
	 *
	 * @access public, static
	 * @param  boolean $blnUsePagination - Wheather or not to use pagination
	 * @return \Core\Util\Pagination\Pagination
	 */
	public static final function quickSearch($blnUsePagination = true)
	{
		// http://stackoverflow.com/questions/6919661/select-within-20-kilometers-based-on-latitude-longitude
		// http://zcentric.com/2010/03/11/calculate-distance-in-mysql-with-latitude-and-longitude/comment-page-1/#comment-783
		// http://www.scribd.com/doc/2569355/Geo-Distance-Search-with-MySQL
		// http://stackoverflow.com/questions/1006654/fastest-way-to-find-distance-between-two-lat-long-points
		// http://stackoverflow.com/questions/1006654/fastest-way-to-find-distance-between-two-lat-long-points
		// http://stackoverflow.com/questions/574691/mysql-great-circle-distance-haversine-formula
			
		// Variables...
		$Application	 = \Core\Application::getInstance();
		$reqDispatcher   = \Core\Net\HttpRequest::getInstance();
		$arrFilter		 = array();
		$arrFilteredRad	 = array();
		$arrCenterPoint	 = array();
			
		$fltMinLat 	 	 = (float) $reqDispatcher->getRequestParam('minLat');
		$fltMaxLat 	 	 = (float) $reqDispatcher->getRequestParam('maxLat');
		$fltMinLng 	 	 = (float) $reqDispatcher->getRequestParam('minLng');
		$fltMaxLng 	 	 = (float) $reqDispatcher->getRequestParam('maxLng');
		$fltLat 	 	 = (float) $reqDispatcher->getRequestParam('lat');
		$fltLng 	 	 = (float) $reqDispatcher->getRequestParam('lng');
			
		$strLocation 	 = $reqDispatcher->getRequestParam('location');
		$blnRent   	 	 = $reqDispatcher->getRequestParam('rent');
		$blnSale   	 	 = $reqDispatcher->getRequestParam('sale');
		$order   	 	 = $reqDispatcher->getRequestParam('order');
		$sortOrder 	 	 = (int) $reqDispatcher->getRequestParam('sort');
		$fltPriceFrom 	 = (int) $reqDispatcher->getRequestParam('inputPriceFrom');
		$fltPriceTo      = (int) $reqDispatcher->getRequestParam('inputPriceTo');
		$intBuildingType = (int) $reqDispatcher->getRequestParam('buildingType');
		$intBedsAmt   	 = (int) $reqDispatcher->getRequestParam('beds');
		$intBathsAmt   	 = (int) $reqDispatcher->getRequestParam('baths');
		$closestList   	 = (int) $reqDispatcher->getRequestParam('closest');
		$limit      	 = (int) $reqDispatcher->getRequestParam('limit');
		
		$geoLocation = null;
		//$geoLocation     = $Application->getGeoLocator()->getGeoCoordinates();
			
			
		// Location settings
		// Try to get a more accurate lat/lng bounds
		if (
				(true === empty($fltMinLat)) ||
				(true === empty($fltMinLng))
		) {
			$objJsonResponse = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . urlencode($strLocation));
			$objGeometry  	 = json_decode($objJsonResponse);
	
			if (false === empty($objGeometry->status) && ($objGeometry->status === 'OK')) {
				$objGeometryData = array_shift($objGeometry->results);
				if (
						(false === empty($objGeometryData->geometry->location->lat)) &&
						(false === empty($objGeometryData->geometry->location->lng))
				) {
					$arrFilteredRad['minLat'] = $objGeometryData->geometry->bounds->southwest->lat;
					$arrFilteredRad['minLon'] = $objGeometryData->geometry->bounds->southwest->lng;
					$arrFilteredRad['maxLat'] = $objGeometryData->geometry->bounds->northeast->lat;
					$arrFilteredRad['maxLon'] = $objGeometryData->geometry->bounds->northeast->lng;
						
					$arrCenterPoint['lat'] = $objGeometryData->geometry->location->lat;
					$arrCenterPoint['lng'] = $objGeometryData->geometry->location->lng;
						
					$reqDispatcher->setRequestParam('location', $objGeometryData->formatted_address);
				}
			}
		}
		else {
			$arrFilteredRad	= array(
					'minLat' => $fltMinLat,
					'maxLat' => $fltMaxLat,
					'minLon' => $fltMinLng,
					'maxLon' => $fltMaxLng
			);
	
			$arrCenterPoint = array(
					'lat' => $fltLat,
					'lng' => $fltLng
			);
		}
			
			
		// Rent or Sale
		$blnRent   	 	 = $reqDispatcher->getRequestParam('rent');
		$order   	 	 = $reqDispatcher->getRequestParam('order');
			
		switch (true) {
			case (false === empty($blnSale) && true === empty($blnRent)) : {
				// Sale only
				// Rent or Sale
				$arrInlineFilter = array('(a.price > ' => '0)');
				// Price details
				if (false === empty($fltPriceTo)) {
					$arrInlineFilter = array('(a.price BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ')' => '');
				}
				break;
			}
	
			case (false === empty($blnRent) && true === empty($blnSale)) : {
				// Rent only
				// Rent or Sale
				$arrInlineFilter = array('(a.leaseRent >' => '0)');
				// Price details
				if (false === empty($fltPriceTo)) {
	
					$arrInlineFilter = array('(a.leaseRent BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ')' => '');
				}
				break;
			}
	
			default: {
				// Rent or Sale
				$arrInlineFilter = array('(a.price > 0 OR a.leaseRent >' => '0)');
				// Price details
				if (false === empty($fltPriceTo)) {
					$arrInlineFilter = array('((a.price BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ') OR (' .
							'a.leaseRent BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . '))' => '');
				}
			}
		}
			
		// Building Type...
		if (false === empty($intBuildingType)) {
			$arrFilter['a.listingsBuildingTypeId'] = $intBuildingType;
		}
			
		// Beds
		if (false === empty($intBedsAmt)) {
			$arrInlineFilter['a.bedrooms >='] = $intBedsAmt;
		}
			
		// bathrooms
		if (false === empty($intBathsAmt)) {
			$arrInlineFilter['a.bathrooms >='] = $intBathsAmt;
		}
			
		// Order by
		$__order = (false === empty($order) && (true === in_array(strtolower($order), array('asc', 'desc'))) ? $order : 'asc');
		//$__sortOrder = 'IF(distance_from_centerpoint IS NOT NULL, distance_from_centerpoint, a.price)';
		$__sortOrder = 'distance_from_centerpoint asc, a.price';
			
		if (false === empty($sortOrder)) {
			switch ($sortOrder) {
				case 1 : { $__sortOrder = 'a.price ' . $__order . ', a.leaseRent'; break; }
				case 2 : { $__sortOrder = 'a.id'; break; }
				case 3 : { $__sortOrder = (false === empty($geoLocation) ? 'distance' : 'a.id'); break; }
			}
		}
			
		// Find by closes listings from centerpoint
		if ($closestList > 0) {
			$geoLocation['radius'] = 10;
			$arrFilteredRad = array();
		}
		/*
		LISTINGS::getItemObjectClassView(array(
			'cacheQuery'		=> false,
			'sql_no_cache'		=> false,
			'center_point'		=> $arrCenterPoint,
			'filtered_radius'	=> $arrFilteredRad,
			'filter'			=> $arrFilter,
			'origin'			=> $geoLocation,
			'filter_inline_unescaped' => $arrInlineFilter,
			'imagePositionId' 	=> array(1),
			'orderBy'			=> $__sortOrder,
			'direction'			=> $__order,
			'debug' 			=> true
		));
		*/

		// Begin class object view
		// array_merge($this->getApplication()->getGeoLocator()->getGeoCoordinates(), array('radius' => '16332'))
		$returnObject = false;
		if (true === $blnUsePagination)
		{
			$objPagination = \Core\Util\Pagination\Pagination::getInstance();
			$objPagination->setDefaultItemsPerPage(15);
			$objPagination->setMidRange(6);
			$objPagination->setIsFriendlyUrl(true);
			$objPagination->setBaseUrl(\Core\Net\Url::getCanonicalUrl(NULL, false, true, true, array('page')));
			$objPagination->paginateFromClassObjectView('\Core\Hybernate\Listings\Listings', 
				\Core\Hybernate\Listings\Listings::getItemObjectClassView(array(
					'cacheQuery'		=> false,
					'sql_no_cache'		=> false,
					'center_point'		=> $arrCenterPoint,
					'filtered_radius'	=> $arrFilteredRad,
					'filter'			=> $arrFilter,
					'origin'			=> $geoLocation,
					'filter_inline_unescaped' => $arrInlineFilter,
					'imagePositionId' 	=> array(1),
					'orderBy'			=> $__sortOrder,
					'direction'			=> $__order,
					'debug' 			=> false
			), true, false, true, false));
	
			$returnObject = $objPagination;
		}
		else
		{
			$returnObject = \Core\Hybernate\Listings\Listings::getItemObjectClassView(array(
					'cacheQuery'		=> false,
					'sql_no_cache'		=> false,
					'limit'				=> $limit,
					'center_point'		=> $arrCenterPoint,
					'filtered_radius'	=> $arrFilteredRad,
					'filter'			=> $arrFilter,
					'origin'			=> $geoLocation,
					'filter_inline_unescaped' => $arrInlineFilter,
					'imagePositionId' 	=> array(1),
					'orderBy'			=> $__sortOrder,
					'direction'			=> $__order,
					'debug' 			=> false
			));
		}
			
		return ($returnObject);
	}
}