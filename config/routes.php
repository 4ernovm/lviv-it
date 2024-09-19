<?php

use Chernoff\LvivItTestAssignment\Controller;

return function(FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/upload/{name}', [Controller\MainController::class, 'upload']);
    $r->addRoute('GET', '/download/{name}', [Controller\MainController::class, 'download']);
};
