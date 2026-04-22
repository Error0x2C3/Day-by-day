<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Members</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Other Members</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <ul>
                <?php foreach ($members as $member): ?>
                    <li><a href='member/profile/<?= $member['pseudo'] ?>'><?= $member['pseudo'] ?></a> 

                        <?php if ($member['follower'] == 1 && $member['followee'] == 1): ?>
                            &harr; is a mutual friend
                            <form class='link' action='member/unfollow' method='post'>
                                <input type='text' name='unfollow' value='<?= $member['pseudo'] ?>' hidden>
                                <input type='submit' value='[drop]'>
                            </form>
                        <?php elseif ($member['follower'] == 1): ?>
                            &larr; you are following 
                            <form class='link' action='member/unfollow' method='post'>
                                <input type='text' name='unfollow' value='<?= $member['pseudo'] ?>' hidden>
                                <input type='submit' value='[drop]'>
                            </form>
                        <?php elseif ($member['followee'] == 1): ?>
                            &rarr; is following you 
                            <form class='link' action='member/follow' method='post'>
                                <input type='text' name='follow' value='<?= $member['pseudo'] ?>' hidden>
                                <input type='submit' value='[recip]'>
                            </form>
                        <?php else: ?>
                            <form class='link' action='member/follow' method='post'>
                                <input type='text' name='follow' value='<?= $member['pseudo'] ?>' hidden>
                                <input type='submit' value='[follow]'>
                            </form>
                        <?php endif; ?>
                    <?php endforeach; ?>
            </ul>
        </div>
    </body>
</html>