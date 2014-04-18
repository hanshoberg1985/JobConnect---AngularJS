/**
 * Created with JetBrains PhpStorm.
 * User: Moses Esan
 * Date: 23/08/13
 * Time: 11:42
 * To change this template use File | Settings | File Templates.
 */
var asInitVals = new Array();

$(document).ready(function() {

    var oTable = $('#example').dataTable( {
        "aaSorting": [],
        "bPaginate": false,
        "bLengthChange": false,
        "bInfo": false,
        "bAutoWidth": false,
        "sDom": '<"top"i>rt<"bottom"flp><"clear">'
    } );

    $('body').on('click','#sort-box div.filter-by section', function(){

        var type= $(this).attr('class');
        var search_value= $(this).attr('id');

        clearFilters();

        if (type === "cg"){
            $("tfoot input.search_category").val(search_value);
            $("tfoot input.search_category").trigger("click");
        }else if (type === "tp"){
            $("tfoot input.search_type").val(search_value);
            $("tfoot input.search_type").trigger("click");
        }else if (type === "lc"){
            $("tfoot input.search_location").val(search_value);
            $("tfoot input.search_location").trigger("click");
        }
    });

    function clearFilters()
    {
        $("tfoot input.search_category").val("");
        $("tfoot input.search_type").val("");
        $("tfoot input.search_location").val("");

        $("tfoot input.search_category").trigger("click");
        $("tfoot input.search_type").trigger("click");
        $("tfoot input.search_location").trigger("click");
    }

    /*Filter By Category */
    $('body').on('click','tfoot input.search_category', function(){
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );

    /*Filter By Type */
    $('body').on('click','tfoot input.search_type', function(){
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );

    /*Filter by location */
    $('body').on('click','tfoot input.search_location', function(){
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );


    $('body').on('click', '.result-box', function(){
        clearAll();
        $(this).addClass('selected-job');

        $('.overlay').show();
        var user_id = $(this).attr('data-user');
        var job_id = $(this).attr('data-jobid');
        var linkedin = $(this).attr('data-linkedin');

        ajaxCall(user_id, job_id);

        displayJobInfo();

        if (linkedin.length > 5) getLinkedIN(linkedin);
        else displayProfile("../images/placeholder.png", $(this).attr('data-realname'), "Recruitment Consultant", "Location Not Available")

        getOtherJobs(user_id);

    });

    $('body').on('click', '.overlay', function(){
        $('#job-info-wrapper').hide('slide', {direction: 'right'}, 500);
        $('.overlay').hide();
        $('.result-box').removeClass('selected-job');
        clearAll();
        $('#other-jobs').html('');
        $('#recruiter-box').hide();
        applyUserBox();

        $('#your_applications h1').html("YOUR APPLICATIONS");
    });

    $('body').on('click', '.aj-box', function(){
        clearAll();
        applyUserBox();
        $('.overlay').show();
        var user_id = $(this).attr('data-user');
        var job_id = $(this).attr('data-jobid');
        var linkedin = $(this).attr('data-linkedin');

        ajaxCall(user_id, job_id);

        displayJobInfo();

        if (linkedin.length > 5) getLinkedIN(linkedin);
        else displayProfile("../images/placeholder.png", $(this).attr('data-realname'), "Recruitment Consultant", "Location Not Available")

        getOtherJobs(user_id);
    });

    $('body').on('click', '.oj-box', function(){
        clearAll();
        $('.overlay').show();
        $(this).addClass('selected-job');

        var user_id = $(this).attr('data-user');
        var job_id = $(this).attr('data-jobid');

        ajaxCall(user_id, job_id);

        displayJobInfo();
    });

    function ajaxCall(user_id, job_id)
    {
        var cct = $("input[name=csrf_test_name]").val();

        $.ajax({
            type: "POST",
            contentType: "application/x-www-form-urlencoded; charset=ISO-8859-1",
            url: "/index.php/dashboard/view_job",
            data: {csrf_test_name: cct, user_id:user_id, job_id:job_id},
            success: function(result)
            {
                $('#job-info-wrapper .overlay').hide();
                $('.job-info-box').html(result);

            },error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
            }
        });
    }

    function clearAll()
    {
        $('.job-info-box').html("");
        $('#job-info-wrapper .overlay').show();
        $('.result-box').removeClass('selected-job');
        $('.oj-box').removeClass('selected-job');
    }

    function applyUserBox()
    {
        if ($('#apply').is(':visible') )
        {
            var i = 0;

            $('#apply input[type="text"]').each(function(){
                valu = $(this).val();
                if (valu.length > 0) i++;
            });

            if (i <= 0)
            {
                $('#apply').hide();
                $('#users-box').show();
            }
        }else
        {
            $('#users-box').show();
        }

    }

    function displayJobInfo()
    {
        $('#job-info-wrapper').show('slide', {direction: 'right'}, 500);
    }

    function getLinkedIN(linkedInProfile)
    {
        IN.API.Profile("url="+linkedInProfile+"").fields('picture-url', 'firstName', 'lastName', 'headline', 'public-profile-url', 'location:(name)', 'positions:(is-current,company)').result(extractProfiles);
    }

    function extractProfiles(profiles)
    {
        var members = profiles.values[0];

        profileImg = members.pictureUrl;
        realname = members.firstName+' '+members.lastName;
        position = members.headline;
        locate = members.location.name;

        displayProfile(profileImg, realname, position, locate);

        $('#recruiter-box #profile-edit a').html("View Recruiters Linkedin Profile");
        $('#recruiter-box #profile-edit a').attr('href', members.publicProfileUrl);
        $('#recruiter-box #profile-edit a').attr('title', "View Recruiters Linkedin Profile");
        $('#recruiter-box #profile-edit a').attr('target', "_blank");
    }

    function displayProfile(profileImg, realname, position, location)
    {
        $('#recruiter-box #profile-img img').attr('src', profileImg);
        $('#recruiter-box #profile-info h1').html(realname);
        $('#recruiter-box #profile-info h2').html(position);
        $('#recruiter-box .user-location span').html(location);
        $('#recruiter-box').show();
        $('#users-box').hide();

    }

    function getOtherJobs(user_id)
    {
        var cct = $("input[name=csrf_test_name]").val();

        $.ajax({
            type: "POST",
            url: "/index.php/dashboard/getOtherJobs",
            data: {csrf_test_name: cct, user_id:user_id},
            success: function(result)
            {
                $('#your_applications h1').html("OTHER JOBS FROM THIS RECRUITER");
                $('#other-jobs').html(result);

            },error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
            }
        });
    }

    $('body').on('click','#sort-box div#sort-by section', function(){

        var type= $(this).attr('id');

        if (type === "jt") $("th.title").trigger("click");
        else if (type === "dt") $("th.date").trigger("click");
        else if (type === "lt") $("th.location").trigger("click");
        else if (type === "at") $("th.agency").trigger("click");

    });

    /* Apply For Job */

    $('body').on('click','#apply-box', function(){
        $('#recruiter-box').hide();
        var jobs_id = $(this).attr('data-jobsid');
        var job_title = $(this).attr('data-jobtitle');
        var job_location = $(this).attr('data-joblocation');
        var job_salary = $(this).attr('data-jobsalary');

        $('.application-info .title').html(job_title);
        $('.application-info .location').html(job_location);
        $('.application-info .salary').html(job_salary);

        $('input[name="jobs_id"]').val(jobs_id);

        $('#apply').show();

    });

    $('.application-info .app_form').ajaxForm({
        beforeSend: function() {
            $('#apply .overlay').show();
        },
        success: function(result) {
            $('#apply .overlay').hide();
            $('#apply .application-info .mid-box').html(result);
        }
    });




    /* Search for job */
    $('#search-box .search_form').ajaxForm({
        beforeSend: function() {
            $('#search-result h1').html("<img src='../images/search-loader.gif'/> Searching, Please Wait...");
        },
        success: function(result) {
            $('#search-result').html(result);
        }
    });



    /*Filter By Keyword */

    $("#search-keyword").keyup(function () {
        //split the current value of searchInput
        var data = this.value.split(" ");
        //create a jquery object of the rows
        var jo = $("#example").find("tr");
        if (this.value == "") {
            jo.show();
            return;
        }
        //hide all the rows
        jo.hide();

        //Recusively filter the jquery object to get results.
        jo.filter(function (i, v) {
            var $t = $(this);
            for (var d = 0; d < data.length; ++d) {
                if ($t.is(":contains('" + data[d] + "')")) {
                    return true;
                }
            }
            return false;
        })
            //show the rows that match.
            .show();
    }).focus(function () {
            this.value = "";
            $(this).unbind('focus');
        });


    /*Load More Search Result */
    $('#all-result').on('scroll', function(){
        alert("mo");
        if($(this).scrollTop() +
            $(this).innerHeight()
            >= $(this)[0].scrollHeight)
        {
            alert('end reached');
        }
    });    
    
});

