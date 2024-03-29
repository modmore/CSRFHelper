<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8e4aeba44501ab5543d62ecdebbec7b7
{
    public static $files = array (
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
    );

    public static $prefixLengthsPsr4 = array (
        'm' => 
        array (
            'modmore\\CSRFHelper\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'modmore\\CSRFHelper\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8e4aeba44501ab5543d62ecdebbec7b7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8e4aeba44501ab5543d62ecdebbec7b7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8e4aeba44501ab5543d62ecdebbec7b7::$classMap;

        }, null, ClassLoader::class);
    }
}
