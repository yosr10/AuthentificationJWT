<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User; 
use App\Exception\FormExeption; 
use App\Form\userType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository ; 
use JMS\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
    
     /**
     * @Route("/user", name="createUser",methods="POST")
     */

    public function createUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $form = $this->createForm(IUserType::class,$user);
   
           $form->submit($data);
   
           if ($form->isSubmitted() && $form->isValid())
           {
            $entityManager  = $this->getDoctrine()->getManager();

            $entityManager ->persist($user);

            $entityManager ->flush();
             
           }
           $response = array(
           
            'code' => 0,
            'message' => 'created with success!',
            'errors' => null, 
            'result' => null
    
        );  
            
           return new JsonResponse($response, Response::HTTP_CREATED);
    }
   
  /**
   
     * @Route("/user/{id}", name="deleteuser",methods={"DELETE"})
     *

     */
    public function deleteuser($id):JsonResponse
    {

        $user = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);
      if (!$user) {
        throw $this->createNotFoundException(
            'No user found for id '.$id
        );}
        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse(['status' => 'user deleted']);
    }
}
