<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/25
 * Time: 10:05
 */
require_once './EventDispatcherInterface.php';
require_once './EventSubscriberInterface.php';
require_once './Event.php';

class EventDispatcher implements EventDispatcherInterface{
    public $listeners = [];
    private $sorteds = [];

    public function dispatch($eventName, Event $event = null){
        if($event==null){
            $event = new Event();
        }

        $listeners = $this->getListeners($eventName);

        foreach ($listeners as $listener){
            if($event->isPropagationStopped()){
                break;
            }

            $listener($event);
        }
    }

    public function addListener($eventName, $listener, $priority = 0){
        $this->listeners[$eventName][$priority][] = $listener;
    }

    public function addSubscriber(EventSubscriberInterface $subscriber){
        $listeners = $subscriber->getSubscribedEvents();
        foreach ($listeners as $eventName=>$listener){
            if(is_array($listener)){
                if(is_array($listener[0])){
                    foreach ($listener as $key=>$value){
                        if(isset($value[1])){
                            $this->listeners[$eventName][$value[1]][] = [$subscriber,$value[0]];
                        }else{
                            $this->listeners[$eventName][0][] = [$subscriber,$value[0]];
                        }
                    }
                }else{
                    $this->listeners[$eventName][$listener[1]][] = [$subscriber,$listener[0]];
                }
            }else{
                $this->listeners[$eventName][0][] = [$subscriber,$listener];
            }
        }
    }

    public function removeListener($eventName, $listener){
        $listeners = $this->listeners[$eventName];
        foreach ($listeners as $priority=>$value){
            foreach ($value as $k=>$v){
                if($v==$listener){
                    unset($this->listeners[$eventName][$priority][$k]);
                }
            }
        }
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber){
        $listeners = $subscriber->getSubscribedEvents();
        foreach ($listeners as $eventName=>$listener){
            if(is_array($listener)){
                if(is_array($listener[0])){
                    foreach ($listener as $value){
                        $priority = isset($value[1]) ? $value[1] : 0;
                        $index = array_search($this->listeners[$eventName][$priority],[$subscriber,$value[0]]);
                        unset($this->listeners[$eventName][$priority][$index]);
                    }
                }else{
                    $priority = isset($listener[1]) ? $listener[1] : 0;
                    $index = array_search($this->listeners[$eventName][$priority],[$subscriber,$listener[0]]);
                    unset($this->listeners[$eventName][$priority][$index]);
                }
            }else{
                foreach($this->listeners[$eventName][0] as $key=>$value){
                    if($value == [$subscriber,$listener]){
                        unset($this->listeners[$eventName][0][$key]);
                    }
                }
            }
        }
    }

    public function getListeners($eventName = null){
        $this->sorteds = [];
        $this->sortEvents($eventName);
        return $this->sorteds;
    }

    public function getListenerPriority($eventName, $listener){
        $listeners = $this->listeners[$eventName];
        foreach ($listeners as $priority=>$value){
            if($listener==$value){
                return $priority;
            }
        }
        return null;
    }

    public function hasListeners($eventName = null){
        return isset($this->listeners[$eventName]);
    }

    public function sortEvents($eventName = null){
        $listeners = [];
        if($eventName==null){
            foreach ($this->listeners as $key=>$value){
                foreach ($value as $k=>$v){
                    if (isset($listeners[$k])){
                        foreach ($v as $vv){
                            array_push($listeners[$k],$vv);
                        }
                    }else{
                        $listeners[$k] = $v;
                    }
                }
            }
            krsort($listeners);
        }else{
            $listeners = $this->listeners[$eventName];
            krsort($listeners);
        }

        foreach ($listeners as $priority=>$listener){
            foreach ($listener as $k=>$v){
                $this->sorteds[] = $v;
            }
        }

        return $this->sorteds;
    }
}