<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-27
 * Time: 12:28
 */

namespace FoodConfig\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="ingredient")
 */
class Ingredient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", name="name")
     */
    protected $name;

    /**
     * @ORM\Column(type="text", name="count")
     */
    protected $count;

    /**
     * @ORM\ManyToMany(targetEntity="Dish", mappedBy="ingredient")
     */
    private $dish;

    public function __construct()
    {
        $this->dish = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDish()
    {
        return $this->dish;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return mixed
     */
    public function setName($name)
    {
        return $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function setCount($count)
    {
        return $this->count = $count;
    }

}