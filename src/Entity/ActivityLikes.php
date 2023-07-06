<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActivityLikes
 *
 * @ORM\Table(name="activity_likes", indexes={@ORM\Index(name="activity_fk", columns={"activity_id"}), @ORM\Index(name="user_fk", columns={"user_id"})})
 * @ORM\Entity
 */
class ActivityLikes
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
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="idUser")
     * })
     */
    private $user;

    /**
     * @var \Activities
     *
     * @ORM\ManyToOne(targetEntity="Activities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_id", referencedColumnName="id_activity")
     * })
     */
    private $activity;

    public function getIdLike(): ?int
    {
        return $this->idLike;
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

    public function getActivity(): ?Activities
    {
        return $this->activity;
    }

    public function setActivity(?Activities $activity): self
    {
        $this->activity = $activity;

        return $this;
    }


}
