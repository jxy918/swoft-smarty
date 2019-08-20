#swoft-smarty#

smarty template view base on swoft.

Swoft Smarty View Component


## Install

- composer command

```
composer require jxy918/swoft-smarty

```

- smarty default config

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


##Use in Controller

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

resource/template/smarty.html

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

## LICENSE

The Component is open-sourced software licensed under the [Apache license](LICENSE).