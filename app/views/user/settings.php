<?php use TheWall\Core\Helpers; ?>
<div class="row">
    <h4>
        Settings
    </h4>
</div>
<div class="row">
    <div class="six columns">
        <form action="<?php echo BASE_URL.'user/settings'; ?>" method="post">
            <ul>
                <li class="field">
                    <label for="username">Username:</label>
                </li>
                <li class="field">
                    <input type="text" name="username" class="small input" value="<?php echo Helpers\Sanitizor::escapeHTML($this->user->getUserName()); ?>"/>
                </li>
                <li class="field">
                    <label for="email">Email:</label>
                </li>
                <li class="field">
                    <input type="email" name="email" class="small input" value="<?php echo Helpers\Sanitizor::escapeHTML($this->user->getEmail()); ?>"/>
                </li>
                <li class="field">
                    <label for="password">
                           Password:
                    </label>
                </li>
                <li class="field">
                    <input type="password" name="old_password" class="small input" placeholder="old password.."/>
                </li>
                <li class="field">
                    <input type="password" name="new_password" class="small input" placeholder="new password.."/>
                </li>

                <input type="hidden" name="csrftoken" value="<?php echo Helpers\Sanitizor::escapeHTML(Helpers\Session::get('csrftoken')); ?>" />

                <li class="field">
                    <button class="primary btn medium" style="color:white;line-height:20px;border-radius:3px;font-weight:lighter;"><span style="margin:0 10px;">Save Changes</span></button>
                </li>
            </ul>
        </form>
    </div>
</div>

