<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use AppBundle\Services\Helpers;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
        echo "Index del servicio";
        die();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    public function getBooksAction(Request $request) {
        //Objeto ORM para manipular datos con Doctrine
        $em = $this->getDoctrine()->getManager();
        //Recuperación de datos … método FINDALL
        $books = $em->getRepository('AppBundle:Book')->findAll();
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        //Generamos la respuesta cumpliendo los parámetros (status, code, msg, data)
        $response_data = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Books results',
            'books' => $books
        );
        //Regresamos la respuesta utilziando el método json del helpers
        return $helpers->json($response_data);
    }

    public function getBooksByEditorialAction(Request $request, $author_id) {
        //Objeto ORM para manipular datos con Doctrine
        $em = $this->getDoctrine()->getManager();
        //Recuperación de datos … método FINDALL
        $books = $em->getRepository('AppBundle:Book')->findBy(array('author' => $author_id));
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        //Generamos la respuesta cumpliendo los parámetros (status, code, msg, data)
        $response_data = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Books results',
            'books' => $books
        );
        //Regresamos la respuesta utilziando el método json del helpers
        return $helpers->json($response_data);
    }

}
