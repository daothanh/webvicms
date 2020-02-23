<?php
return [
    'groups' => [
        'admin' => [
            'admin.*',
            'api.admin.*',
        ],
        'web' => [
            'api.profile',
            'api.profile.*',
            'api.order',
            'api.order.*',
            'api.web.*',
            'w.*',
            'page',
            'home',
            'login',
            'logout',
            'register'
        ]
    ],
];
