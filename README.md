# swoft-smarty

[![Latest Stable Version](https://img.shields.io/packagist/v/jxy918/swoft-smarty.svg)](https://packagist.org/packages/jxy918/swoft-smarty)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Swoft Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://www.swoft.org/docs)
[![Swoft License](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/blob/master/LICENSE)

sowft框架smarty模板组件

smarty template component for swoft.

Swoft-smarty Component


## Install, 安装

- composer install command

```
composer require jxy918/swoft-smarty

```

- smarty default config
- swoft框架里 smarty 的默认配置如下, 默认不需要添加, 如果想要修改, 可以把下面配置放入到bean.php里面, 进行相应的修改即可

```
'smarty' => [
    'debugging'=>true,
    'caching'=>true,
    'cacheLifetime'=>120,
    'leftDelimiter' => '<!--{',
    'rightDelimiter' => '}-->',
    'templateDir' => '@base/resource/template',
    'compileDir' => '@base/runtime/template_c',
    'cacheDir' => '@base/runtime/cache'
]
```

## Use in Controller, 控制器里使用如下

- app/Http/Controlle/SmartyController.php
- resource/template/smarty.html

app/Http/Controlle/SmartyController.php

```
<?php declare(strict_types=1);


namespace App\Http\Controller;

use Swoft;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class SmartyController
 *
 * @since 2.0
 *
 * @Controller(prefix="smarty")
 */
class SmartyController
{
    /**
     * @RequestMapping("index")
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     * @throws \Swoft\Exception\SwoftException
     */
    public function assign(): Response
    {
        $tpl = Swoft::getBean('smarty')->initView();
        $data = ['nickname'=>'jxy918', 'sex'=>'男', 'msg'=>' hello smarty'];
        $tpl->assign('data', $data);
        $content = $tpl->fetch('smarty.html');
        return context()->getResponse()->withContentType(ContentType::HTML)->withContent($content);
    }
}


```

模板文件 resource/template/smarty.html

```
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test smayrt</title>
</head>
<body>
<div>
    <div>
        <h1>Hello Smarty</h1>
    </div>
    <ul>
        <!--{foreach key=k item=v from=$data}-->
        <li><!--{$k}-->:<!--{$v}--></li>
        <!--{/foreach}-->
    </ul>
</div>
</body>
</html>

```
