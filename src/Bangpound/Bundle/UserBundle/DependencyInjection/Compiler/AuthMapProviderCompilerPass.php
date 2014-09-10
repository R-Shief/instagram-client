<?php
/**
 * Created by PhpStorm.
 * User: bjd
 * Date: 9/2/14
 * Time: 11:44 PM
 */

namespace Bangpound\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AuthMapProviderCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('hwi_oauth.user.provider.fosub_bridge');
        $definition->addMethodCall('setRepository', array(new Reference('authmap_repository')));
        $definition->addMethodCall('setObjectManager', array(new Reference('fos_user.entity_manager')));
    }
}
