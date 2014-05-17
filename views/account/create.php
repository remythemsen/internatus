<h4>Create new account</h4>
<div class="row">
    <div class="six columns">
        <form id="create_user_form" action="<?php echo BASE_URL; ?>account/create_do" method="post">
            <ul>
                <li class="field"><label>Username</label></li>
                <li class="field"><input class="narrow input" type="text" name="username" /></li>
                <li class="field"><label>Password</label></li>
                <li class="field"><input class="narrow input" type="password" name="password" /></li>
                <li class="field"><label>Email</label></li>
                <li class="field"><input class="narrow input" type="email" name="email" /></li>

                <li><input class="medium primary btn" style="color:white; width:120px;" type="submit"></li>
            </ul>
        </form>
    </div>
    </div>
