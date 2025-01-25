<?php
if (isset($_GET['target'])) {
    $target = $_GET['target'];
    echo strtoupper($target);
} else {
    echo "لطفاً پارامتر target را وارد کنید.";
}
?>
