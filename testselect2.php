<?php

#
# HTML view and modify calls database
#
# Copyright Sergey I. Lang
# 2018
#

include 'view/top.phtml';

if (!isset($_SESSION['login']))
    header('Location: index.php');

include 'view/category.phtml';
include 'view/footer.phtml';
?>
