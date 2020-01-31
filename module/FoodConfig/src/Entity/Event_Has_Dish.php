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
 * @ORM\Table(name="event_has_dish")
 */
class Event_Has_Dish
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="event_id")
     */
    protected $event_id;

    /**
     * @ORM\Column(type="integer", name="dish_id")
     */
    protected $dish_id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $dish_id
     */
    public function setDishId($dish_id)
    {
        $this->dish_id = $dish_id;
    }

    /**
     * @return mixed
     */
    public function getDishId()
    {
        return $this->dish_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @param mixed $event_id
     */
    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
    }

}