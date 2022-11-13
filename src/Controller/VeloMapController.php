<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VeloMapController extends AbstractController
{
    /**
     * @Route("/velo/map", name="app_velo_map")
     */
    public function index(): Response
    {
        return $this->render('velo_map/index.html.twig');
    }

    /**
     * @Route("/velo/velosdata", name="velosdata")
     */
    public function getData(HttpClientInterface $httpClient) {
        $response = $httpClient->request('GET', 'https://data.strasbourg.eu/api/records/1.0/search/?dataset=stations-velhop&rows=21&facet=na');
  
          $data = $response->toArray()['records'];
  
          $array = [];
  
          foreach($data as $records) {
            $array[$records['recordid']]['na'] = $records['fields']['na'];
            $array[$records['recordid']]['coordonnees'] = $records['fields']['coordonnees'];
            $array[$records['recordid']]['to'] = $records['fields']['to'];
            $array[$records['recordid']]['av'] = $records['fields']['av'];
            $array[$records['recordid']]['fr'] = $records['fields']['fr'];
          }
  
          $velosData = new JsonResponse($array);
          return $velosData;
      }
}
