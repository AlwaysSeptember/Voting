<?php

declare(strict_types = 1);

/**
 * This file holds functions that are required by all environments.
 */

use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedQuestion;
use ASVoting\Model\VotingChoice;
use ASVoting\Model\VotingMotion;
use ASVoting\Model\VotingQuestion;
use Ramsey\Uuid\Uuid;

/**
 * @param array $indexes
 * @return mixed
 * @throws Exception
 */
function getConfig(array $indexes)
{
    static $options = null;
    if ($options === null) {
        require __DIR__ . '/../config.php';
        require __DIR__ . '/../autoconf.php';
    }

    $data = $options;

    foreach ($indexes as $index) {
        if (array_key_exists($index, $data) === false) {
            throw new \Exception("Config doesn't contain an element for $index, for indexes [" . implode('|', $indexes) . "]");
        }

        $data = $data[$index];
    }

    return $data;
}

function getExceptionText(\Throwable $exception): string
{
    $text = "";
    do {
        $text .= get_class($exception) . ":" . $exception->getMessage() . "\n\n";
        $text .= $exception->getTraceAsString();

        $exception = $exception->getPrevious();
    } while ($exception !== null);

    return $text;
}

function saneErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine): bool
{
    if (error_reporting() === 0) {
        // Error reporting has been silenced
        if ($errorNumber !== E_USER_DEPRECATED) {
        // Check it isn't this value, as this is used by twig, with error suppression. :-/
            return true;
        }
    }
    if ($errorNumber === E_DEPRECATED) {
        return false;
    }
    if ($errorNumber === E_CORE_ERROR || $errorNumber === E_ERROR) {
        // For these two types, PHP is shutting down anyway. Return false
        // to allow shutdown to continue
        return false;
    }
    $message = "Error: [$errorNumber] $errorMessage in file $errorFile on line $errorLine.";
    throw new \Exception($message);
}

/**
 * Decode JSON with actual error detection
 */
function json_decode_safe(?string $json)
{
    if ($json === null) {
        throw new \ASVoting\Exception\JsonException("Error decoding JSON: cannot decode null.");
    }

    $data = json_decode($json, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    }

    $parser = new \Seld\JsonLint\JsonParser();
    $parsingException = $parser->lint($json);

    if ($parsingException !== null) {
        throw $parsingException;
    }

    if ($data === null) {
        throw new \ASVoting\Exception\JsonException("Error decoding JSON: null returned.");
    }

    throw new \ASVoting\Exception\JsonException("Error decoding JSON: " . json_last_error_msg());
}


/**
 * @param mixed $data
 * @param int $options
 * @return string
 * @throws Exception
 */
function json_encode_safe($data, $options = 0): string
{
    $result = json_encode($data, $options);

    if ($result === false) {
        throw new \Exception("Failed to encode data as json: " . json_last_error_msg());
    }

    return $result;
}


/**
 * Get the options to use when hashing passwords.
 * The cost should be tuned for the hash to take something like a
 * quarter of a second of CPU time to hash.
 *
 * @return array
 */
function get_password_options()
{
    $options = [
        'cost' => 12,
    ];

    return $options;
}

/**
 * @param string $password
 * @return string
 */
function generate_password_hash(string $password): string
{
    $options = get_password_options();
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);

    if ($hash === false) {
        throw new \Exception('Failed to hash password.');
    }

    return $hash;
}


function getClientIpAddress() : string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //shared internet
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   // from load balancer
        $ipString = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ipParts = explode(',', $ipString);
        if ($ipParts === false) {
            throw new \Exception("Failed to explode ipString.");
        }

        if (count($ipParts) > 0) {
            return trim($ipParts[0]);
        }
    }

    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Recursive directory search
 * @param string $folder
 * @param string $pattern
 * @return array
 */
function recursiveSearch(string $folder, string $pattern)
{
    $dir = new \RecursiveDirectoryIterator($folder);
    $ite = new \RecursiveIteratorIterator($dir);
    $files = new \RegexIterator($ite, $pattern, \RegexIterator::GET_MATCH);
    $fileList = array();
    foreach ($files as $file) {
        $fileList = array_merge($fileList, $file);
    }
    return $fileList;
}


/**
 * @param $value
 *
 * @return array{string, null}|array{null, mixed}
 */
