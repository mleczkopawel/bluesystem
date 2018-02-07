<?php

namespace Log\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Auto generated entity
 *
 * Created at 06.02.2018 23:57:07
 *
 * @ORM\Entity
 * @ORM\Table(name="log_apiapiapi1")
 */
class ApiApiApi1Log extends MainLog
{

    /**
     * @var string
     * @ORM\Column(name="naamee12", type="string", length=255)
     */
    private $naamee12 = null;

    /**
     * @var string
     * @ORM\Column(name="teksty", type="string", length=255)
     */
    private $teksty = null;

    private $typeName = 'APIAPIAPI1';

    private $sortNumber = 1;

    private $codeName = 'apiapiapi1';

    public function getNaamee12()
    {
        return $this->naamee12;
    }

    public function setNaamee12($naamee12)
    {
        $this->naamee12 = $naamee12;
        return $this;
    }

    public function getTeksty()
    {
        return $this->teksty;
    }

    public function setTeksty($teksty)
    {
        $this->teksty = $teksty;
        return $this;
    }

    public function getTypeName()
    {
        return $this->typeName;
    }

    public function getSortNumber()
    {
        return $this->sortNumber;
    }

    public function getCodeName()
    {
        return $this->codeName;
    }


}

