/**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fraisforfait", inversedBy="idfichefrais")
     * @ORM\JoinTable(name="lignefraisforfait",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idFicheFrais", referencedColumnName="idFicheFrais")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idFraisForfait", referencedColumnName="idFraisForfait")
     *   }
     * )
     */
    private $idfraisforfait;