<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Embeddable
 */
class Address
{
    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(
     *      max = 120,
     *      min = 3
     * )
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(
     *      max = 120,
     *      min = 3
     * )
     *
     * @var string
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(
     *      max = 120,
     *      min = 3
     * )
     *
     * @var string
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(
     *      max = 120,
     *      min = 3
     * )
     *
     * @var string
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(
     *      max = 10,
     *      min = 4
     * )
     *
     * @var string
     */
    private $postalCode;

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry(string $country): User
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Set state.
     *
     * @param string $state
     *
     * @return User
     */
    public function setState(string $state): User
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity(string $city): User
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set street.
     *
     * @param string $street
     *
     * @return User
     */
    public function setStreet(string $street): User
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Set postalCode.
     *
     * @param string $postalCode
     *
     * @return User
     */
    public function setPostalCode(string $postalCode): User
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }
}
