<?php

namespace App\Controller;

use App\Form\ActeurSType;
use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use App\Form\EditUtilisateursType;
use App\Services\cart\CartService;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateursController extends AbstractController
{
    /**
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function index( ): Response
    {
        
        return $this->render('utilisateurs/index.html.twig', [
            'controller_name' => 'UtilisateursController',
        ]);
    }
      /**
     * @Route("/utilisateurs/add",name="userAdd")
     */

    public function AddUser(Request $request , UserPasswordEncoderInterface $encoder,CartService $cartService){

        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();
        
        $em = $this->getDoctrine()->getManager();
        $user= new Utilisateurs();
        $form=$this ->createForm(UtilisateursType::class,$user);
        $form->add('Register', SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
           
            $tel=$user->getTelephone();
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setTelephone("+216".$tel);
            
            $user->setEtat("Disponible");


            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('home');

        }

        return $this->render('utilisateurs/addUser.html.twig', [
            'userForm'=>$form->createView(),
            'elements' => $dataPanier,
            'total' => $total,
        ]);
    }

     /**
     * @Route("/utilisateurs/update/{id}",name="userupdate")
     */
    public function Update($id,UtilisateursRepository $rep,Request $request,CartService $cartService){
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();


        $user=$rep->find($id);

        $form=$this->createform(EditUtilisateursType::class,$user);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $file = $form->get('Image')->getData();
            
            if($file != null){
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('uploads_directory'), $filename);
            $user->setImage($filename);
            }

        $em=$this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('success','Info changed');
        return $this->redirectToRoute('usercompte');

        }return $this->render("utilisateurs/update.html.twig", [
            'userForm'=>$form->createView(),
            'user'=>$user,
            'elements' => $dataPanier,
            'total' => $total
          
        ]);

     }
     /**
     * @Route("/utilisateurs/compte",name="usercompte")
     */
    public function Compte(CartService $cartService){
        
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        return $this->render("utilisateurs/compte.html.twig", [
            'elements' => $dataPanier,
            'total' => $total,
        ]);


     }
     /**
      * @Route("/utilisateurs/deleteimg/{id}",name="imgdetele")
      */

     public function deleteImg($id, UtilisateursRepository $rep){
         
         $user=$rep->find($id);
         $user->setImage(null);
         $em = $this->getDoctrine()->getManager();
         $em->flush();

         $this->addFlash('success', 'Image deleted');
         return $this->redirectToRoute('usercompte');


     }
     
     /**
     * @Route("/utilisateurs/updatepass",name="passupdate")
     */
    public function EditPassword(UtilisateursRepository $rep ,Request $request , UserPasswordEncoderInterface $encoder,CartService $cartService ){
        
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        if($request->isMethod('POST')){
            $em= $this->getDoctrine()->getManager();
            $user=$this->getUser();
            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($encoder->encodePassword($user,$request->request->get('pass')));
                $em->flush();
                $this->addFlash('success','Password changed');
                return $this->redirectToRoute('usercompte');
               
            }else
            $this->addFlash('error','Password does not match');


        }
        return $this->render("utilisateurs/updatepass.html.twig", [
            'elements' => $dataPanier,
            'total' => $total,
        ]);


     }
      /**
     * @Route("/utilisateurs/confirmuser",name="confirmuser")
     */
    public function ConfirmUser(UtilisateursRepository $rep ,Request $request ,CartService $cartService){
        
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        if($request->isMethod('POST')){

            $em= $this->getDoctrine()->getManager();
            $user=$this->getUser();
            if(($request->request->get('question1') == $user->getQuestionSecurite1()) &&
            ($request->request->get('question2') == $user->getQuestionSecurite2())) {
              
                return $this->redirectToRoute('passupdate');
               
            }else
            $this->addFlash('error','Reply does not match');


        }
        return $this->render("utilisateurs/confirmuser.html.twig", [
            'elements' => $dataPanier,
            'total' => $total,
        ]);

     }
    

     
     /**
     * @Route("/login", name="login")
     */
    public function login(CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        return $this->render('security/login.html.twig', [
            'elements' => $dataPanier,
            'total' => $total,
        ]);
    }
    /**
     * @Route("/loginadmin", name="loginadmin")
     */
    public function loginadmin(): Response
    {
            return $this->render('security/loginadmin.html.twig', [
          
        ]);
    }
     /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}

  

     
}
