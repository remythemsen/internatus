<?php use TheWall\Core\Helpers; ?>
<div class="row">
    <h4>List of Users</h4>
</div>
<div class="row">
    <div class="twelve columns">
        <table class="rounded">
            <thead>
            <tr>
                <th>id</th>
                <th>username</th>
                <th>email</th>
                <th>action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($this->users as $user) : ?>
                <tr>
                    <td><?php echo Helpers\Sanitizor::escapeHTML($user->getId()); ?></td>
                    <td><?php echo Helpers\Sanitizor::escapeHTML($user->getUsername()); ?></td>
                    <td><?php echo Helpers\Sanitizor::escapeHTML($user->getEmail()); ?></td>
                    <td>
                        <form action="<?php echo BASE_URL.'user/delete'; ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $user->getId(); ?>" />
                            <input type="hidden" name="csrftoken" value="<?php echo Helpers\Session::get('csrftoken'); ?>"/>
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

