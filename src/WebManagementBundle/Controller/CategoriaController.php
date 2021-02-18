<?php

namespace WebManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class CategoriaController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        //Obtenemos el objeto usuario
        $usuario = $this->getUser();

        //vamos a recoger las categorias y las vamos a mostrar
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryCategoria = $entityManager->getRepository('WebManagementBundle:Categoria');
        $categorias = $repositoryCategoria->findBy(
            ['usuario'=>$usuario->getId()],
            ['id'=>'ASC']
        );

        return $this->render('WebManagementBundle:Categoria:index.html.twig', array(
            'categorias' => $categorias
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(){
        //Obtenemos el usuario
        $usuario = $this->getUser();

        //vamos a crear una categoria vacia
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryCategoria = $entityManager->getRepository('WebManagementBundle:Categoria');
        $idCategoria = $repositoryCategoria->add($entityManager, $usuario->getId());

        return $this->redirectToRoute('categorias_details', array(
            'id'=>$idCategoria
        ));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function detailsAction(Request $request){
        //conseguimos el id de atributo
        $idCategoria = $request->attributes->get('id');

        //obtenemos el objeto categoria
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryCategoria = $entityManager->getRepository('WebManagementBundle:Categoria');
        $categoria = $repositoryCategoria->find($idCategoria);

        return $this->render('WebManagementBundle:Categoria:details.html.twig', array(
            'categoria' => $categoria
        ));

        return new Response('estamos en details');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function categoriaAjaxAction(Request $request){
        $csrfToken = $request->request->get('_csrf_token');
        $data = $request->request->all();
        if ($this->isCsrfTokenValid('edit_categoria', $csrfToken)) {
            $idCategoria = $data['idCategoria'];

            //obtenemos el objeto categoria
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryCategoria = $entityManager->getRepository('WebManagementBundle:Categoria');
            $categoria = $repositoryCategoria->find($idCategoria);

            //actualizamos los valores
            $categoria->setNombre($data['nombre']);
            if(isset($data['activo'])){
                $categoria->setActivo(true);
            }else{
                $categoria->setActivo(false);
            }
            $entityManager->flush();
        }

        $json=array(
            'redirect'=>$this->generateUrl('categorias_index'),
        );
        return new JsonResponse($json);
    }
}
