<?php

declare (strict_types = 1);

/**
 * This file contains factory functions that create objects from either
 * configuration values, user input or other external data.
 *
 */

use Auryn\Injector;
use ASVoting\Config;
use Psr\Http\Message\ResponseInterface;
use SlimAuryn\ExceptionMiddleware;

/**
 * @return \Monolog\Logger
 * @throws Exception
 */
function createLogger()
{
    $log = new \Monolog\Logger('logger');
    $directory = __DIR__ . "/../var";
    if (!@mkdir($directory) && !is_dir($directory)) {
        throw new \Exception("Log directory doesn't exist.");
    }

    $filename = $directory . '/oauth.log';

    $log->pushHandler(new \Monolog\Handler\StreamHandler($filename, \Monolog\Logger::WARNING));

    return $log;
}

/**
 * @return PDO
 * @throws Exception
 */
function createPDO()
{
    $config = getConfig(Config::ASVOTING_DATABASE_INFO);

    $string = sprintf(
        'mysql:host=%s;dbname=%s',
        $config['host'],
        $config['schema']
    );

    try {
        $pdo = new \PDO($string, $config['username'], $config['password'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_TIMEOUT => 3,
            \PDO::MYSQL_ATTR_FOUND_ROWS => true
        ));
    }
    catch (\Exception $e) {
        throw new \Exception(
            "Error creating PDO:" . $e->getMessage(),
            $e->getCode(),
            $e
        );
    }

    return $pdo;
}

/**
 * @return Redis
 * @throws Exception
 */
function createRedis()
{
    $redisInfo = getConfig(Config::ASVOTING_REDIS_INFO);

    $redis = new Redis();
    $redis->connect($redisInfo['host'], $redisInfo['port']);
    $redis->auth($redisInfo['password']);
    $redis->ping();

    return $redis;
}

function forbidden(\Auryn\Injector $injector)
{
    $injector->make("Please don't use this object directly; create a more specific type to use.");
}


