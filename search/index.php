<?php
    require_once '../Core/Application.php';
	require_once 'listings_data_access.php';
	$Application = \Core\Application::getInstance();
    $listingData = \core\api\Listings::getBootstrapData();
    $listingData = \core\api\Listings::getListingsCollection();
    $bootstrapData = $listingData;
    unset($bootstrapData['results']);
    $siteName = $Application->getConfigs()->get('Application.site.site_name');
?>

<!DOCTYPE html>
<!--[if lt IE 8]>
<html lang="en-CA" xmlns:fb="//ogp.me/ns/fb#" class="ie">
<![endif]-->

<!--[if IE 8]>
<html lang="en-CA" xmlns:fb="//ogp.me/ns/fb#" class="ie ie8">
<![endif]-->

<!--[if IE 9]>
<html lang="en-CA" xmlns:fb="//ogp.me/ns/fb#" class="ie ie9">
<![endif]-->

<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en-CA" xmlns:fb="//ogp.me/ns/fb#">
<!--<![endif]-->

<head>
    <link rel="dns-prefetch" href="//maps.googleapis.com">
    <link rel="dns-prefetch" href="//maps.gstatic.com">
    <link rel="dns-prefetch" href="//mts0.googleapis.com">
    <link rel="dns-prefetch" href="//mts1.googleapis.com">

    <meta charset="utf-8">
    <link href="assets/css/common.css" media="all" rel="stylesheet" type="text/css" />
    <link href="assets/js/chosen/chosen.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="assets/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="assets/css/map_search.css" media="screen" rel="stylesheet" type="text/css" />

    <script>
        var sherlock_firstbyte = Number(new Date());
        var googleMapsUrl = '//maps.googleapis.com/maps/api/js?language=en-CA&sensor=false&v=3.13&libraries=places';
        var userAttributeCookies = {
            flags_name: 'flags',
            roles_name: 'roles',
            flags: {
                "can_see_pretzel": 1,
                "can_see_community": 2,
                "display_assets_for_pretzel": 4,
                "og_publish": 16,
                "has_wishlisted": 32,
                "update_cached": 64,
                "revert_to_admin": 128,
                "facebook_connected": 256,
                "has_search": 1024,
                "search_help_dropdown": 2048,
                "has_dates": 4096,
                "show_fb_prompt": 8192,
                "lightweight_wishlist": 16384,
                "can_send_profile_messages": 32768,
                "just_logged_in": 65536,
                "has_been_host": 131072,
                "should_drop_pellet": 262144,
                "is_active_host": 1048576,
                "can_see_meetups": 134217728,
                "should_drop_sift_pellet": 268435456,
                "can_see_groups": 536870912,
                "has_verified_phone": 1073741824,
                "has_profile_pic": 2147483648
            },
            roles: {
                "is_admin": 1,
                "is_aircrew": 8,
                "is_content_manager": 1048576,
                "is_stats_admin": 16777216
            }
        };
    </script>
    <title><?php echo $listingData['meta_description']; ?> - <?php echo $siteName; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="canonical" href="https://www.airbnb.ca/s/Montreal--Canada">
    <meta name="description" content="<?php echo $listingData['meta_description']; ?>">
    <meta property="og:image" content="https://a0.muscache.com/airbnb/static/logos/200x200-2bfa74c5a3542a898901cdee9638a6ee.png">
    <meta name="viewport" content="width=1000,maximum-scale=1.0">
    <link rel="image_src" href="https://a0.muscache.com/airbnb/static/airbnb_logo-0887e76cd6fd403d016dd652455acbb6.png">

    <!--[if lt IE 9]>
      <script src="assets/js/html5-shiv.js" type="text/javascript"></script>
    <![endif]-->
    <link rel="shortcut icon" type="image/x-icon" href="https://a0.muscache.com/airbnb/static/logotype_favicon-2e5a2c7c6a64c00b95ed01dec8b85f57.ico">
</head>

