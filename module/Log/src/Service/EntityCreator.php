<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 22:53
 */

namespace Log\Service;


use Doctrine\ORM\EntityManager;
use Log\Entity\MainLog;
use Zend\Code\Generator\AbstractMemberGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Reflection\ClassReflection;

/**
 * Class EntityCreator
 * @package Log\Service
 */
class EntityCreator {

    /**
     *
     */
    const SHORT_DESCRIPTION = 'Auto generated entity';
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * EntityCreator constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $name
     * @param array $properties
     */
    public function createEntity(string $name, array $properties = []) {
        shell_exec('php composer.phar doctrine-cc');
        shell_exec('php composer.phar doctrine-update');
        shell_exec('php composer.phar doctrine-cc');
        $entity = new ClassGenerator();
        $docblocks = new DocBlockGenerator();
        $docblocks->setShortDescription(self::SHORT_DESCRIPTION);
        $docblocks->setLongDescription('Created at ' . (new \DateTime())->format('d.m.Y H:i:s'));
        $docblocks->setTag([
            'name' => 'ORM\Entity',
        ]);
        $docblocks->setTag([
            'name' => 'ORM\Table(name="log_' . strtolower($this->dashesToCamelCase($name)) . '")',
        ]);

        $entity->setNamespaceName('Log\Entity')
            ->setName($this->dashesToCamelCase($name, true) . 'Log')
            ->setExtendedClass(MainLog::class)
            ->setDocblock($docblocks)
            ->addUse('Doctrine\ORM\Mapping as ORM');

        foreach ($properties as $property) {
            $this->createPropertyWithSetersAndGetters($entity, $property['name'], $property['type'], true, null);
        }

        $this->createPropertyWithSetersAndGetters($entity, 'type_name', 'default', false, strtoupper($this->dashesToCamelCase($name)));
        $this->createPropertyWithSetersAndGetters($entity, 'sort_number', 'default', false, 1);
        $this->createPropertyWithSetersAndGetters($entity, 'code_name', 'default', false, strtolower($this->dashesToCamelCase($name)));

        $file = new FileGenerator([
            'classes' => [$entity],
        ]);

        $code = $file->generate();
        shell_exec('php composer.phar doctrine-cc');
        shell_exec('php composer.phar doctrine-update');
        shell_exec('php composer.phar doctrine-cc');
        file_put_contents(__DIR__ . '/../Entity/' . $entity->getName() . '.php', $code);
        shell_exec('php composer.phar doctrine-cc');
        shell_exec('php composer.phar doctrine-update');
        shell_exec('php composer.phar doctrine-cc');
        sleep(10);
        header('Location:https://' . $_SERVER['HTTP_HOST'] . '/admin/dashboard');die;
    }

    /**
     * @param string $name
     * @param array $properties
     * @throws \Doctrine\DBAL\DBALException
     * @throws \ReflectionException
     */
    public function rebuildEntity(string $name, array $properties = []) {
        shell_exec('php composer.phar doctrine-cc');
        shell_exec('php composer.phar doctrine-update');
        shell_exec('php composer.phar doctrine-cc');
        $props = null;
        foreach ($properties as $property) {
            $props .= ' ' . $property['name'];
            switch ($property['type']) {
                case 'string': $props .= ' VARCHAR(255)'; break;
            }
            $props .= ' NOT NULL,';
        }

        $stmt = 'CREATE TABLE log_' . strtolower($this->dashesToCamelCase($name)) .
            ' (id INT NOT NULL, ' .
            $props .
            ' PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
            ALTER TABLE log_' . strtolower($this->dashesToCamelCase($name)) .
            ' ADD CONSTRAINT FK_' . rand(1000, 999999999) . ' FOREIGN KEY (id) REFERENCES logs (id) ON DELETE CASCADE;';

        $sql = $this->entityManager->getConnection()->prepare($stmt);
        $sql->execute();
        shell_exec('php composer.phar doctrine-cc');
        shell_exec('php composer.phar doctrine-update');
        shell_exec('php composer.phar doctrine-cc');
        $this->updateMainEntity($this->dashesToCamelCase($name, true), $this->dashesToCamelCase($name . '_log', true));
        shell_exec('php composer.phar doctrine-cc');
        shell_exec('php composer.phar doctrine-update');
        shell_exec('php composer.phar doctrine-cc');
    }

