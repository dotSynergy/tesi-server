<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use \Datetime;

/**
 * @ORM\Entity
 * @ORM\Table(name="sensors_data")
 * 
 */

class SensorsData
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    public DateTime $ts;
    /**
     * @var float|null
     * @ORM\Column(type="string",nullable=true)
     */
    public float $lat;
    /**
     * @var float|null
     * @ORM\Column(type="float",nullable=true)
     */
    public float $lng;
    /**
     * @var float|null
     * @ORM\Column(type="float",nullable=true)
     */
    public float $alt;
    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    public float $tmp;
    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    public float $e_tmp;
    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    public float $hum;
    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    public float $e_hum;
    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    public int $light;
    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    public int $ppm;

    public function __construct()
    {
        $this->ts = new DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

}