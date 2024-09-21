<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/inicio', name: 'tasks')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /* ==================== prueba de entidades y relaciones ====================
        $task_repo = $this->entityManager->getRepository(Task::class);
        $tasks = $task_repo->findAll();
        foreach ($tasks as $task) {
            echo $task->getUserId()->getEmail(). ' '. $task->getTitle() . "<br/>";
        }
        $user_repo = $this->entityManager->getRepository(User::class);
        $users = $user_repo->findAll();
        foreach ($users as $user) {
            echo "<h1> {$user->getName()} {$user->getSurname()} </h1> <br/>";
            foreach ($user->getTasks() as $task) {
                echo $task->getTitle(). ': '. $task->getContent() . "<br/>";
            }
        }
        ================================================================================
        */
        $task_repo = $this->entityManager->getRepository(Task::class);
        $tasks = $task_repo->findBy([], ['id' => 'DESC']);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tarea/{id}', name: 'task_detail')]
    public function detail(Task $task)
    {
        if (!$task){
            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/detail.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/crear-tarea', name: 'task_creation')]
    public function creation(Request $request, UserInterface $user)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTimeImmutable('now'));
            $task->setUserId($user);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
        }

        return $this->render('task/creation.html.twig', [
            'edit' => false,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mis-tareas', name: 'my_tasks')]
    public function myTasks(UserInterface $user)
    {
        $tasks = $user->getTasks();

        return $this->render('task/my_tasks.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/editar-tarea/{id}', name: 'edit')]
    public function edit(Task $task, Request $request, UserInterface $user)
    {
        if (!$user || $user->getId() != $task->getUserId()->getId()) {
            return $this->redirectToRoute('tasks');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
        }

        return $this->render('task/creation.html.twig', [
            'edit' => true,
            'form' => $form->createView(),
        ]);
    }

    #[Route('eliminar/{id}', name: 'delete')]
    public function delete(Task $task, Request $request, UserInterface $user)
    {
        if (!$user || !$task || $user->getId() != $task->getUserId()->getId()) {
            return $this->redirectToRoute('tasks');
        }
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $this->redirectToRoute('tasks');
    }
}
