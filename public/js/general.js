$( document ).ready(function() {

    /*
    | DIALOGS
     */

    // Settings
    $("#content").append('<div id="dialog"></div>');
    $( "#dialog" ).dialog({ autoOpen: false });


    // CREATE USER
    $('#create-btn').on("click", function() {
        $("#dialog").dialog( "option", "title", "Create Account" );

        var createUserForm = [
            '<form action="'+config['BASE_URL']+'user/create" method="post">',
            '<ul>',
            '<li class="field"><input class="medium input" placeholder="Username" type="text" name="username" /></li>',
            '<li class="field"><input class="medium input" placeholder="Email" type="text" name="email" /></li>',
            '<li class="field"><input class="medium input"  placeholder="Password"  type="password" name="password" /></li>',
            '<li class="field"><input class="btn secondary" style="color:white; height:30px; width:90px;"  type="submit" value="Create"></li>',
            '</ul>',
            '</form>'
        ].join("\n");

        $("#dialog").html(createUserForm);
        $("#dialog" ).dialog( "open" );
        return false;
    });

    // CREATE MESSAGE
    $('#sendMessageButton').on("click", function() {
       $("#dialog").dialog("option", "title", "New Message");
       var createMessageForm = [
           '<form action="'+config['BASE_URL']+'messages/create" method="post">',
           '<ul>',
           '<li class="field"><input class="medium input" placeholder="To:(username)" type="text" name="username" /></li>',
           '<li class="field"><textarea class="medium input"  placeholder="Message:" type="text" name="text"></textarea></li>',
           '<li class="field"><input class="btn secondary" style="color:white; height:30px; width:90px;"  type="submit" value="Create"></li>',
           '</ul>',
           '</form>'
       ].join("\n");
       $("#dialog").html(createMessageForm);
       $("#dialog").dialog("open");
       return false;
    });

    /*
    | Nortifications
     */

    // close button
    $('.remove-notification').on("click", function() {

        $(this).parent().remove();

    });

});

