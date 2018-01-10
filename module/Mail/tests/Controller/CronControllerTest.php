<?php
namespace ApplicationTest\Controller;

use Mail\Controller\CronController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CronControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testIndexActionCannotBeAccessedWithoutPublicKey()
    {
        $this->dispatch('/mailing');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Mail');
        $this->assertControllerName(CronController::class);
        $this->assertControllerClass('CronController');
        $this->assertMatchedRouteName('mailing');
    }

    public function testIndexActionCanBeAccessed()
    {
        $publicKey = $this->getApplicationServiceLocator()->get('Config')['monitoring']['public_key'];

        $this->dispatch('/mailing', 'GET', ['key' => $publicKey]);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Mail');
        $this->assertControllerName(CronController::class);
        $this->assertControllerClass('CronController');
        $this->assertMatchedRouteName('mailing');
    }
}