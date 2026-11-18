<?php
$json='{"name":"aline","age":17,"subjects":["php","html","css"]},{"kigali":"kigali"}}';
$data=json_decode($json,true);

echo $data["name"],"\n";
echo $data["subjects"][1],"\n";
echo $data["address"]["city"],"\n";

$obj=json_decode($json);
echo $obj->name,"\n";
echo $obj->address->city,"\n";
?>