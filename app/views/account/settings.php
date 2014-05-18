
<div class="row">
    <span class="light label headline rooms-table"><h5>Settings Page</h5></span>
</div>
<br />
<section class="tabs vertical">

    <ul class="tab-nav two columns">
        <li id="account-tab-button"><a href="#">Account</a></li>
        <?php if($this->account->is_admin()) : ?>
            <li id="site-tab-button"><a href="account/settings/site">Site</a></li>
            <li id="users-tab-button"><a href="#">Users</a></li>
        <?php endif; ?>
    </ul>

    <div id="account-tab" class="tab-content ten columns settings-section">
        <h5>Account Information</h5>    
        <br />
        <ul>
            <li>
                <h6>Account Name:</h6>
                <p><?php echo $this->account->get_username(); ?> - <a class="settings-change badge light" href="<?php echo BASE_URL; ?>account/update/username">change</a></p>
            </li>
            <li>
                <h6>Email:</h6>
                <p><?php echo $this->account->get_email(); ?> - <a class="settings-change badge light" href="<?php echo BASE_URL; ?>account/update/email">change</a></p>
            </li>
            <li>
                <h6>Password:</h6>
                <p><a class="settings-change badge light" href="<?php echo BASE_URL; ?>account/update/password">Update Password</a></p>
            </li>
        </ul>
    </div>
    
    <?php if($this->account->is_admin()) : ?>
    <div id="site-tab" class="tab-content ten columns settings-section">
       
       <form name="site_setup_form" class="four columns" id="page_title_form" method="post" enctype="multipart/form-data" action="<?php echo BASE_URL.'account/change_site_name'; ?>">          <p>Site Settings</p>
            <ul>
                <li class="field">
                    <input type="text" class="input" placeholder="Page Title" name="page_title"/>
                </li>     
                <li class="field">
                    <input type="submit" class="btn primary" style="width:120px; color:white;" value="Save"/>
                </li>
            </ul>
        </form>
    </div>

    <div id="users-tab" class="tab-content ten columns settings-section">
              <table class="striped rounded">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Can Book?</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="users">
            </tbody>
       </table>

    </div>



    <?php endif; ?>

    
</section>