function convertToValue($value)
{
    if (is_scalar($value) === true) {
        return [
            null,
            $value
        ];
    }
    if ($value === null) {
        return [
            null,
            null
        ];
    }

    $callable = [$value, 'toArray'];
    if (is_object($value) === true && is_callable($callable)) {
        return [
            null,
            $callable()
        ];
    }
    if (is_object($value) === true) {
        if ($value instanceof \DateTimeInterface) {
            // Format as Atom time with microseconds
            return [
                null,
//                $value->format("Y-m-d\TH:i:s.uP")
                $value->format(DateTimeInterface::RFC3339)
            ];
        }
    }

    if (is_array($value) === true) {
        $values = [];
        foreach ($value as $key => $entry) {
            [$error, $value] = convertToValue($entry);
            if ($error !== null) {
                return [$error, null];
            }
            $values[$key] = $value;
        }

        return [
            null,
            $values
        ];
    }

    if (is_object($value) === true) {
        return [
            sprintf(
                "Unsupported type [%s] of class [%s] for toArray.",
                gettype($value),
                get_class($value)
            ),
            null
        ];
    }

    return [
        sprintf(
            "Unsupported type [%s] for toArray.",
            gettype($value)
        ),
        null
    ];
}




/**
 * Fetch data and return statusCode, body and headers
 */
function fetchUri(string $uri, string $method, array $queryParams = [], string $body = null, array $headers = [])
{
    $query = http_build_query($queryParams);
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $uri . $query);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

    $allHeaders = [];

    if ($body !== null) {
        $allHeaders[] = 'Content-Type: application/json';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    }


    foreach ($headers as $header) {
        $allHeaders[] = $header;
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $allHeaders);

    $headers = [];
    $handleHeaderLine = function ($curl, $headerLine) use (&$headers) {
        $headers[] = $headerLine;
        return strlen($headerLine);
    };
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, $handleHeaderLine);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $body = curl_exec($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    return [$statusCode, $body, $headers];
}

/**
 * Fetch data and only return successful request
 */
function fetchDataWithHeaders($uri, array $headers)
{
    [$statusCode, $body, $responseHeaders] = fetchUri($uri, 'GET', [], null, $headers);

    if ($statusCode === 200) {
        return json_decode_safe($body);
    }

    throw new \Exception("Failed to fetch data from " . $uri);
}

/**
 * Fetch data and only return successful request
 */
function fetchData($uri)
{
    [$statusCode, $body, $headers] = fetchUri($uri, 'GET');

    if ($statusCode === 200) {
        return json_decode_safe($body);
    }

    throw new \Exception("Failed to fetch data from " . $uri);
}


/**
 * Escape characters that are meaningful in SQL like searches
 * @param string $string
 * @return mixed
 */
function escapeMySqlLikeString(string $string)
{
    return str_replace(
        ['\\', '_', '%', ],
        ['\\\\', '\\_', '\\%'],
        $string
    );
}

// Docker IP addresses are apparently "172.XX.X.X",
// Which should be in an IPV4 PRIVATE ADDRESS SPACE
// https://www.arin.net/knowledge/address_filters.html
function isIpAddressDockerBoxHost(string $ipAddress)
{
    if (substr($ipAddress, 0, 4) !== '172.') {
        return false;
    }

    $ipParts = explode('.', $ipAddress);

    if (count($ipParts) !== 4) {
        return false;
    }

    $ipPart1 = (int)$ipParts[1];
    if ($ipPart1 >= 16 && $ipPart1 <= 31) {
        return true;
    }

    return false;
}

function isIpAddressSameCluster(string $ipAddress)
{
    if (strpos($ipAddress, '10.') === 0) {
        return true;
    }

    return false;
}

function showRawCharacters(string $result)
{
    $resultInHex = unpack('H*', $result);
    $resultInHex = $resultInHex[1];

    $bytes = str_split($resultInHex, 2);
    $resultSeparated = implode(', ', $bytes); //byte safe
    return $resultSeparated;
}


function buildInString(string $prefix, $entries)
{
    $strings = [];
    $params = [];
    $count = 0;

    foreach ($entries as $entry) {
        $currentString = ':' . $prefix . $count;
        $strings[] = $currentString;
        $params[$currentString] = $entry;
        $count += 1;
    }

    return [implode(', ', $strings), $params];
}


