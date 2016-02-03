<?php
$post = null;
$input = fopen('php://input', 'r');
while ($line = fgets($input)) {
    $post .= $line;
}
$request = serialize([
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'headers' => getallheaders(),
    'raw_post' => $post,
    'post' => $_POST,
]);
header('Request-Data: ' . $request);
echo $request;
