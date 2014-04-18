<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 27/08/13
 * Time: 17:23
 * To change this template use File | Settings | File Templates.
 */

foreach ($jobInfo as $job_item):?>
    <section id="apply-box"
             data-jobsid="<?php echo $job_item['jobs_id']?>"
             data-jobtitle="<?php echo $job_item['jobTitle']?>"
             data-joblocation="<?php echo $job_item['location']?>"
             data-jobsalary="<?php echo $job_item['salary']?>">
        <button>
            Apply For This Job
        </button>

        <div class="social-icons">
            <a href="#"><img src="<?php echo base_url(); ?>images/facebook-3-24.ico"></a>
            <a href="#"><img src="<?php echo base_url(); ?>images/twitter-3-24.ico"></a>
            <a href="#"><img src="<?php echo base_url(); ?>images/google-plus-3-24.ico"></a>
        </div>
    </section>

    <h1 class="job-title"><?php echo $job_item['jobTitle']?></h1>

    <section id="brief-info">
        <dl>
            <dt>Job Category:</dt>
            <dd ><a href=""><em><?php echo $job_item['sector']?></em></a></dd>

            <dt>Job Type:</dt>
            <dd><a href=""><em><?php echo $job_item['type']?></em></a></dd>

            <dt>Experience:</dt>
            <dd><a href=""><em><?php echo $job_item['education']?></em></a></dd>

            <dt>Location:</dt>
            <dd><a href=""><em><?php echo $job_item['location']?></em></a></dd>

            <dt>Salary:</dt>
            <dd><a href=""><em><?php echo $job_item['salary']?></em></a></dd>

            <dt>Start Date:</dt>
            <dd><a href=""><em><?php echo $job_item['starting']?></em></a></dd>

        </dl>
    </section>

    <section id="job-description">
        <p class="description"><?php echo $job_item['description']?></p>
    </section>

<?php endforeach; ?>