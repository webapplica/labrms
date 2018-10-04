<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => base_path('\\') . env('SNAPPY_PDF', '/usr/local/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => base_path('\\') . env('SNAPPY_IMAGE', '/usr/local/bin/wkhtmltoimage'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
