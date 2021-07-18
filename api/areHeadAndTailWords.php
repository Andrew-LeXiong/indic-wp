<?php
require("../word_processor.php");

if (isset($_GET['string']) && isset($_GET['language']) && isset($_GET['string2'])) {
    $string = $_GET['string'];
    $language = $_GET['language'];
    $string2 = $_GET['string2'];
}
else if(isset($_GET['input1']) && isset($_GET['input2']) && isset($_GET['input3'])) {
    $string = $_GET['input1'];
    $language = $_GET['input2'];
    $string2 = $_GET['input3'];
}

if (!empty($string) && !empty($language)) {
    $processor = new wordProcessor($string, $language);
    $result = $processor->areHeadAndTailWords($string2);
    response(200, "areHeadAndTailWords() executed", $string, $string2, $language, $result);
}
else if ((isset($string) && empty($string)) || (isset($string2) && empty($string2))) {
    invalidResponse("Invalid or Empty Word");
}
else if (isset($language) && empty($language)) {
    invalidResponse("Invalid or Empty Language");
}
else {
    invalidResponse("Invalid Request");
}

function invalidResponse($message) {
    response(400, $message, NULL, NULL, NULL, NULL);
}

function response($responseCode, $message, $string, $string2, $language, $data) {
    // Locally cache results for two hours
    header('Cache-Control: max-age=7200');

    // JSON Header
    header('Content-type:application/json;charset=utf-8');

    http_response_code($responseCode);
    $response = array("response_code" => $responseCode, "message" => $message, "string" => $string, "string2" => $string2,"language" => $language, "data" => $data);
    $json = json_encode($response);
    echo $json;
}

?>
