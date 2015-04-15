<?php
    require_once '/var/www/spf.dns04.com/Core/Application.php';
	require_once '/var/www/spf.dns04.com/source-template-map/search-map-only/listings_data_access.php';
	$Application = \Core\Application::getInstance();
    //$listingData = \core\api\Listings::getBootstrapData();
    //$listingData = \core\api\Listings::getListingsCollection();
    //$bootstrapData = $listingData;
    //unset($bootstrapData['results']);
    
    $bootstrapData = \core\api\Listings::getListingsCollection();
    //$bootstrapData['listingData'] = array();
    
    $siteName = $Application->getConfigs()->get('Application.site.site_name');
	
//var_dump($bootstrapData['results']); die;
//var_dump(\core\api\Listings::getListingsCollection()); die;

	/*
	$bootstrapData = array (
	  'filters' => NULL,
	  'pagination_footer' => '',
	  'visible_results_count' => 0,
	  'results_count_string' => '',
	  'center_lat' => null,
	  'center_lng' => null,
	  'meta_description' => '',
	  'pagination_mode' => 'classic',
	  'page' => 1,
	  'location' => array('location' => ''),
	  'geo' => array ()
	);
	*/
?>
<style type="text/css">
@media (max-width: 480px) {
	.fullscreen {
	  top: 20px;
	}
}
.map-refresh-controls {
	position: absolute;
	/*left: 250px;*/
	right: 165px;	
	top: 20px;
	
	background: #ffffff;
	border-bottom: 1px solid rgba(0, 0, 0, 0.2);
	font-size: 12px;
	height: 35px;
	padding: 0;
	text-align: center;
	z-index: 99;
	float: left;
	margin: 0px;
}
.map-auto-refresh {
  	padding: 0px 8px;
	/*border: 1px solid #dbdbdb;
	border-radius: 2px;
	background-color: #fefefe;*/
}

