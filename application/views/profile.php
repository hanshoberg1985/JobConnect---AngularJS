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
        <meta name="viewport" content="width=device-width; initial-scale=1.0">

    </head>

    <body>
        <div id="header">

        </div>
        <div id="wrapper">
            <div id="leftBar">

            </div>
            <div id="main">

            </div>
            <div id="rightBar">
                <div id="profile-box">
                    <a data-href="user" id="profile-img">
                        <img src="../../images/profile.jpg"/>
                    </a>
                    <section id="profile-info">
                        <h1>Moses Esan</h1>
                        <h2>Web Developer</h2>
                        <p class="user-location">
                            <img src="../../images/location-icon.png"/>
                            <span>Dublin,</span>
                            <span>Ireland</span>
                        </p>
                    </section>
                    <section id="profile-edit">
                        <a href="#" title="Edit Your Profile">Edit Profile</a>
                    </section>

                </div>
                <div id="your_applications">
                    <h1>Your Applications</h1>
                    <section class="all_applications">
                        <div class="application_box even">

                        </div>
                        <div class="application_box odd">

                        </div>
                        <div class="application_box even">

                        </div>
                        <div class="application_box odd">

                        </div>
                        <div class="application_box even">

                        </div>
                        <div class="application_box odd">

                        </div>
                        <div class="application_box even">

                        </div>

                    </section>

                </div>

            </div>
        </div>
    </body>
</html>

