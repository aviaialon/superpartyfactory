<?php
class_exists('\\Core\\Application') ||
    require_once realpath(dirname(__FILE__)) . '../../Core/Application.php';

\Core\Application::bootstrapResource("\\Core\\Hybernate\\Listings\\Listings");
\Core\Application::bootstrapResource("\\Core\\Hybernate\\Individual\\Individual_Phones");


$Application         = \Core\Application::getInstance();
$requestDispatcher   = $Application->getRequestDispatcher();
$objListing          = \Core\Hybernate\Listings\Listings::getInstance((int) $requestDispatcher->getRequestParam('id'));
($objListing->getId() > 0) || $requestDispatcher->pageNotFound();
$objListing->setViews(((int) $objListing->getViews()) + 1)->save();

$requestDispatcher->setViewData('listingsData', array_shift(LISTINGS::getItemObjectClassView(array(
    'filter_unescaped'  => array('a.id ' => (int) $objListing->getId()),
    'imagePositionId'     => array(1),
    'limit'              => 1
))));

$requestDispatcher->setViewData('individualPhones', \Core\Hybernate\Individual\Individual_Phones::getClassView(array(
    'columns'          => array('a.*', 'CONCAT("(", a.areaCode, ") ", a.phoneNumber) as display_number', 'cd.description as description'),
    'filter'          => array('individualId' => (int) $objListing->getIndividualId()),
    'inner_join'    => array('phone_types_description cd' => 'cd.phoneTypeId = a.phoneTypeId AND cd.langId = ' . $Application->translate(1, 2, 3)),
    'groupBy'        => 'a.phoneNumber'
)));

$arrListingData   = $requestDispatcher->getViewData('listingsData');
$arrIndivPhone    = $requestDispatcher->getViewData('individualPhones');
$strApplicationStaticResPath = $Application->getBaseStaticResourcePath();
$strListingDetail = \Core\Hybernate\Listings\Listings::createFriendlyUrl($arrListingData);
$arrAddressData      = explode('|', $arrListingData['addressText']);
$strImage           = (false === empty($arrListingData['imagePosition1']) ? $arrListingData['imagePosition1'] : $strApplicationStaticResPath . 'images/no-image/no_image.jpg');
$strIndividualImage = (false === empty($arrListingData['individual_photo']) ? $arrListingData['individual_photo'] : $strApplicationStaticResPath . 'images/no-image/no_image.jpg');
$intPrice = (false === empty($arrListingData['price']) && ((int) $arrListingData['price'] > 0) ? $arrListingData['price'] : $arrListingData['leaseRent']);
?>
<style type="text/css">
.quick-view {
        -webkit-font-smoothing: antialiased;
        background-color: #f0f0f0;
        color: #707070;
        font-family: "Open Sans", "Arial", sans-serif;
        font-size: 14px;
        height: 100%;
        margin: 0px;
        padding: 0 8px;
    }

.quick-view .properties-rows .filter {
    display:inline-block;
    zoom:1;
    -webkit-box-shadow:0px 1px 1px rgba(0, 0, 0, 0.1);
    -moz-box-shadow:0px 1px 1px rgba(0, 0, 0, 0.1);
    box-shadow:0px 1px 1px rgba(0, 0, 0, 0.1);
    background-color:#fff;
    display:block;
    margin:0px 0px 30px 0px;
    height:auto;
    padding:10px
}
.quick-view .properties-rows .filter:after {
    height:0;
    content:".";
    display:block;
    clear:both;
    visibility:hidden
}
.quick-view .properties-rows .filter form {
    float:right;
    margin:0px
}
.quick-view .properties-rows .filter form .control-group {
    float:right;
    margin-bottom:0px
}
.quick-view .properties-rows .filter form .control-group label {
    color:#1b1b1b;
    font-weight:bold;
    line-height:30px;
}
.quick-view .properties-rows .filter form .control-group select {
    width:100px;
}

.quick-view .properties-rows .filter form .control-group select.___inputOrder {
    width:170px;
}

