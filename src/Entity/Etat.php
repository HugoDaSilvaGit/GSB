<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etat
 *
 * @ORM\Table(name="Etat")
 * @ORM\Entity(repositoryClass="App\Repository\EtatRepository")
 */
class Etat
{
    /**
     * @var string
     *
     * @ORM\Column(name="idEtat", type="string", length=2, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idetat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelleEtat", type="string", length=30, nullable=true, options={"default"="NULL"})
     */
    private $libelleetat = 'NULL';

    /** TO STRING FOR ID */
    public function __toString()
    {
        return (string) $this->getIdetat();
    }

    public function getIdetat(): ?string
    {
        return $this->idetat;
    }

    public function getLibelleetat(): ?string
    {
        return $this->libelleetat;
    }

    public function setLibelleetat(?string $libelleetat): self
    {
        $this->libelleetat = $libelleetat;

        return $this;
    }


}
