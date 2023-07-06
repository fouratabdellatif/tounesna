<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RateHotel
 *
 * @ORM\Table(name="rate_hotel", indexes={@ORM\Index(name="id_hotel", columns={"id_hotel"}), @ORM\Index(name="id_rate", columns={"id_rate"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class RateHotel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_rate", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRate;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var float|null
     *
     * @ORM\Column(name="rate", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=5)
     */
    private $rate = NULL;

    /**
     * @var \Hotel
     *
     * @ORM\ManyToOne(targetEntity="Hotel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_hotel", referencedColumnName="idh")
     * })
     */
    private $idHotel;

    public function getIdRate(): ?int
    {
        return $this->idRate;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIdHotel(): ?Hotel
    {
        return $this->idHotel;
    }

    public function setIdHotel(?Hotel $idHotel): self
    {
        $this->idHotel = $idHotel;

        return $this;
    }


}
