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

?>
<div>
Если Вы знаете, к какой части тела или системе организма относится ваще заболевание,
ищите тесты по этому списку. Под каждым заголовком перечислены тесты, относящиеся к конкретной системе организма
или органу. Выберите тест, название которого больше всего вам подходит.
</div>
<?php

include 'view/category.phtml';
include 'view/footer.phtml';
?>
