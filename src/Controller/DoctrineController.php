<?php


namespace App\Controller;

use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctrine")
 */
class DoctrineController extends AbstractController
{
    /**
     * @Route()
     */
    public function index(SettingRepository $settingRepository)
    {
        $settingRepository->find(1);

        $qb = $settingRepository->createQueryBuilder('t');

        $qb->where('t.id = :id')
            ->setParameter('id', 1);

        $results = $qb->getQuery()->getResult();

        die;
    }
}
