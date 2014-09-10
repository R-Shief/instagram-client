<?php

namespace Bangpound\Bundle\UserBundle;

use Bangpound\Bundle\UserBundle\DependencyInjection\Compiler\AuthMapProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BangpoundUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AuthMapProviderCompilerPass());
    }
}
