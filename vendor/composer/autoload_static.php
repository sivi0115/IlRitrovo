<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4d693d299ebe1d0211ddf6e692795d78
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'c15d4a1253e33e055d05e547c61dcb71' => __DIR__ . '/..' . '/smarty/smarty/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'View\\' => 5,
        ),
        'U' => 
        array (
            'Utility\\' => 8,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Smarty\\' => 7,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'N' => 
        array (
            'None\\ilRitrovo\\' => 15,
        ),
        'F' => 
        array (
            'Foundation\\' => 11,
        ),
        'E' => 
        array (
            'Entity\\' => 7,
        ),
        'C' => 
        array (
            'Controller\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'View\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/View',
        ),
        'Utility\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Utility',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Smarty\\' => 
        array (
            0 => __DIR__ . '/..' . '/smarty/smarty/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'None\\ilRitrovo\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Foundation\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Foundation',
        ),
        'Entity\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Entity',
        ),
        'Controller\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Controller',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4d693d299ebe1d0211ddf6e692795d78::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4d693d299ebe1d0211ddf6e692795d78::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4d693d299ebe1d0211ddf6e692795d78::$classMap;

        }, null, ClassLoader::class);
    }
}
