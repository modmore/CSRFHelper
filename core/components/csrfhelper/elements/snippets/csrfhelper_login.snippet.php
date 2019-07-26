<?php
/**
 * CSRF Helper for Login (and Register + UpdateProfile snippets)
 *
 * This snippet validates a CSRF token as a hook for the Login snippets.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var LoginHooks $hook
 *
 */

$path = $modx->getOption('csrfhelper.core_path', null, MODX_CORE_PATH . 'components/csrfhelper/');
$path .= 'vendor/autoload.php';
require_once $path;

use modmore\CSRFHelper\Csrf;
use modmore\CSRFHelper\InvalidTokenException;
use modmore\CSRFHelper\Storage\SessionStorage;

// Allow logout to pass without a CSRF key
$actionKey = $hook->controller->getProperty('actionKey', 'service');
$logoutKey = $hook->controller->getProperty('logoutKey', 'logout');
if (isset($_REQUEST[$actionKey]) && $_REQUEST[$actionKey] === $logoutKey) {
    return true;
}

$key = $modx->getOption('csrfKey', $scriptProperties, 'default');

$storage = new SessionStorage();
$csrf = new Csrf($storage, $modx->getUser());
$token = $hook->getValue('csrf_token');

try {
    $csrf->check($key, $token);
    return true;
}
catch (InvalidTokenException $e) {
    $hook->addError('csrf_token', 'Your security token did not match the expected token.');
    $modx->log(modX::LOG_LEVEL_WARN, '[csrfhelper_login] Received an invalid CSRF token');
    return false;
}
catch (Error $e) {
    throw $e;
}
catch (Exception $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[csrfhelper_login] Could not safely generate the CSRF token: ' . $e->getMessage());
    $hook->addError('csrf_token', 'Could not securely generate a CSRF token to protect your form submission.');
}
return false;