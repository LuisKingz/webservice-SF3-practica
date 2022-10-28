<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WebserviceController extends Controller {

    public function getAuthorsAction(Request $request) {
        //Objeto Doctrine ORM
        $em = $this->getDoctrine()->getManager();
        //Helpers (Transforma JSON Response)
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        //Obtener autores findAll(Todos los registros)
        $authors = $em->getRepository('AppBundle:Author')->findAll();
        $data_response = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Authors results',
            'authors' => $authors
        );

        return $helpers->json($data_response);
    }

    public function getBooksAction(Request $request) {
        //Objeto Doctrine ORM
        $em = $this->getDoctrine()->getManager();
        //Helpers (Transforma JSON Response)
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        //Obtener autores findAll(Todos los registros)
        $books = $em->getRepository('AppBundle:Book')->findAll();
        $data_response = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Books results',
            'books' => $books
        );

        return $helpers->json($data_response);
    }

    public function getEditorialsAction(Request $request) {
        //Objeto Doctrine ORM
        $em = $this->getDoctrine()->getManager();
        //Helpers (Transforma JSON Response)
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        //Obtener autores findAll(Todos los registros)
        $editorials = $em->getRepository('AppBundle:Editorial')->findAll();
        $data_response = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Editorials results',
            'editorials' => $editorials
        );

        return $helpers->json($data_response);
    }

    public function createAuthorAction(Request $request) {
        $helpers = $this->get(\AppBundle\Services\Helpers::class);

        $json = $request->get("json", null);
        $params = json_decode($json);

        $name = $params->name;
        $lastname = $params->lastname;

        $author = new \AppBundle\Entity\Author();
        $author->setName($name);
        $author->setLastname($lastname);

        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();
        $data = array(
            "status" => "success",
            "code" => 200,
            "msg" => "Create books",
            "json" => $json
        );

        return $helpers->json($data);
    }

    public function getBooksByEditorialAction(Request $request) {
        $id = $request->get("id", null);
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('AppBundle:Book')->findBy(['editorial' => $id]);
        $data_response = array(
            "status" => "success",
            "code" => 200,
            "msg" => "Books results",
            "books" => $books
        );
        return $helpers->json($data_response);
    }

    public function getAuthorAction(Request $request) {
        $id = $request->get("id", null);
        //Objeto Doctrine ORM
        $em = $this->getDoctrine()->getManager();
        //Helpers (Transforma JSON Response)
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        //Obtener autores findAll(Todos los registros)
        $author = $em->getRepository('AppBundle:Author')->find($id);
        $data_response = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Author results',
            'author' => $author
        );

        return $helpers->json($data_response);
    }

    public function updateAuthorAction(Request $request) {
        $helpers = $this->get(\AppBundle\Services\Helpers::class);
        $em = $this->getDoctrine()->getManager();

        $json = $request->get("json", null);
        $params = json_decode($json);

        $name = $params->name;
        $lastname = $params->lastname;
        $id = $params->id;

        $author = $em->getRepository('AppBundle:Author')->find($id);

        $author->setName($name);
        $author->setLastname($lastname);

        $em->persist($author);
        $em->flush();
        $data = array(
            "status" => "success",
            "code" => 200,
            "msg" => "Update author",
            "json" => $json
        );

        return $helpers->json($data);
    }

    public function deleteAuthorAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $helpers = $this->get(\AppBundle\Services\Helpers::class);

        $id = $request->get("id", null);

        $author = $em->getRepository('AppBundle:Author')->find($id);

        if ($author) {
            $booksInAuthor = $em->getRepository('AppBundle:Book')->findBy(['author' => $author]);
            if (count($booksInAuthor)== 0) {
                $em->remove($author);
                $em->flush();
                $data_response = array(
                    "status" => "success",
                    "code" => 200,
                    "msg" => "Autor eliminado"
                );
            } else {
                $data_response = array(
                    "status" => "success",
                    "code" => 200,
                    "msg" => "No se pudo eliminar el autor"
                );
            }
        } else {
            $data_response = array(
                "status" => "success",
                "code" => 200,
                "msg" => "No existe ese autor"
            );
        }
        return $helpers->json($data_response);
    }

    public function createBookAction(Request $request) {
        $helpers = $this->get(\AppBundle\Services\Helpers::class);

        $em = $this->getDoctrine()->getManager();
        $json = $request->get("json", null);
        $params = json_decode($json);

        $name = $params->name;
        $description = $params->description;
        $price = $params->price;
        $page = $params->page;
        $isbn = $params->isbn;
        $status = 1;
        
        $id_editorial = $params->editorial;
        $id_author = $params->author;
        
        $author = $em->getRepository("AppBundle:Author")->find($id_author);
        $editorial = $em->getRepository("AppBundle:Editorial")->find($id_editorial);

        $book = new \AppBundle\Entity\Book();
        
        $book->setName($name);
        $book->setDescription($description);
        $book->setIsbn($isbn);
        $book->setPage($page);
        $book->setPrice($price);
        $book->setStatus($status);
        
        $book->setAuthor($author);
        $book->setEditorial($editorial);

        $em->persist($book);
        $em->flush();
        $data = array(
            "status" => "success",
            "code" => 200,
            "msg" => "Create books",
            "json" => $json
        );

        return $helpers->json($data);
    }
}
