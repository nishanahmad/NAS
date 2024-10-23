<?php

$expiry_date = date('Y-m-d',strtotime("+6 day")).'T06:00:00.000Z';

var_dump($expiry_date);

$expiry_date = str_replace("T06:00:00.000Z", "",$expiry_date);
$expiry_date_locale = date("d-M-Y",strtotime($expiry_date));

var_dump($expiry_date_locale);