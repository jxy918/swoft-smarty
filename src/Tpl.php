<?php declare(strict_types=1);

namespace Swoft\Smarty;

use Couchbase\ViewQueryEncodable;
use function in_array;
use function rtrim;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Stdlib\Helper\FileHelper;
use Swoft\Stdlib\Helper\ObjectHelper;
use Swoft\Smarty\Contract\TplInterface;

/**
 * Class Smarty - PHP view scripts Smarty
 *
 * @since 1.0
 * @Bean("tpl")
 */
class Tpl implements TplInterface
{
    /**
     * @var string Default view suffix.
     */
    protected $suffix = 'html';

    /**
     * @var array Allowed suffix list. It use auto add suffix.
     */
    protected $suffixes = [];

    /**
     * @var string View storage base path
     */
    protected $viewsPath = '';

    /**
     * @var array Attributes for the view
     */
    protected $attributes = [];

    /**
     * 出示化smarty对象
     * @var null
     */
    protected $smarty = null;

    /**
     * Class constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->suffixes = self::DEFAULT_SUFFIXES;
        ObjectHelper::init($this, $config);
        $this->initSmarty();
    }

    /**
     * Render a view, if layout file is setting, will use it.
     * throws RuntimeException if view file does not exist
     *
     * @param string            $view
     * @param array             $data   extract data to view, cannot contain view as a key
     * @param string|null|false $layout Override default layout file
     * @return string
     * @throws \Throwable
     */
    public function display(string $view, array $data = [], $layout = null): string
    {
        $output = $this->fetch($view, $data);
        return $this->renderContent($output, $data, $layout);
    }

    /**
     * @param string      $content
     * @param array       $data
     * @param string|null $layout override default layout file
     * @return string
     * @throws \Throwable
     */
    public function renderBody(string $content, array $data = [], $layout = null): string
    {
        return $this->renderContent($content, $data, $layout);
    }

    /**
     * @param string      $content
     * @param array       $data
     * @param string|null $layout override default layout file
     * @return string
     * @throws \Throwable
     */
    public function renderContent(string $content, array $data = [], $layout = null): string
    {
        // Render layout
        if ($layout = $layout ?: $this->layout) {
            $mark    = $this->placeholder;
            $main    = $this->fetch($layout, $data);
            $content = \preg_replace("/$mark/", $content, $main, 1);
        }

        return $content;
    }

    /**
     * @param string $view
     * @param array  $data
     * @param bool   $outputIt
     * @return string
     * @throws \Throwable
     */
    public function include(string $view, array $data = [], $outputIt = true): string
    {
        if ($outputIt) {
            echo $this->fetch($view, $data);
            return '';
        }

        return $this->fetch($view, $data);
    }

    /**
     * Renders a view and returns the result as a string
     * throws RuntimeException if $viewsPath . $view does not exist
     *
     * @param string $view
     * @param array  $data
     * @return mixed
     * @throws \Throwable
     */
    public function fetch(string $view, array $data = [])
    {
        $file = $this->getViewFile($view);

        if (!is_file($file)) {
            throw new \RuntimeException("cannot render '$view' because the view file does not exist. File: $file");
        }
        $data = \array_merge($this->attributes, $data);
        try {
            \ob_start();
            $this->protectedIncludeScope($file, $data);
            $output = \ob_get_clean();
        } catch (\Throwable $e) { // PHP 7+
            \ob_end_clean();
            throw $e;
        }

        return $output;
    }

    /**
     * @param $view
     * @return string
     */
    public function getViewFile(string $view): string
    {
        $view = $this->getRealView($view);

        return FileHelper::isAbsPath($view) ? $view : $this->getViewsPath() . $view;
    }

    /**
     * @param string $file
     * @param array  $data
     */
    protected function protectedIncludeScope($file, array $data): void
    {
        \extract($data, EXTR_OVERWRITE);
        include $file;
    }

    /**
     * Get the attributes for the renderer
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Set the attributes for the renderer
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * Add an attribute
     *
     * @param $key
     * @param $value
     */
    public function addAttribute($key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Retrieve an attribute
     *
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!isset($this->attributes[$key])) {
            return null;
        }

        return $this->attributes[$key];
    }

    /**
     * Get the view path
     *
     * @return string
     */
    public function getViewsPath(): string
    {
        return $this->viewsPath ? \Swoft::getAlias($this->viewsPath) : '';
    }

    /**
     * Set the view path
     *
     * @param string $viewsPath
     */
    public function setViewsPath(string $viewsPath): void
    {
        if ($viewsPath) {
            $this->viewsPath = rtrim($viewsPath, '/\\') . '/';
        }
    }

    /**
     * @param string $view
     * @return string
     */
    protected function getRealView(string $view): string
    {
        $sfx = FileHelper::getSuffix($view, true);
        $ext = $this->suffix;

        // No ext. eg: 'home/index'
        if ($sfx === '') {
            return $view . '.' . $ext;
        }

        if ($sfx === $ext || in_array($sfx, $this->suffixes, true)) {
            return $view;
        }

        return $view . '.' . $ext;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     */
    public function setSuffix(string $suffix): void
    {
        $this->suffix = $suffix;
    }

    /**
     * 初始化smarty对象
     */
    protected function initSmarty() {
        $this->smarty = new \Smarty();
        $this->smarty->debugging = true;
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 120;
        $this->smarty->left_delimiter = '<!--{';
        $this->smarty->right_delimiter = '}-->';
        $this->smarty->addTemplateDir('@base/resource/views');
    }
}
