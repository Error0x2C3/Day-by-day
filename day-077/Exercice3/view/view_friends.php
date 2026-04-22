<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Your Friends</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Your Friends</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <?php if (count($mutuals) != 0): ?>
                <h2>Your mutual friends</h2>
                <ul>
                    <?php foreach ($mutuals as $member): ?> 
                        <li><a href='member/profile/<?= $member ?>'><?= $member ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (count($followers) != 0): ?>
                <h2>Your followers</h2>
                <ul>
                    <?php foreach ($followers as $member): ?> 
                        <li><a href='member/profile/<?= $member ?>'><?= $member ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (count($followees) != 0): ?>
                <h2>Members you are following</h2>
                <ul>
                    <?php foreach ($followees as $member): ?> 
                        <li><a href='member/profile/<?= $member ?>'><?= $member ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>    

        </div>
    </body>
</html>
