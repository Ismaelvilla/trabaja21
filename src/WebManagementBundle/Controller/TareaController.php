<?php

namespace WebManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class TareaController extends Controller
{

    public function indexAction()
    {
        //cogemos el entity
        $entity = $this->getDoctrine()->getManager();

        $tareaRepository = $entity->getRepository('WebManagementBundle:Tarea');
        $tareas = $tareaRepository->findAll();

        return $this->render('WebManagementBundle:Tarea:index.html.twig', array('tareas' => $tareas));
    }

    public function nuevaAjaxAction(Request $request){
        //Obtenemos el usuario
        $usuario = $this->getUser();

        $nombreTarea = $request->query->get('nombreTarea');

        //cogemos el entity
        $entity = $this->getDoctrine()->getManager();
        $tareaRepository = $entity->getRepository('WebManagementBundle:Tarea');
        $tareaRepository->newTask($entity, $nombreTarea, $usuario->getId());

        //obtenemos todas las tareas para mostrar
        $tareas = $tareaRepository->findAll();
        $retorno = $this->render('WebManagementBundle:Tarea:gridTareas.html.twig', array('tareas' => $tareas));

        return $retorno;
    }

    public function modificarAjaxAction(Request $request){
        //Recogemos los datos, id y nombre modificado de la tarea
        $id = $request->query->get('id');
        $nombreTarea = $request->query->get('nombreTarea');
        //cogemos el entity
        $entity = $this->getDoctrine()->getManager();
        $tareaRepository = $entity->getRepository('WebManagementBundle:Tarea');
        //buscamos la tarea
        $tarea = $tareaRepository->find($id);
        $tarea->setTarea($nombreTarea);
        $entity->flush();
        //obtenemos todas las tareas para mostrar, para devolver la vista gridTareas
        $tareas = $tareaRepository->findAll();
        $retorno = $this->render('WebManagementBundle:Tarea:gridTareas.html.twig', array('tareas' => $tareas));

        return $retorno;
    }

    public function eliminarAjaxAction(Request $request){
        //cogemos el entity y el repositorio Tareas
        $entity = $this->getDoctrine()->getManager();
        $tareaRepository = $entity->getRepository('WebManagementBundle:Tarea');
        //Recogemos los id de las tareas para borrar, separadas por el simbolo |
        $seleccionados = $request->query->get('seleccionados');
        $eliminados = explode("|", $seleccionados);
        //Recorremos las tareas para ir borrandolas
        for($i=0; $i<count($eliminados); $i++){
            if(strlen($eliminados[$i])!=0){
                $tarea = $tareaRepository->find($eliminados[$i]);
                $entity->remove($tarea);
                $entity->flush();
            }
        }
        //obtenemos todas las tareas para mostrar
        $tareas = $tareaRepository->findAll();
        $retorno = $this->render('WebManagementBundle:Tarea:gridTareas.html.twig', array('tareas' => $tareas));
        return $retorno;
    }
}
