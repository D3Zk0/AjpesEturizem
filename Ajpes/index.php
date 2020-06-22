<?php

use AJPES;

    $l = new AJPES();
    $l->setTestMode();
    $response = $l->report();

    echo $response;


?>