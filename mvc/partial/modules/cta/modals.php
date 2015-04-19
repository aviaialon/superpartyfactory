<?php /* Begin the signup/login to post a add */ ?>
<div aria-hidden="true" role="dialog" tabindex="-1" id="cs_ad_post_modal" class="modal fade add-to-favborites-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="cs-login-favorites  afterlogin">
              <script>
				jQuery(document).ready(function(){
					jQuery('#ControlForm_104892 input').keydown(function(e) {
					if (e.keyCode == 13) {
						cs_user_authentication('http://directory.chimpgroup.com/wp-admin/admin-ajax.php','104892');
					}
				});
				jQuery("#cs-signup-form-section-favorites-104892").hide();
				jQuery("#accout-already-favorites-104892").hide();
				  jQuery("#signup-now-favorites-104892").click(function(){
					jQuery("#login-from-104892").hide();
					jQuery("#signup-now-favorites-104892").hide();
					jQuery("#cs-signup-form-section-favorites-104892").show();
					jQuery("#accout-already-favorites-104892").show();
				  });
				  jQuery("#accout-already-favorites-104892").click(function(){
					jQuery("#login-from-104892").show();
					jQuery("#signup-now-favorites-104892").show();
					jQuery("#cs-signup-form-section-favorites-104892").hide();
					jQuery("#accout-already-favorites-104892").hide();
				  });
				});
			  </script>
              <section class="sg-header ">
                <button data-dismiss="modal" class="close" type="button"> <span aria-hidden="true"><i class="icon-times"></i></span><span class="sr-only">Close</span> </button>
                <div class="header-element login-from login-form-id-104892" id="login-from-104892">
                  <h6>User Sign in</h6>
                  <p>Login to add new listings.</p>
                  <form method="post" class="wp-user-form webkit" id="ControlForm_104892">
                    <fieldset>
                      <span class="status status-message" style="display:none"></span>
                      <p class="sg-email"> <span class="iconuser"></span>
                        <input type="text" name="user_login" size="20" tabindex="12" onfocus="if(this.value =='Username') { this.value = ''; }" onblur="if(this.value == '') { this.value ='Username'; }" value="Username">
                      </p>
                      <p class="sg-password"> <span class="iconepassword"></span>
                        <input type="password" name="user_pass" size="20" tabindex="12" onfocus="if(this.value =='Password') { this.value = ''; }" onblur="if(this.value == '') { this.value ='Password'; }" value="Password">
                      </p>
                      <p> <i class="icon-angle-right"></i>
                        <input type="button" name="user-submit" class="cs-bgcolor" value="Sign in" onclick="javascript:cs_user_authentication('http://directory.chimpgroup.com/wp-admin/admin-ajax.php','104892')" />
                        <input type="hidden" name="redirect_to" value="http://directory.chimpgroup.com/" />
                        <input type="hidden" name="redirect_to_ad" value="1" />
                        <input type="hidden" name="user-cookie" value="1" />
                        <input type="hidden" value="ajax_login" name="action">
                        <input type="hidden" name="login" value="login" />
                      </p>
                    </fieldset>
                  </form>
                </div>
                <div class="user-sign-up" id="cs-signup-form-section-favorites-104892" style="display:none">
                  <h6>User Sign Up</h6>
                  <form method="post" class="wp-user-form" id="wp_signup_form_104892" enctype="multipart/form-data">
                    <fieldset>
                      <div id="result_104892" class="status-message">
                        <p class="status"></p>
                      </div>
                      <p class="sg-email"> <span class="iconuser"></span>
                        <input type="text" name="user_login" size="20" tabindex="12" onfocus="if(this.value =='Username') { this.value = ''; }" onblur="if(this.value == '') { this.value ='Username'; }" value="Username">
                      </p>
                      <p class="sg-password"> <span class="iconemail"></span>
                        <input type="email" name="user_email" size="20" tabindex="12" onfocus="if(this.value =='E-mail address') { this.value = ''; }" onblur="if(this.value == '') { this.value ='E-mail address'; }" value="E-mail address">
                      </p>
                      <p> <i class="icon-angle-right"></i>
                        <input type="button" name="user-submit"  value="Sign Up" class="cs-bgcolor"  onclick="javascript:cs_registration_validation('http://directory.chimpgroup.com/wp-admin/admin-ajax.php','104892')" />
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
                      <li><a href="javascript:void(0);" title="Facebook" id="cs-social-login-GtXomfb"  data-original-title="Facebook" class="social_login_login_facebook"><span class="social-mess-top fb-social-login" style="display:none">Please set API key</span><i class="icon-facebook2"></i>Login With Facebook</a></li>
                      <li><a href="javascript:void(0);" title="Twitter" id="cs-social-login-GtXomtw" data-original-title="twitter" class="social_login_login_twitter"><span class="social-mess-top tw-social-login" style="display:none">Please set API key</span><i class="icon-twitter6"></i>Login With twitter</a></li>
                      <li><a  href="javascript:void(0);" rel="nofollow" title="google-plus" id="cs-social-login-GtXomgp" data-original-title="google-plus" class="social_login_login_google"><span class="social-mess-top gplus-social-login" style="display:none">Please set API key</span><i class="icon-google-plus"></i>Login with Google Plus</a></li>
                    </ul>
                  </div>
                </div>
                <!-- End of social_login_ui div -->
              </section>
              <aside class="sg-footer"> <a href="http://directory.chimpgroup.com/my-account/lost-password/" class="left-side">Forget Password</a>
                <p id="signup-now-favorites-104892" class="right-side"><a>Sign Up</a></p>
                <p id="accout-already-favorites-104892" class="right-side"><a style="font-size:12px;">Sign In </a></p>
              </aside>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php /* Begin the signup/login to post a add */ ?>