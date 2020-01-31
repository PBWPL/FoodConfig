<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-05-21
 * Time: 13:32
 */

namespace FoodConfig\Controller;

use FoodConfig\Entity\Diet_Has_Dish;
use FoodConfig\Entity\Dish;
use FoodConfig\Entity\Ingredient;
use FoodConfig\Entity\Dish_Has_Ingredient;
use FoodConfig\Entity\Event_Has_Dish;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class DishController extends AbstractActionController
{

    protected $aclConfig;
    protected $entityManager;
    protected $authManager;
    function __construct($aclConfig, $entityManager, $authManager) {
        $this->aclConfig = $aclConfig;
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
    }

    public function CreateAction()
    {
        $em = $this->entityManager;
        $user = $this->authManager->getUser();

        $diets = $em->getRepository("FoodConfig\Entity\Diet")->findAll();
        $difficulties = $em->getRepository("FoodConfig\Entity\Difficulty")->findAll();
        $types = $em->getRepository("FoodConfig\Entity\Type")->findAll();
        $events = $em->getRepository("FoodConfig\Entity\Event")->findAll();
        $cuisines = $em->getRepository("FoodConfig\Entity\Cuisine")->findAll();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            $dish = new Dish();
            $dish->setName($data['name']);

            $ext = '.' . strtolower(pathinfo($this->params()->fromFiles('dishpic')['name'], PATHINFO_EXTENSION));
            $name = date("Y_m_d") . "_" . rand(10000, 99999) . $ext;
            $path = '/img/dish/' . $name;
            if (strpos($name, '.jpg') OR strpos($name, '.png')) {
                move_uploaded_file($this->params()->fromFiles('dishpic')['tmp_name'], PUBLIC_PATH . $path);
            }
            $dish->setPicture($name);

            $dish->setContent($data['content']);
            $dish->setShort($data['short']);

            $dish->setQuantity($data['quantity']);
            $dish->setPreparationTime($data['preparationtime']);
            $date = date("Y-m-d H:i:s");
            $dish->setCreateTime($date);

            $cuisine = $em->getRepository("FoodConfig\Entity\Cuisine")->findOneBy(['id' => $data['cuisine']]);
            $type = $em->getRepository("FoodConfig\Entity\Type")->findOneBy(['id' => $data['type']]);
            $difficulty = $em->getRepository("FoodConfig\Entity\Difficulty")->findOneBy(['id' => $data['difficulty']]);
            

            $dish->setCuisine($cuisine);
            $dish->setType($type);
            $dish->setDifficulty($difficulty);
            

            $this->entityManager->persist($dish);
            $this->entityManager->flush();

            foreach ($data['event'] as $e) {
                $event = new Event_Has_Dish();
                
                $_event = $em->getRepository("FoodConfig\Entity\Event")->findOneBy(['id' => $e]);
                
                $event->setEventId($_event->getId());
                $event->setDishId($dish->getId());
                $this->entityManager->persist($event);
                $this->entityManager->flush();
            }

            foreach ($data['diet'] as $d) {
                $diet = new Diet_Has_Dish();

                $_diet = $em->getRepository("FoodConfig\Entity\Diet")->findOneBy(['id' => $d]);
                
                $diet->setDietId($_diet->getId());
                $diet->setDishId($dish->getId());
                $this->entityManager->persist($diet);
                $this->entityManager->flush();
            }

           

            foreach ($data['ingredient'] as $k => $i) {
                $count = (int) $data['count'][$k];
                if(empty($i)) continue; 
                if($data['count'][$k] <= 0) continue;

                $_ingredient = $em->getRepository("FoodConfig\Entity\Ingredient")->findOneBy(['name' => $i, 'count' => $count]);
                if(!$_ingredient) 
                {
                    $_ingredient = new Ingredient();
                    $_ingredient->setName($i);
                    $_ingredient->setCount($count);

                    $this->entityManager->persist($_ingredient);
                    $this->entityManager->flush();
                }

                $ingredient = new Dish_Has_Ingredient();
                $ingredient->setIngredientId($_ingredient->getId());
                $ingredient->setDishId($dish->getId());
                $this->entityManager->persist($ingredient);
                $this->entityManager->flush();
            }
            $this->flashMessenger()->addSuccessMessage('Danie zostało dodane');
            return $this->redirect()->toRoute('home');
        }

        return new ViewModel([
            'diets' => $diets,
            'difficulties' => $difficulties,
            'types' => $types,
            'events' => $events,
            'cuisines' => $cuisines
            ]);

    }

    public function RemoveAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {

            try {
                $pid = $this->params()->fromRoute('id', -1);

                $dish = $this->entityManager->getRepository(Dish::class)->findOneBy([
                    'id' => $pid
                ]);


                $diet_has_dish = $this->entityManager->getRepository(Diet_Has_Dish::class)->findBy([
                    'dish_id' => $pid
                ]);

                $event_has_dish = $this->entityManager->getRepository(Event_Has_Dish::class)->findBy([
                    'dish_id' => $pid
                ]);

                foreach($diet_has_dish as $item) {
                    $this->entityManager->remove($item); 
                }

                foreach($event_has_dish as $item) {
                    $this->entityManager->remove($item); 
                }

                $this->entityManager->remove($dish);
                $this->entityManager->flush();

                return new JsonModel([
                    'status' => 'SUCCESS'
                ]);
            } catch(Exception $e) {
                return new JsonModel([
                    'status' => 'ERROR',
                    'message' => ''
                ]);
            }

        }
        else
        {
            return new JsonModel([
                'status' => 'ERROR',
                'message' => 'Incorrect request'
            ]);
        }
    }
}