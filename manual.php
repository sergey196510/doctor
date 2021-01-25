<?php

include 'view/top.phtml';

if (!isset($_SESSION['login']))
    exit;

echo "<div style='text-align: left;'>";
echo "<pre>";
include 'doc/manual.txt';
echo "</pre>";
echo "</div>";

include 'view/footer.phtml';

?>
