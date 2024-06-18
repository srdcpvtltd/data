<?php

return array(
    'upload_path' => [
        'full' => public_path() . '/uploads/' . date('Y') . '/' . date('m') . '/',
        'ref' => '/' . date('Y') . '/' . date('m') . '/',
    ],
    'sizes' => [
        'thumbnail' => [200, 150],
        'medium' => [600, 450]
    ],
);
