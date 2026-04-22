<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $recipient->pseudo ?>'s Messages</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script>
            const messages = <?= $messages_json ?>;
            let tblMessages;
            let trie_column;
            let ordre = 1;
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    tblMessages = document.getElementById('message_list');
                    console.log(messages);
                    displayTable();
                }
            };

            function toogle_class_ordre(field){
                // 1. On vérifie la classe actuelle directement sur 'field'
                if (field.classList.contains(ordre)) {
                    ordre = ordre === 1 ? 0 : 1;
                    console.log(ordre);
                }
            }
            function sort(field){
                console.log(field);
                if(field.id === 'col_datetime'){
                    //On récupére le nom de la colonne qu'on va trier.
                    trie_column = 'datetime';
                    // On détermine si l'ordre est asc ou pas.
                    ordre = ordre ===1 ? 1:0;
                    console.log(ordre+ " test sur l'ordre asc ou pas.");
                }else if(field.id === 'col_author'){
                    trie_column = 'author';
                    ordre = ordre ===1 ? 1:0;
                    console.log(ordre+ " test sur l'ordre asc ou pas.");
                }else if(field.id === 'col_body'){
                    trie_column = 'body';
                    ordre = ordre ===1 ? 1:0;
                    console.log(ordre+ " test sur l'ordre asc ou pas.");
                }
                // contient le nom de la propriété sur base de laquelle on veut trier
                messages.sort(function (a,b){
                    // odre = 1 => asc,0 =>desc
                    if (a[trie_column] < b[trie_column])
                        return ordre=== 1 ? -1: 1;
                    if (a[trie_column] > b[trie_column])
                        return ordre=== 1 ? 1: -1;
                    return 0;
                });
                // On change la classe "asc" en "desc".
                toogle_class_ordre(field);

                displayTable();
            }

            function displayTable(){
                let html = "<tr><th id='col_datetime' onclick='sort(this);'  class="+ordre+">Date/Time"+ ( ordre === 1 ? " &#9650" : " &#9660")+"</th>" +
                    "<th id='col_author' onclick='sort(this);' class="+ordre+">Author"+ ( ordre === 1 ? " &#9650" : " &#9660")+"</a></th>" +
                    "<th id='col_body' onclick='sort(this);' class="+ordre+">Message"+ ( ordre === 1 ? " &#9650" : " &#9660")+"</th>" +
                    "<th>Private?</th>" +
                    "<th>Action</th></tr>";
                for (let m of messages) {
                    html += "<tr>";
                    html += "<td>" + m.datetime + "</td>";
                    html += "<td><a href='member/profile/"+m.author+"'>"+m.author+"</a></td>";
                    html += "<td>" + m.body + "</td>";
                    html += "<td><input type='checkbox' disabled" + (m.private ? ' checked' : '') + "></td>";
                    html += "<td>" + (m.erasable ? "<form class='link' action='message/delete' method='post'><input type='text' name='id_message' value='"+m.id+"' hidden><input type='submit' value='erase'></form>" : "") + "</td>";
                    html += "</tr>";
                }
                tblMessages.innerHTML = html;
            }
        </script>
    </head>
    <body>
        <div class="title"><?= $recipient->pseudo ?>'s Messages</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <h4 id="message_form_title">Type here to leave a message:</h4>
            <form id="message_form" action="message/index/<?= $recipient->pseudo ?>" method="post">
                <textarea id="body" name="body" rows='3'></textarea><br>
                <input id="private" name="private" type="checkbox">Private message<br>
                <input id="post" type="submit" value="Post">
            </form>
            
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            
            <p>These are <?= $recipient->pseudo ?>'s messages:</p>

            <input id="refresh" type="button" value="Refresh" hidden>
            <table id="message_list" class="message_list">
                <tr>
                    <th>Date/Time</th>
                    <th>Author</th>
                    <th>Message</th>
                    <th>Private?</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($messages as $message): ?>
                    <?php if (($message->private && ($message->author == $user || $message->recipient == $user)) || !$message->private): ?>
                        <tr>
                            <td><?= $message->date_time ?></td>
                            <td><a href='member/profile/<?= $message->author->pseudo ?>'><?= $message->author->pseudo ?></a></td>
                            <td><?= $message->body ?></td>
                            <td><input type='checkbox' disabled <?= ($message->private ? ' checked' : '') ?>></td>
                            <td>
                                <?php if ($user == $message->author || $user == $message->recipient): ?>
                                    <form class='link' action='message/delete' method='post' >
                                        <input type='text' name='id_message' value='<?= $message->post_id ?>' hidden>
                                        <input type='submit' value='erase'>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </body>
</html>