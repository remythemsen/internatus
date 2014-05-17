<div class="row">
    <h4>Update <?php echo ucfirst($this->setting); ?></h4>
</div>
<div class="row">
    <div class="twelve columns">
        <form id="update_form" class="four columns" action="<?php echo BASE_URL.'account/update_do/'.$this->setting; ?>" method="post">
            <ul>
            <li class="field"><input class="input" type="text" name="update_input" placeholder="<?php echo ucfirst($this->setting); ?>.." /></li>
                <li><input class="medium primary btn" style="color:white; width:120px;" type="submit" value="Save"></li>
            </ul>
        </form>
    </div>
</div>

