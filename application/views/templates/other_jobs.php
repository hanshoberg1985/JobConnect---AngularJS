<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 28/08/13
 * Time: 13:15
 * To change this template use File | Settings | File Templates.
 */

$count =0;
$class="";

foreach ($otherJobs as $job_item):
    if ($count % 2 == 0) $class="even"; else $class="odd";
?>
    <div class="oj-box <?php echo $class?>" data-user="<?php echo $job_item['user_id']?>" data-jobid="<?php echo $job_item['jobs_id']?>">
        <p class="title"><?php echo $job_item['jobTitle']?></p>
        <p class="salary"><?php echo $job_item['salary']?></p>
        <p class="location"><?php echo $job_item['location']?></p>
    </div>
    <?php $count++;
endforeach; ?>