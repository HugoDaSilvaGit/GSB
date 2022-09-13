<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cvvehicule
 *
 * @ORM\Table(name="Cvvehicule")
 * @ORM\Entity(repositoryClass="App\Repository\CvvehiculeRepository")
 */
class Cvvehicule
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcv", type="integer", length=3, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcv;

    /**
     * @var int
     *
     * @ORM\Column(name="cv", type="integer", length=1, nullable=false, options={"fixed"=true})
     */
    private $cv;

    /**
     * @var int
     *
     * @ORM\Column(name="distancemaxcv", type="integer", length=6, nullable=false, options={"fixed"=true})
     */
    private $distancemaxcv;

    /**
     * @var int
     *
     * @ORM\Column(name="facteurcv", type="decimal", precision=10, scale=3, nullable=false, options={"fixed"=true})
     */
    private $facteurcv;

    /**
     * @var int
     *
     * @ORM\Column(name="constantecv", type="integer", length=4, nullable=false, options={"default"="0"})
     */
    private $constantecv= 0;

    public function __toString()
    {
        return (int) $this->getIdcv();
    }

    /**
     * @return int
     */
    public function getIdcv(): int
    {
        return $this->idcv;
    }

    /**
     * @param int $idcv
     */
    public function setIdcv(int $idcv): void
    {
        $this->idcv = $idcv;
    }

    /**
     * @return int
     */
    public function getCv(): int
    {
        return $this->cv;
    }

    /**
     * @param int $cv
     */
    public function setCv(int $cv): void
    {
        $this->cv = $cv;
    }

    /**
     * @return int
     */
    public function getDistancemaxcv(): int
    {
        return $this->distancemaxcv;
    }

    /**
     * @param int $distancemaxcv
     */
    public function setDistancemaxcv(int $distancemaxcv): void
    {
        $this->distancemaxcv = $distancemaxcv;
    }

    /**
     * @return int
     */
    public function getFacteurcv(): string
    {
        return $this->facteurcv;
    }

    /**
     * @param int $facteurcv
     */
    public function setFacteurcv(int $facteurcv): void
    {
        $this->facteurcv = $facteurcv;
    }

    /**
     * @return int
     */
    public function getConstantecv(): int
    {
        return $this->constantecv;
    }

    /**
     * @param int $constantecv
     */
    public function setConstantecv(int $constantecv): void
    {
        $this->constantecv = $constantecv;
    }


}
