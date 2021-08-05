<?php
$jsonobj = '{"Peter":35,"Ben":37,"Joe":43, "Wives" : ["1" : [1, 2, 3], 2, 3]}';
 
var_dump(json_decode($jsonobj, true));
?>