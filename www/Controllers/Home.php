<?php

namespace App\Controllers;
use App\Core\View;

class Home
{

    public function home(): void
    {
        $view = new View("mainPage/home.php","front.php");
    }

}