    /**
     * @param ClassGenerator $entity
     * @param string $name
     * @param string $type
     * @param bool $createSetter
     * @param $defaultValue
     */
    private function createPropertyWithSetersAndGetters(ClassGenerator $entity, string $name, string $type, bool $createSetter = true, $defaultValue) {
        $prop = new PropertyGenerator();
        $prop->setName($this->dashesToCamelCase($name));
        $prop->setVisibility(AbstractMemberGenerator::VISIBILITY_PRIVATE);

        if ($type != 'default') {
            $docblock = new DocBlockGenerator();
            $docblock->setTag([
                'name' => 'var',
                'description' => $type,
            ]);

            switch ($type) {
                case 'string': {
                    $docblock->setTag([
                        'name' => 'ORM\Column(name="' . strtolower($name) . '", type="' . $type .'", length=255)',
                    ]);
                } break;
            }
            $prop->setDocBlock($docblock);
        } else {
            $prop->setDefaultValue($defaultValue);
        }
        $entity->addPropertyFromGenerator($prop);

        $get = new MethodGenerator();
        $get->setName('get' . $this->dashesToCamelCase($name, true));
        $get->setBody('return $this->' . $this->dashesToCamelCase($name) . ';');
        $get->setVisibility(AbstractMemberGenerator::VISIBILITY_PUBLIC);
        $entity->addMethodFromGenerator($get);

        if ($createSetter) {
            $set = new MethodGenerator();
            $set->setParameter($this->dashesToCamelCase($name));
            $set->setName('set' . $this->dashesToCamelCase($name, true));
            $set->setBody('$this->' . $this->dashesToCamelCase($name) . ' = $' . $this->dashesToCamelCase($name) . ';' . "\n" .'return $this;');
            $set->setVisibility(AbstractMemberGenerator::VISIBILITY_PUBLIC);
            $entity->addMethodFromGenerator($set);
        }
    }

    /**
     * @param string $name
     * @param string $entity
     * @throws \ReflectionException
     */
    private function updateMainEntity(string $name, string $entity) {
        $mainLogEntity = ClassGenerator::fromReflection(new ClassReflection(MainLog::class));
        $tags = $mainLogEntity->getDocBlock()->getTags();

        $newTags = [];
        $discriminators = null;
        /**
         * @var Tag $tag
         */
        foreach ($tags as $tag) {
            if (explode('\\', $tag->getName())) {
                if (isset(explode('\\', $tag->getName())[1])) {
                    if (explode('(', explode('\\', $tag->getName())[1])[0] == 'DiscriminatorMap') {
                        $discMap = explode('(', explode('\\', $tag->getName())[1])[1];
                        $fields = explode('}', $discMap);
                        $discriminators = '(' . $fields[0] . ',"' . strtolower($name) . '"="' . $entity . '"})';
                    } else {
                        $newTags[] = $tag;
                    }
                }
            }
        }
        $mainLogEntityDockBlock = new DocBlockGenerator();
        $mainLogEntityDockBlock->setTags($newTags);
        $mainLogEntityDockBlock->setTag([
            'name' => 'ORM\DiscriminatorMap' . $discriminators,
        ]);
        $mainLogEntity->setDocBlock($mainLogEntityDockBlock);
        $mainLogEntity->addUse('Auth\Entity\Client');
        $mainLogEntity->addUse('Auth\Entity\User');
        $mainLogEntity->addUse('Doctrine\Common\Collections\ArrayCollection');
        $mainLogEntity->addUse('Doctrine\ORM\Mapping as ORM');
        $file = new FileGenerator([
            'classes' => [$mainLogEntity],
        ]);
        $code = $file->generate();
        file_put_contents(__DIR__ . '/../Entity/MainLog.php', $code);
    }

    /**
     * @param string $string
     * @param bool $capitalizeFirstCharacter
     * @return string
     */
    private function dashesToCamelCase(string $string, bool $capitalizeFirstCharacter = false): string {
        $str = str_replace('_', '', ucwords($string, '_'));
        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }
        return $str;
    }

    /**
     * @throws \ReflectionException
     */
    public function setNoAbstract() {
        $mainLogEntity = ClassGenerator::fromReflection(new ClassReflection(MainLog::class));
        $tags = $mainLogEntity->getDocBlock()->getTags();
        foreach ($tags as $tag) {
            if ($exp = explode('\\', $tag->getName())) {
                if (isset($exp[1])) {
                    if (explode('(', $exp[1])[0] == 'DiscriminatorMap') {
                        $discMap = explode('(', explode('\\', $tag->getName())[1])[1];
                        $fields = explode('}', $discMap);
                        $arrClasses = explode(',', str_replace(['{', '"'], '', $fields[0]));
                        foreach ($arrClasses as $arrClass) {
                            $className = explode('=', $arrClass)[1];
                            $classEntity = ClassGenerator::fromReflection(new ClassReflection('Log\Entity\\' . $className));
                            $classEntity->setAbstract(false);
                            $classEntity->addUse('Doctrine\ORM\Mapping as ORM');
                            $file = new FileGenerator([
                                'classes' => [$classEntity],
                            ]);
                            $code = $file->generate();
                            file_put_contents(__DIR__ . '/../Entity/' . $className . '.php', $code);
                        }
                    } else {
                        $newTags[] = $tag;
                    }
                }
            }
        }
    }
}