function compareArrays(array $expected, array $actual, array $currentKeyPath = [])
{
    $errors = [];

    ksort($expected);
    ksort($actual);
    foreach ($expected as $key => $value) {
        $keyPath = $currentKeyPath;
        $keyPath[] = $key;

        if (array_key_exists($key, $actual) === false) {
            $errors[implode('.', $keyPath)] = "Missing key should be value " . \json_encode($expected[$key]);
        }
        else if (is_array($expected[$key]) === true && is_array($actual[$key]) === true) {
            $deeperErrors = compareArrays($expected[$key], $actual[$key], $keyPath);
            $errors = array_merge($errors, $deeperErrors);
        }
        else {
            $expectedValue = \json_encode($expected[$key]);
            $actualValue = \json_encode($actual[$key]);
            if ($expectedValue !== $actualValue) {
                $errors[implode('.', $keyPath)] = "Values don't match.\nExpected " . $expectedValue . "\n vs actual " . $actualValue . "\n";
            }
        }

        unset($actual[$key]);
    }

    foreach ($actual as $key => $value) {
        $keyPath = $currentKeyPath;
        $keyPath[] = $key;
        $errors[implode('.', $keyPath)] = "Has extra value of " . \json_encode($value);
    }

    return $errors;
}

function getMimeTypeFromFilename($filename)
{
    $contentTypesByExtension = [
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpg',
        'png' => 'image/png',
    ];

    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $extension = strtolower($extension);

    if (array_key_exists($extension, $contentTypesByExtension) === false) {
        throw new \Exception("Unknown file type [$extension]");
    }

    return $contentTypesByExtension[$extension];
}

function str_putcsv($dataHeaders, $dataRows)
{
    # Generate CSV data from array
    $fh = fopen('php://temp', 'rw'); # don't create a file, attempt
    # to use memory instead

    assert($fh !== false, "File handle is false.");

    /** @var $fh \resource */
    if ($dataHeaders !== null) {
        fputcsv($fh, $dataHeaders);
    }

    foreach ($dataRows as $row) {
        fputcsv($fh, $row);
    }
    rewind($fh);
    $csv = stream_get_contents($fh);
    fclose($fh);

    return $csv;
}





function getReasonPhrase(int $status)
{
    $knownStatusReasons = [
        420 => 'Enhance Your Calm',
        421 => 'what the heck',
        512 => 'Server known limitation',
    ];

    return $knownStatusReasons[$status] ?? '';
}

function formatTextToAnchor($question): string
{
    $text = str_replace(' ', '_', $question);

    /** @var string|null $text */
    $text = preg_replace('#[^\w]#', '', $text);
    if ($text === null) {
        throw new \Exception("Preg replace failed.");
    }

    return $text;
}


function linkableTitle(string $title): string
{
    $questionAnchor = formatTextToAnchor($title);

    $output = '<div>';
    $output .= '<a href="#' . $questionAnchor . '">';
    $output .= '<i class="fa fa-link" aria-hidden="true"></i>';
    $output .= '<h3>' . $title . '</h3>';
    $output .= '</a>';
    $output .= '</div>';

    return $output;
}





function createId(): string
{
    return bin2hex(random_bytes(16));
}


function getMemoryLimit()
{
    $memoryLimit = ini_get('memory_limit');

    if ($memoryLimit === false) {
        throw new \Exception("Failed to get memory_limit.");
    }

    if (strrpos($memoryLimit, 'M') === (strlen($memoryLimit) - 1)) {
        $memoryLimitValue = ((int)$memoryLimit) * 1024 * 1024;
    }
    else if (strrpos($memoryLimit, 'G') === (strlen($memoryLimit))) {
        $memoryLimitValue = ((int)$memoryLimit) * 1024 * 1024 * 1024;
    }
    else {
        throw new \Exception("Could not understand memory limit of [$memoryLimit]");
    }

    return $memoryLimitValue;
}

function getPercentMemoryUsed() : int
{
    $maxMemory = memory_get_peak_usage();

    $memoryLimitValue = getMemoryLimit();

    $percentMemoryUsed = (int)((100 * $maxMemory) / $memoryLimitValue);

    return $percentMemoryUsed;
}


