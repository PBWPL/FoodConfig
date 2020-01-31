<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-27
 * Time: 12:26
 */

namespace FoodConfig\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="diet_has_dish")
 */
class Diet_Has_Dish
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="diet_id")
     */
    protected $diet_id;

    /**
     * @ORM\Column(type="integer", name="dish_id")
     */
    protected $dish_id;

    /**
     * @return mixed
     */
    public function getDishId()
    {
        return $this->dish_id;
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
    public function getDietId()
    {
        return $this->diet_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $dish_id
     */
    public function setDishId($dish_id)
    {
        $this->dish_id = $dish_id;
    }

    /**
     * @param mixed $diet_id
     */
    public function setDietId($diet_id)
    {
        $this->diet_id = $diet_id;
    }
}