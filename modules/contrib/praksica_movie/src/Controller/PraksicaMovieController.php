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


class PraksicaMovieController extends ControllerBase {
    
    protected $entityQuery;
    protected $entityTypeManager;

    public static function create(ContainerInterface $container){
        return new static( $container->get('entity.query'),
                            $container->get('entity_type.manager')                    
        );
    }

    public function __construct(QueryFactory $entityQuery, EntityTypeManagerInterface $entityTypeManager){
        $this-> entityQuery = $entityQuery;
        $this-> entityTypeManager = $entityTypeManager;
    }

    /*public function movie(){
        return array(
            '#title' => 'Praksica Movie',
            '#markup' => 'This is some content.'
        );
    }*/

    /*public function page(){

        $items = array(
            array('name' => 'Movie one'),
            array('name' => 'Movie two'),
            array('name' => 'Movie tree'),
            array('name' => 'Movie four'),
        );

        return array(
            '#theme' => 'movie_list',
            '#items' => $items,
            '#title' => 'Our movie list'
        );
    }*/

    public function getMovieData(){
        //console.log("usao u get");
        $nids= $this->entityQuery->get('node')->condition('type', 'movie')->execute();
        $movie_nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
        $movies = array();
        //console.log("Inicijalizovao movies. ");
        /*foreach ($movie as $movie_nodes){
            $n = $n + 1;
            echo("uslo u foreach");

            $movies[] = array(
                'title' => $movie->title->value,
                'description' => $movie->description->value,
                'type' => $movie->type->value
            );
        }*/
        //console.log($movies);
        return $movies;
    }

    public function movies(){
        $nids= $this->entityQuery->get('node')->condition('type', 'movie')->execute();
        $movie_nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
        $movies2= array();
        foreach ($movie_nodes as $movie){
            $movies_from_for = array(
                    'title' => $movie->title->value,
                    'description' => $movie->field_description_field->value,
                    'image' => $movie->field_image_field1->entity->createFileUrl()
            );
            array_push ($movies2, $movies_from_for);
        }
        return array(
            '#theme' => 'movie_list',
            '#title' => 'All movies',
            '#movies' => $movies2
        );
       
    }
};