<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-04
 * Time: 23:34
 */

namespace FoodConfig\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use FoodConfig\Entity\Dish;
use FoodConfig\Entity\User;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;
use FoodConfig\Form\ContactForm;
use Laminas\View\Model\JsonModel;

class GuestController extends AbstractActionController
{

    protected $recapchaKey;
    protected $mailService;
    protected $aclConfig;
    protected $entityManager;
    protected $authManager;
    function __construct($aclConfig, $entityManager, $authManager, $recapchaKey, $mailService) {
        $this->recapchaKey = $recapchaKey;
        $this->mailService = $mailService;
        $this->aclConfig = $aclConfig;
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;

    }

    public function searchAction()
    {
        $em = $this->entityManager;
        $user = $this->authManager->getUser();
        
        $tagFilter = $this->params()->fromQuery('query', '');
        $page = $this->params()->fromQuery('page', 1);

        $dietFilter = $this->params()->fromQuery('diet', null);
        $difficultyFilter = $this->params()->fromQuery('difficulty', null);
        $preparation_timeFilter = $this->params()->fromQuery('preparation_time', null);
        $typeFilter = $this->params()->fromQuery('type', null);
        $eventFilter = $this->params()->fromQuery('event', null);
        $cuisineFilter = $this->params()->fromQuery('cuisine', null);
        $ingredientFilter = $this->params()->fromQuery('ingredient', null);


        if($_GET['type'] == 'public') {
            $query = $this->params()->fromQuery('query', '');
            $results = $em->createQueryBuilder()->select('ingredient')->from("FoodConfig\Entity\Ingredient", 'ingredient')->andWhere('ingredient.name LIKE :tag')->setParameters(array('tag' => '%'.$query.'%'))->setMaxResults(50)->getQuery()->getResult();

            $res = [];
            $tmp = [];
            foreach($results as $r) {
                if(!in_array(strtolower($r->getName()), $tmp))
                $res[] = [
                    'id' => $r->getId(),
                    'text' => $r->getName() 
                ];
                $tmp[] = strtolower($r->getName());
            }

            return new JsonModel([
                'status' => 'SUCCESS',
                'results' => $res
            ]);
        }

        $diets = $em->getRepository("FoodConfig\Entity\Diet")->findAll();
        $difficulties = $em->getRepository("FoodConfig\Entity\Difficulty")->findAll();
        $types = $em->getRepository("FoodConfig\Entity\Type")->findAll();
        $events = $em->getRepository("FoodConfig\Entity\Event")->findAll();
        $cuisines = $em->getRepository("FoodConfig\Entity\Cuisine")->findAll();

        $query = $em->createQueryBuilder()->from('FoodConfig\Entity\Dish', 'dish');

        if($difficultyFilter) {
            //one to one
            $query->andWhere('dish.difficulty_id IN ('.implode(',',$difficultyFilter).')');
        }

        if($typeFilter) {
            //one to one
            $query->andWhere('dish.type_id IN ('.implode(',',$typeFilter).')');
        }

        if($preparation_timeFilter) {
            //one to one
            if ($preparation_timeFilter == 1) {
                $query->andWhere('dish.preparation_time <= 30');
            } elseif ($preparation_timeFilter == 2) {
                $query->andWhere('dish.preparation_time > 30 AND dish.preparation_time <= 60');
            } elseif ($preparation_timeFilter == 3) {
                $query->andWhere('dish.preparation_time > 60');
            }
        }

        $eids = [];
        if($eventFilter) {
            //get ids of dishes, which belong to event

            $_results = $em->createQueryBuilder()->select('event_has_dish')->from('FoodConfig\Entity\Event_Has_Dish', 'event_has_dish')->andWhere('event_has_dish.event_id IN ('.implode(',',$eventFilter).')')->getQuery()->getResult();
         
            $tmp = [];

            foreach($_results as $r) {
                if(!is_array($tmp[$r->getDishId()]))
                $tmp[$r->getDishId()] = [];

                array_push($tmp[$r->getDishId()], $r->getEventId());
            }

            foreach($tmp as $k => $r) {
                $flag = true;
                foreach($eventFilter as $f) {
                    if(!in_array($f,$r)) $flag = false;
                }

                if($flag) 
                    array_push($eids, $k);
            }
        }

        $iids = [];
        if($ingredientFilter) {
            //get ids of dishes, which belong to ingredient

            $_results = $em->createQueryBuilder()->select('dish_has_ingredient')->from('FoodConfig\Entity\Dish_Has_Ingredient', 'dish_has_ingredient')->andWhere('dish_has_ingredient.ingredient_id IN ('.implode(',',$ingredientFilter).')')->getQuery()->getResult();

            $tmp = [];

            foreach($_results as $r) {
                if(!is_array($tmp[$r->getDishId()]))
                $tmp[$r->getDishId()] = [];

                array_push($tmp[$r->getDishId()], $r->getIngredientId());
            }

            foreach($tmp as $k => $r) {
                $flag = true;
                foreach($ingredientFilter as $f) {
                    if(!in_array($f,$r)) $flag = false;
                }

                if($flag) 
                    array_push($iids, $k);
            }
        }

        $dids = [];
        if($dietFilter) {
            //get ids of events, which belong to diet

            $_results = $em->createQueryBuilder()->select('diet_has_dish')->from('FoodConfig\Entity\Diet_Has_Dish', 'diet_has_dish')->andWhere('diet_has_dish.diet_id IN ('.implode(',',$dietFilter).')')->getQuery()->getResult();
            
            
            $tmp = [];

            foreach($_results as $r) {
                if(!is_array($tmp[$r->getDishId()]))
                $tmp[$r->getDishId()] = [];

                array_push($tmp[$r->getDishId()], $r->getDietId());
            }

            foreach($tmp as $k => $r) {
                $flag = true;
                foreach($dietFilter as $f) {
                    if(!in_array($f,$r)) $flag = false;
                }

                if($flag) 
                    array_push($dids, $k);
            }
        } 


        if($dietFilter && $eventFilter && $ingredientFilter) {
            //get intersection 
            $ids = array_intersect($eids, $dids, $iids);
        }
        else if($dietFilter && $ingredientFilter) {
            $ids = array_intersect($dids, $iids);
        }
        else if($dietFilter && $eventFilter) {
            $ids = array_intersect($dids, $eids);
        }
        else if($ingredientFilter && $eventFilter) {
            $ids = array_intersect($iids, $eids);
        }
        else if($dietFilter)
        {
            //get just diet ids
            $ids = $dids;
        }
        else if($eventFilter)
        {
            //get just event ids
            $ids = $eids;
        }
        else if($ingredientFilter)
        {
            //get just event ids
            $ids = $iids;
        }

        if($dietFilter || $eventFilter || $ingredientFilter) {
            if(count($ids) == 0) $query->andWhere('dish.id = -1');
            else $query->andWhere('dish.id IN ('.implode(',', $ids).')');
        }

        if($cuisineFilter) {
            //one to one
            $query->andWhere('dish.cuisine_id IN ('.implode(',',$cuisineFilter).')');
        }

        if ($tagFilter) {
            $tagFilter = explode(',', $tagFilter);
            $query->andWhere('(dish.name LIKE :tag OR dish.short LIKE :tag)')->setParameters(array('tag' => '%'.$tagFilter[0].'%'));
        }

        //echo $query->select('count(dish.id)')->getQuery()->getSQL();
        //exit();
        $count_page = $query->select('count(dish.id)')->getQuery()->getSingleScalarResult();
        

        $adapter = new DoctrineAdapter(new ORMPaginator($query->select('dish'), false));
        $paginator = new Paginator($adapter);

        $paginator->setDefaultItemCountPerPage(9);
        $paginator->setCurrentPageNumber($page);

        $count_dish = $count_page;
        $count_page = ceil($count_page/9);

        if ($count_dish > 0 && $page > $count_page) {
            return $this->redirect()->toRoute('search', [
                'action' => 'search',
                'page' => $count_page
            ]);
        }

        if ($page == 0) {
            return $this->redirect()->toRoute('search', [
                'action' => 'search',
                'page' => 1
            ]);
        }

        $view = new ViewModel([
            'diets' => $diets,
            'difficulties' => $difficulties,
            'types' => $types,
            'events' => $events,
            'cuisines' => $cuisines,
            'count_dish' => $count_dish,
            'count_page' => $count_page,
            'page' => $page,
            'paginator' => $paginator,
            'user' => $user,
            'tag' => $tagFilter
        ]);
        
        if ($this->getRequest()->isXmlHttpRequest()) {
                $view->setTerminal(true);
        }

        return $view;
    }

