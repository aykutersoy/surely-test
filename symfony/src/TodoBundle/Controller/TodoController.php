<?php

namespace TodoBundle\Controller;

use TodoBundle\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Todo controller.
 *
 * @Route("todo")
 */
class TodoController extends Controller
{
    /**
     * Lists all todo entities.
     *
     * @Route("/", name="todo_index")
     * @-Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $todos = $em->getRepository('TodoBundle:Todo')->findAll();

        return $this->render('todo/index.html.twig', array(
            'todos' => $todos,
        ));
    }

    /**
     * Creates a new todo entity.
     *
     * @Route("/new", name="todo_new")
     * @-Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm('TodoBundle\Form\TodoType', $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $todo->setName($form['name']->getData());
            $todo->setCreatedDate(new \DateTime());
            $todo->setIsCompleted(false);


            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/new.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing todo entity.
     *
     * @Route("/edit/{id}", name="todo_edit")
     * @-Method({"GET", "POST"})
     */
    public function editAction(Request $request, Todo $todo)
    {
        $editForm = $this->createForm('TodoBundle\Form\TodoType', $todo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/edit.html.twig', array(
            'todo' => $todo,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing todo entity.
     *
     * @Route("/complete/{id}", name="todo_complete")
     * @-Method({"POST"})
     */
    public function completeAction($id)
    {

        $em = $this->getDoctrine()->getManager();


        $todo = $em->getRepository('TodoBundle:Todo')->find($id);

        $todo->setIsCompleted(true);

        $em->persist($todo);
        $em->flush();

        $this->addFlash(
            'notice',
            'Todo Completed'
        );

        return $this->redirectToRoute('todo_index');

    }
    /**
     * Deletes a todo entity.
     *
     * @Route("/del/{id}", name="todo_delete")
     * @-Method("DELETE")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove( $this->getDoctrine()->getRepository('TodoBundle:Todo')->find($id) );
        $em->flush();

        $this->addFlash(
            'notice',
            'Todo Removed'
        );

        return $this->redirectToRoute('todo_index');
    }

    /**
     * Creates a form to delete a todo entity.
     *
     * @param Todo $todo The todo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Todo $todo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('todo_delete', array('id' => $todo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
