<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-27
 * Time: 12:30
 */

namespace FoodConfig\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_enjoy_dish")
 */
class User_Enjoy_Dish
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id = null;

    /**
     * @ORM\Column(name="user_id")
     */
    protected $user_id;

    /**
     * @ORM\Column(name="dish_id")
     */
    protected $dish_id;

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param mixed $dish_id
     */
    public function setDishId($dish_id)
    {
        $this->dish_id = $dish_id;
    }
}