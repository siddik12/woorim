

jQuery(document).ready(function() {



    /*

        Background slideshow

    */

    $.backstretch([

      "http://mszahirtraders.com/qbinventory_abk/images/img/backgrounds/2.jpg"

    , "http://mszahirtraders.com/qbinventory_abk/images/img/backgrounds/2.jpg"

    , "http://mszahirtraders.com/qbinventory_abk/images/img/backgrounds/2.jpg"

    ], {duration: 3000, fade: 750});



    /*

        Tooltips

    */

    $('.links a.home').tooltip();

    $('.links a.blog').tooltip();



    /*

        Form validation

    */

    $('.register form').submit(function(){

        $(this).find("label[for='login_type']").html('Account Type');

        $(this).find("label[for='loginid']").html('Employee Id');

       /* $(this).find("label[for='username']").html('Username');

        $(this).find("label[for='email']").html('Email');

*/        $(this).find("label[for='password']").html('Password');

        ////

        var login_type = $(this).find('select#login_type').val();

        var loginid = $(this).find('input#loginid').val();

       /* var username = $(this).find('input#username').val();

        var email = $(this).find('input#email').val();

*/        var password = $(this).find('input#password').val();

        if(login_type == '') {

            $(this).find("label[for='login_type']").append("<span style='display:none' class='red'> - Please select account Type.</span>");

            $(this).find("label[for='login_type'] span").fadeIn('medium');

            return false;

        }

        if(loginid == '') {

            $(this).find("label[for='loginid']").append("<span style='display:none' class='red'> - Please enter your employee id.</span>");

            $(this).find("label[for='loginid'] span").fadeIn('medium');

            return false;

        }

       /* if(username == '') {

            $(this).find("label[for='username']").append("<span style='display:none' class='red'> - Please enter a valid username.</span>");

            $(this).find("label[for='username'] span").fadeIn('medium');

            return false;

        }

        if(email == '') {

            $(this).find("label[for='email']").append("<span style='display:none' class='red'> - Please enter a valid email.</span>");

            $(this).find("label[for='email'] span").fadeIn('medium');

            return false;

        }

*/        if(password == '') {

            $(this).find("label[for='password']").append("<span style='display:none' class='red'> - Please enter a valid password.</span>");

            $(this).find("label[for='password'] span").fadeIn('medium');

            return false;

        }

    });





});