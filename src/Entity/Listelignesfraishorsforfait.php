<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Traversable;

class Listelignesfraishorsforfait
{
    public function __construct()
    {
    }

    public function addLignefraishorsforfait(Lignefraishorsforfait  $Lignefraishorsforfait)
    {
        $lignefraishorsforfait=$Lignefraishorsforfait->getIdlignefraishorsforfait();
        $this->$lignefraishorsforfait = $Lignefraishorsforfait;
    }

    public function getLignefraishorsforfait(): Lignefraishorsforfait
    {
        return $this->Lignefraishorforfait;
    }

    public function setLignefraishorsforfait(Lignefraishorsforfait $Lignefraishorforfait): void
    {
        $this->Lignefraishorforfait = $Lignefraishorforfait;
    }

    public function getcount()
    {
        $nblignesfraishorsforfait=count((array)$this);
        dump($nblignesfraishorsforfait=$nblignesfraishorsforfait-1);
        return $nblignesfraishorsforfait;
    }

    /*public function getTotal(): int
    {
        foreach($this as $unelignefraisforfait){
            $somme = $unelignefraisforfait->getMontant();
            $montant = $montant + $somme;
        }
        $unelignefraisforfait=$this->convertObjectClass(
            array_merge((array)$this, (array)$montant
            ),'App\Entity\Lignefraishorsforfait');
        dump($unelignefraisforfait);
        return $unelignefraisforfait;
    }*/
}