//function createTwigForSite(\Auryn\Injector $injector)
//{
//    // The templates are included in order of priority.
//    $templatePaths = [
//        __DIR__ . '/../app/template'
//    ];
//
//    $loader = new Twig_Loader_Filesystem($templatePaths);
//    $twig = new Twig_Environment($loader, array(
//        'cache' => false,
//        'strict_variables' => true,
//        'debug' => true // TODO - needs config
//    ));
//
//    // Inject function - allows DI in templates.
//    $function = new Twig_SimpleFunction('inject', function (string $type) use ($injector) {
//        return $injector->make($type);
//    });
//    $twig->addFunction($function);
//
//
//    $rawParams = ['is_safe' => array('html')];
//
//    $twigFunctions = [
//        'renderNavbarLinks' => 'renderNavbarLinks'
//    ];
//
//    foreach ($twigFunctions as $functionName => $callable) {
//        $function = new Twig_SimpleFunction($functionName, function () use ($injector, $callable) {
//            return $injector->execute($callable);
//        });
//        $twig->addFunction($function);
//    }
//
//    $function = new Twig_SimpleFunction('linkableTitle', 'linkableTitle', $rawParams);
//    $twig->addFunction($function);
//
//    $rawTwigFunctions = [
//        'memory_debug' => 'memory_debug',
//        'request_nonce' => 'request_nonce',
//        'emitDnsPreFetch' => 'emitDnsPreFetch'
//    ];
//
//    foreach ($rawTwigFunctions as $functionName => $callable) {
//        $function = new Twig_SimpleFunction($functionName, function () use ($injector, $callable) {
//            return $injector->execute($callable);
//        }, $rawParams);
//        $twig->addFunction($function);
//    }
//
//
//
//    $function = new Twig_SimpleFunction(
//        'getStripeCheckoutStartUrl',
//        function (\ASVoting\Model\Project $project) use ($injector) {
//            $routeInfo = $injector->make(\ASVoting\RouteInfo\StandardRouteInfo::class);
//
//            return $routeInfo->getStripeCheckoutStartUrl($project);
//        },
//        $rawParams
//    );
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction(
//        'getInvoiceStartUrl',
//        function (\ASVoting\Model\Project $project) use ($injector) {
//            $routeInfo = $injector->make(\ASVoting\RouteInfo\StandardRouteInfo::class);
//
//            return $routeInfo->getInvoiceStartUrl($project);
//        },
//        $rawParams
//    );
//    $twig->addFunction($function);
//
//    return $twig;
//}
//
//
//function createTwigForAdmin(\Auryn\Injector $injector, \ASVoting\AdminSession\StandardAdminSession $adminSession)
//{
//    // The templates are included in order of priority.
//    $templatePaths = [
//        __DIR__ . '/../admin/template'
//    ];
//
//    $loader = new Twig_Loader_Filesystem($templatePaths);
//
//    $twigParams = [
//        'cache' => false,
//        'strict_variables' => true,
//        'debug' => true // TODO - needs config
//    ];
//
//    $twig = new \ASVoting\TwigAdminSessionRender(
//        $adminSession,
//        $loader,
//        $twigParams
//    );
//
//    // Inject function - allows DI in templates.
//    $function = new Twig_SimpleFunction('inject', function (string $type) use ($injector) {
//        return $injector->make($type);
//    });
//    $twig->addFunction($function);
//
//    $rawParams = ['is_safe' => array('html')];
//    $rawTwigFunctions = [
//        'memory_debug' => 'memory_debug',
//        'request_nonce' => 'request_nonce',
//    ];
//
//    foreach ($rawTwigFunctions as $functionName => $callable) {
//        $function = new Twig_SimpleFunction($functionName, function () use ($injector, $callable) {
//            return $injector->execute($callable);
//        }, $rawParams);
//        $twig->addFunction($function);
//    }
//
//    return $twig;
//}
//
//
//
//
//function createTwigForAdminInternal(\Auryn\Injector $injector)
//{
//    // The templates are included in order of priority.
//    $templatePaths = [
//        __DIR__ . '/../admin/template'
//    ];
//
//    $loader = new Twig_Loader_Filesystem($templatePaths);
//
//    $twigParams = [
//        'cache' => false,
//        'strict_variables' => true,
//        'debug' => true // TODO - needs config
//    ];
//
//    $twig = new \Twig_Environment(
//        $loader,
//        $twigParams
//    );
//
//    // Inject function - allows DI in templates.
//    $function = new Twig_SimpleFunction('inject', function (string $type) use ($injector) {
//        return $injector->make($type);
//    });
//    $twig->addFunction($function);
//
//    $rawParams = ['is_safe' => array('html')];
//    $rawTwigFunctions = [
//        'memory_debug' => 'memory_debug',
//        'request_nonce' => 'request_nonce',
//    ];
//
//    foreach ($rawTwigFunctions as $functionName => $callable) {
//        $function = new Twig_SimpleFunction($functionName, function () use ($injector, $callable) {
//            return $injector->execute($callable);
//        }, $rawParams);
//        $twig->addFunction($function);
//    }
//
//    return $twig;
//}


//function createTwigForSuperAdmin(
//    \Auryn\Injector $injector,
//    \ASVoting\SuperSession\SuperSession $superSession
//) {
//    // The templates are included in order of priority.
//    $templatePaths = [
//        __DIR__ . '/../adminsuper/template'
//    ];
//
//    $loader = new Twig_Loader_Filesystem($templatePaths);
//
//    $twigParams = [
//        'cache' => false,
//        'strict_variables' => true,
//        'debug' => true // TODO - needs config
//    ];
//
//    $twig = new \ASVoting\TwigSuperSessionRender(
//        $superSession,
//        $loader,
//        $twigParams
//    );
//
//    // Inject function - allows DI in templates.
//    $function = new Twig_SimpleFunction('inject', function (string $type) use ($injector) {
//        return $injector->make($type);
//    });
//    $twig->addFunction($function);
//
//    $rawParams = ['is_safe' => array('html')];
//    $rawTwigFunctions = [
//        'memory_debug' => 'memory_debug',
//        'request_nonce' => 'request_nonce',
//    ];
//
//    foreach ($rawTwigFunctions as $functionName => $callable) {
//        $function = new Twig_SimpleFunction($functionName, function () use ($injector, $callable) {
//            return $injector->execute($callable);
//        }, $rawParams);
//        $twig->addFunction($function);
//    }
//
//    return $twig;
//}


/**
 * @return \Doctrine\ORM\EntityManager
 */
function createDoctrineEntityManager()
{
    $config = getConfig(\ASVoting\Config::ASVOTING_DATABASE_INFO);

    $connectionParams = array(
        'dbname' => $config['schema'],
        'user' => $config['username'],
        'password' => $config['password'],
        'host' => $config['host'],
        'driver' => 'pdo_mysql',
    );

    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        [__DIR__ . "/Example/Model"],
        true,
        __DIR__ . "/../var/doctrine"
    );

    // TODO - precompile these in the build step.
    // $config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_ALWAYS);

    // obtaining the entity manager
    return \Doctrine\ORM\EntityManager::create($connectionParams, $config);
}



