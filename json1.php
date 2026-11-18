
<?php
$json = '{"name": "aline","age": 17,"subjects": ["php", "html", "css"],"address": {"city": "Kigali"}}';
$data = json_decode($json, true);
echo $data["name"] . "<br>";
echo $data["subjects"][1] . "<br>";
echo $data["address"]["city"] . "<br>";
$obj = json_decode($json);
echo $obj->name . "<br>";
echo $obj->address->city . "<br>";
?>
