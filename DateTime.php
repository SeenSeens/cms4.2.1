<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$CurrenTime = time();
$DateTime = strftime("%d-%m-%Y %H:%M:%S", $CurrenTime);
echo $DateTime;
?>