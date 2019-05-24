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

        //<img src="wp-content/plugins/estimator/public/images/img_lights.jpg" style="width: 150px">
        $logoPath = parse_url(get_option('customer_logo'));
        $this->twig->addGlobal('ESTIMATES_PLUGIN_BUILD_PUBLIC', ESTIMATES_PLUGIN_BUILD_PUBLIC);
        $this->twig->addGlobal('ROOT_PUBLIC_PLUGIN_URL', ROOT_PUBLIC_PLUGIN_URL);
        $this->twig->addGlobal('CUSTOMER_LOGO', substr($logoPath['path'], 1));
        $this->twig->addGlobal('CUSTOMER_NAME', get_option('customer_name'));
        $this->twig->addGlobal('CUSTOMER_EMAIL', get_option('customer_email'));
        $this->twig->addGlobal('CUSTOMER_PHONE', get_option('customer_phone'));
        $this->twig->addGlobal('ESTIMATE_BOTTOM_COPY', get_option('estimate_bottom_copy'));
    }

}
