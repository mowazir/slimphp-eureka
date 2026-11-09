<?php

use Slim\App;

return function(App $app) {
    
    $app->addErrorMiddleware(
        config('middleware.error_details.displayErrorDetails'),
        config('middleware.error_details.logErrors'),
        config('middleware.error_details.logErrorDetails')
    );

};
