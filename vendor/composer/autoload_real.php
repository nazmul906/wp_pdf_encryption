<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbddf9b3a5a6239a14b8d3c02c64bb20f
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitbddf9b3a5a6239a14b8d3c02c64bb20f', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbddf9b3a5a6239a14b8d3c02c64bb20f', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbddf9b3a5a6239a14b8d3c02c64bb20f::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}