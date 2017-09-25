<?php

use Wqy\Pipeline\PipelineBuilder;

require __DIR__ . '/../vendor/autoload.php';



$pipes = [
    function ($req, $next) {
        var_dump(__LINE__);
//                 var_dump($next);
        $res = $next($req);
        var_dump(__LINE__);
        return $res;
    },
    function ($req, $next) {
        var_dump(__LINE__);
        $res = $next($req);
        var_dump(__LINE__);
        return $res;
    },
    function ($req, $next) {
        var_dump(__LINE__);
        $req[] = __LINE__;
        $res = $next($req);
        var_dump(__LINE__);
        return $res;
    },
];

$res = (new PipelineBuilder())->send(['helo'])->through($pipes)->then(function ($req) {
    $req[] = 'dest';
    return $req;
});

var_dump($res);
