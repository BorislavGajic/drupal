<?php

namespace Drupal\praksica_movie\Controller;

use Drupal\Core\Controller\ControllerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeMenagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactory;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PraksicaMovieController extends ControllerBase {
    
    protected $entityQuery;
    protected $entityTypeManager;
    protected $requestStack;

    public static function create(ContainerInterface $container){
        return new static( $container->get('entity.query'),
                            $container->get('entity_type.manager'),
                            $container->get('request_stack')                 
        );
    }

    public function __construct(QueryFactory $entityQuery, EntityTypeManagerInterface $entityTypeManager, RequestStack $requestStack){
        $this-> entityQuery = $entityQuery;
        $this-> entityTypeManager = $entityTypeManager;
        $this-> requestStack = $requestStack;
    }


    public function movies(){

        $request = $this->requestStack->getCurrentRequest();
        $searchString = $request->get('search');
        $movies = $this->loadAll($searchString);
        

        return array(
            '#theme' => 'movie_list',
            '#title' => 'All movies',
            '#movies' => $movies,
            'searchString' => $searchString
        );
       
    }

    public function loadAll($searchString){
        if(empty($searchString)){
            $nids= $this->entityQuery->get('node')->condition('type', 'movie')->execute();
        }else{
            $nids= $this->entityQuery->get('node')->condition('type', 'movie')->condition('title', $searchString,'CONTAINS')->execute();
        }
        
        $movie_nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
        $movies= array();
        foreach ($movie_nodes as $movie){
            $movies_from_for = array(
                    'title' => $movie->title->value,
                    'description' => $movie->field_description_field->value,
                    'image' => $movie->field_image_field1->entity->createFileUrl()
            );
            array_push ($movies, $movies_from_for);
        }
        return $movies;
    }
}