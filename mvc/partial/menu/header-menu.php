<?php
	$Application   = \Core\Application::getInstance();
    $assetsBaseImg = $Application->getConfigs()->get('Application.core.mvc.controller.assets.base.img');
?>
<header id="main-header" class="header_1 top-bar-off"> 
  <!-- Top Strip --> 
  
  <!-- Main Header -->
  <div class="main-navbar">
    <div class="container">
      <aside class="left-side">
        <div class="logo">
        	<a href="/"><img src="<?php echo $assetsBaseImg; ?>/logo.png"></a>
        </div>
      </aside>
      <aside class="right-side">
        <?php /* BEGIN NAVIGATION */ ?>
        <nav class="navigation"> <!--<a class="cs-click-menu"><i class="icon-list8"></i></a>-->
          <ul>
            <li id="menu-item-1487" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu current-menu-item dropdown sub-menu current_page_item dropdown sub-menu menu-item-home dropdown sub-menu current-menu-ancestor dropdown sub-menu current-menu-parent dropdown sub-menu menu-item-has-children"><a href="http://directory.chimpgroup.com/">HOME</a>
              <ul class="sub-dropdown">
                <li id="menu-item-3509" class="menu-item  menu-item-type-post_type  menu-item-object-page  current-menu-item  page_item  page-item-367  current_page_item"><a href="http://directory.chimpgroup.com/">Home 1</a></li>
                <li id="menu-item-3508" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/home-v2/">Home 2</a></li>
                <li id="menu-item-3534" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/home-v3/">Home 3</a></li>
                <li id="menu-item-3882" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/directory/realestate/">RealEstate</a></li>
                <li id="menu-item-3883" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/directory/motors/">Motors</a></li>
                <li id="menu-item-3884" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/directory/jobs/">Jobs</a></li>
                <li id="menu-item-3885" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/directory/hotels/">Hotels</a></li>
                <li id="menu-item-3975" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/directory/education/">Education</a></li>
              </ul>
              <!--End Sub Menu --> 
            </li>
            <li id="menu-item-829" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children"><a href="#">PAGES</a>
              <ul class="sub-dropdown">
                <li id="menu-item-3547" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children"><a href="#">Shortcodes</a>
                  <ul class="sub-dropdown">
                    <li id="menu-item-167" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/typography/">Typography</a></li>
                    <li id="menu-item-3128" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/common-elements/">Common Elements</a></li>
                    <li id="menu-item-3129" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/media-elements/">Media Elements</a></li>
                  </ul>
                  <!--End Sub Menu --> 
                </li>
                <li id="menu-item-153" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children"><a href="#">News List</a>
                  <ul class="sub-dropdown">
                    <li id="menu-item-156" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/blog-large/">News Style #1</a></li>
                    <li id="menu-item-154" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/blog-grid/">News Style #2</a></li>
                    <li id="menu-item-157" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/blog-medium/">News Style #3</a></li>
                    <li id="menu-item-158" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/blog-small/">News Style #4</a></li>
                    <li id="menu-item-1582" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/blog-box/">News Style #5</a></li>
                    <li id="menu-item-155" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/blog-masnory/">News Style #6</a></li>
                    <li id="menu-item-844" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children"><a href="#">News Post</a>
                      <ul class="sub-dropdown">
                        <li id="menu-item-845" class="menu-item  menu-item-type-post_type  menu-item-object-post"><a href="http://directory.chimpgroup.com/audio-post/">Audio Post</a></li>
                        <li id="menu-item-851" class="menu-item  menu-item-type-post_type  menu-item-object-post"><a href="http://directory.chimpgroup.com/soundcloud-post/">Sound Cloud Post</a></li>
                        <li id="menu-item-847" class="menu-item  menu-item-type-post_type  menu-item-object-post"><a href="http://directory.chimpgroup.com/video-post/">Video Post</a></li>
                        <li id="menu-item-846" class="menu-item  menu-item-type-post_type  menu-item-object-post"><a href="http://directory.chimpgroup.com/slider-post-single-image/">Slider Post</a></li>
                      </ul>
                      <!--End Sub Menu --> 
                    </li>
                  </ul>
                  <!--End Sub Menu --> 
                </li>
                <li id="menu-item-1534" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/contact-us/">Contact Us</a></li>
                <li id="menu-item-2012" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/plan-and-pricing/">Plan And Pricing</a></li>
                <li id="menu-item-2441" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/privacy-policy/">Privacy Policy</a></li>
                <li id="menu-item-1986" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/faqs/">FAQ&#8217;s</a></li>
                <li id="menu-item-832" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/pf-under-construction/">Under Construction</a></li>
                <li id="menu-item-830" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/404">404 : Nothing Found Page</a></li>
                <li id="menu-item-831" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/?s=no+search+result&amp;submit=">No Search Result</a></li>
              </ul>
              <!--End Sub Menu --> 
            </li>
            <li id="menu-item-3170" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children"><a href="#">CATEGORIES</a>
              <ul class="sub-dropdown">
                <li id="menu-item-3171" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/categories-view/">Categories Styles</a></li>
                <li id="menu-item-3146" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=property&amp;submit=">Property</a></li>
                <li id="menu-item-3147" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=motors&amp;submit=">Motors</a></li>
                <li id="menu-item-3167" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=for-sale&amp;submit=">For Sale</a></li>
                <li id="menu-item-3160" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=jobs&amp;submit=">Jobs</a></li>
                <li id="menu-item-3161" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=services&amp;submit=">Services</a></li>
                <li id="menu-item-3163" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=restaurant&amp;submit=">Restaurant</a></li>
                <li id="menu-item-3164" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;submit=">Pets</a></li>
                <li id="menu-item-3165" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=hotels-travel&amp;submit=">Hotel &#038; Travel</a></li>
              </ul>
              <!--End Sub Menu --> 
            </li>
            <li id="menu-item-1724" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children"><a href="#">LISTING</a>
              <ul class="sub-dropdown">
                <li id="menu-item-1725" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/heading-section/">Heading Section Blank</a></li>
                <li id="menu-item-1726" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/listing-plain-heading/">Listing Plain Heading</a></li>
                <li id="menu-item-1732" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/listing-map/">Listing Map</a></li>
                <li id="menu-item-1734" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/listing-revolution-slider/">Listing Revolution Slider</a></li>
                <li id="menu-item-1731" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/listing-banner/">Listing Banner</a></li>
                <li id="menu-item-1728" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/listing-adsense/">Listing AdSense</a></li>
              </ul>
              <!--End Sub Menu --> 
            </li>
            <li id="menu-item-1573" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children"><a href="http://Agents&amp;Agencies">AGENTS</a>
              <ul class="sub-dropdown">
                <li id="menu-item-1698" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/agents/">Agents</a></li>
                <li id="menu-item-1697" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/agencies/">Agencies</a></li>
                <li id="menu-item-2951" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/top-agencies/">Top Agencies</a></li>
                <li id="menu-item-1700" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/agents/">Filterable Agents</a></li>
                <li id="menu-item-1701" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom"><a href="http://directory.chimpgroup.com/author/allencole/?action=detail&amp;uid=21">Single Agent</a></li>
                <li id="menu-item-1699" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/register-as-agent/">Register as Agent</a></li>
              </ul>
              <!--End Sub Menu --> 
            </li>
            <li id="menu-item-2648" class="menu-item dropdown mega-menu cs-mega-menu menu-item-type-post_type dropdown mega-menu cs-mega-menu menu-item-object-page dropdown mega-menu cs-mega-menu menu-item-has-children"><a href="http://directory.chimpgroup.com/feature-main-features/">FEATURES</a>
              <ul class="mega-grid" >
                <li id="menu-item-2626" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children">
                  <ul class="sub-dropdown">
                    <li id="menu-item-2611" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a title="new" href="http://directory.chimpgroup.com/feature-one-click-demo-install/"><i class="icon-thumbs-up"></i>Some menu item here</a></li>
                    <li id="menu-item-2623" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/features-fully-responsive/"><i class="icon-mobile4"></i>Some menu item here</a></li>
                    <li id="menu-item-2625" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/features-unlimited-colors/"><i class="icon-palette"></i>Some menu item here</a></li>
                    <li id="menu-item-2622" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/features-650-google-fonts/"><i class="icon-font"></i>Some menu item here</a></li>
                    <li id="menu-item-2621" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-wpml-ready/"><i class="icon-language"></i>Some menu item here</a></li>
                    <li id="menu-item-2614" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-premium-support/"><i class="icon-life-bouy"></i>Some menu item here</a></li>
                    <li id="menu-item-2615" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-seo-friendly-urls/"><i class="icon-hyperlink"></i>Some menu item here</a></li>
                  </ul>
                  <!--End Sub Menu --> 
                </li>
                <li id="menu-item-2627" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children">
                  <ul class="sub-dropdown">
                    <li id="menu-item-2620" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-unlimited-sidebars/"><i class="icon-list3"></i>Some menu item here</a></li>
                    <li id="menu-item-2645" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-advanced-seo/"><i class="icon-network"></i>Some menu item here</a></li>
                    <li id="menu-item-2613" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-paypal-integrated/"><i class="icon-paypal4"></i>Some menu item here</a></li>
                    <li id="menu-item-2602" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-advertising-spaces/"><i class="icon-qrcode"></i>Some menu item here</a></li>
                    <li id="menu-item-2619" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-unlimited-directory-types/"><i class="icon-keyboard4"></i>Some menu item here</a></li>
                    <li id="menu-item-2608" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-featured-paid-listings/"><i class="icon-feather"></i>Some menu item here</a></li>
                    <li id="menu-item-2603" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-charge-fees-for-submissions/"><i class="icon-money"></i>Some menu item here</a></li>
                  </ul>
                  <!--End Sub Menu --> 
                </li>
                <li id="menu-item-2628" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children">
                  <ul class="sub-dropdown">
                    <li id="menu-item-2609" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-listing-with-expiration-date/"><i class="icon-lab2"></i>Some menu item here</a></li>
                    <li id="menu-item-2601" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-shortcodes/"><i class="icon-code"></i>Some menu item here</a></li>
                    <li id="menu-item-2616" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-solid-theme-options/"><i class="icon-tools3"></i>Some menu item here</a></li>
                    <li id="menu-item-2607" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-drag-drop-page-builder/"><i class="icon-building"></i>Some menu item here</a></li>
                    <li id="menu-item-2644" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-listings-to-favorites/"><i class="icon-heart10"></i>Some menu item here</a></li>
                    <li id="menu-item-2604" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-complete-review-system/"><i class="icon-star-half-empty"></i>Some menu item here</a></li>
                    <li id="menu-item-2606" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-create-faq-sections/"><i class="icon-question-circle"></i>Some menu item here</a></li>
                  </ul>
                  <!--End Sub Menu --> 
                </li>
                <li id="menu-item-2629" class="menu-item dropdown sub-menu menu-item-type-custom dropdown sub-menu menu-item-object-custom dropdown sub-menu menu-item-has-children">
                  <ul class="sub-dropdown">
                    <li id="menu-item-2113" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-payment-gateways/"><i class="icon-paypal4"></i>Some menu item here</a></li>
                    <li id="menu-item-2624" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-revolution-slider/"><i class="icon-pictures5"></i>Some menu item here</a></li>
                    <li id="menu-item-2618" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-unlimited-categories/"><i class="icon-list6"></i>Some menu item here</a></li>
                    <li id="menu-item-2617" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-sort-listings-by-different-criteria/"><i class="icon-sort-alpha-desc"></i>Some menu item here</a></li>
                    <li id="menu-item-2605" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-contact-form-phone/"><i class="icon-envelope7"></i>Some menu item here</a></li>
                    <li id="menu-item-2610" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/feature-location-based-searches/"><i class="icon-pin-alt"></i>Some menu item here</a></li>
                  </ul>
                  <!--End Sub Menu --> 
                </li>
              </ul>
              <!--End Sub Menu --> 
            </li>
            <li id="menu-item-3448" class="menu-item dropdown sub-menu menu-item-type-post_type dropdown sub-menu menu-item-object-page dropdown sub-menu menu-item-has-children"><a href="http://directory.chimpgroup.com/shop/">SHOP</a>
              <ul class="sub-dropdown">
                <li id="menu-item-3445" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/my-account/">My Account</a></li>
                <li id="menu-item-3446" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/checkout/">Checkout</a></li>
                <li id="menu-item-3447" class="menu-item  menu-item-type-post_type  menu-item-object-page"><a href="http://directory.chimpgroup.com/cart/">Cart</a></li>
              </ul>
              <!--End Sub Menu --> 
            </li>
          </ul>
        </nav>
        <?php /* END NAVIGATION */ ?>
        <?php /* POST AN ADD + ADD TO FAVS LOGIN BOX */ ?>
        <div class="btn-sec"><a data-toggle="modal" data-target="#cs_ad_post_modal" class="hd-btn cs-bgcolor"><i class="icon-plus3"></i>Post an Ad</a>
          <?php /* END POST AN ADD + ADD TO FAVS LOGIN BOX */ ?>
          <?php /* MENU LOGIN BOX */ ?>
          <div class="cs-login-sec">
            <ul>
              <li><a href="#" class="cs-user"><i class="icon-user2"></i></a>
                <div class="cs-signup" style="display: none;"> 
                  <!-- Header Element --> 
                  <script>
                            jQuery(document).ready(function(){
                                jQuery('#ControlForm_207536 input').keydown(function(e) {
                                if (e.keyCode == 13) {
                                    cs_user_authentication('http://directory.chimpgroup.com/wp-admin/admin-ajax.php','207536');
                                }
                            });
                            jQuery("#cs-signup-form-section").hide();
                            jQuery("#accout-already").hide();
                              jQuery("#signup-now").click(function(){
                                jQuery("#login-from-207536").hide();
                                jQuery("#signup-now").hide();
                                jQuery("#cs-signup-form-section").show();
                                jQuery("#accout-already").show();
                              });
                              jQuery("#accout-already").click(function(){
                                jQuery("#login-from-207536").show();
                                jQuery("#signup-now").show();
                                jQuery("#cs-signup-form-section").hide();
                                jQuery("#accout-already").hide();
                              });
                            });
                         </script>
                  <section class="sg-header ">
                    <div class="header-element login-from login-form-id-207536" id="login-from-207536">
                      <h6>User Sign in</h6>
                      <form method="post" class="wp-user-form webkit" id="ControlForm_207536">
                        <fieldset>
                          <span class="status status-message" style="display:none"></span>
                          <p class="sg-email"> <span class="iconuser"></span>
                            <input type="text" name="user_login" size="20" tabindex="12" onfocus="if(this.value =='Username') { this.value = ''; }" onblur="if(this.value == '') { this.value ='Username'; }" value="Username">
                          </p>
                          <p class="sg-password"> <span class="iconepassword"></span>
                            <input type="password" name="user_pass" size="20" tabindex="12" onfocus="if(this.value =='Password') { this.value = ''; }" onblur="if(this.value == '') { this.value ='Password'; }" value="Password">
                          </p>
                          <p> <i class="icon-angle-right"></i>
                            <input type="button" name="user-submit" class="cs-bgcolor" value="Sign in" onclick="javascript:cs_user_authentication('http://directory.chimpgroup.com/wp-admin/admin-ajax.php','207536')" />
                            <input type="hidden" name="redirect_to" value="http://directory.chimpgroup.com/" />
                            <input type="hidden" name="user-cookie" value="1" />
                            <input type="hidden" value="ajax_login" name="action">
                            <input type="hidden" name="login" value="login" />
                          </p>
                        </fieldset>
                      </form>
                    </div>
                    <div class="user-sign-up" id="cs-signup-form-section" style="display:none">
                      <h6>User Sign Up</h6>
                      <form method="post" class="wp-user-form" id="wp_signup_form_207536" enctype="multipart/form-data">
                        <fieldset>
                          <div id="result_207536" class="status-message">
                            <p class="status"></p>
                          </div>
                          <p class="sg-email"> <span class="iconuser"></span>
                            <input type="text" name="user_login" size="20" tabindex="12" onfocus="if(this.value =='Username') { this.value = ''; }" onblur="if(this.value == '') { this.value ='Username'; }" value="Username">
                          </p>
                          <p class="sg-password"> <span class="iconemail"></span>
                            <input type="email" name="user_email" size="20" tabindex="12" onfocus="if(this.value =='E-mail address') { this.value = ''; }" onblur="if(this.value == '') { this.value ='E-mail address'; }" value="E-mail address">
                          </p>
                          <p> <i class="icon-angle-right"></i>
                            <input type="button" name="user-submit"  value="Sign Up" class="cs-bgcolor"  onclick="javascript:cs_registration_validation('http://directory.chimpgroup.com/wp-admin/admin-ajax.php','207536')" />
                            <input type="hidden" name="role" value="member" />
                            <input type="hidden" name="action" value="cs_registration_validation" />
                          </p>
                        </fieldset>
                      </form>
                    </div>
                    <div class="hd_sepratore"><span>OR</span></div>
                    <div class="footer-element comment-form-social-connect social_login_ui ">
                      <div class="social_login_facebook_auth">
                        <input type="hidden" name="client_id" value="744120872337074" />
                        <input type="hidden" name="redirect_uri" value="http://directory.chimpgroup.com/index.php?social-login=facebook-callback" />
                      </div>
                      <div class="social_login_twitter_auth">
                        <input type="hidden" name="client_id" value="ghnkXI0p6BtyBMM8puLJZa73L" />
                        <input type="hidden" name="redirect_uri" value="http://directory.chimpgroup.com/index.php?social-login=twitter" />
                      </div>
                      <div class="social_login_google_auth">
                        <input type="hidden" name="client_id" value="965431802636-tfsn47ou2jh6jetrm58krqba2gl3o04h.apps.googleusercontent.com" />
                        <input type="hidden" name="redirect_uri" value="http://directory.chimpgroup.com/wp-login.php?loginGoogle=1" />
                      </div>
                      <div class="sg-social">
                        <ul>
                          <li><a href="javascript:void(0);" title="Facebook" id="cs-social-login-2id4rfb"  data-original-title="Facebook" class="social_login_login_facebook"><span class="social-mess-top fb-social-login" style="display:none">Please set API key</span><i class="icon-facebook2"></i>Login With Facebook</a></li>
                          <li><a href="javascript:void(0);" title="Twitter" id="cs-social-login-2id4rtw" data-original-title="twitter" class="social_login_login_twitter"><span class="social-mess-top tw-social-login" style="display:none">Please set API key</span><i class="icon-twitter6"></i>Login With twitter</a></li>
                          <li><a  href="javascript:void(0);" rel="nofollow" title="google-plus" id="cs-social-login-2id4rgp" data-original-title="google-plus" class="social_login_login_google"><span class="social-mess-top gplus-social-login" style="display:none">Please set API key</span><i class="icon-google-plus"></i>Login with Google Plus</a></li>
                        </ul>
                      </div>
                    </div>
                    <!-- End of social_login_ui div --> 
                  </section>
                  <aside class="sg-footer"> <a href="http://directory.chimpgroup.com/my-account/lost-password/" class="left-side">Forget Password</a>
                    <p id="signup-now" class="right-side"><a>Sign Up</a></p>
                    <p id="accout-already" class="right-side"><a style="font-size:12px;">Sign In </a></p>
                  </aside>
                  
                  <!-- Footer Element --> 
                </div>
              </li>
            </ul>
          </div>
        </div>
        <?php /* EOF MENU LOGIN BOX */ ?>
      </aside>
    </div>
  </div>
</header>
<div class="clear"></div>