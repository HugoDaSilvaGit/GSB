<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fraisforfait
 *
 * @ORM\Table(name="FraisForfait")
 * @ORM\Entity(repositoryClass="App\Repository\FraisforfaitRepository")
 */
class Fraisforfait
{
    /**
     * @var string
     *
     * @ORM\Column(name="idFraisForfait", type="string", length=3, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfraisforfait;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelleFraisForfait", type="string", length=20, nullable=true, options={"default"="NULL","fixed"=true})
     */
    private $libellefraisforfait = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="montantFraisForfait", type="decimal", precision=5, scale=2, nullable=true, options={"default"="NULL"})
     */
    private $montantfraisforfait = 'NULL';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fichefrais", mappedBy="idfraisforfait")
     */
    private $idfichefrais;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idfichefrais = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdfraisforfait(): ?string
    {
        return $this->idfraisforfait;
    }

    public function getLibellefraisforfait(): ?string
    {
        return $this->libellefraisforfait;
    }

    public function setLibellefraisforfait(?string $libellefraisforfait): self
    {
        $this->libellefraisforfait = $libellefraisforfait;

        return $this;
    }

    public function getMontantfraisforfait(): ?string
    {
        return $this->montantfraisforfait;
    }

    public function setMontantfraisforfait(?string $montantfraisforfait): self
    {
        $this->montantfraisforfait = $montantfraisforfait;

        return $this;
    }

    /**
     * @return Collection|Fichefrais[]
     */
    public function getIdfichefrais(): Collection
    {
        return $this->idfichefrais;
    }

    public function addIdfichefrai(Fichefrais $idfichefrai): self
    {
        if (!$this->idfichefrais->contains($idfichefrai)) {
            $this->idfichefrais[] = $idfichefrai;
            $idfichefrai->addIdfraisforfait($this);
        }

        return $this;
    }

    public function removeIdfichefrai(Fichefrais $idfichefrai): self
    {
        if ($this->idfichefrais->removeElement($idfichefrai)) {
            $idfichefrai->removeIdfraisforfait($this);
        }

        return $this;
    }

}
