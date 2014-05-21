<div class="row" style=" padding:20px 0;margin-top:20px;">
    <h3 style="padding-left:20px;">Welcome to <?php echo Config::get()->general->pagetitle; ?>!<h3>
</div>
<div class="row">
    <div class="six columns" style="padding-right:20px;padding-left:20px;">
    <h5>Login to start using <?php echo Config::get()->general->pagetitle; ?></h5></span>
    <br />
    <h3>Login</h3>
        <form action="<?php echo BASE_URL; ?>account/login" method="post">
            <ul>
                <li class="field"><input class="medium input" placeholder="Username" type="text" name="username" /></li>
                <li class="field"><input class="medium input"  placeholder="Password"  type="password" name="password" /></li>
                <li class="field"><input class="btn primary pretty" style="color:white; height:30px; width:90px;"  type="submit" value="Login"></li>
            </ul>
        </form>
    </div>
    <div class="six columns" style="border-left:1px solid #ddd; padding-left:40px;padding-right:20px;">
        <h5>Don't have an account yet ?</h5>
<br />

        <h3>Create Account</h3> 
        <form id="create_user_form" action="<?php echo BASE_URL; ?>account/create" method="post">
            <ul>
                <li class="field"><input class="medium input" placeholder="Username" type="text" name="username" /></li>
                <li class="field"><input class="medium input" placeholder="Password" type="password" name="password" /></li>
                <li class="field"><input class="medium input" placeholder="Email" type="email" name="email" /></li>

                <li><input class="medium secondary pretty btn" style="color:white; font-weight:normal;width:140px;" value="Create Account" type="submit"></li>
            </ul>
        </form>

    </div>

</div>
