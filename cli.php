<?php

require "TempMail.php";

use TempMail\TempMail;

$Email = new TempMail();
$create = $Email->CreateAddress("AnasYousef6969");
print_r($Email->GetAddress());

