    </div> <!-- end of content -->
    <div id="footer">
        <div class="row">
        <p>This website is the product of a Webdevelopment assignment for Copenhagen School of Design & Technology.</p>
        </div>
    </div>
    
    <?php if(isset($this->js_config)) : ?>
        <!-- getting js config array -->
        <script>var config = <?php echo json_encode($this->js_config); ?>;</script>
    <?php endif; ?>
    <!-- Grab Google CDN's jQuery, fall back to local if offline -->
    <!-- 2.0 for modern browsers, 1.10 for .oldie -->
    <script>
    var oldieCheck = Boolean(document.getElementsByTagName('html')[0].className.match(/\soldie\s/g));
    if(!oldieCheck) {
    document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"><\/script>');
    } else {
    document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"><\/script>');
    }
    </script>
    <script>
    if(!window.jQuery) {
    if(!oldieCheck) {
      document.write('<script src="<?php echo BASE_URL; ?>js/libs/jquery-2.0.2.min.js"><\/script>');
    } else {
      document.write('<script src="<?php echo BASE_URL; ?>js/libs/jquery-1.10.1.min.js"><\/script>');
    }
    }
    </script>
    <script src="<?php echo BASE_URL; ?>js/libs/jquery-ui-1.10.4.custom.min.js"></script>

    <!-- general application wide scripts -->
    <script src="<?php echo BASE_URL; ?>js/general.js"></script>

    <!--
    Include gumby.js followed by UI modules followed by gumby.init.js
    Or concatenate and minify into a single file -->
    <script gumby-touch="js/libs" src="<?php echo BASE_URL; ?>js/libs/gumby.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.retina.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.fixed.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.skiplink.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.toggleswitch.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.checkbox.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.radiobtn.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.tabs.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/gumby.navbar.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/ui/jquery.validation.js"></script>
    <script src="<?php echo BASE_URL; ?>js/libs/gumby.init.js"></script>
    <script src="<?php echo BASE_URL; ?>js/plugins.js"></script>
    <script src="<?php echo BASE_URL; ?>js/main.js"></script>
    
    <?php 
        if(isset($this->js) && count($this->js) > 0) {
            echo '<!-- Grabbing js files specific for the view --><br />'; // for debugging purposes.

            foreach($this->js as $js) {
                echo '<script src="'.$js.'"></script>';
            }
        }
    ?>

    

    <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
    <![endif]-->

</body>
</html>
