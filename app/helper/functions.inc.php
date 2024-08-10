<?php

function dd($data)
{
    die(var_dump($data));
}
function redirect($url) {
    header("Location: $url");
}
function getCurrentTimeInMillis(): int{
    return round(microtime(true)*1000);
}
