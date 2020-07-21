<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slim MailForm</title>
</head>
<body>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <?php if (!isset($_POST['to'])) : ?>
            <p>送信先アドレスを指定してください</p>
        <?php else : ?>
            <?php
                $headers = "From: from@example.com";
                if (mb_send_mail($_POST['to'], @$_POST['subject'], @$_POST['body'], $headers)) {
                    echo '<p>メール送信しました</p>';
                } else {
                    echo '<p>メール送信に失敗しました</p>';
                }
            ?>
        <?php endif ?>
    <?php endif ?>
    <form action="./" method="POST">
        <dl>
            <dt><label for="to">send to:</label></dt>
            <dd><input type="text" name="to" id="to" placeholder="user@example.com"></dd>
            <dt><label for="subject">subject:</label></dt>
            <dd><input type="text" name="subject" id="subject" placeholder="title of mail"></dd>
            <dt><label for="body">body:</label></dt>
            <dd><textarea name="body" id="body" placeholder="content of mail"></textarea></dd>
        </dl>
        <input type="submit" value="送信">
    </form>
</body>
</html>