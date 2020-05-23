<?php

declare(strict_types = 1);

use Portal\EventEnd;
use Portal\EventStart;

require_once __DIR__ . '/../../vendor/autoload.php';

$generator = new \Portal\Generator();

$events = [];

$events[] = new EventStart(
    'google_2fa_setup',
    'google_2fa_setup',
    [
        new EventEnd('secret_missing', "Secret was not available in session."),
        new EventEnd('code_missing', "Code was not present in submitted form."),
        new EventEnd('code_failed_check', "Trying to validate the submitted code against the secret in the session failed."),
        new EventEnd('code_confirmed', null),
    ]
);

$events[] = new EventStart(
    'google_2fa_login',
    'google_2fa_login',
    [
        new EventEnd('user_not_in_session', "Form submitted without user being in session."),
        new EventEnd('user_has_no_google_2fa', "Form submitted for user that does not have 2FA setup"),
        new EventEnd('secret_was_wrong', ""),
        new EventEnd('secret_success', null),
    ]
);



$events[] = new EventStart(
    'admin_login',
    'admin_login',
    [
        new EventEnd('username_password_not_matched', null),
        new EventEnd('matched_now_do_google_2fa', null),
        new EventEnd('matched_no_2fa', null),
    ]
);





$code = $generator->generateFromEvents($events);

file_put_contents(__DIR__ . '/../portal_functions.php', $code);
