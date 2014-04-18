/**
 * Created with Zhao Jiang.
 * User: Zhao Jiang
 * Date: 23/09/13
 * Time: 22:26
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function() { 
    
    $('#login-user').val('');
    $('#login-pwd').val('');
    $('#reg-firstname').val('');
    $('#reg-surname').val('');
    $('#reg-cv').val(''); 
    $('#reg-email').val('');
    $('#reg-pwd').val('');
    $('#reg-confirm-pwd').val('');   
       
    $('#login-user').on('focus', function() {
        $(this).val(""); 
    });
   
    $('#login-pwd').on('focus', function() {
        $(this).val(""); 
    });    
    
    $('#reg-firstname').on('focus', function() {
        $(this).val(""); 
    });   
    
    $('#reg-surname').on('focus', function() {
        $(this).val(""); 
    });           
    
    $('#reg-email').on('focus', function() {
        $(this).val(""); 
    });               
    
    $('#reg-pwd').on('focus', function() {
        $(this).val(""); 
    });                 
    
    $('#reg-confirm-pwd').on('focus', function() {
        $(this).val(""); 
    });      
    
    $('#reg-cv').on('focus', function() {
        $(this).val(""); 
    });      
    
    $('#login-link').on('click', function() {
        if($('#login-user').val() == "") {
            alert("Please input username!");
            return;
        }
        
        if($('#login-pwd').val() == "") {
            alert("Please input password!");
            return;
        }
        
        $('#loginForm').submit();
    });
    
    $('.register-not-now').on('click', function() {
        $('#goDashboardForm').submit();
    });
    
    $('.register-next').on('click', function() {
        if($('#reg-email').val() == "" || !isEmailAddress($('#reg-email').val())) {
            alert("Please input email correctly");
            return;
        }
        
        if($('#reg-pwd').val() == "") {
            alert("Please input password correctly");
            return;
        }
        
        if($("#reg-confirm-pwd").val() == "") {
            alert("Please input re-password correctly");
            return;
        }
        
        if($('#reg-pwd').val() != $('#reg-confirm-pwd').val()) {
            alert("The password and Re-password is difference");
            return;
        } 
        
        if($('#reg-firstname').val() == "") {
            alert("Please input first name correctly");
            return;
        }
        
        if($('#reg-surname').val() == "") {
            alert("Please input surname correctly");
            return;
        }
        
        $('#registerForm').submit();
    });
    
    $('#postJob').on('click', function() {
        $('#jobTypeBox').hide();
        $('#registerBox').show();
        $('#reg-usertype').val("recruiter");
    });
    
    $('#findJob').on('click', function() {
        $('#jobTypeBox').hide();
        $('#registerBox').show();
        $('#reg-usertype').val("candidate");
    });
    
    function isEmailAddress(str) {
       var emailRegxp =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
       if(!emailRegxp.test(str)) return false;
       return true;
    } 
   
});



