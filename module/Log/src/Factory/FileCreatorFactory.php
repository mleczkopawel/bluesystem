<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 22:53
 */

namespace Log\Factory;


use Interop\Container\ContainerInterface;
use Log\Service\FileCreator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FileCreatorFactory
 * @package Log\Factory
 */
class FileCreatorFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FileCreator|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $classGenerator = new ClassGenerator();
        $docBlockGenerator = new DocBlockGenerator();
        $fileGenerator = new FileGenerator();
        return new FileCreator(
            $classGenerator,
            $docBlockGenerator,
            $fileGenerator
        );
    }
}