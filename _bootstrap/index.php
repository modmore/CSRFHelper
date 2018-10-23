<?php
/* Get the core config */
if (!file_exists(dirname(dirname(__FILE__)).'/config.core.php')) {
    die('ERROR: missing '.dirname(dirname(__FILE__)).'/config.core.php file defining the MODX core path.');
}

echo "<pre>";
/* Boot up MODX */
echo "Loading modX...\n";
require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
echo "Initializing manager...\n";
$modx->initialize('mgr');
$modx->getService('error','error.modError', '', '');
$modx->setLogTarget('HTML');

$componentPath = dirname(dirname(__FILE__));

///** @var CSRFHelper $csrfhelper */
//$modx->setOption('csrfhelper.core_path', $componentPath.'/core/components/csrfhelper/');
//$csrfhelper = $modx->getService('csrfhelper','CSRFHelper', $componentPath.'/core/components/csrfhelper/model/csrfhelper/');


/* Namespace */
if (!createObject('modNamespace',array(
    'name' => 'csrfhelper',
    'path' => $componentPath.'/core/components/csrfhelper/',
    'assets_path' => $componentPath.'/assets/components/csrfhelper/',
),'name', false)) {
    echo "Error creating namespace csrfhelper.\n";
}

/* Path settings */
if (!createObject('modSystemSetting', array(
    'key' => 'csrfhelper.core_path',
    'value' => $componentPath.'/core/components/csrfhelper/',
    'xtype' => 'textfield',
    'namespace' => 'csrfhelper',
    'area' => 'Paths',
    'editedon' => time(),
), 'key', false)) {
    echo "Error creating csrfhelper.core_path setting.\n";
}

if (!createObject('modSystemSetting', array(
    'key' => 'csrfhelper.assets_path',
    'value' => $componentPath.'/assets/components/csrfhelper/',
    'xtype' => 'textfield',
    'namespace' => 'csrfhelper',
    'area' => 'Paths',
    'editedon' => time(),
), 'key', false)) {
    echo "Error creating csrfhelper.assets_path setting.\n";
}

/* Fetch assets url */
$url = 'http';
if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
    $url .= 's';
}
$url .= '://'.$_SERVER["SERVER_NAME"];
if ($_SERVER['SERVER_PORT'] != '80') {
    $url .= ':'.$_SERVER['SERVER_PORT'];
}
$requestUri = $_SERVER['REQUEST_URI'];
$bootstrapPos = strpos($requestUri, '_bootstrap/');
$requestUri = rtrim(substr($requestUri, 0, $bootstrapPos), '/').'/';
$assetsUrl = "{$url}{$requestUri}assets/components/csrfhelper/";

if (!createObject('modSystemSetting', array(
    'key' => 'csrfhelper.assets_url',
    'value' => $assetsUrl,
    'xtype' => 'textfield',
    'namespace' => 'csrfhelper',
    'area' => 'Paths',
    'editedon' => time(),
), 'key', false)) {
    echo "Error creating csrfhelper.assets_url setting.\n";
}

// Snippets
if (!createObject('modSnippet', array(
    'name' => 'csrfhelper',
    'static' => true,
    'static_file' => $componentPath.'/core/components/csrfhelper/elements/snippets/csrfhelper.snippet.php',
), 'name', false)) {
    echo "Error creating csrfhelper snippet.\n";
}
if (!createObject('modSnippet', array(
    'name' => 'csrfhelper_formit',
    'static' => true,
    'static_file' => $componentPath.'/core/components/csrfhelper/elements/snippets/csrfhelper_formit.snippet.php',
), 'name', false)) {
    echo "Error creating csrfhelper_formit snippet.\n";
}

if (!createObject('modSnippet', array(
    'name' => 'csrfhelper_login',
    'static' => true,
    'static_file' => $componentPath.'/core/components/csrfhelper/elements/snippets/csrfhelper_login.snippet.php',
), 'name', false)) {
    echo "Error creating csrfhelper_formit snippet.\n";
}


echo "Done.";


/**
 * Creates an object.
 *
 * @param string $className
 * @param array $data
 * @param string $primaryField
 * @param bool $update
 * @return bool
 */
function createObject ($className = '', array $data = array(), $primaryField = '', $update = true) {
    global $modx;
    /* @var xPDOObject $object */
    $object = null;

    /* Attempt to get the existing object */
    if (!empty($primaryField)) {
        if (is_array($primaryField)) {
            $condition = array();
            foreach ($primaryField as $key) {
                $condition[$key] = $data[$key];
            }
        }
        else {
            $condition = array($primaryField => $data[$primaryField]);
        }
        $object = $modx->getObject($className, $condition);
        if ($object instanceof $className) {
            if ($update) {
                $object->fromArray($data);
                return $object->save();
            } else {
                $condition = $modx->toJSON($condition);
                echo "Skipping {$className} {$condition}: already exists.\n";
                return true;
            }
        }
    }

    /* Create new object if it doesn't exist */
    if (!$object) {
        $object = $modx->newObject($className);
        $object->fromArray($data, '', true);
        return $object->save();
    }

    return false;
}