function array_contains($needle, array $haystack): bool
{
    return in_array($needle, $haystack, true);
}


/**
 * @param string[] $headers
 * @param mixed[][] $rows
 * @return string
 */
function renderTable($headers, $rows)
{
    $thead = '';
    foreach ($headers as $header) {
        $thead .= sprintf("<td>%s</td>\n", $header);
    }

    $tbody = '';
    foreach ($rows as $row) {
        $tbody .= "<tr>\n";
        foreach ($row as $value) {
            $tbody .= sprintf("<td>%s</td>\n", $value);
        }

        $tbody .= "</tr>\n";
    }

    $table = <<< TABLE
<table>
  <thead>
    <tr>
     $thead
    </tr>
  </thead>
  <tbody>
    $tbody
  </tbody>
</table>
TABLE;


    return $table;
}

function formatTraceLine(array $trace)
{

    $location = '??';
    $function = 'unknown';

    if (isset($trace["file"]) && isset($trace["line"])) {
        $location = $trace["file"]. ':' . $trace["line"];
    }
    else if (isset($trace["file"])) {
        $location = $trace["file"] . ':??';
    }
//    else {
//        var_dump($trace);
//        exit(0);
//    }

    $baseDir = realpath(__DIR__ . '/../');
    if ($baseDir === false) {
        throw new \Exception("Couldn't find parent directory from " . __DIR__);
    }

    $location = str_replace($baseDir, '', $location);

    if (isset($trace["class"]) && isset($trace["type"]) && isset($trace["function"])) {
        $function = $trace["class"] . $trace["type"] . $trace["function"];
    }
    else if (isset($trace["class"]) && isset($trace["function"])) {
        $function = $trace["class"] . '_' . $trace["function"];
    }
    else if (isset($trace["function"])) {
        $function = $trace["function"];
    }
    else {
        $function = "Function is weird: " . json_encode(var_export($trace, true));
    }

    return sprintf(
        "%s %s",
        $location,
        $function
    );
}

function getExceptionStack(\Throwable $exception): string
{
    $line = "Exception of type " . get_class($exception). "\n";

    foreach ($exception->getTrace() as $trace) {
        $line .=  formatTraceLine($trace);
    }

    return $line;
}


/**
 * @param Throwable $exception
 * @return string[]
 */
function getExceptionStackAsArray(\Throwable $exception)
{
    $lines = [];
    foreach ($exception->getTrace() as $trace) {
        $lines[] = formatTraceLine($trace);
    }

    return $lines;
}



function randomPassword(int $length): string
{
    $characters = '0123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ!@£$%^&*()/?{}[]';
    $charactersLength = mb_strlen($characters);
    $randString = '';

    for ($i = 0; $i < $length; $i++) {
        $offset = $charactersLength - 1;
        $position = random_int(0, $offset);
        $randString .= mb_substr($characters, $position, 1);
    }

    return $randString;
}

function renderTableHtml($items, array $headers, callable $rowFn)
{
    $thead = '';
    foreach ($headers as $header) {
        $thead .= sprintf("     <th>%s</th>\n", $header);
    }

    $tbody = '';
    foreach ($items as $item) {
        $tbody .= $rowFn($item);
    }

    $table = <<< TABLE
<table>
  <thead>
    <tr>
$thead
    </tr>
  </thead>
  <tbody>
$tbody
  </tbody>
</table>
TABLE;

    return $table;
}


function getExceptionInfoAsArray(\Throwable $exception)
{
    $data = [
        'status' => 'error',
        'message' => $exception->getMessage(),
    ];

    $previousExceptions = [];

    do {
        $exceptionInfo = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'trace' => getExceptionStackAsArray($exception),
        ];

        $previousExceptions[] = $exceptionInfo;
    } while (($exception = $exception->getPrevious()) !== null);

    $data['details'] = $previousExceptions;

    return $data;
}

/**
 * Format an array of strings to have a count at the start
 * e.g. $lines = ['foo', 'bar'], output is:
 *
 * #0 foo
 * #1 bar
 */
function formatLinesWithCount(array $lines): string
{
    $output = '';
    $count = 0;

    foreach ($lines as $line) {
        $output .= '  #' . $count . ' '. $line . "\n";
        $count += 1;
    }

    return $output;
}

