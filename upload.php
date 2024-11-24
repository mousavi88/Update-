<?php
// Pard Lino
header('Content-Type: application/json; charset=utf-8');
if (!is_dir('uploads')) {
    mkdir('uploads', 0755, true);
}
$htaccessContent = <<<HTACCESS
<FilesMatch "\.(jpg|jpeg|png|gif|bmp|tiff|tif|webp|svg|ico|heic|heif|raw|psd|eps|pdf|mp4|avi|mov|wmv|mkv|flv|webm|mpeg|mpg|3gp|m2ts|ts|vob|divx|aac|flac|alac|aiff|pcm|dsd|m4a|amr|ape|mid|midi|mts|wma|mp3|wav|ogg)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>

<FilesMatch "\.(apk|rar|zip|swb|sh|7z|xz|exe|bat)$">
    ForceType application/octet-stream
    Header set Content-Disposition attachment
</FilesMatch>

<FilesMatch ".*">
    ForceType text/plain
</FilesMatch>
HTACCESS;

file_put_contents('uploads/.htaccess', $htaccessContent);
file_put_contents('uploads/index.html', '');
file_put_contents('index.html', '');
function GENNAME($LEN = 16) {
    $CHAR = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $CHARLEN = strlen($CHAR);
    $NAMERANDOM = '';
    for ($i = 0; $i < $LEN; $i++) {
        $NAMERANDOM .= $CHAR[rand(0, $CHARLEN - 1)];
    }
    return $NAMERANDOM;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $Target_DIR = 'uploads/';
    $FEX = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $NAME = GENNAME() . '.' . $FEX;
    $Target_File = $Target_DIR . $NAME;
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$linkfile = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $Target_File;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $Target_File)) {
        $TEMP = ['Status' => true, 'MSG' => 'فایل شما با موفقیت اپلود شد', 'Link' => $linkfile];
    } else {
        $TEMP = ['Status' => false, 'MSG' => 'فایل شما مشکلی دارد که نمیشود آن را اپلود کرد'];
    }
} else {
	$TEMP = ['Status' => false, 'MSG' => 'مشکلی بوجود آمد'];
}
exit(json_encode($TEMP, JSON_UNESCAPED_UNICODE));
?>
