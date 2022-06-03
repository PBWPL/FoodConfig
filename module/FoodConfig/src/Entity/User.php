<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:34
 */

namespace FoodConfig\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FoodConfig\Entity\Dish;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{

    const ACTIVE = 1;

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="level")
     */
    protected $level;

    /**
     * @ORM\Column(type="integer", name="active", unique=false, nullable=true)
     */
    protected $active = 1;

    /**
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="surname")
     */
    protected $surname;


    /**
     * @ORM\Column(name="usershow", nullable=false)
     */
    protected $usershow;

    /**
     * @ORM\Column(type="string", name="avatar", unique=true, nullable=false)
     */
    protected $avatar = 'default.png';

    /**
     * @ORM\Column(name="create_time")
     */
    protected $create_time;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="level", referencedColumnName="id")
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="Dish", inversedBy="user")
     * @ORM\JoinTable(name="user_enjoy_dish",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="dish_id", referencedColumnName="id")}
     *      )
     */
    protected $dish;

    private $userDishes;

    function __construct() {
        $this->role = new ArrayCollection();
        $this->dish = new ArrayCollection();
    }

    public function checkIfUserLikedDish($dishID) {
        if ($this->getUserDishes() == null) {
            return false;
        }
        return in_array($dishID, array_keys($this->getUserDishes()));
    }

    /**
     * @return mixed
     */
    public function getUserDishes()
    {
        foreach($this->getDish() as $dish) {
            $this->userDishes[$dish->getId()] = $dish;
        }

        return $this->userDishes;
    }

    /**
     * @param mixed $userDishes
     */
    public function setUserDishes($userDishes)
    {
        $this->userDishes = $userDishes;
    }

    /**
     * @return mixed
     */
    public function getDish()
    {
        return $this->dish;
    }

    /**
     * @param mixed $dish
     */
    public function setDish($dish)
    {
        $this->dish = $dish;
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
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
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getUsershow()
    {
        return $this->usershow;
    }

    /**
     * @param mixed $usershow
     */
    public function setUsershow($usershow)
    {
        $this->usershow = $usershow;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

}