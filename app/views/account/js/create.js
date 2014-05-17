$( document ).ready(function() {    


$('#create_user_form').validation({
          // pass an array of required field objects
          required: [
            {
              // name should reference a form inputs name attribute
              // just passing the name property will default to a check for a present value
                name: 'username',
            },
            {
                name: 'password',
            },
            {
                name:'email',
            }
          ],
          // callback for failed validaiton on form submit
          fail: function() {
            Gumby.error('Form validation failed');
          }
          // callback for successful validation on form submit
           
       });
});
