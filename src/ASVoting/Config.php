<?php

declare(strict_types = 1);

namespace ASVoting;

use asvoting\Config\TwigConfig;

class Config
{
    const ENVIRONMENT_LOCAL = 'local';
    const ENVIRONMENT_PROD = 'prod';

    const ASVOTING_DATABASE_INFO = ['asvoting', 'database'];

    const ASVOTING_REDIS_INFO = ['asvoting', 'redis'];

//    const EXAMPLE_TWILIO_INFO = ['asvoting', 'twilio'];
//
//    const EXAMPLE_STRIPE_INFO = ['asvoting', 'stripe'];



    const STRIPE_PLATFORM_SECRET_KEY = ['asvoting', 'stripe_key', 'secret'];

    const STRIPE_WEBHOOK_SECRET = ['asvoting', 'stripe_key', 'webhook_secret'];


    /** The base domain for the api e.g. 'http://local.api.opensourcefees.com' */
    const STRIPE_PLATFORM_ADMIN_DOMAIN = ['asvoting', 'admin_domain'];

    /** The base domain for the api e.g. 'http://local.api.opensourcefees.com' */
    const STRIPE_PLATFORM_API_DOMAIN = ['asvoting', 'api_domain'];

    /** The base domain for the site e.g. 'http://local.app.opensourcefees.com' */
    const STRIPE_PLATFORM_APP_DOMAIN = ['asvoting', 'app_domain'];

    /** The base domain for the api e.g. 'http://local.internal.opensourcefees.com' */
    const STRIPE_PLATFORM_INTERNAL_DOMAIN = ['asvoting', 'internal_domain'];


    const ASVOTING_CORS_ALLOW_ORIGIN = ['asvoting', 'cors', 'allow_origin'];

    const ASVOTING_ENVIRONMENT = ['asvoting', 'env'];

    const ASVOTING_ALLOWED_ACCESS_CIDRS = ['asvoting', 'allowed_access_cidrs'];

    // This is used for naming the server for external services. e.g.
    // Google authenticator. It should have a unique name per environment
    const ASVOTING_SERVER_NAME = ['asvoting', 'server_name'];

    const TWIG_INFO_CACHE = ['twig', 'cache'];
    const TWIG_INFO_DEBUG = ['twig', 'debug'];


    const ASVOTING_STRIPE_TEST_ACCOUNT = ['asvoting', 'stripe_test_account'];

    const ASVOTING_COOKIE_DOMAIN_ADMIN = ['asvoting', 'admin_cookie_domain'];

//    const asvoting_COOKIE_DOMAIN_APP = ['asvoting', 'app_cookie_domain'];

    const ASVOTING_COOKIE_DOMAIN_ADMINSUPER = ['asvoting', 'super_cookie_domain'];

    const SUPERADMIN_USERNAMES_AND_HASHES = ['asvoting', 'super_usernames_and_hashes'];


    public static function get($index)
    {
        return getConfig($index);
    }

    public static function testValuesArePresent()
    {
        $rc = new \ReflectionClass(self::class);
        $constants = $rc->getConstants();

        foreach ($constants as $constant) {
            $value = getConfig($constant);
        }
    }

    public function getCorsAllowOriginForApi()
    {
        return $this->get(self::ASVOTING_CORS_ALLOW_ORIGIN);
    }

    public static function getEnvironment()
    {
        return getConfig(self::ASVOTING_ENVIRONMENT);
    }

    public function getAllowedAccessCidrs()
    {
        return $this->get(self::ASVOTING_ALLOWED_ACCESS_CIDRS);
    }

//    public function getTwigConfig() : TwigConfig
//    {
//        return new TwigConfig(
//            getConfig(self::TWIG_INFO_CACHE),
//            getConfig(self::TWIG_INFO_DEBUG)
//        );
//    }

    public static function isProductionEnv()
    {
        if (self::getEnvironment() === Config::ENVIRONMENT_LOCAL) {
            return false;
        }

        return true;
    }

    public static function useSsl()
    {
        if (self::getEnvironment() !== 'local') {
            return true;
        }
        return false;
    }

//    public static function getSuperAdminUsernamesAndHashes()
//    {
//
//        return getConfig(self::SUPERADMIN_USERNAMES_AND_HASHES);
//    }
//
//    public function getAdminDomain(): string
//    {
//        return getConfig(self::STRIPE_PLATFORM_ADMIN_DOMAIN);
//    }

//    public function getAppDomain(): string
//    {
//        return getConfig(self::STRIPE_PLATFORM_APP_DOMAIN);
//    }
//
//    public function getApiDomain(): string
//    {
//        return getConfig(self::STRIPE_PLATFORM_API_DOMAIN);
//    }

//    public function getStripeWebhookSecret(): string
//    {
//        return getConfig(self::STRIPE_PLATFORM_API_DOMAIN);
//    }
//
//    public function getInternalDomain(): string
//    {
//        return getConfig(self::STRIPE_PLATFORM_INTERNAL_DOMAIN);
//    }

//    public static function getCookieDomainAdmin()
//    {
//        return getConfig(self::asvoting_COOKIE_DOMAIN_ADMIN);
//    }
//
//    public static function getCookieDomainAdminSuper()
//    {
//        return getConfig(self::asvoting_COOKIE_DOMAIN_ADMINSUPER);
//    }
}
