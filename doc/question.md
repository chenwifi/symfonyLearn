### 遇到的问题

1. 构建自己的另一个bundle的时候，通过type-hint形式传递container里面的service不成功，查阅文档才发现当前的controller同样需要注册成为service才能成功。

2. 在service里面是无法使用container的。这是为什么？？

3. 我在service里面创建

   ```php
   $products = $entityManager->createQuery(
               'SELECT p FROM AcmeTestBundle:Product p ORDER BY p.name ASC'
           )->getResult();
   ```

   这里的Product必须是大写的，但是在Repository里面小写也是可以的，这是为什么。

4. tag的作用？？？tags的作用？？？autoconfig的作用:根据你的类自动补全configuration，比如tag，终于明白了为什么eventsubscribe不用tag？？？autowiring???-----type-hint

5. 观察事件是何时注册？？（重要）subscribe是如何注册的。

6. 观察httpkernel是如何实例化。

7. 参数传递。（依赖注入）

   ```php
   $this->argumentValueResolvers;
   getDebug_ArgumentResolverService();
   ```

   ServiceValueResolver->container=servicelocator

   servicelocator->factory = 

   ```php
   array('Acme\\TestBundle\\Controller\\RandomController:testSerAction' => function () {
       return ${($_ = isset($this->services['service_locator.il7dx6d']) ? $this->services['service_locator.il7dx6d'] : $this->load('getServiceLocator_Il7dx6dService.php')) && false ?: '_'};
   }, 'Acme\\TestBundle\\Controller\\RandomController::testSerAction' => function () {
       return ${($_ = isset($this->services['service_locator.il7dx6d']) ? $this->services['service_locator.il7dx6d'] : $this->load('getServiceLocator_Il7dx6dService.php')) && false ?: '_'};
   })
   ```

   factory();

   ```php
   return $this->services['service_locator.il7dx6d'] = new \Symfony\Component\DependencyInjection\ServiceLocator(array('logger' => function () {
       return ${($_ = isset($this->services['logger']) ? $this->services['logger'] : $this->getLoggerService()) && false ?: '_'};
   }, 'messageGenerator' => function () {
       $f = function (\Acme\TestBundle\Service\MessageGenerator $v = null) { return $v; }; return $f(${($_ = isset($this->services['Acme\TestBundle\Service\MessageGenerator']) ? $this->services['Acme\TestBundle\Service\MessageGenerator'] : $this->load('getMessageGeneratorService.php')) && false ?: '_'});
   }));
   ```

   返回

   ```php
   $this->services['service_locator.il7dx6d'] = new \Symfony\Component\DependencyInjection\ServiceLocator
   ```

   factory=messageGenerator,logger

   需要注意的definition：

   debug.argument_resolver

   argument_resolver.service

   service_locator.il7dx6d=>getServiceLocator_Il7dx6dService.php

   说白了，都绕不开实例化，分析参数类型等等。在RegisterControllerArgumentLocatorsPass.php文件里面为依赖注入做了一些准备。最终替换掉argument_resolver.service里的argument。在某个地方controller被标记为controller.service_arguments的tag。

8. 观察一下routing。



### 小发现

1. ```php
   $this->argumentValueResolvers
   $this->argumentMetadataFactory
   //这两个属性是怎么来的？？？
   ```

2. 

