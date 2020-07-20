<?php

namespace App\services;


class TwigRendererServices implements IRenderer
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function render($template, $params = [])
    {
        $content = $this->renderTemplate($template, $params);

        return $this->renderTemplate('twigLayouts/main',
            [
                'content' => $content
            ]
        );
    }

    public function renderTemplate($template, $params = [])
    {
        return $this->twig->render($template . '.twig', $params);
    }
}