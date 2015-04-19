<?php
    $Application   = \Core\Application::getInstance();
    $assetsBaseCcs = $Application->getConfigs()->get('Application.core.mvc.controller.assets.base.css');
	$assetsBaseJs  = $Application->getConfigs()->get('Application.core.mvc.controller.assets.base.js');
	$cssFileAssets = array(
		$assetsBaseCcs . "/theme.css",
		$assetsBaseCcs . "/iconmoon.css",
		$assetsBaseCcs . "/bootstrap.css",
		$assetsBaseCcs . "/style.css",
		$assetsBaseCcs . "/responsive.css",
		$assetsBaseCcs . "/bootstrap-theme.css",
		$assetsBaseCcs . "/sumoselect.css",
		$assetsBaseCcs . "/jquery-ui.css",
		$assetsBaseCcs . "/owl.carousel.css",
		$assetsBaseCcs . "/map.css"
    );
?>
<meta charset="UTF-8">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo sprintf('%s | %s', 
	$Application->getConfigs()->get('Application.core.mvc.site.name'), 
	$Application->getConfigs()->get('Application.core.mvc.site.title')); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="shortcut icon" href="http://directory.chimpgroup.com/wp-content/themes/directory-theme/assets/images/favicon.png" />
<?php foreach ($cssFileAssets as $cssFileAsset) { ?>
<link rel="stylesheet" id="<?php echo(md5($cssFileAsset)); ?>"  href="<?php echo $cssFileAsset; ?>" type="text/css" media="all" />
<?php } ?>
<script type="text/javascript" src="http://www.geoplugin.net/javascript.gp"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?language=en-CA&sensor=false&v=3.13&libraries=places"></script>
<script type="text/javascript" src="//google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>
<script type="text/javascript" src="<?php echo $assetsBaseJs; ?>/map/core/jq.core.amber.js"></script>
<script type="text/javascript" src="<?php echo $assetsBaseJs; ?>/application.js"></script>