function createMemoryWarningCheck(
    Config $config,
    \Auryn\Injector $injector
) : \ASVoting\MemoryWarningCheck\MemoryWarningCheck {
    $env = Config::getEnvironment();

    if ($env === Config::ENVIRONMENT_LOCAL) {
        return $injector->make(\ASVoting\MemoryWarningCheck\DevEnvironmentMemoryWarning::class);
    }

    return $injector->make(\ASVoting\MemoryWarningCheck\ProdMemoryWarningCheck::class);
}

function createAllowedAccessMiddlewareForApp(Config $config)
{
    $allowedAccessCidrs = $config->getAllowedAccessCidrs();

    $allowedPaths = [
        '/coming_soon',
        '/debug/info',
    ];

    return new \ASVoting\Middleware\AllowedAccessMiddleware(
        $allowedAccessCidrs,
        $allowedPaths,
        '/coming_soon'
    );
}


//function createAllowedAccessMiddlewareForSuper(Config $config)
//{
//    $allowedAccessCidrs = $config->getAllowedAccessCidrs();
//
//    $allowedPaths = [
//        '/nope',
//        '/debug/info',
//        '/logout'
//    ];
//
//    return new \ASVoting\Middleware\AllowedAccessMiddleware(
//        $allowedAccessCidrs,
//        $allowedPaths,
//        '/nope'
//    );
//}

//function createRoutesForApp()
//{
//    return new \SlimAuryn\Routes(__DIR__ . '/../routes/app_routes.php');
//}

//function createRoutesForAdmin()
//{
//    return new \SlimAuryn\Routes(__DIR__ . '/../routes/admin_routes.php');
//}
//
function createRoutesForApi()
{
    return new \SlimAuryn\Routes(__DIR__ . '/../routes/api_routes.php');
}

//function createRoutesForInternal()
//{
//    return new \SlimAuryn\Routes(__DIR__ . '/../routes/internal_routes.php');
//}
//
//function createRoutesForSuperAdmin()
//{
//    return new \SlimAuryn\Routes(__DIR__ . '/../routes/superadmin_routes.php');
//}

/**
 * Creates the ExceptionMiddleware that converts all known app exceptions
 * to nicely formatted pages for the super admin site
 */
function createExceptionMiddlewareForSuperAdminSite(\Auryn\Injector $injector)
{
    $exceptionHandlers = [
        \ASVoting\Exception\DebuggingCaughtException::class => 'debuggingCaughtExceptionExceptionMapperApp',
    ];

    $resultMappers = getResultMappers($injector);

    return new \SlimAuryn\ExceptionMiddleware(
        $exceptionHandlers,
        $resultMappers
    );
}

/**
 * Creates the ExceptionMiddleware that converts all known app exceptions
 * to nicely formatted pages for the app/user facing sites
 */
