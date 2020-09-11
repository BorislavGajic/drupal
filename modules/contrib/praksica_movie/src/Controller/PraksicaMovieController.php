<?php

namespace Drupal\praksica_movie\Controller;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Controller\ControllerBase;

class PraksicaMovieController extends ControllerBase {
    
    public function movie(){
        return array(
            '#title' => 'Praksica Movie',
            '#markup' => 'This is some content.'
        );
    }

    public function page(){

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
    }
}