.quick-view .properties-rows .filter form .control-group .control-label {
    width:100px
}
.quick-view .properties-rows .filter form .control-group .controls {
    margin-left:120px
}
.quick-view .properties-rows .filter form .control-group .chzn-container {
    -webkit-box-sizing:border-box;
    -moz-box-sizing:border-box;
    box-sizing:border-box
}
.quick-view .properties-rows .property {
    display:inline-block;
    zoom:1;
    -webkit-box-shadow:0px 1px 1px rgba(0, 0, 0, 0.1);
    -moz-box-shadow:0px 1px 1px rgba(0, 0, 0, 0.1);
    box-shadow:0px 1px 1px rgba(0, 0, 0, 0.1);
    background-color:#fff;
    margin-bottom:30px;
    padding:0px;
    position:relative
}
.quick-view .properties-rows .property:after {
    height:0;
    content:".";
    display:block;
    clear:both;
    visibility:hidden
}
.quick-view .properties-rows .property:last-child {
    margin-bottom:0px
}
.quick-view .properties-rows .property .title-price {
    padding-bottom:3px;
    padding-top:17px
}
.quick-view .properties-rows .property .title-price .title h2 {
    line-height:1;
    margin:0px
}
.quick-view .properties-rows .property .title {
    margin-left: 15px;
    padding-bottom: 8px;
}
.quick-view .properties-rows .property .title-price .title h2 a {
    -webkit-transition:color 0.2s ease-in;
    -moz-transition:color 0.2s ease-in;
    -o-transition:color 0.2s ease-in;
    transition:color 0.2s ease-in;
    color:#06a7ea;
    font-size:18px;
    font-weight:normal;

}
.quick-view .properties-rows .property .title-price .title h2 a:hover {
    color:#0584b8;
    text-decoration:none
}
.quick-view .properties-rows .property .title-price .price {
    clear:right;
    color:#313131;
    float:right;
    font-size:16px;
    margin-top:3px;
    padding-right:30px;
    text-align:right
}
.quick-view .properties-rows .property .body {
    padding-bottom:7px;
    margin-left: 15px;
}
.quick-view .properties-rows .property .body p {
    padding-right:30px
}
.quick-view .properties-rows .property .image {
    position:relative;
    text-align:center;
    margin-left: 15px;
}
.quick-view .properties-rows .property .image .content a {
    -webkit-transition:background-color 0.2s ease-in;
    -moz-transition:background-color 0.2s ease-in;
    -o-transition:background-color 0.2s ease-in;
    transition:background-color 0.2s ease-in;
    background-color:rgba(0, 0, 0, 0);
    display:block;
    height:100%;
    left:0px;
    position:absolute;
    top:0px;
    width:100%
}
.quick-view .properties-rows .property .image .content a:hover {
    background-color:rgba(0, 0, 0, 0.5);
    background-image:url("assets/images/listings/eye.png");
    background-position:center center;
    background-repeat:no-repeat
}
.quick-view .properties-rows .property .location {
    background-image:url("assets/images/listings/border.png");
    background-position:left bottom;
    background-repeat:repeat-x;
    color:#707070;
    font-size:18px;
    line-height:1;
    margin-bottom:12px;
    /*margin-right:30px;*/
    padding-bottom:12px
}
.quick-view .properties-rows .property .area {
    display:inline-block;
    margin-right:10px
}
.quick-view .properties-rows .property .area .key {
    -webkit-border-radius:0px;
    -moz-border-radius:0px;
    border-radius:0px;
    text-shadow:none;
    background-color:transparent;
    font-size:14px;
    padding:0px
}
.quick-view .properties-rows .property .bedrooms {
    background-image:url("assets/images/listings/bedrooms.png");
    background-position:left center;
    background-repeat:no-repeat;
    background-size:21px 12px;
    display:inline-block;
    margin-right:10px;
    padding-left:30px
}
@media (-webkit-min-device-pixel-ratio: 2), (-moz-min-device-pixel-ratio: 2) {
.quick-view .properties-rows .property .bedrooms {
background-image:url("assets/images/listings/bedrooms@2x.png")
}
}
.quick-view .properties-rows .property .bathrooms {
    background-image:url("assets/images/listings/bathrooms.png");
    background-position:left center;
    background-repeat:no-repeat;
    background-size:14px 20px;
    display:inline-block;
    margin-right:10px;
    padding-left:25px
}
.quick-view .properties-rows [class*="qv-span3"] {
float: left;
min-height: 1px;
/*margin-left: 30px;*/
}
.quick-view .properties-rows .qv-qv-span36 {
width: 570px;
}
.quick-view .properties-rows .qv-span33 {
width: 270px;
}

