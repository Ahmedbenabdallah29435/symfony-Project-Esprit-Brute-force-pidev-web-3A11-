<?php

namespace App\Controller;

use App\Form\ActeurSType;
use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use App\Form\EditUtilisateursType;
use App\Services\cart\CartService;
use App\Repository\UtilisateursRepository;
use Exception;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
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

    #***************************MOBILE*******************************#

    /**
     * @Route("/utilisateursjson", name="json_index", methods={"GET"})
     */
    public function afficherJSON(UtilisateursRepository $UtilisateursRepository, SerializerInterface $serializer): Response
    {
        $result = $UtilisateursRepository->findAll();
        /* $n = $normalizer->normalize($result, null, ['groups' => 'pack:read']);
        $json = json_encode($n); */
        $json = $serializer->serialize($result, 'json', ['groups' => 'utilisateurs:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/registerJSON",name="registerJSON")
     */

    public function registerJSON(Request $request, NormalizerInterface $Normalizer, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateurs = new Utilisateurs();
        $utilisateurs->setNom($request->get('Nom'));
        $utilisateurs->setPrenom($request->get('Prenom'));
        $utilisateurs->setEmail($request->get('Email'));
        $utilisateurs->setTelephone($request->get('Telephone'));
        $utilisateurs->setPassword($request->get('Password'));

        $em->persist($utilisateurs);
        $em->flush();
        $jsonContent = $Normalizer->normalize($utilisateurs, 'json', ['groups' => 'utilisateurs:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/loginJSON", name="loginJSON", methods={"POST"})
     */
    public function signin(Request $request ,UtilisateursRepository $rep): Response
    {
        $email= $request->query->get('Email');
        $password = $request->query->get('Password');
        $user=$rep->findOneBy(['Email'=>$email]);
        if($user){
            if((password_verify($password,$user->getPassword())) ||($password==$user->getPassword())) {
                $serializer= new Serializer([new ObjectNormalizer()]);
                $formatted=$serializer->normalize($user);
                return new JsonResponse($formatted);

            }
            else return new Response("failed");
        }
        else return new Response("failed");
    }

    /**
     * @Route("/edituserJSON", name="edituserJSON", methods={"POST"})
     */
    public function edituser(Request $request, UtilisateursRepository $rep, UserPasswordEncoderInterface $encoder){
        $id=$request->get("id");
        $user = $rep->find($id);

        $nom = $request->query->get("Nom");
        $prenom = $request->query->get("Prenom");
        $telephone = $request->query->get("Telephone");
        $email=$request->query->get("Email");
        $password = $request->get('Password');

    try{
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setTelephone($telephone);
        $user->setEmail($email);
        $user->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

       return new JsonResponse("success",200);
    }catch(Exception $ex){
        return new Response("Failed",$ex->getMessage());
    }

        }

    /**********************************************************/

  

     
}