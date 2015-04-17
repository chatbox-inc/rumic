<?php
/**
 * Created by PhpStorm.
 * User: mkkn
 * Date: 2015/04/16
 * Time: 7:10
 */

namespace Chatbox\Rumic;

use Laravel\Lumen\Application as Lumen;
use Dotenv;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;


/**
 * Class Application
 * @package Chatbox\Rumic
 *
 */
class Application extends Lumen{

    public function __construct()
    {
        parent::__construct();
//        $this->singleton(
//            'Illuminate\Contracts\Debug\ExceptionHandler',
//            'App\Exceptions\Handler'
//        );
//
//        $this->singleton(
//            'Illuminate\Contracts\Console\Kernel',
//            'App\Console\Kernel'
//        );
// $app->middleware([
//     // 'Illuminate\Cookie\Middleware\EncryptCookies',
//     // 'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
//     // 'Illuminate\Session\Middleware\StartSession',
//     // 'Illuminate\View\Middleware\ShareErrorsFromSession',
//     // 'Laravel\Lumen\Http\Middleware\VerifyCsrfToken',
// ]);

// $app->routeMiddleware([

// ]);
// $app->register('App\Providers\AppServiceProvider');
    }

    public function loadConfiguration($config=[]){
        $methods = [
            "implements",
            "middlewares",
            "routeMiddelwares",
            "providers"
        ];

        foreach($methods as $entry => $method){
            if(is_numeric($entry)){
                $entry = $method;
                $method = "configurate".ucfirst($method);
            }
            if(isset($config[$entry])){
                $this->{$method}($config[$entry]);
            }
        }
    }

    #region core setter
    /**
     * @param $abstract
     * @param $method
     */
    public function addAvailableBindgins($abstract,$method)
    {
        $this->availableBindings[$abstract] = $method;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param string $storagePath
     */
    public function setStoragePath($storagePath)
    {
        $this->storagePath = $storagePath;
    }

    /**
     * @param string $configPath
     */
    public function setConfigPath($configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * @param string $resourcePath
     */
    public function setResourcePath($resourcePath)
    {
        $this->resourcePath = $resourcePath;
    }

    #endregion

    # region implements系

    protected function configurateImplements(array $config){
        foreach($config as $value){
            $value = $value + [null,null,true];
            list($abstract,$concrete,$shared) = $value;
            $this->bind($abstract,$concrete,$shared);
        }
    }

    protected function configurateMiddlewares(array $config){
        $this->middleware($config);
    }

    protected function configurateRouteMiddlewares(array $config){
        $this->routeMiddleware($config);
    }

    protected function configurateProviders(array $config){
        foreach($config as $value){
            $this->register($value);//lumenのregisterは第二/第三引数使ってない。
        }
    }
    # endregion

    protected function addRoute($method, $uri, $action)
    {
        if (is_array($this->groupAttributes) && isset($this->groupAttributes["mount"])) {
        }

        parent::addRoute($method, $uri, $action); // TODO: Change the autogenerated stub
    }


    public function loadEnv($dir){
        Dotenv::load($dir);
    }

    public function artisan(){
        $kernel = $this->make(
            'Illuminate\Contracts\Console\Kernel'
        );
        exit($kernel->handle(new ArgvInput, new ConsoleOutput));
    }
}