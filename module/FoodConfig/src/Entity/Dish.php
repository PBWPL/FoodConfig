<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-25
 * Time: 23:10
 */

namespace FoodConfig\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="dish")
 */
class Dish
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="name", unique=false, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", name="picture", unique=true, nullable=false)
     */
    protected $picture;

    /**
     * @ORM\Column(type="text", name="content")
     */
    protected $content;

    /**
     * @ORM\Column(type="text", name="short")
     */
    protected $short;

    /**
     * @ORM\Column(type="integer", name="quantity")
     */
    protected $quantity;

    /**
     * @ORM\Column(type="integer", name="preparation_time")
     */
    protected $preparation_time;

    /**
     * @ORM\Column(name="create_time")
     */
    protected $create_time;

    /**
     * @ORM\Column(type="integer", name="cuisine_id")
     */
    protected $cuisine_id;

    /**
     * @ORM\Column(type="integer", name="type_id")
     */
    protected $type_id;

    /**
     * @ORM\Column(type="integer", name="difficulty_id")
     */
    protected $difficulty_id;

    /**
     * @ORM\ManyToOne(targetEntity="Cuisine")
     * @ORM\JoinColumn(name="cuisine_id", referencedColumnName="id")
     */
    protected $cuisine;

    /**
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Difficulty")
     * @ORM\JoinColumn(name="difficulty_id", referencedColumnName="id")
     */
    protected $difficulty;

    /**
     * @ORM\ManyToMany(targetEntity="Ingredient", inversedBy="dish")
     * @ORM\JoinTable(name="dish_has_ingredient",
     *      joinColumns={@ORM\JoinColumn(name="dish_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ingredient_id", referencedColumnName="id")}
     *      )
     **/
    protected $ingredient;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="dish")
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="dish")
     */
    protected $event;

    /**
     * @ORM\ManyToMany(targetEntity="Diet", mappedBy="dish")
     */
    protected $diet;

    public function __construct() {
        $this->ingredient = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->event = new ArrayCollection();
        $this->diet = new ArrayCollection();
        //$this->cuisine = new ArrayCollection();
        //$this->type = new ArrayCollection();
        //$this->difficulty = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param mixed $short
     */
    public function setShort($short)
    {
        $this->short = $short;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getPreparationTime()
    {
        if ($this->preparation_time == NULL) {
            return "0:30";
        }
        return sprintf("%d:%02d", floor($this->preparation_time / 60), $this->preparation_time % 60);
    }

    /**
     * @param mixed $preparation_time
     */
    public function setPreparationTime($preparation_time)
    {
        $this->preparation_time = $preparation_time;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * @param mixed $create_time
     */
    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;
    }

    /**
     * @return mixed
     */
    public function getCuisineId()
    {
        return $this->cuisine_id;
    }

    /**
     * @param mixed $cuisine_id
     */
    public function setCuisineId($cuisine_id)
    {
        $this->cuisine_id = $cuisine_id;
    }

    /**
     * @param mixed $type_id
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * @return mixed
     */
    public function getDifficultyId()
    {
        return $this->difficulty_id;
    }

    /**
     * @param mixed $difficulty_id
     */
    public function setDifficultyId($difficulty_id)
    {
        $this->difficulty_id = $difficulty_id;
    }

    /**
     * @return mixed
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @return mixed
     */
    public function getDiet()
    {
        return $this->diet;
    }

    /**
     * @return mixed
     */
    public function setDiet($diet)
    {
        return $this->diet = $diet;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getCuisine()
    {
        return $this->cuisine;
    }

    /**
     * @return mixed
     */
    public function setCuisine($cuisine)
    {
        return $this->cuisine = $cuisine;
    }

    /**
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @return mixed
     */
    public function setDifficulty($difficulty)
    {
        return $this->difficulty = $difficulty;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return mixed
     */
    public function setEvent($event)
    {
        return $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function setType($type)
    {
        return $this->type = $type;
    }

}