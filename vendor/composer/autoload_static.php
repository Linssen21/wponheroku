<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite376a92da5b437500167084d0d3c8778
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInite376a92da5b437500167084d0d3c8778::$classMap;

        }, null, ClassLoader::class);
    }
}
