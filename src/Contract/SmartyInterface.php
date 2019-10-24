<?php declare(strict_types=1);

namespace Swoft\Smarty\Contract;

/**
 * Class SmartyInterface The interface of view
 * @since 1.0
 */
interface SmartyInterface
{
    /**
     * 初始化smarty对象
     * @return mixed
     */
    public function initView();
}