function createExceptionMiddlewareForApp(\Auryn\Injector $injector)
{
    $exceptionHandlers = [
        // We don't use this. All forms are api based.
        /// \Params\Exception\ValidationException::class => 'foo',
        \ASVoting\Exception\DebuggingCaughtException::class => 'debuggingCaughtExceptionExceptionMapperApp',
    ];

    $resultMappers = getResultMappers($injector);

    return new \SlimAuryn\ExceptionMiddleware(
        $exceptionHandlers,
        $resultMappers
    );
}

/**
 * Creates the ExceptionMiddleware that converts all known app exceptions
 * to nicely formatted pages for the api
 */
function createExceptionMiddlewareForApi(\Auryn\Injector $injector)
{
    $exceptionHandlers = [
        \Params\Exception\ValidationException::class => 'paramsValidationExceptionMapperApi',
        \ASVoting\Exception\DebuggingCaughtException::class => 'debuggingCaughtExceptionExceptionMapperForApi',
        //        \ParseError::class => 'parseErrorMapper',
//        \PDOException::class => 'pdoExceptionMapper',
    ];

    return new \SlimAuryn\ExceptionMiddleware(
        $exceptionHandlers,
        getResultMappers($injector)
    );
}


/**
 * Creates the objects that map StubResponse into PSR7 responses
 */
function getResultMappers(\Auryn\Injector $injector)
{
    $twigResponseMapperFn = function (
        \SlimAuryn\Response\TwigResponse $twigResponse,
        ResponseInterface $originalResponse
    ) use ($injector) {

        $twigResponseMapper = $injector->make(\SlimAuryn\ResponseMapper\TwigResponseMapper::class);

        return $twigResponseMapper($twigResponse, $originalResponse);
    };

    $markdownResponseMapperFn = function (
        \ASVoting\Response\MarkdownResponse $markdownResponse,
        ResponseInterface $originalResponse
    ) use ($injector) {
        $markdownResponseMapper = $injector->make(\ASVoting\Service\MarkdownResponseMapper::class);

        return $markdownResponseMapper($markdownResponse, $originalResponse);
    };

    return [
        \SlimAuryn\Response\StubResponse::class => '\SlimAuryn\ResponseMapper\ResponseMapper::mapStubResponseToPsr7',
//        \ASVoting\Response\MarkdownResponse::class => $markdownResponseMapperFn,
        ResponseInterface::class => 'SlimAuryn\ResponseMapper\ResponseMapper::passThroughResponse',
        'string' => 'convertStringToHtmlResponse',
        \SlimAuryn\Response\TwigResponse::class => $twigResponseMapperFn
    ];
}

function createExceptionMiddlewareForAdminSite(\Auryn\Injector $injector)
{
//    $validation = $injector->make(\ASVoting\Service\ValidationExceptionFlashAndRedirect::class);

    $exceptionHandlers = [
        \Params\Exception\ValidationException::class => $validation,
//        \ParseError::class => 'parseErrorMapper',
//        \PDOException::class => 'pdoExceptionMapper',
        \ASVoting\Exception\DebuggingCaughtException::class => 'debuggingCaughtExceptionExceptionMapperApp',
    ];

    return new SlimAuryn\ExceptionMiddleware(
        $exceptionHandlers,
        getResultMappers($injector)
    );
}


function createExceptionMiddlewareForInternalSite(\Auryn\Injector $injector)
{
//    $validation = $injector->make(\ASVoting\Service\ValidationExceptionFlashAndRedirect::class);

    $exceptionHandlers = [
//        \Params\Exception\ValidationException::class => $validation,
//        \ASVoting\Exception\DebuggingCaughtException::class => 'debuggingCaughtExceptionExceptionMapperApp',
    ];

    return new SlimAuryn\ExceptionMiddleware(
        $exceptionHandlers,
        getResultMappers($injector)
    );
}


function createSlimAurynInvokerFactory(
    \Auryn\Injector $injector,
    \SlimAuryn\RouteMiddlewares $routeMiddlewares
) {
    $resultMappers = getResultMappers($injector);

    return new SlimAuryn\SlimAurynInvokerFactory(
        $injector,
        $routeMiddlewares,
        $resultMappers
    );
}


