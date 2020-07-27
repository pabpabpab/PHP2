<?php

namespace App\services;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;
use \Twig\Extension\DebugExtension;
use App\traits\MsgMaker;

class TwigRendererServices implements IRenderer
{
    use MsgMaker;

    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader([
            dirname(__DIR__ ) . '/views',
            dirname(__DIR__) . '/views/layouts',
        ]);

        $this->twig = new Environment(
            $loader,
            [
                'debug' => true,
            ]
        );

        $this->twig->addExtension(new DebugExtension());
    }

    /**
     * @param $template
     * @param array $params
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($template, $params = [])
    {
        $params['msg'] = $this->getMSG();
        $params['cartCount'] = (int) $_SESSION['cartCount'];

        try {
            return $this->twig->render($template . '.twig', $params);
        } catch (\Exception $exception) {
            return  $exception->getMessage();
        }
    }
}