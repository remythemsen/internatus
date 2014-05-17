$( document ).ready(function() {    


$('#update_form').validation({
          // pass an array of required field objects
          required: [
            {
              // name should reference a form inputs name attribute
              // just passing the name property will default to a check for a present value
              name: 'update_input',
            }
          ],
          // callback for failed validaiton on form submit
          fail: function() {
            Gumby.error('Form validation failed');
          }
          // callback for successful validation on form submit
           
       });
});
