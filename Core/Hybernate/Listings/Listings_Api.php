<?php
namespace Core\Hybernate\Listings;
/**
 * Listings Api management used with Hybernate loader
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
class Listings_Api extends \Core\Hybernate\Listings\Listings
{
	/**
      * Requested search parameters to use the api search method
      *
      * @access protected
      * @var array
      */
     protected $_REQUESTED_SEARCH_PARAMS = array();

     /**
      * Available search parameters to use the api search method
      *
      * @access protected
      * @var array
      */
     protected $_AVAILABLE_SEARCH_PARAMS = array(
        'location'            => 'string',
        'buildingType'        => 'int',
        'roomType'            => 'array',
        'beds'                => 'int',
        'baths'               => 'int',
        'priceMin'            => 'int',
        'priceMax'            => 'int',
        'order'               => 'string',
        'sort'                => 'string',
        'limit'               => 'int',
        'page'                => 'int',
        'lang'                => 'string',
        'filtered_radius'     => 'array',
        'closest'               => 'boolean'
     );

     /**
      * Geometry object
      *
      * @access protected
      * @var Object
      */
     protected $_GEOMETRY_API_DATA = array();
	 
	 /**
	  * Trigger on before save
	  *
	  * @access public
	  * @return void;
	  */
     protected final function onBeforeSave()
     {
         // Block saving features
         $this->arrChanges = array();
     }
	 
	 /**
	  * Processes request params
	  *
	  * @access public
	  * @return void;
	  */
     public final function processParams()
     {
        foreach ($this->_REQUESTED_SEARCH_PARAMS as $index => $paramValue)
        {
            switch ($index)
            {
                //case 'filtered_radius' :
                case 'location' :
                {
                    // Here, we override the location data if sw_lat, sw_lng, ne_lat and ne_lng params
                    // are provided (this is provided when the map is dragged
                    // USING the $arrViewParams['filtered_radius'] property
                    if (
                        (empty($this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['sw_lat']) === false) &&
                        (empty($this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['sw_lng']) === false) &&
                        (empty($this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['ne_lat']) === false) &&
                        (empty($this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['ne_lng']) === false)
                    ) {
                        continue;
                    }

                    //
                    // Otherwise, we filter by location
                    //
                    $objJsonResponse = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBY0uU2dbDCyhE-7_ClSgLjLmLsa_8dZ38&sensor=false&address=' .
                                        urlencode($paramValue));
                    $objGeometry       = json_decode($objJsonResponse, true);

                    if (false === empty($objGeometry['status']) && ($objGeometry['status'] === 'OK')) {
                        $objGeometryData = array_shift($objGeometry['results']);
                        $this->_GEOMETRY_API_DATA = $objGeometryData;
						
                        if (
                                (false === empty($objGeometryData['geometry']['location']['lat'])) &&
                                (false === empty($objGeometryData['geometry']['location']['lng']))
                        ) {
                            $__locationData             = array();
                            $__locationData['location'] = $objGeometryData['formatted_address'];

                            $this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['sw_lat'] = $objGeometryData['geometry']['bounds']['southwest']['lat'];
                            $this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['sw_lng'] = $objGeometryData['geometry']['bounds']['southwest']['lng'];
                            $this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['ne_lat'] = $objGeometryData['geometry']['bounds']['northeast']['lat'];
                            $this->_REQUESTED_SEARCH_PARAMS['filtered_radius']['ne_lng'] = $objGeometryData['geometry']['bounds']['northeast']['lng'];
                        }
                    }

                    $this->_REQUESTED_SEARCH_PARAMS[$index] = $__locationData;

                    break;
                }
            }
        }

        return ($this->_REQUESTED_SEARCH_PARAMS);
     }
	
	 /**
	  * Sets a request param
	  *
	  * @param  string $searchParamKey   The request param key
	  * @access string $searchParamValue The request param value
	  * @access string $defaultValue     The default request param value
	  * @access public
	  * @return void;
	  */
     public final function setSearchParam($searchParamKey, $searchParamValue = null, $defaultValue = null)
     {
         if (true === array_key_exists($searchParamKey, $this->_AVAILABLE_SEARCH_PARAMS))
         {
			 $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey] = null;
			 
             switch ($this->_AVAILABLE_SEARCH_PARAMS[$searchParamKey])
             {
                     case 'string' :
                        $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey] = $searchParamValue;
                        if (false === empty($defaultValue) && true === empty($searchParamValue)) {
                            $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey] = $defaultValue;
                        }
                        break;

                    case 'int' :
                        if ((int) $searchParamValue > 0)
                            $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey] = (int) $searchParamValue;
                        else if ((int) $defaultValue > 0)
                            $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey] = (int) $defaultValue;
                        break;

                    case 'array' :
                        $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey] = (array) $searchParamValue;
                        break;

                    case 'boolean' :
                        $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey] = (bool) $searchParamValue;
                        break;
             }
         }
     }

     /**
	  * Sets a request param
	  *
	  * @param  string $searchParamKey The request param key
	  * @access public
	  * @return String;
	  */
     public final function getSearchParam ($searchParamKey)
     {
         $returnData = false;

         if (true === array_key_exists($searchParamKey, $this->_REQUESTED_SEARCH_PARAMS)) {
             $returnData = $this->_REQUESTED_SEARCH_PARAMS[$searchParamKey];
         }

         return ($returnData);
     }

	 /**
	  * Initiates a api search
	  *
	  * @param  boolean $usePagination Weather or not to return a \Core\Util\Pagination\Pagination instance
	  * @access public
	  * @return \Core\Util\Pagination\Pagination | array;
	  */
     public final function apiSearch($usePagination = true)
     {
        $requestParams = $this->processParams();

        // Class object view array
        $arrFilter       = array();
        $arrInlineFilter = array();
        $arrFilteredRad  = array();
        $geoLocation     = array();

        $arrCenterPoint = $this->calculateCenter(array(array(
            'latitude'  => $requestParams['filtered_radius']['sw_lat'],
            'longitude' => $requestParams['filtered_radius']['sw_lng'],
        ), array(
            'latitude'  => $requestParams['filtered_radius']['ne_lat'],
            'longitude' => $requestParams['filtered_radius']['ne_lng'],
        )));

        // Variables
        $blnRent         = (true === in_array('rent', $requestParams['roomType']));
        $blnSale         = (true === in_array('sale', $requestParams['roomType']));
        $fltPriceFrom    = (int) $requestParams['priceMin'];
        $fltPriceTo      = (int) $requestParams['priceMax'];
        $intBuildingType = $requestParams['buildingType'];
        $intBedsAmt      = $requestParams['beds'];
        $intBathsAmt     = $requestParams['baths'];
        $order           = $requestParams['order'];
        $sortOrder       = $requestParams['sort'];     // 1 - price, 2 - id

        // Filter by map bounds
        // Used when dragging the map
        if (empty($requestParams['filtered_radius']) === false)
        {
            $arrFilteredRad    = array(
                'minLat' => $requestParams['filtered_radius']['sw_lat'],
                'minLon' => $requestParams['filtered_radius']['sw_lng'],
                'maxLat' => $requestParams['filtered_radius']['ne_lat'],
                'maxLon' => $requestParams['filtered_radius']['ne_lng']
            );
        }
		
		/*- TODO: Fix me!!
        if (true === $requestParams['closest']) {
            $Application             = \Core\Application::getInstance();
            $geoLocation             = $Application->getGeoLocator()->getGeoCoordinates();
            $geoLocation['radius']     = 10;
            $arrFilteredRad         = array();
        }
		-*/

        // ------------------------
        // Rent or Sale and price
        // ------------------------
        switch (true) {
            // Sale only
            case (true === $blnSale && false === $blnRent) : {
                $arrInlineFilter = array('(a.price > ' => '0)');
                // Price details
                $arrInlineFilter = array('(a.price BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ')' => '');
                break;
            }

            // Rent only
            case (false === empty($blnRent) && true === empty($blnSale)) : {
                $arrInlineFilter = array('(a.leaseRent >' => '0)');
                // Price details
                $arrInlineFilter = array('(a.leaseRent BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ')' => '');
                break;
            }

            default: {
                // Rent or Sale
                $arrInlineFilter = array('(a.price > 0 OR a.leaseRent >' => '0)');
                // Price details
                $arrInlineFilter = array('((a.price BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . ') OR (' .
                                        'a.leaseRent BETWEEN ' . $fltPriceFrom . ' AND ' . $fltPriceTo . '))' => '');
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
        $__order     = (false === empty($order) && (true === in_array(strtolower($order), array('asc', 'desc'))) ? $order : 'asc');
        $__sortOrder = 'distance_from_centerpoint asc, a.id';

        if (false === empty($sortOrder)) {
            switch ($sortOrder) {
                case 1 : { $__sortOrder = 'a.price ' . $__order . ', a.leaseRent'; break; }
                case 2 : { $__sortOrder = 'a.id'; break; }
            }
        }

        if (true === $usePagination) {
            //$objDatabase = APPLICATION::getInstance()->getDatabase();
            //$objDatabase->iQuery('SET CHARACTER SET utf8');
            $objPagination = \Core\Util\Pagination\Pagination::getInstance();
            $objPagination->setDefaultItemsPerPage(18);
            $objPagination->setMidRange(6);
            $objPagination->setIsFriendlyUrl(true);
            $objPagination->setLocation($requestParams['location']);
            $objPagination->setCenterPoint($arrCenterPoint);
            $objPagination->setGeometry($this->_GEOMETRY_API_DATA);
            $objPagination->setBaseUrl(\Core\Net\Url::getCanonicalUrl(NULL, false, true, true, array('page')));
            $objPagination->paginateFromClassObjectView('\Core\Hybernate\Listings\Listings', 
				\Core\Hybernate\Listings\Listings::getItemObjectClassView(array(
                    'cacheQuery'                => false,
                    'sql_no_cache'              => false,
                    'filtered_radius'           => $arrFilteredRad,
                    'origin'                    => $geoLocation,
                    'filter'                    => $arrFilter,
                    'filter_inline_unescaped'   => $arrInlineFilter,
                    'center_point'              => $arrCenterPoint,
                    'imagePositionId'           => array(1),
                    'orderBy'                   => $__sortOrder,
                    'direction'                 => $__order,
                    'debug'                     => false
            ), true, false, true, true));

        } else {

            $objPagination = \Core\Hybernate\Listings\Listings::getItemObjectClassView(array(
                    'cacheQuery'                => false,
                    'sql_no_cache'              => false,
                    'filtered_radius'           => $arrFilteredRad,
                    'origin'                    => $geoLocation,
                    'filter'                    => $arrFilter,
                    'filter_inline_unescaped'   => $arrInlineFilter,
                    'center_point'              => $arrCenterPoint,
                    'imagePositionId'           => array(1),
                    'orderBy'                   => $__sortOrder,
                    'direction'                 => $__order,
                    'debug'                     => false
            ));
        }

        return ($objPagination);
     }
}