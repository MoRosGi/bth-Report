<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();

        /**
         * @var string $timezone
         */
        $timezone = $this->getContainer()->getParameter('timezone');

        date_default_timezone_set($timezone);
    }
}
