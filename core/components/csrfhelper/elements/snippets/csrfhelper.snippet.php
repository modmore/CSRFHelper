<?php
/**
 * CSRF Helper
 *
 * This snippet generates a secure token to be used for combating CSRF attacks.
 *
 * Properties:
 * - &key: unique key for the CSRF token, recommended to make this unique per form
 * - &singleUse: by default a token is valid for a day, set to 1 to generate a unique token each time
 *
 * Call like this:
 *
 *   <input type="hidden" name="csrf_token" value="[[!csrfhelper? &key=`login` &singleUse=`1`]]">
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$path = $modx->getOption('csrfhelper.core_path', null, MODX_CORE_PATH . 'components/csrfhelper/');
$path .= 'vendor/autoload.php';
require_once $path;

use modmore\CSRFHelper\Csrf;
use modmore\CSRFHelper\Storage\SessionStorage;

$key = $modx->getOption('key', $scriptProperties, 'default');
$singleUse = (bool)$modx->getOption('singleUse', $scriptProperties, false);

$storage = new SessionStorage();
$csrf = new Csrf($storage, $modx->getUser());

try {
    if ($singleUse) {
        return $csrf->generate($key);
    }

    return $csrf->get($key);
}
catch (Error $e) {
    throw $e;
}
catch (Exception $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[csrfhelper] Could not safely generate the CSRF token: ' . $e->getMessage());
}
return '';