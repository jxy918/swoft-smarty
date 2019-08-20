<?php declare(strict_types=1);

namespace Swoft\Smarty\Contract;

/**
 * Class TplInterface The interface of view
 * @since 1.0
 */
interface TplInterface
{
    public const DEFAULT_SUFFIXES = ['php', 'tpl', 'html'];

    /**
     * @param string            $view
     * @param array             $data
     * @param string|null|false $layout Override default layout file
     *
     * @return string
     */
    public function display(string $view, array $data = [], $layout = null): string;

    /**
     * @param string $view
     * @param array  $data
     * @return string
     */
    public function fetch(string $view, array $data = []): string;
}