function purgeExceptionMessage(\Throwable $exception)
{
    $rawMessage = $exception->getMessage();

    $purgeAfterPhrases = [
        'with params'
    ];

    $message = $rawMessage;

    foreach ($purgeAfterPhrases as $purgeAfterPhrase) {
        $matchPosition = strpos($message, $purgeAfterPhrase);
        if ($matchPosition !== false) {
            $message = substr($message, 0, $matchPosition + strlen($purgeAfterPhrase));
            $message .= '**PURGED**';
        }
    }

    return $message;
}

function getTextForException(\Throwable $exception)
{
    $currentException = $exception;
    $text = '';

    do {
        $text .= sprintf(
            "Exception type:\n  %s\n\nMessage:\n  %s \n\nStack trace:\n%s\n",
            get_class($currentException),
            purgeExceptionMessage($currentException),
            formatLinesWithCount(getExceptionStackAsArray($currentException))
        );

        $currentException = $currentException->getPrevious();
    } while ($currentException !== null);

    return $text;
}


function getRandomId(): string
{
    $foo = random_bytes(32);

    return hash("sha256", $foo);
}


//function getIniMemoryBytes()
//{
//    $val = trim($val);
//    $last = strtolower($val[strlen($val)-1]);
//    switch($last) {
//        // The 'G' modifier is available since PHP 5.1.0
//        case 'g':
//            return $val * 1024 * 1024 * 1024;
//        case 'm':
//            return $val * 1024 * 1024;
//        case 'k':
//            return $val * 1024;
//    }
//    return $val;
//}


function showException(\Exception $exception)
{
    echo "oops";
    do {
        echo get_class($exception) . ":" . $exception->getMessage() . "\n\n";
        echo nl2br($exception->getTraceAsString());

        echo "<br/><br/>";
        $exception = $exception->getPrevious();
    } while ($exception !== null);
}


function convertDataToMotions(array $motionsData)
{
    $motions = [];
    foreach ($motionsData as $motionData) {
        $motions[] = convertDataToMotion($motionData);
    }


    return $motions;
}

function convertDataToMotion($data)
{
    $questions = [];
    foreach ($data['questions'] as $question) {
        $choices = [];

        foreach ($question['choices'] as $choice) {
            $choices[] = new ProposedChoice($choice['text']);
        }

        $questions[] = new ProposedQuestion($question['text'], $question['voting_system'], $choices);
    }

    $proposedMotion = new ProposedMotion(
        $data['type'],
        $data['name'],
        \DateTimeImmutable::createFromFormat(\DateTime::RFC3339, '2020-07-02T12:00:00Z'),
        \DateTimeImmutable::createFromFormat(\DateTime::RFC3339, '2020-07-02T12:00:00Z'),
        $questions
    );

    return $proposedMotion;
}


function createVotingMotionFromProposedMotion(ProposedMotion $proposedMotion)
{
    $votingQuestions = [];

    foreach ($proposedMotion->getQuestions() as $proposedQuestion) {
        $votingChoices = [];
        foreach ($proposedQuestion->getChoices() as $proposedChoice) {
            $votingChoices[] = new VotingChoice(
                Uuid::uuid4()->toString(),
                $proposedChoice->getText()
            );
        }

        $votingQuestions[] = new VotingQuestion(
            Uuid::uuid4()->toString(),
            $proposedQuestion->getText(),
            $proposedQuestion->getVotingSystem(),
            $votingChoices
        );
    }

    return new VotingMotion(
        Uuid::uuid4()->toString(),
        $proposedMotion->getType(),
        $proposedMotion->getName(),
        $proposedMotion->getStartDatetime(),
        $proposedMotion->getCloseDatetime(),
        $votingQuestions
    );
}

/**
 * Check if the proposedMotion should be open.
 *
 * This is mostly a check on the date range.
 *
 * @param ProposedMotion $proposedMotion
 * @return bool
 */
function proposedMotionShouldBeOpen(ProposedMotion $proposedMotion)
{
    $now = new DateTimeImmutable();

    // start time is in the future
    if ($proposedMotion->getStartDatetime() > $now) {
        return false;
    }

    // close time is in the past
    if ($proposedMotion->getCloseDatetime() < $now) {
        return false;
    }

    return true;
}
