<?php

header("HTTP/1.1 500 Internal Server Error");
header('Content-Type: application/json;');
echo '{"Response":"Internal Server Error", "StatusCode":500}';

?>