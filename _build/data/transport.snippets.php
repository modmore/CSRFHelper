<?php
$snips = array(
    'csrfhelper' => 'Generates a CSRF token for a specific form.',
    'csrfhelper_formit' => 'Hook to use with FormIt to validate a CSRF token.',
);

$snippets = array();
$idx = 0;

foreach ($snips as $name => $description) {
    $idx++;
    $snippets[$idx] = $modx->newObject('modSnippet');
    $snippets[$idx]->fromArray(array(
       'name' => $name,
       'description' => $description . ' (Part of Commerce)',
       'snippet' => getSnippetContent($sources['snippets'] . strtolower($name) . '.snippet.php')
    ));
}

return $snippets;
