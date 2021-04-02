#!/usr/bin/env php
<?php

if (version_compare(PHP_VERSION, '7.0.0') < 0 ) {
    fwrite(STDERR,'This script requires PHP version 7.0 or greater'.PHP_EOL);
}

// Verifying URL syntax
$sshSchemeUrl = $argv[1];

if (filter_var($sshSchemeUrl, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
    $parsedURL = parse_url($sshSchemeUrl);
} else {
    fwrite(STDERR, "Invalid URL format: $sshSchemeUrl" . PHP_EOL);
    exit(2);
}
//var_dump($parsedURL);

if ($parsedURL['scheme'] !== strtolower('ssh')) {
    fwrite(STDERR, "This script only supports the ssh schema type." . PHP_EOL);
    exit(2);
}

$sshCmd = "ssh ";

if ((array_key_exists('port', $parsedURL)) && ($parsedURL['port'] !== 22)) {
    $sshCmd .= " -p " . (int) $parsedURL['port'] . " ";
}

if ((array_key_exists('user', $parsedURL)) && (strlen($parsedURL['user'])) > 0) {
    $sshCmd .= ' ' . escapeshellarg($parsedURL['user'] ).'@';
}

if (filter_var($parsedURL['host'], FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) || filter_var($parsedURL['host'], FILTER_VALIDATE_IP)) {
    $sshCmd .= escapeshellarg($parsedURL['host']);
} else {
    fwrite(STDERR, "Invalid hostname");
    exit(2);
}
fwrite(STDOUT,$sshCmd);
exit(0);

?>


