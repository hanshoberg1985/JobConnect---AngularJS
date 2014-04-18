<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 15/08/13
 * Time: 14:49
 * To change this template use File | Settings | File Templates.
 */
?>



<html>
    <head>
        <title>Dashboard</title>

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


        <script>
            $(function() {
                $( "#sort-box" ).accordion({
                    heightStyle: "content"
                });
            });

        </script>


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


    </head>

    <body>
        <div id="header">
            <section id="header-right">
                <?php
                    if(isset($userdata['login_state']) && $userdata['login_state'] == TRUE) {
                ?>                
                <form id="logoutForm" action="/index.php/home/logout" method="post">                
                    <input type="submit" id="logout" class="log-in-btn" value="logout" />
                </form>                              
                <?php
                    } else {
                ?>
                <span title="Click To Register" id="register" class="left marginR10">Register</span>
                <input type="button" id="login" class="left log-in-btn" value="Log In" />
                <?php
                    }
                ?>
            </section>
            <section id="logo"></section>
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
            <div id="main">
                    <div id="dashboard-wrapper" class="page_wrapper">
                        <div class="overlay"></div>
                        <div id="all-jobs">
                            <h1>Most Recent Jobs (<em><?php echo $total ?></em> Positions Available)</h1>
                            <p class="instruction">
                                Click on the job box or the job title to see the full posting, or click the company's name to view all jobs from the company.
                            </p>
                            <div id="all-jobs-left">

                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                    <thead>
                                    <tr>
                                        <th class="title"></th>
                                        <th class="date"></th>
                                        <th class="location"></th>
                                        <th class="agency"></th>
                                        <th class="type"></th>
                                        <th class="sector"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="gradeX">
                                        <?php
                                        $categories = array();
                                        $types = array();
                                        $locations = array();
                                        foreach ($feed as $job_item):?>
                                        <td><section class="aj-box" data-user="<?php echo $job_item['user_id']?>" data-jobid="<?php echo $job_item['jobs_id']?>"
                                                     data-linkedin="<?php echo $job_item['linkedin']?>" data-realname="<?php echo $job_item['realname']?>" data-email="<?php echo $job_item['email']?>">
                                                <div class="aj-job-info">
                                                    <p class="title"><?php echo $job_item['jobTitle']?></p>
                                                    <p class="company"><span class="agency"><?php echo $job_item['company']?></span> - <span class="timestamp"><?php echo $job_item['updated']?></span></p>
                                                    <p class="company"><?php echo $job_item['sector']?></p>
                                                    <p class="aj-job-description"><?php echo $job_item['description']?></p>
                                                    <p class="aj-job-location"><?php echo $job_item['location']?></p>
                                                    <p class="aj-job-salary"><?php echo $job_item['salary']?></p>
                                                </div>
                                            </section>
                                        </td>
                                        <td class="hidden"><span><?php echo $job_item['updated']?></span></td>
                                        <td class="hidden"><span><?php echo $job_item['location']?></span></td>
                                        <td class="hidden"><span><?php echo $job_item['company']?></span></td>
                                        <td class="hidden"><span><?php echo $job_item['type']?></span></td>
                                        <td class="hidden"><span><?php echo $job_item['sector']?></span></td>
                                    </tr>
                                    <?php
                                    $categories[] = $job_item['sector'];
                                    $types[] = $job_item['type'];
                                    $locations[] = $job_item['location'];
                                    endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th><input type="text" class="search_title" /></th>
                                        <th><input type="text" class="search_date" /></th>
                                        <th><input type="text" class="search_location" /></th>
                                        <th><input type="text" class="search_agency" /></th>
                                        <th><input type="text" class="search_type" /></th>
                                        <th><input type="text" class="search_category" /></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div id="all-jobs-right">
                               <!-- <p class="header"></p> -->
                                <h3 style="margin-top: -10px;" class="filter-by-keyword">Filter By Keyword</h3>
                                <div id="sort-by">
                                    <input name="filter-keyword" type="text" id="search-keyword" placeholder="Keyword"/>
                                </div>
                                <div id="sort-box">
                                    <h3>Sort By</h3>
                                    <div id="sort-by">
                                        <section id="jt">
                                            <span class="key">Job Title</span>
                                        </section>
                                        <section id="dt">
                                            <span class="key">Date Added/Updated</span>
                                        </section>
                                        <section id="lt">
                                            <span class="key">Location</span>
                                        </section>
                                        <section id="at">
                                            <span class="key">Agency</span>
                                        </section>
                                    </div>
                                    <h3>Category</h3>
                                    <div id="sort-by" class="filter-by">
                                        <section class="cg">
                                            <span class="key">All Categories</span>
                                            <span class="value"><?php echo count($categories)?></span>
                                        </section>
                                        <?php
                                        sort($categories);
                                        foreach (array_count_values($categories) as $key=>$value):?>
                                        <section id="<?php echo $key?>" class="cg">
                                            <span class="key"><?php echo $key?></span>
                                            <span class="value"><?php echo $value?></span>
                                        </section>
                                        <?php endforeach; ?>
                                    </div>
                                    <h3>Type</h3>
                                    <div id="sort-by" class="filter-by">
                                        <section class="cg">
                                            <span class="key">All Job Types</span>
                                            <span class="value"><?php echo count($types)?></span>
                                        </section>
                                        <?php
                                        sort($types);
                                        foreach (array_count_values($types) as $key=>$value):?>
                                        <section id="<?php echo $key?>" class="tp">
                                            <span class="key"><?php echo $key?></span>
                                            <span class="value"><?php echo $value?></span>
                                        </section>
                                        <?php endforeach; ?>
                                    </div>
                                    <h3>Location</h3>
                                    <div id="sort-by" class="filter-by">
                                        <section class="cg">
                                            <span class="key">All Locations</span>
                                            <span class="value"><?php echo count($locations)?></span>
                                        </section>
                                        <?php
                                        sort($locations);
                                        foreach (array_count_values($locations) as $key=>$value):?>
                                        <section id="<?php echo $key?>" class="lc">
                                            <span class="key"><?php echo $key?></span>
                                            <span class="value"><?php echo $value?></span>
                                        </section>
                                        <?php endforeach; ?>

                                    </div>
                                </div>
                            </div>

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
            <div id="rightBar">
                    <div id="profile-box">
                        <div id="users-box">
                            <a data-href="user" id="profile-img">
                                <img src="<?php echo base_url(); ?>images/profile.jpg"/>
                            </a>
                            <section id="profile-info">
                                <h1><?php if(isset($userdata['forename'])) echo $userdata['forename'];?> <?php if(isset($userdata['surname'])) echo $userdata['surname'];?></h1>
                                <h2>Web Developer</h2>
                                <p class="user-location">
                                    <img src="<?php echo base_url(); ?>images/location-icon.png"/>
                                    <span>Dublin 22</span>
                                </p>
                            </section>
                            <section id="profile-edit">
                                <a href="#" title="">My Profile</a>
                            </section>
                        </div>
                        <div id="recruiter-box">
                            <a data-href="user" id="profile-img">
                                <img src="<?php echo base_url(); ?>images/squarelogo.jpg"/>
                            </a>
                            <section id="profile-info">
                                <h1></h1>
                                <h2></h2>
                                <p class="user-location">
                                    <img src="<?php echo base_url(); ?>images/location-icon.png"/>
                                    <span></span>
                                </p>
                            </section>
                            <section id="profile-edit">
                                <a href="#" title="View Recruiter's Profile">View Recruiter's Profile</a>
                            </section>
                        </div>
                        <div id="apply">
                            <div class="overlay">
                                <p>Sending Application. Please Wait...</p>
                            </div>
                            <h1>Apply for this job</h1>
                            <div class="application-info">
                                <form id ="formElem" class="app_form" action="/index.php/dashboard/applyForJob" method="post" enctype="multipart/form-data">
                                    <div class="mid-box">
                                        <p class="title"></p>
                                        <p class="location"></p>
                                        <p class="salary"></p>
                                        <p class="apply-info">
                                            Thank you for your interest, to apply for the above opportunity, please enter your details and upload your cv using the form below.
                                        </p>
                                        <input type="text" name="forename" maxlength="255" placeholder="First Name"/>
                                        <input type="text" name="surname" maxlength="255" placeholder="Surname"/>
                                        <input type="text" name="email" maxlength="255" placeholder="Email (required)"/>
                                        <input type="text" name="home_phone" maxlength="255" placeholder="Telephone"/>
                                        <input type="file" name="resume"/>
                                        <input type='hidden' name='<?php echo $this->security->get_csrf_token_name()?>' value='<?php echo $this->security->get_csrf_hash()?>'/>
                                        <input type='hidden' name='jobs_id' value/>
                                        <input type="submit" value="Send Appication"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="your_applications">
                        <h1>Your Applications</h1>
                        <section id="other-jobs">
                            <div class="oj-box even">
                                <div class="result-box">
                                </div>
                            </div>
                        </section>

                    </div>

                </div>
        </div>

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