function createSlimAppForApp(
    Injector $injector,
    \Slim\Container $container,
    \ASVoting\AppErrorHandler\AppErrorHandler $appErrorHandler
) {
    // quality code.
    $container['foundHandler'] = $injector->make(\SlimAuryn\SlimAurynInvokerFactory::class);

    // TODO - this shouldn't be used in production.
    $container['errorHandler'] = $appErrorHandler;
//  function ($container) use ($appErrorHandler) {
//        return $appErrorHandler;
//    };

    $container['phpErrorHandler'] = $appErrorHandler;
//        function ($container) {
//        return $container['errorHandler'];
//    };

    $app = new \Slim\App($container);

    $app->add($injector->make(\SlimAuryn\ExceptionMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\ContentSecurityPolicyMiddleware::class));
//    $app->add($injector->make(\ASVoting\Middleware\BadHeaderMiddleware::class));
//    $app->add($injector->make(\ASVoting\Middleware\AllowedAccessMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\MemoryCheckMiddleware::class));

    return $app;
}


function createSlimAppForApi(
    Injector $injector,
    \Slim\Container $container,
    \ASVoting\AppErrorHandler\AppErrorHandler $appErrorHandler
) {
    // quality code.
    $container['foundHandler'] = $injector->make(\SlimAuryn\SlimAurynInvokerFactory::class);

    // TODO - this shouldn't be used in production.
    $container['errorHandler'] = $appErrorHandler;

    $container['phpErrorHandler'] = $appErrorHandler;
//        function ($container) {
//        return $container['errorHandler'];
//    };

    $app = new \Slim\App($container);

    $app->add($injector->make(\SlimAuryn\ExceptionMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\MemoryCheckMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\AllowAllCors::class));

    return $app;
}


function createSlimAppForSuperAdmin(
    Injector $injector,
    \Slim\Container $container,
    \ASVoting\AppErrorHandler\AppErrorHandler $appErrorHandler
) {
    // quality code.
    $container['foundHandler'] = $injector->make(\SlimAuryn\SlimAurynInvokerFactory::class);

    // TODO - this shouldn't be used in production.
    $container['errorHandler'] = $appErrorHandler;


    $container['phpErrorHandler'] = $appErrorHandler;
//        function ($container) {
//        return $container['errorHandler'];
//    };

    $app = new \Slim\App($container);
    $app->add($injector->make(\SlimAuryn\ExceptionMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\ContentSecurityPolicyMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\SuperLoginCheck::class));
    $app->add($injector->make(\ASVoting\Middleware\AllowedAccessMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\Session::class));
    $app->add($injector->make(\ASVoting\Middleware\MemoryCheckMiddleware::class));

    return $app;
}

function createSlimAppForAdmin(
    Injector $injector,
    \Slim\Container $container,
    \ASVoting\AppErrorHandler\AppErrorHandler $appErrorHandler
) {
    // quality code.
    $container['foundHandler'] = $injector->make(\SlimAuryn\SlimAurynInvokerFactory::class);

    // TODO - this shouldn't be used in production.
    $container['errorHandler'] = $appErrorHandler;

    $container['phpErrorHandler'] = function ($container) {
        return $container['errorHandler'];
    };

    $app = new \Slim\App($container);

    // These are run in reverse order.
    $app->add($injector->make(\ASVoting\Middleware\ContentSecurityPolicyMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\AdminProjectSelectedCheck::class));
    $app->add($injector->make(\ASVoting\Middleware\AdminLoginCheck::class));
    $app->add($injector->make(\ASVoting\Middleware\Session::class));
    $app->add($injector->make(\ASVoting\Middleware\MemoryCheckMiddleware::class));
    $app->add($injector->make(\SlimAuryn\ExceptionMiddleware::class));

    return $app;
}