    public function dishAction()
    {
        $user = $this->authManager->getUser();
        $id = $this->params()->fromRoute('id');
        $dish = $this->entityManager->getRepository(Dish::class)
            ->findOneBy(['id' => $id]);

        $likedish = $this->entityManager->getRepository(User::class);

        return new ViewModel([
            'dish' => $dish,
            'user' => $user,
        ]);
    }

    public function policyAction()
    {
        return new ViewModel();
    }


    public function contactAction()
    {
        // new form
        $form = new ContactForm($this->entityManager, $this->recapchaKey);
        $user = $this->authManager->checkIdentity();


        $user_1 = $this->entityManager->getRepository(User::class);
        $user_1 = $user_1->findOneBy(['id' => $user['id']]);


        if ($user) {
            $data = [
                'email' => $user_1->getEmail()
            ];
            $form->setData($data);
        }

        // request
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setData($data);

            if ($form->isValid()) {

                $data = $form->getData();
                $message = $data['message'];
                $email_1 = $data['email'];

                // mailtemplate
                $content = [
                    'email_1' => $email_1,
                    'message'     => $message,
                ];
                // send email through event manager
                $this->getEventManager()->trigger('send_mail_1', $this, $content);
                $this->flashMessenger()->addSuccessMessage('E-mail wysłany! Dziękujemy za wiadomość!');
                return $this->redirect()->toRoute('contact', [
                    'action' => 'contact'
                ]);
            }
        }

        return new ViewModel([
            'form'		=> $form
        ]);
    }
}