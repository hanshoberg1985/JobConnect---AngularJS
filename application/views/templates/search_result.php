<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 05/09/13
 * Time: 18:10
 * To change this template use File | Settings | File Templates.
 */

?>

<script>
    $('#all-result').enscroll({
        showOnHover: true,
        verticalTrackClass: 'track3',
        verticalHandleClass: 'handle3'
    });
</script>

    <h1>Search Result - <em><?php echo $total?></em> Jobs Found</h1>
    <section id="all-result">
<?php foreach ($search_result as $result):?>
    <div class="result-box" data-user="<?php echo $result['user_id']?>" data-jobid="<?php echo $result['jobs_id']?>">
        <p class="title"><?php echo $result['jobTitle']?></p>
        <p class="company"><?php echo $result['company']?></p>
        <p class="summary"><?php echo $result['description']?></p>
        <p class="location"><?php echo $result['location']?></p>
    </div>
<?php endforeach; ?>
    </section>