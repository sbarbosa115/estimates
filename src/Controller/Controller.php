<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    public $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(ESTIMATES_PLUGIN_DIR . 'templates');
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);
        $this->twig->addGlobal('ESTIMATES_PLUGIN_BUILD_PUBLIC', ESTIMATES_PLUGIN_BUILD_PUBLIC);
        $this->twig->addGlobal('ESTIMATES_PLUGIN_DIR_PUBLIC', ESTIMATES_PLUGIN_DIR_PUBLIC);
    }

}
