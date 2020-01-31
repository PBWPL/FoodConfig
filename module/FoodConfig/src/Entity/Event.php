<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-27
 * Time: 12:27
 */

namespace FoodConfig\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Dish", inversedBy="event")
     * @ORM\JoinTable(name="event_has_dish",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="dish_id", referencedColumnName="id")}
     *      )
     **/
    protected $dish;

    public function __construct()
    {
        $this->dish = new ArrayCollection();
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
    public function getId()
    {
        return $this->id;
    }

}