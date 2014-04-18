<?php
/**
 * Created by Zhao Jiang.
 * User: Zhao Jiang
 * Date: 19/09/13
 * Time: 14:49
 * To change this template use File | Settings | File Templates.
 */
?>

<html>
    <head>
        <title>Home</title>

        <link media='screen' href='<?php echo base_url(); ?>css/main.css' rel='stylesheet' />
        <link media='screen' href='<?php echo base_url(); ?>css/mediaqueries.css' rel='stylesheet' />
        <link media='screen' href='<?php echo base_url(); ?>css/enscroll.css' rel='stylesheet' />
        <link media='screen' href='<?php echo base_url(); ?>css/chosen.css' rel='stylesheet' />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>


        <script src="<?php echo base_url(); ?>js/chosen.jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/ajaxForm.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
        <script src="<?php echo base_url(); ?>js/enscroll-0.4.0.min.js"></script>
        <script src="<?php echo base_url(); ?>js/main.js"></script>
        <script src="<?php echo base_url(); ?>js/home.js"></script>


        <script>
            $(function() {
                $( "#sort-box" ).accordion({
                    heightStyle: "content"
                });
            });

        </script>

         <!--
        <script type="text/javascript" src="http://platform.linkedin.com/in.js" >
            api_key: wx97ca1mr3m3
            onLoad: onLinkedInLoad
            authorize: true
        </script>

        <script type="text/javascript">
            // 2. Runs when the JavaScript framework is loaded
            function onLinkedInLoad() {
                IN.Event.on(IN, "auth");
            }

        </script>
              -->

    </head>

    <body>
        <div id="header">
            <section id="logo"></section>            
            <section id="home-header-right" class="left">
            <form id="loginForm" class="login_form" action="/index.php/home/login" method="post" enctype="multipart/form-data">
                <a title="Log In" id="login-link" href="#" class="left">LOG IN</a>
                <input type="text" id="login-user" name="username" class="login-input-field left" placeholder="USERNAME" value="" title="username" />
                <input type="text" id="login-pwd" name="password" class="login-input-field left" placeholder="PASSWORD" value="" title="password" />                                
            </form>
            </section>            
        </div>
        <div id="wrapper">
            <input type='hidden' name='<?php echo $this->security->get_csrf_token_name()?>' value='<?php echo $this->security->get_csrf_hash()?>' >
            <div id="search-wrapper">
                       <div id="search-box">
                           <form id ="formElem" class="search_form" action="/index.php/dashboard/searchForJob" method="post" enctype="multipart/form-data">
                               <h1>Find your next job.</h1>
                               <div id="input-fields">
                                   <section>
                                       <label for="sector">Sector:</label>
                                       <select data-placeholder="Choose a Sector..." class="chosen-select" tabindex="1" name="sector">
                                           <option value="all">Choose a Sector</option>
                                           <?php foreach ($sectors as $sector):?>
                                               <option value="<?php echo $sector['sector']?>"><?php echo $sector['sector']?></option>
                                           <?php endforeach; ?>
                                       </select>
                                   </section>

                                   <section>
                                       <label for="location">Location:</label>
                                       <select data-placeholder="Choose a Location..." class="chosen-select" tabindex="2" name="location">
                                       <option value="all">Choose a Location</option>
                                       <?php foreach ($locations as $location):?>
                                            <option value="<?php echo $location['location']?>"><?php echo $location['location']?></option>
                                       <?php endforeach; ?>
                                       </select>
                                   </section>

                                   <div id="search-button">
                                       <input type="submit" value=""/>
                                   </div>

                                   <section class="keyword">
                                       <label for="keyword">Keyword:</label>
                                       <input type="text" name="keyword" id="keyword" tabindex="3" />
                                   </section>
                               </div>
                           </form>
                           <p>Advanced Search</p>
                       </div>

                       <div id="search-result">
                           <h1>Use the search box above.</h1>
                       </div> 
            </div>
            <div id="home-main">
                    <div id="home-wrapper" class="page_wrapper">
                        <span class="logo-large"></span>
                        <div class="overlay"></div>
                        <div class="content" id="jobTypeBox">
                            <section>
                                <h1>Get the right freelancer. Get the job done.</h1>
                            </section>
                            <section class="register-content">
                                <section>
                                    <span id="postJob" class="job-type-button left">Post a job. It's free!</span>
                                    <span id="findJob" class="job-type-button marginL10 left">Want a job? Sign up!</span>
                                </section>
                            </section>                           
                        </div>
                        <div class="content" id="registerBox" style="display:none;">
                            <section>
                                <h1>PLEASE REGISTER FOR YOUR FREE PERSONAL ACCOUNT TO ACCESS OUR JOBS DATABASE</h1>
                            </section>
                            <section class="register-content">
                                <form id="registerForm" action="<?php echo base_url(); ?>index.php/home/register" method="post">
                                <input type="hidden" name="reg-usertype" id="reg-usertype" value="" />
                                <section>
                                    <h1 class="marginB10">REGISTER NOW</h1>
                                </section>
                                <section>
                                    <input type="text" class="register-input-field" id="reg-firstname" name="reg-firstname" placeholder="FIRST NAME" value="" />
                                </section>
                                <section>
                                    <input type="text" class="register-input-field" id="reg-surname" name="reg-surname" placeholder="SURNAME" value="" />
                                </section>
                                <section>
                                    <input type="text" class="register-input-field" id="reg-email" name="reg-email" placeholder="EMAIL" value="" />
                                </section>
                                <section>
                                    <input type="text" class="register-input-field" id="reg-pwd" name="reg-pwd" placeholder="PASSWORD" value="" />
                                </section>
                                <section>
                                    <input type="text" class="register-input-field" id="reg-confirm-pwd" name="reg-confirm-pwd" placeholder="RE-PASSWORD" value="" />
                                </section>
                                <section>
                                    <input type="text" class="register-input-field" id="reg-cv" name="reg-upload-cv" placeholder="UPLOAD CV" value="" />
                                </section>
                                </form>
                                <form id="goDashboardForm" action="<?php echo base_url(); ?>index.php/dashboard" method="post"></form>
                                <section>
                                    <span class="register-not-now left">NOT NOW</span>
                                    <span class="register-next left">NEXT</span>
                                </section>
                            </section>
                                                       
                        </div>                        
                    </div>  
                    <div id="job-info-wrapper" class="page_wrapper">
                        <div class="overlay">
                            <p>Getting Job Information. Please Wait...</p>
                        </div>
                        <div class="job-info-box">
                        </div>
                    </div>                      
            </div>
            <div id="home-right-wrapper">
                    <span id="new-job-board"></span>
                    <section>
                        <h1>register using</h1>
                    </section>
                    <section>
                        <span class="linkedin-account left"><span class="linkedin-icon left"></span>Linkedin Account</span>
                    </section>
                    <section id="about-us-wrapper">
                        <h1>What they are saying about us</h1>
                    </section>
                    <ul id="user-saying-content">
                        <li class="left">
                            <img class="user-img left" src="<?php echo base_url(); ?>images/profile.jpg" />
                            <div class="user-saying-content left">
                                <p class="user-saying">"I love using Jobsconnect the interface and application process reduces the time I have to spend search for new career options"</p>
                                <p class="user-info">Jane Bloggs - UK</p>
                            </div>                            
                        </li>
                        <li class="left">
                            <img class="user-img left" src="<?php echo base_url(); ?>images/profile.jpg" />
                            <div class="user-saying-content left">
                                <p class="user-saying">"I love using Jobsconnect the interface and application process reduces the time I have to spend search for new career options"</p>
                                <p class="user-info">Jane Bloggs - UK</p>
                            </div>                            
                        </li>
                    </ul>
            </div>                    
        </div><!-- #wrapper end -->
        
        <div id="footer"><!-- #footer start -->
            <div class="copyright-wrapper">
                <span id="copyright"></span>
            </div>
            <div class="footer-wrapper">
                <div class="social-wrapper">
                    <span class="social facebook" title="facebook"></span>
                    <span class="social twitter" title="twitter"></span>
                    <span class="social google" title="google+"></span>
                </div>
                <span class="apply-for-job" title="Apply for job">APPLY FOR JOB</span>
            </div>
        </div><!-- #footer end -->

        <script type="text/javascript">
            var config = {
                '.chosen-select': {}
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
        </script>
    </body>
</html>

