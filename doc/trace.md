symfony3.4简单流程展示

1. 自动加载

2. 整合debug

3. 整合全局变量到request对象里面

4. 初始化bundle

5. 初始化容器(这里假设容器不用更新，另开一章讲明需要更新的情况，需要更新的容器将经历“编译”的过程，把php代码“编译”成php代码)

   ```php
   $this->initializeContainer();
   //检测是否需要更新"container"
   $fresh = $cache->isFresh() 
   ```

   

6. 进入主流程

   ```php
   return $this->getHttpKernel()->handle($request, $type, $catch);
   ```

7. 首先是事件调度(值得注意的是路由的生成，这个另开一章讲述)

   ```php
   $this->dispatcher->dispatch(KernelEvents::REQUEST, $event);
   ```

8. load controller(其中返回的是可执行的controller::method)

   ```php
   $controller = $this->resolver->getController($request)
   ```

9. 接着是kernel.controller的事件调度

   ```php
   $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);
   ```

10. 获取对应的方法的参数（这个好像是通过getDebug_ArgumentResolverService方法获取，具体倒是可以查看）

    ```php
    $arguments = $this->argumentResolver->getArguments($request, $controller);
    ```

11. 接着是kernel.controller_arguments的事件调度

12. 执行所调用的方法(当然，这里面的反向url也是一个问题)

    ```php
    $response = \call_user_func_array($controller, $arguments);
    ```

13. 接着是kernel.response，kernel.finish_request的事件调度

14. 调用send方法

    ```php
    $response->send();
    ```

15. 输出header，content

16. kernel.terminate的事件调度