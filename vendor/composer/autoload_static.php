<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfc5202e7ec72e506eeeac73667b030ee
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfc5202e7ec72e506eeeac73667b030ee::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfc5202e7ec72e506eeeac73667b030ee::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfc5202e7ec72e506eeeac73667b030ee::$classMap;

        }, null, ClassLoader::class);
    }
}
