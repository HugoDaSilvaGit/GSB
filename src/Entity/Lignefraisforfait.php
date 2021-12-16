<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneFraisForfait
 *
 * @ORM\Table(name="LigneFraisForfait", indexes={@ORM\Index(name="idFicheFrais", columns={"idFicheFrais"})})
 * @ORM\Entity(repositoryClass="App\Repository\LignefraisforfaitRepository")
 */
class Lignefraisforfait
{

    /**
     * @var string|null
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $quantite = 'NULL';

    /**
     * @var \Fichefrais
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Fichefrais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idFicheFrais", referencedColumnName="idFicheFrais")
     * })
     */
    private $idfichefrais;

     /**
     * @var \Fraisforfait
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FraisForfait")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idFraisForfait", referencedColumnName="idFraisForfait")
     * })
     */
    private $idfraisforfait;

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdfichefrais(): ?Fichefrais
    {
        return $this->idfichefrais;
    }

    public function setIdfichefrais(?Fichefrais $idfichefrais): self
    {
        $this->idfichefrais = $idfichefrais;

        return $this;
    }

    public function getIdfraisforfait(): ?Fraisforfait
    {
        return $this->idfraisforfait;
    }

    public function setIdfraisforfait(?Fraisforfait $idfraisforfait): self
    {
        $this->idfraisforfait = $idfraisforfait;

        return $this;
    }

    static function convertObjectClass($array, $final_class) {
        return unserialize(sprintf('O:%d:"%s"%s', strlen($final_class), $final_class, strstr(serialize($array), ':')
        ));
    }


}
