<?php 

 class ViewModel extends Model {
    
    // the global registry object.
     protected $registry;

    // View Model Logic here
     function __construct($registry) {
         $this->registry = $registry;

         // Constructing Base Model class.
         parent::__construct();
     }
}
?>