function createSlimAppForInternal(
    Injector $injector,
    \Slim\Container $container,
    \ASVoting\AppErrorHandler\AppErrorHandler $appErrorHandler
) {
    // quality code.
    $container['foundHandler'] = $injector->make(\SlimAuryn\SlimAurynInvokerFactory::class);

    // TODO - this shouldn't be used in production.
    $container['errorHandler'] = $appErrorHandler;

    $container['phpErrorHandler'] = function ($container) {
        return $container['errorHandler'];
    };

    $app = new \Slim\App($container);

    $app->add($injector->make(\SlimAuryn\ExceptionMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\MemoryCheckMiddleware::class));
    $app->add($injector->make(\ASVoting\Middleware\LocalAccessOnlyMiddleware::class));

    return $app;
}


function createSlimContainer()
{
    $container = new \Slim\Container();
    global $request;

    if (isset($request) && $request !== null) {
        $container['request'] = $request;
    }

    return $container;
}


function createServerName(): \ASVoting\Data\ServerName
{
    return new \ASVoting\Data\ServerName(
        getConfig(Config::ASVoting_SERVER_NAME)
    );
}

function createMiddlewareSession(): \ASVoting\Middleware\Session
{
    $settings = [
        'lifetime'     => '6000 minutes',
        'path'         => '/',
        'domain'       => Config::getCookieDomainAdmin(),
        'secure'       => Config::useSsl(),
        'httponly'     => true,
        'name'         => 'ASVoting_session',
        'autorefresh'  => true,
        'handler'      => null,
        'ini_settings' => [],
    ];

    return new \ASVoting\Middleware\Session($settings);
}


function createMiddlewareSessionForSuper(): \ASVoting\Middleware\Session
{
    $settings = [
        'lifetime'     => '60 minutes',
        'path'         => '/',
        'domain'       => Config::getCookieDomainAdminSuper(),
        'secure'       => Config::useSsl(),
        'httponly'     => true,
        'name'         => 'ASVotingsuper_session',
        'autorefresh'  => true,
        'handler'      => null,
        'ini_settings' => [],
    ];

    return new \ASVoting\Middleware\Session($settings);
}


function createPlatformStripeClient(\ASVoting\Data\AdminDomain $adminDomain)
{
    $clientId = getConfig(['ASVoting', 'stripe_key', 'client_id']);
    $secret = getConfig(['ASVoting', 'stripe_key', 'secret']);
    $webhook_secret = getConfig(['ASVoting', 'stripe_key', 'webhook_secret']);

    return new \ASVoting\Stripe\PlatformStripeClient\StandardPlatformStripeClient(
        $clientId,
        $secret,
        $webhook_secret,
        $adminDomain
    );
}


function createInMemorySuperLoginRepo()
{
    $usernamesAndHashes = Config::getSuperAdminUsernamesAndHashes();

    return new \ASVoting\Repo\SuperLoginRepo\InMemorySuperLoginRepo($usernamesAndHashes);
}

function createHtmlAppErrorHandler(\Auryn\Injector $injector) : \ASVoting\AppErrorHandler\AppErrorHandler
{

    if (Config::isProductionEnv() === true) {
        return $injector->make(\ASVoting\AppErrorHandler\HtmlErrorHandlerForProd::class);
    }

    return $injector->make(\ASVoting\AppErrorHandler\HtmlErrorHandlerForLocalDev::class);
}

function createJsonAppErrorHandler(\Auryn\Injector $injector) : \ASVoting\AppErrorHandler\AppErrorHandler
{
    if (Config::isProductionEnv() === true) {
        return $injector->make(\ASVoting\AppErrorHandler\JsonErrorHandlerForProd::class);
    }

    return $injector->make(\ASVoting\AppErrorHandler\JsonErrorHandlerForLocalDev::class);
}

function createApiDomain(Config $config)
{
    return new  \ASVoting\Data\ApiDomain($config->getApiDomain());
}

function createInternalDomain(Config $config)
{
    return new  \ASVoting\Data\InternalDomain($config->getInternalDomain());
}

function createAdminDomain(Config $config)
{
    return new  \ASVoting\Data\AdminDomain($config->getAdminDomain());
}

function createStripeWebhookSecret(Config $config)
{
    return new  \ASVoting\Data\StripeWebhookSecret($config->getStripeWebhookSecret());
}