.quick-view .properties-rows img {
width: auto\9;
height: auto;
max-width: 100%;
vertical-align: middle;
border: 0;
-ms-interpolation-mode: bicubic;
}

.quick-view .our-agents {
    padding-top: 15px;
    clear: both;
    display: block;
}

.quick-view .our-agents .image {
    float:left;
    padding: 0 25px;
    display: block;
}
</style>
<div class="quick-view">
    <div class="properties-rows">
        <div class="row">
            <div class="property qv-qv-span36" style="padding:10px;">
                <div class="row">
                    <div class="image qv-span33 pull-left" style="margin-top:20px;">
                        <div class="content pull-left">
                            <a href="<?php echo ($strListingDetail); ?>"></a>
                            <img src="<?php echo($strImage); ?>" alt="">
                        </div><!-- /.content -->
                    </div><!-- /.image -->

                    <div class="body qv-span33">
                        <div class="title-price row">
                            <div class="title qv-span33">
                                <h2><a href="<?php echo ($strListingDetail); ?>">
                                    <?php echo(array_shift($arrAddressData) . " " . array_shift($arrAddressData)); ?></a></h2>
                            </div>
                        </div>

                        <div class="location"><?php echo(array_shift($arrAddressData)); ?></div><!-- /.location -->

                        <div class="price">
                            <h3>$<?php echo(number_format($intPrice, 2) . ' ' . ((int) $arrListingData['leaseRent'] > 0 ? ' / ' . $Application->translate('Monthly', 'Mensuel', '每月一次') : '')); ?></h3>
                        </div>
                        <div class="area">
                            <qv-span3 class="key"><?php echo($Application->translate('Area', 'Superficie', '區域')); ?>: </qv-span3><!-- /.key -->
                            <qv-span3 class="value">
                                <?php echo(str_replace('sqft', $Application->translate('sqft', 'pc', '平方英尺'), $arrListingData['sizeInterior'])); ?></qv-span3>
                        </div><!-- /.area -->
                        <div class="bedrooms"><div class="content"><?php echo($arrListingData['bedrooms']); ?></div></div><!-- /.bedrooms -->
                        <div class="bathrooms"><div class="content"><?php echo($arrListingData['bathrooms']); ?></div></div><!-- /.bathrooms -->
                    </div><!-- /.body -->
                </div><!-- /.property -->
                <div class="content qv-span35" style="margin-top: 20px; padding-bottom:10px;">
                    <p class=""><?php echo($arrListingData['description']); ?></p>
                </div>
                <a class="btn btn-primary btn-small list-your-property arrow-right pull-right" href="<?php echo(\Core\Hybernate\Listings\Listings::createFriendlyUrl($arrListingData)); ?>">
                    <?php echo($Application->translate('See More', 'En savoir plus', '更多')); ?></a>
            </div>
        </div>
    </div>
    <div class="row" style="background:#EEE">
        <div class="qv-qv-span36">
            <div class="widget our-agents">
                <div class="">
                    <div class="agent" style="background: #EEE;-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none; padding: 10px;">
                        <div class="row">
                            <div class="image qv-span34">
                                <img src="<?php echo($strIndividualImage); ?>" alt="" style="border-radius: 0px; margin:0 auto;">
                            </div>

                            <div class="qv-span34 agent-quick-view">
                                <div class="name"><h3 style="line-height: 15px;"><?php echo($arrListingData['org_name']); ?></h3></div>
                                <div class="name"><?php echo($arrListingData['individual_full_name']); ?></div><!-- /.name -->
                                <?php foreach ($arrIndivPhone as $intINdex => $arrPhoneData) { ?>
                                    <div class="phone"><?php echo($arrPhoneData['description'] . ': ' . $arrPhoneData['display_number']); ?></div>
                                <?php } ?>
                                <br />
                            </div>
                        </div>
                        <div class="row">
                            <div class="email">
                                <a style="margin-right: 10px;" href="<?php echo($arrListingData['individual_website']); ?>" class="btn btn-primary btn-small arrow-right pull-right" target="_blank"><?php echo($Application->translate('Visit Website', 'Visitez le site web', '訪問網站')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clear"></div>
        <div class="clear"></div>
    </div>
</div>
