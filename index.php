<?php
$config = 'kernel/config.php';
$size = filesize($config);

if ($size == 0) 
{
    header("Location: kernel/install/modules/step-1.php");
}
else
{
	header("Location: modules/main/index.php");
}
?>