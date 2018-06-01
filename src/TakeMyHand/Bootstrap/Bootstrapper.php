<?php
/**
 *
 *  _____     _       _____     _____           _
 * |_   _|___| |_ ___|     |_ _|  |  |___ ___ _| |
 *  | | | .'| '_| -_| | | | | |     | .'|   | . |
 *  |_| |__,|_,_|___|_|_|_|_  |__|__|__,|_|_|___|
 *                        |___|
 *
 * TakeMyHand  Boilerplate-Nano-Framework v0.0.3
 * @category   Composition/IOC
 * @package    TakeMyHand.Container
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * the bootstrapper, heart and core
 *
 */
namespace  TakeMyHand\Bootstrap;
use Exception;
use TakeMyHand\Container;
use TakeMyHand;
use TakeMyHand\Container\IContainer;
use TakeMyHand\Dispatcher\IMessage;

use TakeMyHand\Dispatcher\IMessageDispatcher;
use TakeMyHand\Persistence\IKeyValueStore;
use TakeMyHand\Routing\IRouter;
use TakeMyHand\Routing\Router;


class Bootstrapper implements TakeMyHand\IRootEmitter {
    /** @var IContainer $container*/
    protected $container;
    /** @var IMessageDispatcher $dispatcher*/
    protected $dispatcher;
    /** @var IRouter $router*/
    protected $router;
    /** @var IKeyValueStore $store*/
    protected $store;

    protected $request;

    public function __construct(string $environment=""){
        new TakeMyHand\Env\EnvLoader($_SERVER['DOCUMENT_ROOT'], $environment);
        $this->request = new TakeMyHand\Request();
    }

    //override this to use a different config when changing default core behaviour
    public function setup(){
        try{
            $this->initialize_store();
            $this->build_container();
            $this->start_dispatcher();
            $this->bootstrap_router();
            $this->router->resolve($this->request);

        }catch (Exception $e){
            TakeMyHand\Http\FullFailureHelper::fail("Framework Setup failed!",$e);
        }

    }

    private function bootstrap_router(){

        $this->router = $this->container->resolve('TakeMyHand\Routing\IRouter');
    }

    private function initialize_store(){
        if(empty($_ENV['TMH_KEY_VALUE_STORE'])){
            $this->store = new TakeMyHand\Persistence\NoKeyValueStore();
        }else{
            switch($_ENV['TMH_KEY_VALUE_STORE']){
                /*
                case 'SqlBasedKeyValueStore':
                    break;
                case 'FileBasedKeyValueStore':
                    break;
                case 'Redis':
                    break;*/
                default:
                    throw new Exception('Unsupported key-value-store!');
                    break;
            }
        }

    }

    private function build_container(){
        if(!isset($this->store)){
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new Exception("No Key-Value-Store initialized!");
        }
        if(Container\ContainerBuilder::restoreable($this->store)){
            $this->container = Container\ContainerBuilder::restore($this->store);
        }else{
            $builder = new Container\ContainerBuilder($this->store);
            $builder->register_instance_as('TakeMyHand\Persistence\IKeyValueStore',$this->store);
            $builder->register_instance_as('TakeMyHand\IRootEmitter', $this);
            $builder->register('TakeMyHand\ViewEngine\IViewEngine', function(IContainer $c){
                return new TakeMyHand\ViewEngine\RegexViewEngine($c->resolve('TakeMyHand\IRootEmitter'));
            });
            $builder->scan_extends('TakeMyHand\Behaviour\Behaviour','Behaviour');
            $builder->scan_implements('TakeMyHand\Dispatcher\ISubscribe','Subscriber');
            $builder->register_enumerable('TakeMyHand\Dispatcher\ISubscribe','TakeMyHand\InternalSubscribers\FourOFourSubscriber');
            $builder->register_enumerable('TakeMyHand\Dispatcher\ISubscribe','TakeMyHand\InternalSubscribers\UserNotAuthorizedSubscriber');
            $builder->register_enumerable('TakeMyHand\Dispatcher\ISubscribe','TakeMyHand\InternalSubscribers\ResponseReadySubscriber');
            $builder->register_enumerable('TakeMyHand\Dispatcher\ISubscribe','TakeMyHand\InternalSubscribers\InternalServerErrorSubscriber');
            $builder->register_singleton('TakeMyHand\Dispatcher\IMessageDispatcher', function(IContainer $c){
                $dispatcher = new TakeMyHand\Dispatcher\MessageDispatcher();
                foreach($c->resolve('TakeMyHand\Dispatcher\ISubscribe') as $subscriber){
                    $dispatcher->register($subscriber);
                }
                return $dispatcher;
            });

            $builder->register_singleton('TakeMyHand\Routing\IRouter', function(IContainer $c)  {
                $router = new Router($c->resolve('TakeMyHand\IRootEmitter'));
                $router->start_register();
                foreach($c->resolve('TakeMyHand\Behaviour\Behaviour') as $behaviour){
                    $behaviour->on(new TakeMyHand\Behaviour\Action($router, $c->resolve('TakeMyHand\IRootEmitter')));
                }
                $router->end_register();
                return $router;
            });
            $builder->inject_property('TakeMyHand\Behaviour\Behaviour','auth',function(IContainer $c){
                $auth = $c->resolve('\TakeMyHand\Auth\IAuthenticationManager');
                if($auth != null){
                    return $auth->get_identity();
                }
                return null;
            });
            //entry point to user registration / THIS IS FIXED FOR NOW BECAUSE WHO CARES =P as it doesnt carry a namespace its general enough tbh
            $entry_point_user_module = '\\'.$_ENV['TMH_APP_DIR'].'\CompositionModule';
            $builder->execute_module_registration(new $entry_point_user_module);
            $this->container = $builder->build();
        }
    }

    private function start_dispatcher(){
        $this->dispatcher = $this->container->resolve('TakeMyHand\Dispatcher\IMessageDispatcher');
    }

    public function emit(IMessage $message){
        if(!isset($this->dispatcher)){
            throw new Exception("No dispatcher / message mediator has been registred.");
        }
        $this->dispatcher->send($message);
    }

}