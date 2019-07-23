<?php
echo '<dt>sendmail_path</dt><dd>' . ini_get('sendmail_path') . '</dd>';

$from = 'from@master.example.dev';
$to = 'test@example.dev';
$title = 'メールテスト';
$message = <<<EOL
メール本文をテスト送信しました
届いていますか？
EOL;

echo '<dt>mb_send_mail</dt><dd>' . strval(mb_send_mail($to, $title, $message, 'From: '. $from)) . '</dd>';
