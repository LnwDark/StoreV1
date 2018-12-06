<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfb31f378da09c64362e03c6c4096f637
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Mike42\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Mike42\\' => 
        array (
            0 => __DIR__ . '/..' . '/mike42/escpos-php/src/Mike42',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfb31f378da09c64362e03c6c4096f637::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfb31f378da09c64362e03c6c4096f637::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}