.map-refresh-controls .btn-primary {
	-webkit-font-smoothing: antialiased;
	text-decoration: none;
	display: inline-block;
	margin-bottom: 0;
	border-radius: 0px;
	text-align: center;
	vertical-align: middle;
	font-weight: bold;
	line-height: 1.4;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	white-space: nowrap;
	cursor: pointer;	
	background-color: #2badf3;
	background-image: -webkit-linear-gradient(#2badf3, #2492db);
	background-image: linear-gradient(#2badf3, #2492db);
	border: 1px solid #106fa9;
	color: #fff;
	text-shadow: 0 -1px 0 rgba(0,0,0,0.2);
	box-shadow: 0 1px 2px 0 rgba(0,0,0,0.18),inset 0 0 1px 1px rgba(255,255,255,0.09);
	font-size: 13px;
	font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
	height: 35px;
}

.map-refresh-controls .icon-refresh {
  margin-right: 2px;
  line-height: 1;
  
  font-size: 10px;
  font-weight: 900;
}

.map-auto-refresh label {
  margin-top: 7px;
}

.map-refresh-controls input[type="checkbox"] {
    -webkit-appearance: none;
    margin-top: -0.07em;
    margin-bottom: -0.1em;
    vertical-align: top;
    margin-right: 4px;
    height: 1.35em;
    width: 1.35em;
	
	position: relative;
	margin-left: 0;
	margin-top: 2px;
	border: 1px solid #cdcdcd;
	/*border-radius: 3px;*/
  	transition: border-color 0.3s,box-shadow 0.3s;
	box-shadow: inset 0 1px 1px 0 rgba(0,0,0,0.08),0 1px 0 0 #fff;
}
.map-refresh-controls input[type="checkbox"]:focus {
  outline: none;
  border-color: #00b0ff;
  box-shadow: inset 0 1px 1px 0 rgba(0,0,0,0.08),0 0 2px 2px #ceeaf6;
}
.map-refresh-controls input[type="radio"]:checked:before {
    content: "";
    position: absolute;
    height: 0.45em;
    width: 0.45em;
    border-radius: 0.45em;
    top: 50%;
    left: 50%;
    margin-top: -0.225em;
    margin-left: -0.225em;
    background-color: #e10979;
}

.map-refresh-controls input[type="checkbox"]:checked {
    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAiCAMAAAANmfvwAAABVlBMVEUAAAD2Z7H3j8X3wN33wNz3u9r35e732en3vdv3yeH2Mpj33Or3yuL34e33k8f3zeP3gb736/L37PL36/H2i8P3vtz3lsj2abP2Ya/3tdf3sNX33ev3P572TKT2TqX3XKz2N5r2bbT3w972Qp/3lsj2Y7D2XKz33ev3r9X3qNH3sNX2Opv2OJr2RqH3ncz2PJ33jcT2c7f2brX2dbj2d7n2PZ32Van2SaL3mMn2gb72Vqn2S6T2Vaj2drn2U6j3mcn3Xa33q9L3rtT3xd/30uX2ZbD3rdT2hsD2f7z2d7n39ff2f7z2h8H2RKD2cbb3v9z30+X2dbj2VKj3wt32Yq/3qdH2hcD2arL3pM/2kcX2erv2NJj3msr3yeH3vNr3os72W6v2J5L2K5T2I5D2Goz2II/2HI32Ho72For2E4n2MJf2NZn2GIv2FYn2A4H2DIX2CIP2K5RkCdohAAAAYXRSTlMAt4ETA00XBEoB/iQ5HH4JlwwPEYRGd7K9Ulsp8d3X0f6tJuqEqMQgHmE49vrjdfKOpXdIHv3i33uR3OLQnNRry1dEQDDBPEh5KAdhdvazLy2hy1K5ZosQYS6p9WRAMW/FbFj9OQAAAhxJREFUOMvFkVdz2kAUhREIJIaOaKKG3pttTDfNOIlb4hqnW7vqApP//5RdyBg8A5OHTMb3Tdpvznf3rO6/j8lqpI+Zf0IoJju5S9nvJpadyKnFW7maTut2didi8/riXU1LBjw7LG5MlDl+BpP17YjL4vXVI4BX5qJ2u9sS1QRehFzVj75JNOb1sZlyZWmfv6wKMs+Vko/3GKEo8qWFrtSbQODnPGgS90H0y8EwLmojJTdufTnCFgEWA3qjRUcaEtbv1uOsyYFlZpI6ePvu8lDDFi2Z8RhtOp3hx8int1dGVtMy49P7/b10V4OzOa9G/B42hPITrIfoXQfawdDSgjLSUQ2KogirBLIwaE3rT39V4EGvdoNjDvbPLztvhJnIqeGMh14l06kYfHpSStMUW/iMLPHDBpQVSYsQHvbPC97UOgiR1S7RPj8720tHG0Ca8TDpt9MhZoWwqaE6VwSghaO3V/3lHhIHqhk9bTqlVo2OB/2jrxyUJAjCnRi+rQxLEbRp9rmpxHjgHIahoqAeLi4aQJzxXBlb3M+II3eSd8ZUWRE4oKoAChAUA8iyWThl+PDg7GkQCqIoSRJXaqLWbfhkkzkZOGNhTlksFJkHkTiyOF4iJHI9OIfa/NdClrgiYffa3GvL2pXHLk4tlvEeui1DGgrf2sTj9ZDot4wWchtiduQKo0qqVmvlJ1lmK4JdCasxGKQ/4jr+irza/AalgXKydOt89gAAAABJRU5ErkJggg==") no-repeat center;
    background-size: 0.8em;
}


/*
.map-refresh-controls.touch {
    left: 40px;
}

.map-manual-refresh {
    padding: 8px;
}

.map-auto-refresh {
    padding: 3px 8px;
}


.map-refresh-controls label {
	display: table;
	width: 137px;
	height: 70px;
	text-align: center;
	background: #000;
	padding: 15px 10px 10px 10px;
	vertical-align: middle;
	font-family: "nimbus-sans-condensed", "HelveticaNeue", Helvetica, Arial, sans-serif;
	text-transform: uppercase;
	text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
	color: #ffffff;
	position: relative;
	left: 1px;
	line-height: 120%;
}

.map label {
	width: 200px;
	font-size: 13px;
	height: 45px;
}
*/
.map-canvas {
    height: 100%;
}

.map-canvas .listing {
    margin: 0;
    width: 260px;
    height: auto;
}

.map-canvas .listing-footer {
    background: #fff;
}


.pac-container {
    background: #fff;
    border-top: none;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
}

.pac-item-query {
    color: #6f6f6f;
}

.pac-item {
    border-bottom: #6f6f6f;
    overflow: hidden;
    padding: 0.3em 0.8em;
}

.pac-item:hover,.pac-item.pac-selected {
    background: #00b0ff;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
}

.pac-item:hover .pac-item-query,.pac-item.pac-selected .pac-item-query {
    color: #fff;
}

.pac-item:nth-child(odd) {
    background: #f7f7f7;
}

.pac-item:nth-child(odd):hover,.pac-item:nth-child(odd).pac-selected {
    background: #00b0ff;
}

.pac-container,.pac-container .pac-item:last-child {
    -moz-border-radius: 0 0 2px 2px;
    -webkit-border-radius: 0 0 2px 2px;
    border-radius: 0 0 2px 2px;
}

.pac-container:after {
content: "";
height: 1px !important;
background-image: none !important;
}
</style>
<script type="text/javascript">
    var googleMapsUrl 		 = '//maps.googleapis.com/maps/api/js?language=en-CA&sensor=false&v=3.13&libraries=places',
		MapSearchXMLRpcUri   = 'assets/template/js/map/api.php',
		MarkersImgPath       = '/source-template-map/assets/template/js/map/core/pins';
    var userAttributeCookies = {
        flags_name: 'flags',
        roles_name: 'roles',
        flags: {},
        roles: {}
    };
</script>


<script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery(".fullscreen").click(function() {
                jQuery("body").toggleClass("body-fullscreen");
                jQuery("#map-container").height(jQuery(window).height);
                var map = jQuery("#map-container");
                google.maps.event.trigger(map, "resize");
                jQuery(window).load();
            });
			
			
			/*
            var timer;

            jQuery("input.search_min_price").on('keyup', function(e) {
                clearInterval(timer);  //clear any interval on key up
                timer = setTimeout(function() { //then give it a second to see if the user is finished
                    cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                }, 2000);
            });

            jQuery("input.search_max_price").on('keyup', function(e) {
                clearInterval(timer);  //clear any interval on key up
                timer = setTimeout(function() { //then give it a second to see if the user is finished
                    cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                }, 2000);
            });

            jQuery("input.form-search-text").on('keyup', function(event) {
                clearInterval(timer);  //clear any interval on key up
                timer = setTimeout(function() { //then give it a second to see if the user is finished
                    cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                }, 2000);
            });

            jQuery( "#directory-search-location" ).on('keyup', function(event) {
                jQuery('#geo_loc_option').val('off');
                clearInterval(timer);  //clear any interval on key up
                timer = setTimeout(function() { //then give it a second to see if the user is finished
                    cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                }, 2000);
                jQuery(".loader").html('');
                return false;
            });

            jQuery( "form #directory-search-location" ).live("change", function() {
                jQuery('#geo_loc_option').val('off');
                clearInterval(timer);  //clear any interval on key up
                timer = setTimeout(function() { //then give it a second to see if the user is finished
                    cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                }, 2000);
                jQuery(".loader").html('');
                return false;
            });

            jQuery('.location-icon').click( function(e) {
                setTimeout(function(){
                    cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                },2000);
                return false;
            });

            jQuery( "div.cs-drag-slider" ).click(function(e) {
                clearInterval(timer);  //clear any interval on key up
                timer = setTimeout(function() { //then give it a second to see if the user is finished
                    cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                }, 2000);
            });
			*/
         });


		/*
        if(jQuery("#map1146989938").length>0){
            jQuery( ".MultiControls p.btnOk" ).live("click", function() {
                cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                return false;
            });

            jQuery( "form #directory-field-category" ).live("change", function() {
                //alert('asd');
                jQuery('#geo_loc_option').val('off');
                cs_directory_map_search('1146989938', 'http://directory.chimpgroup.com/wp-admin/admin-ajax.php', 'style-1');
                return false;
            });
        }
		*/
    </script>
