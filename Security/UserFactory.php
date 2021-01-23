<?php


namespace SuperAdmin\Bundle\Security;


use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class UserFactory
 * @package SuperAdmin\Bundle\Security
 */
final class UserFactory implements UserProviderFactoryInterface
{

    public function create(ContainerBuilder $container, $id, $config)
    {
        // TODO: Implement create() method.
    }

    public function getKey() {
        return 'superadmin';
    }

    public function addConfiguration(NodeDefinition $builder) {
        $builder
            ->children()
                ->scalarNode('class')
                    ->cannotBeEmpty()
                    ->defaultValue(User::class)
                    ->validate()
                        ->ifTrue(function ($class) {
                            return !(new \ReflectionClass($class))->implementsInterface(JWTUserInterface::class);
                        })
                        ->thenInvalid('The %s class must implement '.JWTUserInterface::class.' for using the "lexik_jwt" user provider.')
                    ->end()
                ->end()
            ->end()
        ;
    }
}