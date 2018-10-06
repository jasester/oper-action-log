<?php

return [

	'database' => [

		'connection' => '',

		'operaction_log_table' => 'operaction_logs',

        /**
         * administrator model
         */
        'administrator' => \App\User::class,

	],

	'operaction' => [

		'enable' => true,

		/*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs.
         */
        'except' => [
            // 'admin/auth/logs*',
        ],
	]
];