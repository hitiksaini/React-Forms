$(document).ready(function() {
    var followers_info = "";
    var screen_name = "";
    var c = 0;
    var d = 0;
    $(".lds-dual-ring").hide();
    
    // get logged in user detail
    function fetchUserInfo() {
        $.ajax({
            url: './controller.php?userdata=true',
            dataType: 'json',
            type: 'GET',
            success: function(results) {
                $('#test2').hide();
                $("#name_user").html(results.name);
                $("#user_pic").attr('src', results.propic);
                $('#followers-names').attr('data-value', results.screen_name);
                var list = '';
                var length = results.tweets.length;
                length = (length >= 10) ? 10 : length;
                screen_name = results.screen_name;
                for (i = 0; i < length; i++) {
                    if (i == 0) {
                        list += '<div class="item active" style="height:100px;text-align: center;color:#fff;font-size: 1.50rem;"><br /><b>' + results.tweets[i].text + '</b></div>';
                    } else {
                        list += '<div class="item" style="height:100px;text-align: center;font-size: 1.50rem;color:#fff;"><br /><b>' + results.tweets[i].text + '</b></div>';
                    }

                }
                $('.carousel-inner').html(list);
                $('#carouselExampleIndicators').carousel();
                fetchFollowersInfo();
            }
        });
    }

    // get follower detail
    function fetchFollowersInfo() {
        $.ajax({
            url: './controller.php?fetchFollowers=' + screen_name,
            dataType: 'json',
            type: 'GET',
            success: function(results) {
                var list = "";
                var length = results.followers.length;
                followers_info = results.followers;
                length = (length >= 10) ? 10 : length;
                for (i = 0; i < length; i++) {
                    var id = results.followers[i].screen_name;
                    var anchor = "<a class='followers-name' style='font-size: 1.50rem;' data-value='" + id + "' >&nbsp;&nbsp;&nbsp;" + results.followers[i].name + "</a>";
                    list += "<div class='col-md-12 follower'>" + "<img src='" + results.followers[i].propic + "' " + "  />" + anchor + "</div>";
                }
                $('#followers').html(list);
            }
        });
    }


    // searchbox in follower display
    $(document).on('input', '#searchbox', function() {
        var data = $(this).val();
        var list = '';
        if (data != "") {
            var length = followers_info.length;
            var pattern = new RegExp("^.*" + data + ".*$", 'i');
            for (i = 0; i < length; i++) {
                var name = followers_info[i].name;
                if (pattern.test(name) == true) {
                    var id = followers_info[i].screen_name;
                    var anchor = "<a class='followers-name' style='font-size: 1.50rem;' data-value='" + id + "' >&nbsp;&nbsp;&nbsp;" + name + "</a>";
                    list += "<div class='col-md-12 follower'> " + "<img src='" + followers_info[i].propic + "' " + " />" + anchor + "</div><br /><br />";
                }
            }
        }
        $('#search').html(list);
    });

    // autosearch from https://jqueryui.com/autocomplete/
    $( function() {
        $( ".search-box" ).autocomplete({
        source: 'controller.php?autosearch=true'
        });
    });

    // show loader on click of searchbox
    $(".search-box").click(function(){

        $(".lds-dual-ring").show();
    });

     // hide loader on click of download button
    $("#downloadFollower").click(function(){
        $(".lds-dual-ring").hide();
    });

    // display logged in user information on page load
    fetchUserInfo();
});
