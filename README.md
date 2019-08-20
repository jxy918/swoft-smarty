#swoft-smarty#

smarty template view base on swoft.

Swoft Smarty View Component


## Install

- composer command

```
composer require jxy918/swoft-smarty

```

- use in controller


```
	/**
	 * @RequestMapping("index")
	 * @throws Throwable
	 */
	public function index(): Response
	{
	    $tpl = Swoft::getBean('smarty')->initView();
	    $nickname = '新风宇宙';
	    $tpl->assign('nickname', $nickname);
	    $ret = $tpl->fetch('aaa.html');
	    return context()->getResponse()->withContentType(ContentType::HTML)->withContent($ret);
	}

```

## LICENSE

The Component is open-sourced software licensed under the [Apache license](LICENSE).