<body class=" ">
    <div id="header" class="navbar navbar-top">
        <div class="navbar-inner">
            <div class="container container-full-width page-container"> <a href="/" class="brand mlspremium"><?php echo ($siteName); ?></a>
                <ul class="nav">
                    <li> <span class="search-header">
                      <form class="form-horizontal">
                           <input name="location" type="text" value="<?php echo $listingData['location']['location'];?>" class="location form-control"
                                placeholder="Where are you going?"/>
                                <button type="submit" class="btn btn-primary search-button"><i class="icon icon-search"></i></button>
                      </form>
                      </span>
                    </li>
                </ul>
                <ul class="nav pull-right help-menu" style="margin-left:0;">
                    <li class="dropdown" data-dropdown-sticky="true"> <a id="help_dropdown" class="dropdown-toggle help-toggle" href="javascript:void(0)"> Help <b class="caret"></b></a>
                        <div class="dropdown-menu help-dropdown dropdown-bordered">
                            <div class="nav-header"> <a href="/help?ref=help-dropdown" class="help-center-link">Visit the Help Centre &#187;</a>
                            </div>
                            <ul class="unstyled list-unstyled">
                                <li class="loading"></li>
                                <li class="hidden"> <a href="/safety?ref=help-dropdown" class="">Visit our Trust & Safety Centre</a>
                                </li>
                                <li class="all_faqs hidden"> <a href="/help/topic/hosting?ref=help-dropdown">See all FAQs</a>
                                </li>
                                <li id="faqadmin" style="display: none;">
                                    <input type="hidden" name="faq_link[page]" value="search-index">
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="list-your-space"> <a id="list-your-space" class="yellow btn btn-special" href="/rooms/new">List Your Space</a>
                    </li>
                </ul>
                <ul class="nav pull-right logged-out">
                    <li id="sign_up"><a data-signup-modal href="/signup_login?redirect_params[action]=index&amp;redirect_params[controller]=search&amp;redirect_params[location]=Montreal%2C+QC%2C+Canada&amp;redirect_params[path_location]=Montreal--QC--Canada">Sign Up</a>
                    </li>
                    <li id="login"><a data-login-modal href="/login?redirect_params[action]=index&amp;redirect_params[controller]=search&amp;redirect_params[location]=Montreal%2C+QC%2C+Canada&amp;redirect_params[path_location]=Montreal--QC--Canada">Log In</a>
                    </li>
                </ul>
                <ul class="nav pull-right logged-in">
                    <li class="dropdown user-item">
                        <a class="dropdown-toggle" href="javascript:void(0)">
                            <div class="mini-media-box media-photo user-profile-image"></div>
                            <span class="value_name">User</span>  <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu dropdown-bordered">
                            <li><a href="/home/dashboard">Dashboard</a>
                            </li>
                            <li>
                                <a href="/rooms"> <span class="singular" style="display: none;">Your Listing</span>  <span class="plural">Your Listings</span>
                                </a>
                            </li>
                            <li><a href="/trips/upcoming">Your Trips</a>
                            </li>
                            <li><a href="/wishlists/my" id="wishlists">Wish Lists</a>
                            </li>
                            <li class="groups"> <a href="/groups"> Groups <span class="label label-pink label-new">New</span> </a>
                            </li>
                            <li> <a href="/invite?r=3"> Invite Friends <span class="label label-pink label-new">New</span> </a>
                            </li>
                            <li><a href="/users/edit">Edit Profile</a>
                            </li>
                            <li><a href="/account">Account</a>
                            </li>
                            <li><a href="/logout" id="header-logout">Log Out</a>
                            </li>
                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                    <li id="inbox-item"> <a href="/inbox"><i class="icon icon-mail hide-text"><span class="text-hide">Inbox</span></i><i class="alert-count position-super fade">0</i></a>
                    </li>
                    <li class="divider-vertical"></li>
                </ul>
            </div>
        </div>
    </div>


    <div class="map-search" data-bootstrap-data="<?php echo htmlentities(str_replace(array('\r', '\t', '\n'), '', \core\api\Listings::jsonEncode($bootstrapData))); ?>">
        <div class="sidebar">
            <div class="filters collapse">
                <?php echo($listingData['filters']); ?>
            </div>

            <div class="sidebar-header clearfix">
                <button class="btn gray show-filters">
                    <i class="icon icon-filter"></i>  <span class="text-more-filters">More Filters</span><span class="text-filters">Filters</span>
                </button>
                <ul class="applied-filters list-unstyled">
                    <li class="hide" data-applied-filter="room_types">
                        <h6><span>&times;</span> Room Type</h6>
                    </li>
                    <li class="hide" data-applied-filter="buildingType">
                        <h6><span>&times;</span> Building Type</h6>
                    </li>
                    <li class="hide" data-applied-filter="price">
                        <h6><span>&times;</span> Price</h6>
                    </li>
                    <li class="hide" data-applied-filter="size">
                        <h6><span>&times;</span> Size</h6>
                    </li>
                    <li class="hide" data-applied-filter="connected">
                        <h6><span>&times;</span> Social Connections</h6>
                    </li>
                    <li class="hide" data-applied-filter="empHost">
                        <h6><span>&times;</span> Employee Host</h6>
                    </li>
                    <li class="hide" data-applied-filter="exp_types">
                        <h6><span>&times;</span> Experience</h6>
                    </li>
                    <li class="hide" data-applied-filter="neighborhoods">
                        <h6><span>&times;</span> Neighbourhoods</h6>
                    </li>
                    <li class="hide" data-applied-filter="hosting_amenities">
                        <h6><span>&times;</span> Amenities</h6>
                    </li>
                    <li class="hide" data-applied-filter="property_type_id">
                        <h6><span>&times;</span> Property Type</h6>
                    </li>
                    <li class="hide" data-applied-filter="languages">
                        <h6><span>&times;</span> Host Language</h6>
                    </li>
                    <li class="hide" data-applied-filter="keywords">
                        <h6><span>&times;</span> Keywords</h6>
                    </li>
                    <li class="hide" data-applied-filter="closest">
                        <h6><span>&times;</span> Nearest Listings</h6>
                    </li>
                    <li class="results-count-item">
                        <h1 class="results-count">1000+ Rentals &middot; Montreal</h1>
                    </li>
                </ul>
            </div>
            <div class="sidebar-header-placeholder"></div>
            <div class="search-results">
                <div class="alert disaster-rooster hide row-space-4"> <a href="#" class="alert-close close">&times;</a>
                    <div class="h4 row-space-2">Urgent Accommodations for those displaced by <span class='disaster-name'></span>
                    </div>
                    <a class="btn btn-primary disaster-guest" data-url-prefix="/s?"> I need a place to stay </a>
                    <a class="btn btn-special disaster-host" data-url-prefix="/disaster/"> I can offer my space for free </a>
                </div>
                <div class="outer-listings-container">
            <ul id="results" class="listings-container list-unstyled clearfix"></ul>
                    <?php #echo($listingData['results']); ?>
                </div>
                <?php #echo($listingData['pagination_footer']); ?>

        <div class="results-footer">
              <div class="page-divider"> </div>
              <div class="pagination-buttons-container">
                <div class="results_count">
                </div>
                <div class="pagination">
                    <ul class="list-unstyled"></ul>
                </div>
              </div>
              <div class="breadcrumbs" itemprop="breadcrumb">

               </div>
            </div>



            </div>
        </div>
        <div class="map">
            <div class="map-canvas"></div>
            <div class="map-refresh-controls">
                <a class="map-manual-refresh btn btn-primary hide"> <i class="icon icon-refresh"></i>Redo search here</a>
                <div class="panel map-auto-refresh hide">
                    <label class="checkbox">
                        <input class="map-auto-refresh-checkbox" type="checkbox" checked="checked">Search when I move the map
                    </label>
                </div>
            </div>
        </div>
        <button class="btn footer-toggle"> <span class="open-content"> <i class="icon icon-globe"></i> Language and Currency </span>  <span class="close-content"><i class="icon icon-remove"></i> Close</span>
        </button>
    </div>

    <div class="container footer-container page-container page-container-fixed">
        <div id="footer" class="row">
            <div class="span3 col-3">
                <h5>Location Settings</h5>
                <div class="language-curr-picker clearfix"> <span class="language-picker"></span>  <span class="currency-picker"></span>
                </div>
                <div>
                    <ul id="asset-experiment-links" class="list-unstyled unstyled hide">
                        <li id="show-pretzel" class="hide"> <a class="btn" href="#">Show Pretzel</a>
                        </li>
                        <li id="hide-pretzel" class="hide"> <a class="btn" href="#">Hide Pretzel</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="span3 col-3">
                <h5>Discover</h5>
                <ul class="unstyled list-unstyled js-footer-links">
                    <li><a href="/trust">Trust & Safety</a>
                    </li>
                    <li><a href="/invite">Invite Friends</a>
                    </li>
                    <li><a href="/wishlists/airbnb_picks">Airbnb Picks</a>
                    </li>
                    <li><a href="/live/open">Airbnb Open</a>
                    </li>
                    <li><a href="/mobile">Mobile</a>
                    </li>
                    <li><a href="/info/why_host">Why Host</a>
                    </li>
                    <li><a href="/hospitality">Hospitality</a>
                    </li>
                    <li><a href="/stories">Stories</a>
                    </li>
                    <li><a href="/sitemaps">Site Map</a>
                    </li>
                </ul>
            </div>
            <div class="span3 col-3">
                <h5>Company</h5>
                <ul class="unstyled list-unstyled js-footer-links">
                    <li><a href="/about/about-us">About</a>
                    </li>
                    <li><a href="/jobs">Jobs</a>
                    </li>
                    <li><a href="/press/news">Press</a>
                    </li>
                    <li><a href="//blog.airbnb.com">Blog</a>
                    </li>
                    <li><a href="/help">Help</a>
                    </li>
                    <li><a href="/policies">Policies</a>
                    </li>
                    <li><a href="/help/responsible-hosting">Responsible Hosting</a>
                    </li>
                    <li><a href="/disaster-response">Disaster Response</a>
                    </li>
                    <li><a href="/terms">Terms & Privacy</a>
                    </li>
                </ul>
            </div>
            <div class="span3 col-3">
                <h5>Join us on</h5>
                <ul class="unstyled list-unstyled js-external-links">
                    <li><a href="//twitter.com/airbnb" target="_blank"> Twitter </a>
                    </li>
                    <li><a href="//www.facebook.com/airbnb" target="_blank"> Facebook </a>
                    </li>
                    <li><a href="https://plus.google.com/+airbnb" rel="publisher" target="_blank"> Google </a>
                    </li>
                    <li><a href="//www.youtube.com/airbnb" target="_blank"> YouTube </a>
                    </li>
                </ul>
                <div id="copyright">&copy; Airbnb, Inc.</div>
            </div>
        </div>
    </div>
    <div id="fb-root"></div>

    <!--[if lt IE 9]>
    <script src="assets/js/libs_jquery_1x-c7da0881fdd03586f756cf4320c99705.js" type="text/javascript"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script src="assets/js/libs_jquery_2x-ea1d02545e031a3899bd2d631073e1e7.js" type="text/javascript"></script>
    <!--<![endif]-->
    <script src="assets/js/o2.1-cf95c4fb52a0e1d06d96029a981f38b4.js" type="text/javascript"></script>
    <script src="assets/js/core-cb909a59830563da6503660a778e23f3.js" type="text/javascript"></script>
    <script src="//maps.googleapis.com/maps/api/js?language=en-CA&sensor=false&v=3.13&libraries=places"></script>
    <script type="text/javascript" src="assets/js/chosen/chosen.jquery.min.js"></script>
    <script src="assets/js/map-search.js" type="text/javascript"></script>
    <script src="assets/js/jquery.colorbox.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/js/mp.direction.api.js"></script>
    <script>
        $(document).ready(function() {
            require('map_search/MapSearchPage').attachTo('.map-search');
            $('.search-button').trigger('click');
        });

        function ___bindQuickView()
        {
            $("a.quick_view").colorbox({
                 scrolling: false,
                'rel': 'listings__qv',
                'transition': 'elastic',
                'current': "<?php echo $Application->translate('Listing', 'Propriété', '財產'); ?> {current} / {total}",
                'next': "<?php echo $Application->translate('Next', 'Prochain', '下'); ?>",
                'previous': "<?php echo $Application->translate('Previous', 'Précédent', '前'); ?>",
                'close': "<?php echo $Application->translate('Close', 'Fermer', '關閉')?>"
            });
        }
    </script>


</body>

</html>
