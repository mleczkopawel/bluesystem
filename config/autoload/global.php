<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'translator' => [
        'locale' => 'pl',
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => getcwd() .  '/data/languages',
                'pattern'  => '%s.php',
            ],
        ],
    ],
    'smtp' => [
        'name' => 'localhost',
        'host' => 'mleczkop.nazwa.pl',
        'port' => 465,
        'connection_class' => 'login',
        'connection_config' => [
            'username' => 'admin@mleczkop.nazwa.pl',
            'password' => 'Titanum!9',
        ],
    ],
    'OAuth' => [
        'auth_code_lifetime' => 30,
        'refresh_token_lifetime' => 30,
        'always_issue_new_refresh_token' => true,
    ],
    'superuser' => [
        'login' => 'superuser',
        'email' => 'superuser@bluesystem.local',
        'password' => 'zaq1@WSX'
    ],
    'logger' => [
        'path' => __DIR__ . '/../../data/logs/',
    ],
];
