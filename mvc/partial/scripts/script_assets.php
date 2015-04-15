<?php
    $Application = \Core\Application::getInstance();
    $assetsBase  = $Application->getConfigs()->get('Application.core.server_root');

	/*
	$minifyPath = $Application->getConfigs()->get('Application.core.mvc.application_path');

	//$minify = \Core\Util\Minification\Minification::getInstance()->minifyFile($assetsBase . 'template/assets/template/js/map/core/map-search.js');

	$minify = \Core\Util\Minification\Minification::getInstance(\Core\Util\Minification\Minification::Minification_Processor_Css)->minify(file_get_contents('/var/www/spf.dns04.com/template/assets/template/js/map/core/map-search.js'));
	var_dump(array('file' => $minify)); die('ok');
	*/
	
	//echo $test->test();
	
	/*
?>
<link href="<?php echo $assetsBase; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo $assetsBase; ?>/css/style.css" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
<link href="<?php echo $assetsBase; ?>/css/icons.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/datatables.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/dataTables.fixedHeader.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/tabletools.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/select2.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/wysihtml5.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/toolbar.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/moment.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/jgrowl.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/tags.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/switch.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/uniform.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/multiselect.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/plupload-2.1.2/js/plupload.full.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/plupload-2.1.2/js/jquery.plupload.queue/jquery.plupload.queue.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/jquery.isotope.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/application.js"></script>

<!-- temp
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/readmore.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/jquery.uploader.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/validate.min.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/table_export/tableExport.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/table_export/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/table_export/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/table_export/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/table_export/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/table_export/jspdf/libs/base64.js"></script>
-->

<script type="text/javascript" src="<?php echo $assetsBase; ?>/js/bootstrap.min.js"></script>
<script type="text/javascript">
    var objApplication = new SYSTEM.APPLICATION.MAIN();
    objApplication.initialise(function (event) {
        objApplication.configure(SYSTEM.APPLICATION.MAIN.CONFIGURATION.LANGUAGE, 'en');
        objApplication.configure(SYSTEM.APPLICATION.MAIN.CONFIGURATION.SITE_URL, '<?php echo $Application->getConfigs()->get('Application.core.base_url'); ?>');
        objApplication.configure(SYSTEM.APPLICATION.MAIN.CONFIGURATION.API_URL, '<?php echo $this->route('api', 'v:2.03', array()); ?>');
        objApplication.startModuleCollection('<?php echo $Application->getRequestDispatcher()->getMvcRequest('controller'); ?>');
    });
</script>
*/ ?>