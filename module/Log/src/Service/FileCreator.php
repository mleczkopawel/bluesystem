<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 22:43
 */

namespace Log\Service;


use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator;

/**
 * Class FileCreator
 * @package Log\Service
 */
class FileCreator {

    /**
     *
     */
    const DESC_FIRST_LINE = 'Auto generated class';

    /**
     * @var ClassGenerator
     */
    private $classGenerator;
    /**
     * @var DocBlockGenerator
     */
    private $docBlockGenerator;
    /**
     * @var FileGenerator
     */
    private $fileGenerator;


    /**
     * FileCreator constructor.
     * @param ClassGenerator $classGenerator
     * @param DocBlockGenerator $docBlockGenerator
     * @param FileGenerator $fileGenerator
     */
    public function __construct(ClassGenerator $classGenerator,
                                DocBlockGenerator $docBlockGenerator,
                                FileGenerator $fileGenerator) {
        $this->classGenerator = $classGenerator;
        $this->docBlockGenerator = $docBlockGenerator;
        $this->fileGenerator = $fileGenerator;

        $this->docBlockGenerator->setShortDescription(self::DESC_FIRST_LINE);
    }


}