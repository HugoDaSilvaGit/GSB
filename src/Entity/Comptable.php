<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comptable
 *
 * @ORM\Table(name="Comptable")
 * @ORM\Entity
 */
class Comptable
{
    /**
     * @var string
     *
     * @ORM\Column(name="idComptable", type="string", length=3, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcomptable;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=true, options={"default"="NULL","fixed"=true})
     */
    private $nom = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=true, options={"default"="NULL","fixed"=true})
     */
    private $prenom = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="login", type="string", length=20, nullable=true, options={"default"="NULL","fixed"=true})
     */
    private $login = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="mdp", type="string", length=20, nullable=true, options={"default"="NULL","fixed"=true})
     */
    private $mdp = 'NULL';

    public function getIdcomptable(): ?string
    {
        return $this->idcomptable;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }


}
