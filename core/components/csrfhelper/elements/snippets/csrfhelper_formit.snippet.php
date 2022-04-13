<?php
/**
 * CSRF Helper for FormIt
 *
 * This snippet validates a CSRF token as a FormIt hook
 *
 * @var modX $modx
 * @var fiHooks $hook
 *
 */

$path = $modx->getOption('csrfhelper.core_path', null, MODX_CORE_PATH . 'components/csrfhelper/');
$path .= 'vendor/autoload.php';
require_once $path;

use modmore\CSRFHelper\Csrf;
use modmore\CSRFHelper\InvalidTokenException;
use modmore\CSRFHelper\Storage\SessionStorage;

$key = $modx->getOption('csrfKey', $hook->config, 'default');

$storage = new SessionStorage();
$csrf = new Csrf($storage, $modx->getUser());
$token = $hook->getValue('csrf_token');


try {
    $csrf->check($key, $token);
    return true;
}
catch (InvalidTokenException $e) {
    $modx->lexicon->load('csrfhelper:default');
    $error = $modx->lexicon('csrfhelper.error');
    $hook->addError('csrf_token', $error);
    $modx->log(modX::LOG_LEVEL_WARN, '[csrfhelper] Received an invalid CSRF token');
    return false;
}
catch (Error $e) {
    throw $e;
}
catch (Exception $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[csrfhelper] Could not safely generate the CSRF token: ' . $e->getMessage());
    $hook->addError('csrf_token', 'Could not securely generate a CSRF token to protect your form submission.');
}
return false;