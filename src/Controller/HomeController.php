<?php

namespace App\Controller;

use App\Form\SearchCarType;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Service\CallApiService;
use App\Service\GetIpAdress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{




    #[Route('/', name: 'app_home')]
    public function index(CarRepository $CarRepository, PaginatorInterface $paginator, Request $request, CallApiService $callApiService,GetIpAdress $getIpAddress): Response
    {
        $ip = $getIpAddress->get_IP_address();
        $loc = file_get_contents("http://ip-api.com/json/$ip");

        $lat=json_decode($loc, true)['lat'];
        $long=json_decode($loc, true)['lon'];
        // dd($long);
        $temperature=$callApiService->getMeteoData($lat,$long);

        $search = new Car();
        $form = $this->createForm(SearchCarType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData()->getName());
            $cars = $CarRepository->findByName($form->getData()->getName());
            // dd($answer);
        } else {
            $cars = $CarRepository->getAll();
        }

        $pages = $paginator->paginate($cars, $request->query->getInt('page', 1), 20);
        
        // dd(localtime());

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'pages' => $pages,
            'searchCarForm' => $form->createView(),
            'temperature'=>$temperature
        ]);
    }
}
