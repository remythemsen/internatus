$( document ).ready(function() {    


   $(function(){

               
       // validation of form

       $('#page_title_form').validation({
          // pass an array of required field objects
          required: [
            {
              // name should reference a form inputs name attribute
              // just passing the name property will default to a check for a present value
              name: 'page_title',
            }
       ],
          // callback for failed validaiton on form submit
          fail: function() {
            Gumby.error('Form validation failed');
          }
          // callback for successful validation on form submit
           
       });
              
       // TODO: Make SWITCH with $url check, and give the corresponding tab 'active'.
       //
       //
       // get update type. 
       var result = $(".alert").text().split(' ');

       // choose the rooms tab, if a notification is displayed (you just created a room)
       if ( result[1] == 'Room') {
           $('#rooms-tab').addClass('active');
           $('#rooms-tab-button').addClass('active');
       } 
       else {
           $('#account-tab').addClass('active');
           $('#account-tab-button').addClass('active');
       }
    
       // ######USERS###### //
        

       $(this).get_users();

   });



   $.fn.get_users = function() {
             // url: ../ (one up (to get rid of settings)
           $.get(config['BASE_URL']+"account/xhr_get_users", function(obj) {
                
                $('#users').html('');
               
                for(var i = 0;i < obj.length; i++) {
                    
                    if(obj[i].is_admin == 1) {
                        obj[i].is_admin = 'Admin';
                        admin_action = 'Demote';
                    } else {
                        obj[i].is_admin = 'Default';
                        admin_action = 'Promote';
                    }

                    if(obj[i].is_active == 0) {
                        obj[i].is_active = 'Inactive';
                        active_action = 'Activate';
                    } else {
                        obj[i].is_active = 'Active';
                        active_action = 'Deactivate';
                    }

                     if(obj[i].can_book == 0) {
                        obj[i].can_book = 'No';
                        can_book_action = 'Enable';
                    } else {
                        obj[i].can_book = 'Yes';
                        can_book_action = 'Disable';
                    }


                    // stored in array for readability
                    var userRow = [
                    '<tr id="user_'+obj[i].id+'" class="user">',
                        '<td>'+obj[i].username+'</td>',
                        '<td>'+obj[i].can_book+' - <a class="edit_user settings-change" rel="can_book-'+obj[i].id+'" href="#">'+can_book_action+'</a></td>',
                        '<td>'+obj[i].is_admin+' - <a class="edit_user settings-change" rel="is_admin-'+obj[i].id+'" href="#">'+admin_action+'</a></td>',
                        '<td>'+obj[i].is_active+' - <a class="edit_user settings-change" rel="is_active-'+obj[i].id+'" href="#">'+active_action+'</a></td>',
                    '</tr>'
                    ].join("");
                    
                    // appended to the users table.
                    $('#users').append(userRow);
                    
                    
                }
                // onclick event listener
                    $('.edit_user').click(function() {
                    
                        var details = $(this).attr('rel').split("-");
                        var id = details[1];
                        var setting = details[0];
                        var action = $(this).text();

                        $.post(config['BASE_URL']+'account/xhr_update_user', {'id': id, 'setting': setting, 'action': action}, function(o) {
                            $(this).get_users();
                        });
                    
                        return false;
                    });

           }, 'json');
       }

});


