<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 21:32
 */

namespace Admin\Navigation;


use Auth\Entity\Client;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Class SystemNavigation
 * @package Admin\Navigation
 */
class SystemNavigation extends DefaultNavigationFactory {

    /**
     * @var array
     */
    private $pagesFromConfig;

    /**
     * SystemNavigation constructor.
     * @param array $config
     */
    public function __construct(array $config) {
        $this->pagesFromConfig = $config;
    }

    /**
     * @param ContainerInterface $container
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getPages(ContainerInterface $container) {
        $entityManager = $container->get(EntityManager::class);
        if (null === $this->pages) {
            $clients = $entityManager->getRepository(Client::class)->findAll();
            $configuration = $this->pagesFromConfig;

            foreach ($clients as $client) {
                $configuration['navigation'][$this->getName()][1]['pages'][$client->getName()] = [
                    'label' => $client->getName(),
                    'route' => 'apps',
                    'action' => 'show',
                    'id' => $client->getId(),
                ];
            }

            $application = $container->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }

        return $this->pages;
    }

}