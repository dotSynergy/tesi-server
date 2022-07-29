<?php

namespace App\Controllers;
 
use Core\Template;
use App\DB\ModelManager;
use App\Models\SensorsData;
 
class VisualizationController extends AbstractController
{
    /**
    * @var EntityManager
    */
    protected $em;

    public function __construct()
    {
        parent::__construct(new Template());
        $this->em = ModelManager::getInstance();
    }
 
    public function indexMethod()
    {
        return parent::getView(
            __METHOD__,
            [
                'title' => $_ENV['APP_NAME'].' - Home',
                'header' => 'Data Visualization App',
            ]
        );
 
    }

    public function dataMethod()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('s')
            ->from('App\Models\SensorsData', 's')
            ->add('orderBy', 's.id DESC')
            ->setMaxResults(1000);

        $query = $qb->getQuery();

        $result = $query->getResult();

        $iterableResult = $query->toIterable();

        echo json_encode($result);

    }
}   