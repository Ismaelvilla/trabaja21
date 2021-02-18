<?php

namespace WebManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmpresaController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        //obtenemos el usuario
        $usuario = $this->getUser()->getId();
        //vamos a recoger las empresas y las vamos a mostrar
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryEmpresa = $entityManager->getRepository('WebManagementBundle:Empresa');
        $empresas = $repositoryEmpresa->findBy(
            ['activo' => true, 'usuario' =>$usuario],
            ['nombre' => 'asc']
        );

        return $this->render('WebManagementBundle:Empresa:index.html.twig', array('empresas'=>$empresas));
    }

    /**
     * Creamos una empresa vacia
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(){
        //Obtenemos el usuario
        $usuario = $this->getUser()->getId();

        //sacamos el entity
        $entityManager = $this->getDoctrine()->getManager();

        //vamos a sacar la categoria
        $repositoryCategoria = $entityManager->getRepository('WebManagementBundle:Categoria');
        $categoria = $repositoryCategoria->find(8);

        //Vamos a sacar la provincia base
        $repositoryProvincia = $entityManager->getRepository('WebManagementBundle:Provincias');
        $provincia = $repositoryProvincia->find(53);

        //Vamos a sacar el municipio base
        $repositoryPoblacion = $entityManager->getRepository('WebManagementBundle:Municipios');
        $municipio = $repositoryPoblacion->find(8117);

        //vamos a crear una empresa vacia
        $repositoryEmpresa = $entityManager->getRepository('WebManagementBundle:Empresa');
        $idEmpresa= $repositoryEmpresa->add($entityManager, $categoria, $provincia, $municipio, $usuario);

        return $this->redirectToRoute('empresas_edit', array('id'=>$idEmpresa));
    }

    /**
     * Editamos una empresa
     *
     * @param Request $request
     * @return Response
     */
    public function detailsAction(Request $request){
        //obtenemos el usuario
        $usuario = $this->getUser()->getId();
        // conseguimos el id de empresa
        $idEmpresa = $request->attributes->get('id');

        //vamos a buscar la empresa
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryEmpresa = $entityManager->getRepository('WebManagementBundle:Empresa');
        $empresa = $repositoryEmpresa->find($idEmpresa);

        //vamos a sacar las provincias
        $repositoryProvincia = $entityManager->getRepository('WebManagementBundle:Provincias');
        $provincias = $repositoryProvincia->findAll();

        //vamos a sacar los municipios
        $repositoryMunicipio = $entityManager->getRepository('WebManagementBundle:Municipios');
        $municipios = $repositoryMunicipio->getMunicipiosProvincia($entityManager, $empresa->getProvincia()->getIdProvincia());

        //vamos a sacar las categorias
        $repositoryCategoria = $entityManager->getRepository('WebManagementBundle:Categoria');
        $categorias = $repositoryCategoria->findby(
            ['activo'=>1, 'usuario'=>$usuario],
            ['nombre' => 'ASC']
        );

        return $this->render('WebManagementBundle:Empresa:details.html.twig',
            array(
                'empresa' => $empresa,
                'provincias' => $provincias,
                'municipios' => $municipios,
                'categorias' => $categorias
            ));
    }

    /**
     * Borramos una empresa
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request){
        //conseguimos el id de empresa
        $idEmpresa = $request->attributes->get('id');

        //vamos a buscar la empresa
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryEmpresa = $entityManager->getRepository('WebManagementBundle:Empresa');
        $empresa = $repositoryEmpresa->find($idEmpresa);

        //borramos la empresa
        $entityManager->remove($empresa);
        $entityManager->flush();

        $json = array(
            'redirect'=>$this->generateUrl('empresas_index')
        );
        return new JsonResponse($json);
    }

    /**
     * Actualizamos una empresa
     *
     * @param Request $request
     * @return Response
     */
    public function updateAction(Request $request){

        $csr_token = $request->request->get('_csrf_token');
        $data = $request->request->all();

        //buscamos la empresa
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryEmpresa = $entityManager->getRepository('WebManagementBundle:Empresa');
        $empresa = $repositoryEmpresa->find($data['id']);

        //buscamos la categoria
        $repositoryCategoria = $entityManager->getRepository('WebManagementBundle:Categoria');
        $categoria = $repositoryCategoria->find($data['categoria']);

        //buscamos la provincia
        $repositoryProvincia = $entityManager->getRepository('WebManagementBundle:Provincias');
        $provincia = $repositoryProvincia->find($data['provincia']);

        //buscamos la población
        $repositoryPoblacion = $entityManager->getRepository('WebManagementBundle:Municipios');
        $poblacion = $repositoryPoblacion->find($data['poblacion']);

        if ($this->isCsrfTokenValid('edit_notification', $csr_token)) {

            $empresa->setNombre($data['nombre']);
            $empresa->setCategoria($categoria);
            $empresa->setProvincia($provincia);
            $empresa->setPoblacion($poblacion);
            $empresa->setEmail($data['email']);
            $empresa->setPrioridad($data['prioridad']);
            $empresa->setActivo(true);
            if($data['comentarios']) $empresa->setComentario($data['comentarios']);

            $entityManager->flush();
        }

        //Mandamos todas las empresas activas
        $empresas = $repositoryEmpresa->findBy(
            ['activo' => true],
            ['nombre' => 'asc']
        );

        return $this->render('WebManagementBundle:Empresa:index.html.twig', array('empresas'=>$empresas));

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function provinciaAjaxAction(Request $request){
        //Sacamos los atributos
        $idProvincia = $request->query->get('idProvincia');
        $idEmpresa = $request->query->get('idEmpresa');

        //Obtenemos el entity
        $entityManager = $this->getDoctrine()->getManager();

        //vamos a sacar la empresa
        $repositoryEmpresa = $entityManager->getRepository('WebManagementBundle:Empresa');
        $empresa = $repositoryEmpresa->find($idEmpresa);

        //vamos a sacar todos los municipios que tiene esta provincia
        $repositoryMunicipio = $entityManager->getRepository('WebManagementBundle:Municipios');
        $municipios = $repositoryMunicipio->getMunicipiosProvincia($entityManager, $idProvincia);

        $response = $this->render('WebManagementBundle:Empresa:cajaMunicipio.html.twig', array(
            'municipios' => $municipios,
            'empresa' => $empresa
        ));

        return $response;
    }
}
