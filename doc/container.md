容器涉及到一个“编译”的过程，即把PHP代码“编译，汇编”成php代码。这里的definition属性主要来自各个bundle下的resource/config/*.xml文件，然后整合definition属性自动生成cache目录下的service文件或者方法。可以说，容器这个概念在symfony3.4是非常重要的。以下是容器的简单流程。



1. 根据文件判断是否需要更新编译的容器，我注释掉appDevDebugProjectContainer.php的返回值一样可以达到重新编译的效果，方便测试。

   ```php
   $fresh = $cache->isFresh()
   ```

2. 总的来说，重要的代码有如下：对应于“编译”，“汇编”

   ```php
   $container = $this->buildContainer();
   $container->compile();
   $this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass())
   ```

3. 先是$container = $this->buildContainer();

   ```php
   //这里对应的是”预处理“
   $this->prepareContainer($container);
   //这里是为以后“编译”添加编译的过程
   $container->addCompilerPass();
   //其中编译的时候下面这个“pass”最重要
   $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($extensions));
   ```

4. 加载配置文件

   ```php
   $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))
   ```

   值得注意的是，service.yml里面的将会增加到“编译前的container的definition属性”，将来会成为一个服务。

   以下关键代码查找出配置文件给出的$classes,然后设置成definition

   ```php
   $classes = $this->findClasses($namespace, $resource, $exclude);
   $this->setDefinition($class, $definition = unserialize($serializedPrototype));
   ```

   以上便是$container = $this->buildContainer();所做的事情——预处理（添加编译pass），加载配置文件。

5. 接下来是$container->compile();

   引入别人的一句话概括：其中很多是框架自己的依赖关系，这些依赖关系类似java的方式，通过xml文件的形式进行声明，这些xml存在于框架的代码中

   ```php
   $compiler->compile($this);
   
   //这是“编译”的关键
   foreach ($this->passConfig->getPasses() as $pass) {
         $pass->process($container);
   }
   
   //其中第一个MergeExtensionConfigurationPass值得留意
   //里面最重要的代码如下，
   //这里争对每一个的extension，即bundle进行编译合成container，不知道是否可以这样理解bundle是里面的一等公民
   foreach ($container->getExtensions() as $name => $extension) {
       //进入代码发现加载的路径都是bundle/Resources/config下的*.xml文件
   	$extension->load($config, $tmpContainer);
   }
   
   //加载xml文件，根据xml文件增加container的definition属性，因为以后是根据definition属性生成service文件或方法的。
   $loader->load('web.xml');
   ```

6. 此时可以看见，经过第一个MergeExtensionConfigurationPass之后，container的值如下：

   ![1555571824059](C:\Users\admin\AppData\Roaming\Typora\typora-user-images\1555571824059.png)

   此时跟后面的比较得知：

   Definition比较多，resources刚刚好。可以推测，接下来的pass所做的事情是优化definition，涉及到内联服务（类比内联函数），删除不用的服务的definition等等。具体有87个pass，可以去看看。

7. 全部编译完成之后，definition是只有大大减少为281个，果然如上述所述。看注释，编译过程做了如下工作，其实还没有明了这个函数的所有作用。

   ```php
   //看注释，compiler是做这些事情：
        * Compiles the container.
        *
        * This method passes the container to compiler
        * passes whose job is to manipulate and optimize
        * the container.
        *
        * The main compiler passes roughly do four things:
        *
        *  * The extension configurations are merged;
        *  * Parameter values are resolved;
        *  * The parameter bag is frozen;
        *  * Extension loading is disabled.
        *
   ```

8. 接下来便是这个了——“汇编”成自动生成的PHP代码

   ```php
   $this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass());
   ```

9. 具体的definition是如何生成service函数和service文件的，我这里有个建议，照着cache目录下面的文件跟着具体的definition对比一下，即可得到一个十分清晰明了的过程。在这里不多说。这里只说一点，属性fileMap，methodMap对应着具体的service文件和service函数，他们对应的条件是：

   ```PHP
   //methodMap对应的条件
   if (!$definition->isSynthetic() && (!$this->asFiles || !$definition->isShared() || $this->isHotPath($definition)))
   ```

   ```php
   //fileMap对应的条件
   if (!$definition->isSynthetic() && $definition->isShared() && !$this->isHotPath($definition))
   ```

10. 之后便将生成的代码以及resources写入文件当中

    ```php
    $cache->write($rootCode, $container->getResources());
    ```

    