<?php
	// echo htmlentities(str_replace(array('\r', '\t', '\n'), '', \core\api\Listings::jsonEncode($bootstrapData)));
	// echo htmlentities(str_replace(array('\r', '\t', '\n'), '', json_encode($bootstrapData)));
?>
<div class="map-search map-search-shortcode cs-search-map-enable cs-search-v1" 
	data-bootstrap-data="<?php echo htmlentities(str_replace(array('\r', '\t', '\n'), '', json_encode($bootstrapData))); ?>">
    
  <!--<div class="outer-listings-container" style="display:none"><ul class="listings-container"></ul></div>-->
    
  <div id="map-container" class="map-container"> 
    <span class="loader"></span> 
    <span class="fullscreen"><i class="icon-arrows"></i> Full Screen</span> 
    <span class="gmapzoomplus" id="gmapzoomplus" style="cursor: pointer;"><i class="icon-plus8"></i></span> 
    <span class="gmapzoomminus" id="gmapzoomminus" style="cursor: pointer;"><i class="icon-minus8"></i></span>
    <div class="cs-control-icons"> 
        <span class="gmaplock" id="gmaplock" style="cursor: pointer;"><i class="icon-lock3"></i></span> 
        <span class="gmapcurrentloc" id="gmapcurrentloc" style="cursor: pointer;"><i class="icon-paperplane3"></i></span> 
    </div>
    <div class="map">
        <div class="map-canvas"></div>
        <div class="map-refresh-controls hidden-xs">
            <a class="map-manual-refresh btn btn-primary hide"><i class="icon icon-refresh"></i> Redo Search Here</a>
            <div class="map-auto-refresh hide">
                <label class="checkbox">
                    <input class="map-auto-refresh-checkbox" type="checkbox" checked="checked">Search when I move the map
                </label>
            </div>
        </div>
    </div>
  </div>

  <!-- Advance search code -->
  <div id="directory-advanced-search">
    <div class="container">
      <div class="directory-advanced-search-content">
        <form class="form-horizontal" accept-charset="UTF-8" id="directory-advance-search-form" method="get" role="search">
          <input type="hidden" name="filter" value="all" />
          <input type="hidden" name="lat" id="lat" value="" />
          <input type="hidden" name="lng" id="lng" value="" />
          <div class="dir-search-fields">
            <ul>
              <li>
                <h6>Search Text</h6>
                <input type="text" class="form-search-text location" maxlength="128" size="30" value="" name="search_text" id="edit-search-api-views-fulltext" placeholder="Enter keyword...">
              </li>
              <script>
                jQuery(document).ready(function($) {
                    window.asd = jQuery('select.form-select').SumoSelect();
                });
            </script>
              <li>
                <h6>All Types</h6>
                <select class="form-select dir-map-search SlectBox" name="type" id="directory-field-category">
                  <option value="">--All Types--</option>
                  <option value="property" >&nbsp;Property</option>
                  <option value="motors" >&nbsp;Motors</option>
                  <option value="for-sale" >&nbsp;For Sale</option>
                  <option value="jobs" >&nbsp;Jobs</option>
                  <option value="services" >&nbsp;Services</option>
                  <option value="restaurant" >&nbsp;Restaurant</option>
                  <option value="pets" >&nbsp;Pets</option>
                  <option value="hotels-travel" >&nbsp;Hotels &#038; Travel</option>
                  <option value="education" >&nbsp;Education</option>
                </select>
              </li>
              <li class="loc-section searchLocFrm">
                <h6>Locations</h6>
                <div class="location-icon __location" data-bind="#map_filter_location">
                	<img src="http://directory.chimpgroup.com/wp-content/plugins/wp-directory/assets/images/maplocation.png" alt="" />
                </div>
                <input type="search" value="" autocomplete="off" title="Location" placeholder="Your location or postal / zip code" name="location" id="map_filter_location" />
              </li>
              <li class="to-field">
                <h6>Distance From Location</h6>
                <div class="input-sec"> <span class="drag-slider-tooltip"></span>
                  <div class="cs-drag-slider slider-distance-range" data-slider-min="10" data-slider-max="300" data-slider-step="1" data-slider-value="150"></div>
                  <input id="radiusSelector" class="cs-range-input" name="radius" type="text" value="150"   />
                </div>
                <script>
                    jQuery(document).ready(function($) {
                        jQuery('div.cs-drag-slider').each(function() {
                             var _this = jQuery(this);
                             tooltip = jQuery('span.ui-slider-handle');
                            _this.slider({
                                range:'min',
                                step: _this.data('slider-step'),
                                min: _this.data('slider-min'),
                                max: _this.data('slider-max'),
                                value: _this.data('slider-value'),
                                slide: function (event, ui) {
                                    //jQuery(this).parents('li.to-field').find('.cs-range-input').val(ui.value)
                                    jQuery( "#radiusSelector" ).val(ui.value);
                                        tooltip = jQuery(this).parents('li.to-field').find('span.ui-slider-handle');
                                        tooltip.html("<strong>"+ui.value+" Miles</strong>");
                                }
                            });
                        });
						
                        jQuery("div.cs-drag-slider span").first().html("<strong>"+ jQuery( "#radiusSelector" ).val() +" Miles</strong>");
                    });
                </script>
              </li>
              <!--<li class="price-search">
                <div class="advance-search-price-range">
                  <h6>Price Range</h6>
                  <ul>
                    <li>
                      <input  id="min_price" onblur="if(this.value == '') { this.value ='Min Price'; }" onfocus="if(this.value =='Min Price') { this.value = ''; }" class="search_min_price"  name="min_price" type="text" value="Min Price">
                    </li>
                    <li>
                      <input  id="max_price" onblur="if(this.value == '') { this.value ='Max Price'; }" onfocus="if(this.value =='Max Price') { this.value = ''; }" class="search_max_price"  name="max_price" type="text" value="Max Price" />
                    </li>
                  </ul>
                </div>
              </li>-->
              <li class="submit-button">
                <input type="hidden" name="cs_directory_search_location" value="Yes" />
                <input type="hidden" name="pagination" value="100" />
                <input type="hidden" name="search_view" value="" />
                <input type="hidden" name="goe_location_enable" value="Yes" />
                <input type="hidden" name="cs_loc_max_input" value="150" />
                <input type="hidden" name="cs_loc_incr_step" value="1" />
                <button name="submit" id="directory-submit-search-view" class="form-submit" ><i class="icon-search6"></i></button>
                <input type="hidden" name="action" value="cs_directory_map_search" />
              </li>
            </ul>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>





<input type="button" class="search-button">
<!--<link href="assets/template/js/map/core/map_search.css" media="screen" rel="stylesheet" type="text/css" />-->
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?language=en-CA&sensor=false&v=3.13&libraries=places"></script>
<!--<script type="text/javascript" src="//maps.google.com/maps/api/js?language=en-CA&sensor=false&#038;libraries=places&#038;ver=4.1.1"></script>-->
<!--<script type="text/javascript" src="assets/template/js/map/core/jq.core.amber.js"></script>-->
<script type="text/javascript" src="assets/template/js/map/core/map-search.js"></script>
<script type="text/javascript" src="assets/template/js/map/core/mp.direction.api.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        require('map_search/MapSearchPage').attachTo('.map-search');
        $('.search-button').trigger('click');
    });

</script>
