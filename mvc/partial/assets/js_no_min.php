<?php
    $Application   = \Core\Application::getInstance();
    $assetsBaseJs  = $Application->getConfigs()->get('Application.core.mvc.controller.assets.base.js');
    $jsFileAssets   = array(
		$assetsBaseJs . "/jquery/jquery-migrate.min.js",
		$assetsBaseJs . "/jquery/jquery.sumoselect.min.js",
		$assetsBaseJs . "/jquery/jquery.prettyphoto.js", /* */
		$assetsBaseJs . "/jquery/jquery.flexslider-min.js", /* */
		$assetsBaseJs . "/jquery/jquery.multiselect.js",
		$assetsBaseJs . "/jquery/masonry.pkgd.min.js",
		$assetsBaseJs . "/jquery/jquery.isotope.js",
		$assetsBaseJs . "/bootstrap_min.js",
		$assetsBaseJs . "/cs-connect.js", /* */
		$assetsBaseJs . "/functions.js", /* */
		$assetsBaseJs . "/sticky_header.js",
		$assetsBaseJs . "/ui/core.min.js",
		$assetsBaseJs . "/ui/widget.min.js",
		$assetsBaseJs . "/ui/position.min.js",
		$assetsBaseJs . "/ui/menu.min.js",
		$assetsBaseJs . "/ui/autocomplete.min.js", /* */
		$assetsBaseJs . "/ui/mouse.min.js", /* */
		$assetsBaseJs . "/ui/slider.min.js", /* */
		$assetsBaseJs . "/owl_carousel_min.js",
		$assetsBaseJs . "/map/core/map-search.js",
		$assetsBaseJs . "/map/core/mp.direction.api.js"
    );
?>
<?php foreach ($jsFileAssets as $jsFileAsset) { ?>
<script type="text/javascript" src="<?php echo $jsFileAsset; ?>"></script>
<?php } ?>
