symfony3.4    Route

位于kernel.request事件调度里面的第五个Symfony\Component\HttpKernel\EventListener\RouterListener



判断路由是否需要更新：

```php
$cache->isFresh()
```

判断是否需要更新的文件：

![1555482229939](C:\Users\admin\AppData\Roaming\Typora\typora-user-images\1555482229939.png)



如果不需要更新：

实例化appDevDebugProjectContainerUrlMatcher，然后返回

具体的调用顺序是：

```php
$parameters = $this->matcher->matchRequest($request);
$matcher = $this->getMatcher();
$this->matcher = new $this->options['matcher_cache_class']($this->context);
$parameters = $this->matcher->match($request->getPathInfo());
```





需要更新则需要调用如下函数：

```php
function (ConfigCacheInterface $cache) {
                $dumper = $this->getMatcherDumperInstance();
                if (method_exists($dumper, 'addExpressionLanguageProvider')) {
                    foreach ($this->expressionLanguageProviders as $provider) {
                        $dumper->addExpressionLanguageProvider($provider);
                    }
                }

                $options = array(
                    'class' => $this->options['matcher_cache_class'],
                    'base_class' => $this->options['matcher_base_class'],
                );

                $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
            }
```

1. 引入各种加载器

2. load config/routing_dev.xml文件

3. 返回的识别类型

   ![1555491648040](C:\Users\admin\AppData\Roaming\Typora\typora-user-images\1555491648040.png)

4. 分析出各种类型的route以及resource，结果如下：

   ![1555492815278](C:\Users\admin\AppData\Roaming\Typora\typora-user-images\1555492815278.png)

5. 分析出各个route的前缀相同的作为group

   ```php
   $tree = $this->buildStaticPrefixCollection($collection);
   ```

   生成如下递归分组

   ![1555494979824](C:\Users\admin\AppData\Roaming\Typora\typora-user-images\1555494979824.png)

6. 写入cache文件

   ```php
   $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
   ```

   