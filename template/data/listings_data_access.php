<?php
namespace core\api;

class_exists('\\Core\\Application') || require_once realpath(dirname(__FILE__)) . '../../Core/Application.php';

\Core\Application::bootstrapResource("\\Core\\Hybernate\\Listings\\Listings");
\Core\Application::bootstrapResource("\\Core\\Hybernate\\Listings\\Listings_Api");
\Core\Application::bootstrapResource("\\Core\\Hybernate\\Listings\\Listings_Description");
\Core\Application::bootstrapResource("\\Core\\Hybernate\\Listings\\Listings_Building_Type");
\Core\Application::bootstrapResource("\\Core\\Hybernate\\Individual\\Individual_Phones");

/**
    TODO:
        1. Add the agent to listings result
        2. Add the proper filters from database
        3. Add multi language support

**/

class Listings
    extends \Exception
    implements \Iterator
{
    public function rewind()     {}
    public function current()     {}
    public function key()         {}
    public function next()         {}
    public function valid()     {}

    /**
     * Returns the request params and defaults them to null
     *
     * @access protected, static
     * @return array
     */
    public static final function _getRequestParams()
    {
        $requestDispatcher = \Core\Net\HttpRequest::getInstance();

        return array_merge(array(
            'location'        => null,
            'sw_lat'          => null,
            'sw_lng'          => null,
            'ne_lat'          => null,
            'ne_lng'          => null,
            'buildingType'    => null,
            'roomType'        => null,
            'priceMin'        => null,
            'priceMax'        => null,
            'beds'            => null,
            'baths'           => null,
            'page'            => 1,
            'order'           => null,
            'sort'            => null,
            'room_types'      => null,
            'price_min'       => null,
            'price_max'       => null,
            'min_bedrooms'    => null,
            'min_bathrooms'   => null,
            'closest'         => null

        ), $requestDispatcher->getRequestParams());
    }

    /**
     * Gets the api search data
     *
     * @param  boolean $usePagination use the pagination object
     * @return \Core\Util\Pagination\Pagination | array
     */
    public static final function getSearchData($usePagination = true)
    {
        $Application   = \Core\Application::getInstance();
        $requestParams = \core\api\Listings::_getRequestParams();
        $listingOutput = array();

        $listingsApi = \Core\Hybernate\Listings\Listings_Api::getInstance();
        $listingsApi->setSearchParam('location', $requestParams ['location'], 'Montreal, Qc. Canada');
        $listingsApi->setSearchParam('filtered_radius', array(
            'sw_lat' => $requestParams ['sw_lat'],
            'sw_lng' => $requestParams ['sw_lng'],
            'ne_lat' => $requestParams ['ne_lat'],
            'ne_lng' => $requestParams ['ne_lng']), array());
        $listingsApi->setSearchParam('buildingType', $requestParams ['buildingType']);
        $listingsApi->setSearchParam('roomType', $requestParams ['room_types']);
        $listingsApi->setSearchParam('priceMin', $requestParams ['price_min'], 1);
        $listingsApi->setSearchParam('priceMax', $requestParams ['price_max'], 10000000);
        $listingsApi->setSearchParam('beds', $requestParams ['min_bedrooms']);
        $listingsApi->setSearchParam('baths', $requestParams ['min_bathrooms']);
        $listingsApi->setSearchParam('page', $requestParams ['page'], 1);
        $listingsApi->setSearchParam('closest', $requestParams ['closest']);

        return $listingsApi->apiSearch($usePagination);
    }

    public static final function getListingsCollection()
    {
        $Application       = \Core\Application::getInstance();
        $requestParams     = \core\api\Listings::_getRequestParams();
        $listingOutput     = array();

        $listingsApi = \Core\Hybernate\Listings\Listings_Api::getInstance();
        $listingsApi->setSearchParam('location', $requestParams ['location'], 'Montreal, Qc. Canada');
        $listingsApi->setSearchParam('filtered_radius', array(
            'sw_lat' => $requestParams ['sw_lat'],
            'sw_lng' => $requestParams ['sw_lng'],
            'ne_lat' => $requestParams ['ne_lat'],
            'ne_lng' => $requestParams ['ne_lng']), array());
        $listingsApi->setSearchParam('buildingType', $requestParams ['buildingType']);
        $listingsApi->setSearchParam('roomType', $requestParams ['room_types']);
        $listingsApi->setSearchParam('priceMin', $requestParams ['price_min'], 1);
        $listingsApi->setSearchParam('priceMax', $requestParams ['price_max'], 10000000);
        $listingsApi->setSearchParam('beds', $requestParams ['min_bedrooms']);
        $listingsApi->setSearchParam('baths', $requestParams ['min_bathrooms']);
        $listingsApi->setSearchParam('page', $requestParams ['page'], 1);
        $listingsApi->setSearchParam('closest', $requestParams ['closest']);
        $listingsApi->setSearchParam('order', $requestParams ['order']);
        $listingsApi->setSearchParam('sort', $requestParams ['sort']);

        $listingsData = $listingsApi->apiSearch(true);

        // -------------------------------------------
        // Build the listings template
        // -------------------------------------------
        $listingsTemplate     = '<ul id="results" class="listings-container list-unstyled clearfix">%s</ul>';
        $listingItemTemplate  = '';
        $propertyIds          = array();
        $listings             = $listingsData->getpageData();

        if (empty($listings) === true) {
            $listingItemTemplate  .= '
                <div class="alert disaster-rooster row-space-4"> <a href="#" class="alert-close close">Ã—</a>
                    <div class="h4 row-space-2">
                        Oops! We didn\'t find anything. You can refine your search by using the filters.
                    </div>' . (empty($requestParams ['closest']) === true ?
                    '<a class="btn btn-primary closest_listings">Show me whats near by</a>' : '') .
                    '<a class="btn btn-special" data-url-prefix="/disaster/"> Notify Me When Something is Available </a>
                </div>
            ';
        }

        foreach ($listings as $listingsDetail) {
            $propertyIds[] = $listingsDetail['id'];
            $arrdress      = explode('|', $listingsDetail['addressText']);
            $strImage      = (false === empty($listingsDetail['imagePosition1']) ? $listingsDetail['imagePosition1'] : '/static/images/no-image/no_image.jpg');
            $detailUrl     = \Core\Hybernate\Listings\Listings_Api::createFriendlyUrl($listingsDetail);
            $individualWeb = (empty($listingsDetail['individual_website']) === false ? $listingsDetail['individual_website'] : $listingsDetail['org_website']);
            $individualImg = (empty($listingsDetail['individual_photo']) === false ? $listingsDetail['individual_photo'] : $listingsDetail['org_logo']);
            if ((int) $listingsDetail['price'] > 0) {
                $price  = number_format($listingsDetail['price'], 2);
                $suffix = '';
            } else {
                $price  = number_format($listingsDetail['leaseRent'], 2);
                $suffix = ' / ' . $Application->translate('Monthly', 'Mensuel', 'æ¯�æœˆä¸€æ¬¡');
            }

            // /listing-detail/quick-view/id:

            $listingItemTemplate  .= '
                <li class="search-result">
                    <div data-lat="' . $listingsDetail['latitude'] . '"
                           data-lng="' . $listingsDetail['longitude'] . '"
                           data-name="' . (substr($listingsDetail['description'], 0, 80)) . '"
                           data-url="' . $detailUrl . '"
                           data-id="' . $listingsDetail['id'] . '"
                           class="listing">
                      <div class="listing-img media-photo">
                          <a class="wish_list_button quick_view cboxElement"
                               href="./quick-view.php?id=' . $listingsDetail['id'] . '"
                               data-hosting_id="' . $listingsDetail['id'] . '"
                               data-name="' . (substr($listingsDetail['description'], 0, 80)) . '"
                               data-address="' . $arrdress[0] . '"
                               data-img="' . $strImage . '"
                               rel="tooltip"
                               data-tooltip-position="right"
                               title="' . $Application->translate('Quick View', 'Vue rapide', 'å¿«é€ŸæŸ¥çœ‹') . '"><i class="icon icon-eye icon-white"></i></a>

                        <div class="listing-img-container">
                            <img src="' . $strImage . '"
                                 data-current="0"
                            data-urls="[&quot;' . $strImage . '&quot;]" />
                        </div>
                        <div class="target-prev listing-slideshow-target block-link">
                            <i class="icon icon-chevron-left listing-slideshow-chevron"></i>
                        </div>

                        <a class="target-details listing-slideshow-target block-link"
                             href="' . $detailUrl . '"></a>
                        <div class="target-next listing-slideshow-target block-link">
                            <i class="icon icon-chevron-right listing-slideshow-chevron"></i>
                        </div>
                        <a class="listing-name media-caption h4" href="' . $detailUrl . '">
                            ' . $arrdress[0] . '
                            <span class="btn btn-primary btn-small instant-book-button pull-right">
                                  ' . ($listingsDetail['listingsBuildingTypeName']) . '
                            </span><br />
                        </a>
                      </div>

                      <div class="listing-footer clearfix">
                          <a href="' . $individualWeb . '" class="media-link media-photo host-img"><img src="' . $individualImg . '" /></a>
                            <a class="listing-quick-info"
                                 href="' . $detailUrl . '"
                                 title="' . (substr($listingsDetail['description'], 0, 80)) . '">
                                <span class="listing-room-type"><strong>' . $listingsDetail['individual_full_name'] . '</strong></span>
                                <span class="listing-room-type">' . substr(strtolower(ucwords($listingsDetail['org_name'])), 0, 24) .
                                    (strlen($listingsDetail['org_name'] >= 24) ? '...' : '') . '</span>
                            </a>
                            <a class="listing-price" href="' . $detailUrl . '">
                                <span class="shift text-special price">
                                    <span class="currency">$</span>
                                    <span class="h2 price-amount">' . $price . '</span>
                                </span>
                                <span class="price-sub">' . $suffix . '</span>
                            </a>
                        </div>
                    </div>
                  </li>
            ';
        }

       $listingOutput['results'] = sprintf($listingsTemplate, $listingItemTemplate);

        // -------------------------------------------
        // Build the filters template
        // -------------------------------------------
        $strBuildingTypes = '';
        $appartmentsType  = \Core\Hybernate\Listings\Listings_Building_Type::getObjectClassView(array(
                'cacheQuery'            => true,
                'columns'               => array('a.id', 'b.display_name'),
                'inner_join'            => array(
                        'listings_building_type_description b' => 'b.listingsBuildingTypeId = a.id AND b.langId = ' . $Application->translate(1, 2, 3)
                ),
                'orderBy'                       => 'a.id',
                'direction'                     => 'ASC'
        ));

        foreach ($appartmentsType as $intIndex => $arrListingsType) {
            $strBuildingTypes .= '<option ' . ((int) $requestParams ['buildingType'] === (int) $arrListingsType['id'] ? 'selected' : '') .
                                 ' value="' . $arrListingsType['id'] . '">' . $arrListingsType['display_name'] . '</option>';
        }

        $listingOutput['filters'] = sprintf('
            <div class="intro-filter clearfix filters-section" data-behavior="tooltip" data-position="left" title="Trip">
                <input type="checkbox" class="closest" name="closest" ' . ((empty($requestParams ['closest']) === false) ? 'checked' : '') . ' style="display: none" />

                <h6 class="filter-label col-3">Listing Type:</h6>
                <form class="form-horizontal trip-form">
                    <!--<i class="icon icon-arrow-right icon-gray"></i>-->
                    <div class="select input-large">
                        <div class="controls relative pull-left searchLocFrm">
                            <input type="text" name="location" value="" placeholder="Type a location" id="map_filter_location" autocomplete="off" />
                            <a href="#" data-bind="#map_filter_location" class="__location btn btn-mini btn-primary __tooltip" original-title="' .
                            $Application->translate('Use my current location', 'Utiliser ma position actuelle', 'ä½¿ç”¨æˆ‘çš„å½“å‰�ä½�ç½®') . '" style="opacity: 1;"></a>
                        </div>
                    </div>

                    <div class="select input-large">
                        <div class="controls relative pull-left">
                            <select id="listingsBuildingType" name="buildingType" class="chosen listingsBuildingType">
                                <option value="">Select a building type</option>
                                %s
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="clearfix room-type-group intro-filter filters-section" data-behavior="tooltip" data-position="left" title="Room Type">
                <h6 class="filter-label col-3">Room Type</h6>
                <div class="btn btn-large btn-flat icon-toggle" data-name="rent"> <i class="icon icon-entire-place"></i>
                    <div class="h5">For Rent</div>
                </div>
                <div class="btn btn-large btn-flat icon-toggle" data-name="sale"> <i class="icon icon-private-room"></i>
                    <div class="h5">For Sale</div>
                </div>
                <div class="btn btn-large btn-flat icon-toggle" data-name="Commercial"> <i class="icon icon-city"></i>
                    <div class="h5">Commercial</div>
                </div>
                <i class="icon icon-question icon-gray" id="room-type-tooltip"></i>
                <div class="tooltip tooltip-right-top" role="tooltip" data-trigger="#room-type-tooltip">
                    <div class="panel-body">
                        <h5>Entire Place</h5>
                        <p>Listings where you have the whole place to yourself.</p>
                    </div>
                    <div class="panel-body">
                        <h5>Private Room</h5>
                        <p>Listings where you have your own room but share some common spaces.</p>
                    </div>
                    <div class="panel-body">
                        <h5>Shared Room</h5>
                        <p>Listings where you\'ll share your room or your room may be a common space.</p>
                    </div>
                </div>
            </div>
            <div class="clearfix exp-type-group filters-section intro-filter hide" data-behavior="tooltip" data-position="left" title="Experience">
                <h6 class="filter-label col-3">Experience</h6>
                <div class="btn btn-large btn-flat icon-toggle" data-name="business"> <i class="icon icon-suitcase"></i>
                    <div class="h5">Business</div>
                </div>
                <div class="btn btn-large btn-flat icon-toggle" data-name="family"> <i class="icon icon-family"></i>
                    <div class="h5">Family friendly</div>
                </div>
                <div class="btn btn-large btn-flat icon-toggle" data-name="romantic"> <i class="icon icon-wine-glasses"></i>
                    <div class="h5">Romantic</div>
                </div>
                <div class="btn btn-large btn-flat icon-toggle" data-name="social"> <i class="icon icon-comments"></i>
                    <div class="h5">Social</div>
                </div>
                <i class="icon icon-question icon-gray" id="exp-type-tooltip"></i>
                <div class="tooltip tooltip-right-top" role="tooltip" data-trigger="#exp-type-tooltip">
                    <div class="panel-body">
                        <h5>Business</h5>
                        <p>Listings that are perfectly suited for the busy, travelling professional.</p>
                    </div>
                    <div class="panel-body">
                        <h5>Family friendly</h5>
                        <p>Listings that are comfortable and spacious for families with kids of all ages.</p>
                    </div>
                    <div class="panel-body">
                        <h5>Romantic</h5>
                        <p>Listings that are perfect for couples who want some romantic privacy.</p>
                    </div>
                    <div class="panel-body">
                        <h5>Social</h5>
                        <p>Listings where you can make new friends and experience the local culture.</p>
                    </div>
                </div>
            </div>
            <div class="clearfix intro-filter filters-section" data-behavior="tooltip" data-position="left" title="Price">
                <h6 class="filter-label col-3">Price</h6>
                <div class="price-range-slider" data-min-price-daily="1000" data-max-price-daily="10000000">
                    <div class="ui-slider-handle"></div>
                    <div class="ui-slider-handle"></div>
                    <p class="min-price">Min Price: <strong> $<span class="price"></span> CAD </strong>
                    </p>
                    <p class="max-price">Max Price: <strong> $<span class="price"></span> CAD </strong>
                    </p>
                </div>
            </div>
            <div class="clearfix size-group filters-section" data-behavior="tooltip" data-position="left" title="Size">
                <h6 class="filter-label col-3">Size</h6>
                <form class="form-horizontal">
                    <div class="select input-medium">
                        <select name="min_bedrooms">
                            <option value="-1">Bedrooms</option>
                            <option value="1">1 Bedroom</option>
                            <option value="2">2 Bedrooms</option>
                            <option value="3">3 Bedrooms</option>
                            <option value="4">4 Bedrooms</option>
                            <option value="5">5 Bedrooms</option>
                            <option value="6">6 Bedrooms</option>
                            <option value="7">7 Bedrooms</option>
                            <option value="8">8 Bedrooms</option>
                            <option value="9">9 Bedrooms</option>
                            <option value="10">10 Bedrooms</option>
                        </select>
                    </div>
                    <div class="select input-medium">
                        <select name="min_bathrooms" class="input-medium">
                            <option value="-1">Washrooms</option>
                            <option value="0.0">0 Washrooms</option>
                            <option value="0.5">0.5 Washrooms</option>
                            <option value="1.0">1 Bathroom</option>
                            <option value="1.5">1.5 Washrooms</option>
                            <option value="2.0">2 Washrooms</option>
                            <option value="2.5">2.5 Washrooms</option>
                            <option value="3.0">3 Washrooms</option>
                            <option value="3.5">3.5 Washrooms</option>
                            <option value="4.0">4 Washrooms</option>
                            <option value="4.5">4.5 Washrooms</option>
                            <option value="5.0">5 Washrooms</option>
                            <option value="5.5">5.5 Washrooms</option>
                            <option value="6.0">6 Washrooms</option>
                            <option value="6.5">6.5 Washrooms</option>
                            <option value="7.0">7 Washrooms</option>
                            <option value="7.5">7.5 Washrooms</option>
                            <option value="8">8+ Washrooms</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="clearfix keywords filters-section" data-behavior="tooltip" data-position="left" title="Keywords">
                <h6 class="filter-label col-3">Keywords</h6>
                <form class="form-horizontal">
                    <input class="keywords-input" type="text" name="keywords" placeholder="ocean side, transit, relaxing...">
                </form>
            </div>
            <div class="filters-footer">
                <button class="btn btn-block btn-large btn-primary search-button">Show Listings</button>
            </div>
        ', $strBuildingTypes);

        // -------------------------------------------
        // Build the fotter pagination template
        // -------------------------------------------
        $scopeGeometry = $listingsData->getGeometry();

        // Remove the first 2 entries
        if (empty($scopeGeometry) === false) {
            $scopeGeometry = array_slice((array) $scopeGeometry, 2);
        }

        $listingOutput['pagination_footer'] = '
            <div class="results-footer">
              <div class="page-divider"> </div>
              <div class="pagination-buttons-container">
                <div class="results_count">
                  <h4> ' . $listingsData->getCurrentPage() . ' &ndash; ' . $listingsData->getTotalPages() . ' of ' . $listingsData->getItemsTotal() . ' Listings </h4>
                </div>
                <div class="pagination">
                    <ul class="list-unstyled">%s</ul>
                </div>
              </div>
              <div class="breadcrumbs" itemprop="breadcrumb">
                  %s
               </div>
            </div>
        ';

        // Pages template
        $strPageTemplate = '';
        $arrPaginationLinks = $listingsData->getPaginationLinks();
        while (list($intIndex, $arrPageLinkData) = each($arrPaginationLinks)) {
            $arrPageLinkData = array_merge(array(
                'link_type' => null,
                'class'     => null,
                'page'      => null,
                'href'      => null,
                'text'        => null
            ), $arrPageLinkData);

            if ($arrPageLinkData['link_type'] == 'all') continue;

            $strPageTemplate .= '<li class="' . ($arrPageLinkData['class'] | '') . '"><a target="' . $arrPageLinkData['page'] .
                                '" href="' . $arrPageLinkData['href'] . '">' . $arrPageLinkData['text'] . '</a></li>';
            /*
            <li class="active"><a rel="start" target="1" href="/s?cdn_spdy=1&amp;location=Laval%2C+QC%2C+Canada&amp;page=1&amp;price_max=836&amp;price_min=201">1</a></li>
            */
        }

        // Item scope template
        $strScopeTemplate = '';
        foreach ($scopeGeometry as $indexGeo => $arrScopeGeoData) {
            $isLastIndex = (true === empty($scopeGeometry[$indexGeo + 1]));
            $arrScopeGeoData['short_name'] = (empty($arrScopeGeoData['short_name']) === false ? $arrScopeGeoData['short_name'] : '');

            if (true === $isLastIndex) {
                $strScopeTemplate .= '<span>' . $arrScopeGeoData['short_name'] . '</span>';
            } else {
                $strScopeTemplate .= '
                    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a href="#" itemprop="url"> <span itemprop="title">' . $arrScopeGeoData['short_name'] . '</span></a>
                        <i class="icon icon-chevron-right breadcrumb-spacer"></i>
                    </span>
                ';
            }
        }

        $listingOutput['pagination_footer'] = sprintf($listingOutput['pagination_footer'], $strPageTemplate, $strScopeTemplate);

        // -------------------------------------------
        // Build the extra data template
        // -------------------------------------------
        $locationParsed = $listingsData->getLocation();
        $centerPoint    = $listingsData->getCenterPoint();

        $listingOutput['visible_results_count'] = $listingsData->getItemsTotal();
        $listingOutput['results_count_string']  = $listingsData->getItemsTotal() . ' Rentals &middot; ' . $locationParsed['location'];
        $listingOutput['center_lat']            = $centerPoint['lat'];
        $listingOutput['center_lng']            = $centerPoint['lng'];
        $listingOutput['meta_description']      = $listingsData->getItemsTotal() . ' Listings in ' . $locationParsed['location'];
        $listingOutput['property_ids']          = $propertyIds;
        $listingOutput['pagination_mode']       = 'classic';
        $listingOutput['page']                  = $listingsApi->getSearchParam('page');
        $listingOutput['per_month']             = false;
        $listingOutput['location']              = $listingsData->getLocation();

        // -------------------------------------------
        // Build the extra data template
        // -------------------------------------------
        $arrGeoLoc = array();
        if (empty($scopeGeometry) === false)
            $arrGeoLoc['city'] = array_shift($scopeGeometry);

        if (empty($scopeGeometry) === false)
            $arrGeoLoc['state'] = array_shift($scopeGeometry);

        if (empty($scopeGeometry) === false)
            $arrGeoLoc['country'] = array_shift($scopeGeometry);

        $listingOutput['geo'] = $arrGeoLoc;
		
		//$listingOutput['listingData'] = $listings;
		
        return ($listingOutput);
    }

    /**
     * Returns the initial bootstrap data
     *
     * @access public, static
     * @return multitype:string number boolean
     */
    public static final function getBootstrapData()
    {
        $Application   = \Core\Application::getInstance();
        $requestParams = \core\api\Listings::_getRequestParams();

        $listingsApi = \Core\Hybernate\Listings\Listings_Api::getInstance();
        $listingsApi->setSearchParam('location', $requestParams ['location'], 'Montreal, Qc. Canada');
        $listingsApi->setSearchParam('filtered_radius', array(
            'sw_lat' => $requestParams ['sw_lat'],
            'sw_lng' => $requestParams ['sw_lng'],
            'ne_lat' => $requestParams ['ne_lat'],
            'ne_lng' => $requestParams ['ne_lng']), array());
        $listingsApi->setSearchParam('buildingType', $requestParams ['buildingType']);
        $listingsApi->setSearchParam('roomType', $requestParams ['room_types']);
        $listingsApi->setSearchParam('priceMin', $requestParams ['price_min'], 1);
        $listingsApi->setSearchParam('priceMax', $requestParams ['price_max'], 10000000);
        $listingsApi->setSearchParam('beds', $requestParams ['min_bedrooms'], 0);
        $listingsApi->setSearchParam('baths', $requestParams ['min_bathrooms'], 0);
        $listingsApi->setSearchParam('page', $requestParams ['page'], 1);
        $listingsApi->setSearchParam('order', $requestParams ['order']);
        $listingsApi->setSearchParam('sort', $requestParams ['sort']);

        $bstReqData     = $listingsApi->processParams();
        $arrCenterPoint = $listingsApi->calculateCenter(array(array(
            'latitude'  => $bstReqData['filtered_radius']['sw_lat'],
            'longitude' => $bstReqData['filtered_radius']['sw_lng'],
        ), array(
            'latitude'  => $bstReqData['filtered_radius']['ne_lat'],
            'longitude' => $bstReqData['filtered_radius']['ne_lng'],
        )));

        $bootstrapData = array (
                'path_location' => $bstReqData['location']['location'],
                'location'      => $bstReqData['location']['location'],
                'visible_results_count' => 1000,
                'results_count_string' => '',
                'per_month' => false,
                'center_lat' => $arrCenterPoint['lat'],
                'center_lng' => $arrCenterPoint['lng'],
                'pagination_mode' => 'classic',
                'pagination_footer' => '<div class="results-footer hide">
                                <div class="page-divider"></div>
                                <div class="pagination-buttons-container">
                                        <div class="results_count">
                                                <h4></h4>
                                        </div>
                                        <div class="pagination">
                                                <ul class="list-unstyled"></ul>
                                        </div>
                                </div>
                                <div class="breadcrumbs" itemprop="breadcrumb"></div>
                        </div>',
                'page' => 1
        );

        return $bootstrapData;
    }

    /**
     * Custom implementation of jaon_encode which support chinese characters
     *
     * @param  mixed  $data The data to encode
     * @return string
     */
    public static final function jsonEncode($data)
    {
        /*if (translate(1, 2, 3) === 1) {
            return json_encode($data);
        }*/

        if( is_array($data) || is_object($data) ) {
            $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );

            if( $islist ) {
                $json = '[' . implode(',', array_map('self::jsonEncode', $data) ) . ']';
            } else {
                $items = Array();
                foreach( $data as $key => $value ) {
                    $items[] = self::jsonEncode("$key") . ':' . self::jsonEncode($value);
                }
                $json = '{' . implode(',', $items) . '}';
            }
        } elseif( is_string($data) ) {
            # Escape non-printable or Non-ASCII characters.
            # I also put the \\ character first, as suggested in comments on the 'addclashes' page.
            $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
            $json = $string;
        } else {
            # int, floats, bools, null
            $json = strtolower(var_export( $data, true ));
        }

        return $json;
    }
}
