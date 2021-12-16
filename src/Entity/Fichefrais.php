<?php

namespace App\Entity;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fichefrais
 *
 * @ORM\Table(name="FicheFrais", uniqueConstraints={@ORM\UniqueConstraint(name="idFicheFrais", columns={"idFicheFrais"})}, indexes={@ORM\Index(name="idEtat", columns={"idEtat"}), @ORM\Index(name="idVisiteur", columns={"idVisiteur"})})
 * @ORM\Entity(repositoryClass="App\Repository\FichefraisRepository")
 */
class Fichefrais
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="number", type="integer", length=2, nullable=false, options={"fixed"=true})
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="idFicheFrais", type="string", length=12, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfichefrais;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbJustificatifs", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $nbjustificatifs = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="montantValide", type="decimal", precision=10, scale=2, nullable=true, options={"default"="NULL"})
     */
    private $montantvalide = 'NULL';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateModif", type="date", nullable=true, options={"default"="NULL"})
     */
    private $datemodif = 'NULL';

    /**
     * @var \Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEtat", referencedColumnName="idEtat")
     * })
     */
    private $idetat;

    /**
     * @var \Visiteur
     *
     * @ORM\ManyToOne(targetEntity="Visiteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVisiteur", referencedColumnName="idVisiteur")
     * })
     */
    private $idvisiteur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idfraisforfait = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    /** TO STRING FOR ID */
    public function __toString()
    {
        return (string) $this->getIdfichefrais();
    }

    public function getIdfichefrais(): ?string
    {
        return $this->idfichefrais;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNbjustificatifs(): ?int
    {
        return $this->nbjustificatifs;
    }

    public function setNbjustificatifs(?int $nbjustificatifs): self
    {
        $this->nbjustificatifs = $nbjustificatifs;

        return $this;
    }

    public function getMontantvalide(): ?string
    {
        return $this->montantvalide;
    }

    public function setMontantvalide(?string $montantvalide): self
    {
        $this->montantvalide = $montantvalide;

        return $this;
    }

    public function getDatemodif(): ?\DateTimeInterface
    {
        return $this->datemodif;
    }

    public function setDatemodif(?\DateTimeInterface $datemodif): self
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    public function getIdetat(): ?Etat
    {
        return $this->idetat;
    }

    public function setIdetat(?Etat $idetat): self
    {
        $this->idetat = $idetat;

        return $this;
    }

    public function getIdvisiteur(): ?Visiteur
    {
        return $this->idvisiteur;
    }

    public function setIdvisiteur(?Visiteur $idvisiteur): self
    {
        $this->idvisiteur = $idvisiteur;

        return $this;
    }

    /**
     * @return Collection|Fraisforfait[]
     */
    public function getIdfraisforfait(): Collection
    {
        return $this->idfraisforfait;
    }

    public function addIdfraisforfait(Fraisforfait $idfraisforfait): self
    {
        if (!$this->idfraisforfait->contains($idfraisforfait)) {
            $this->idfraisforfait[] = $idfraisforfait;
        }

        return $this;
    }

    public function removeIdfraisforfait(Fraisforfait $idfraisforfait): self
    {
        $this->idfraisforfait->removeElement($idfraisforfait);

        return $this;
    }

    static function convertObjectClass($array, $final_class) {
        return unserialize(sprintf('O:%d:"%s"%s', strlen($final_class), $final_class, strstr(serialize($array), ':')
        ));
    }


}
