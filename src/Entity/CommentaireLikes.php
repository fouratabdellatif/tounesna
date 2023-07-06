<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentaireLikes
 *
 * @ORM\Table(name="commentaire_likes", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="com_id", columns={"com_id"})})
 * @ORM\Entity
 */
class CommentaireLikes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_like", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLike;

    /**
     * @var \Commentaire
     *
     * @ORM\ManyToOne(targetEntity="Commentaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="com_id", referencedColumnName="idcom")
     * })
     */
    private $com;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="idUser")
     * })
     */
    private $user;

    public function getIdLike(): ?int
    {
        return $this->idLike;
    }

    public function getCom(): ?Commentaire
    {
        return $this->com;
    }

    public function setCom(?Commentaire $com): self
    {
        $this->com = $com;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }


}
