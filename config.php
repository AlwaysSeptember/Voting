<?php

$options = [];

// Determine if we can use the 'host.docker.internal' name.
$dockerHost  = '10.254.254.254';

// $dockerHost  = 'host.docker.internal';

// DB connection settings
$options['asvoting']['database'] = [
    'schema' => 'asvoting',
    'host' => $dockerHost,
    'username' => 'asvoting',
    'password' => 'D9cACV8Pue3CvM93',
];

// Redis connection settings
$options['asvoting']['redis'] = [
    'host' => $dockerHost,
    'password' => 'ePvDZpYTXzT5N9xAPu24',
    'port' => 6379
];

// Cors settings
$options['asvoting']['cors'] = [
    'allow_origin' => 'http://local.app.opensourcefees.com'
];

// Domains. Used for generating links back to the platform, e.g. for stripe
// auth flow.
//$options['asvoting']['admin_domain'] = 'http://local.voting.phpimagick.com';
$options['asvoting']['app_domain'] = 'http://local.app.voting.phpimagick.com';
$options['asvoting']['api_domain'] = 'http://local.api.voting.phpimagick.com';
//$options['asvoting']['internal_domain'] = 'http://local.internal.phpimagick.com';

// What environment we are using
// production - in production
// production in staging
// 'develop' in develop
// local in local
$options['asvoting']['env'] = 'local';

// Currently the site is locked down as to who can access it.
// Eventually this will be changed to only apply to the super user
// environment.
$options['asvoting']['allowed_access_cidrs'] = [
    '86.7.192.0/24',
    '10.0.0.0/8',
    '127.0.0.1/24',
    "172.0.0.0/8",   // docker local networking
    '192.168.0.0/16'
];

//// Twig settings - TODO move these to server_config.php as they are not secrets.
//$options['twig'] = [
//    'cache' => false,
//    'debug' => true
//];
