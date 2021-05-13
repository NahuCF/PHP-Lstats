<?php

require "admin/config.php";
require "functions.php";

// Logic

$conection = connect_to_database($db_config);
if(!$conection)
    header("Location: error.php");



require "view/sum.view.php";

?>