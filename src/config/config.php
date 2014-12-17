<?php
return array(
    /**
     * The installation path for newly generated modules.
     */
    'path'     => app_path('modules'),
    /**
     * List of files to be included by each module.
     */
    'includes' => array(
        'helpers.php',
        'routes.php',
        'filters.php'
    )
);
