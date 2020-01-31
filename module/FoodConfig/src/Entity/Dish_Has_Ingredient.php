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
 * @ORM\Table(name="dish_has_ingredient")
 */
class Dish_Has_Ingredient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="dish_id")
     */
    protected $dish_id;

    /**
     * @ORM\Column(type="integer", name="ingredient_id")
     */
    protected $ingredient_id;

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
    public function getDishId()
    {
        return $this->dish_id;
    }

    /**
     * @return mixed
     */
    public function getIngredientId()
    {
        return $this->ingredient_id;
    }

    /**
     * @param mixed $dish_id
     */
    public function setDishId($dish_id)
    {
        $this->dish_id = $dish_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $ingredient_id
     */
    public function setIngredientId($ingredient_id)
    {
        $this->ingredient_id = $ingredient_id;
    }
}