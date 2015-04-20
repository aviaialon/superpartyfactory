<?php
$Application   = \Core\Application::getInstance();
$assetsBaseImg = $Application->getConfigs()->get('Application.core.mvc.controller.assets.base.img');
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
	  'location' => array('location' => 'Montreal, Quebec Canada'),
	  'geo' => array ()
);

$mainCategories = \Core\Hybernate\Listings\Listing_Category::getParentCategories();
?>
<div class="map-search map-search-shortcode cs-search-map-enable cs-search-v1" 
	data-bootstrap-data="<?php echo htmlentities(str_replace(array('\r', '\t', '\n'), '', json_encode($bootstrapData))); ?>">
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
              <li>
                <h6>Category</h6>
                
                <select multiple="multiple" class="multi-select multi-select-subcat" name="categories[]" style="display:none">
                	<?php foreach ($mainCategories as $cateoryId => $category) { ?>
                  		<option value="<?php echo $cateoryId; ?>">
							<?php echo ucwords($Application->translate($category['name_en'], $category['name_fr'], $category['name_ch'])); ?></option>  
                    <?php } ?>
                </select>
              </li>
              <li class="loc-section searchLocFrm">
                <h6>Locations</h6>
                <div class="location-icon __location" data-bind="#map_filter_location">
                	<img src="<?php echo $assetsBaseImg; ?>/maplocation.png" alt="" />
                </div>
                <input type="search" value="" autocomplete="off" title="Location" placeholder="Your location or postal / zip code" name="location" id="map_filter_location" />
              </li>
              <li class="to-field">
                <h6>Distance From Location</h6>
                <div class="input-sec"> <span class="drag-slider-tooltip"></span>
                  <div class="cs-drag-slider slider-distance-range" data-slider-min="10" data-slider-max="300" data-slider-step="1" data-slider-value="150"></div>
                  <input id="radiusSelector" class="cs-range-input" name="radius" type="text" value="150"   />
                </div>
              </li>
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
          <input type="button" class="search-button" style="display:none" />
        </form>
      </div>
    </div>
  </div>
</div>
