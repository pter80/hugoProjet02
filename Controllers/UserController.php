<?php
namespace Controllers;

use Entity\User;
use Entity\UserGroup;
class UserController extends Controller

{
  
  public function create()
  {
      echo $this->twig->render('formAccount.twig', []);
  
  }


  public function add($params)
  {
      $em=$params["em"];
      
      //récuper les données du form
      $username = $_POST["username"];
      $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
      $genre = $_POST["genre"];
      $email =  $_POST["email"];
      
      
      //création de l'utilisateur
      $qb=$em->createQueryBuilder();
      $qb->select('u')
        ->from('Entity\User', 'u')
        ->where('u.username =:username')
        ->setParameter('username', $username )
        ->setMaxResults(1)
      ;
      
      $query = $qb->getQuery();
      $user = $query->getOneOrNullResult();
      
      $alertAccount = false; //false si le compte n'existe pas
      if($user){
          //le user existe deja
          $alertAccount = true;
          echo $this->twig->render('formAccount.twig',['alertAccount' =>$alertAccount]);  
      }
      else{
          //le user n'existe pas, je le cree
          //var_dump("création du compte");
          $user = new User();
          $user->setUsername($username);
          $user->setPassword($password);
          $user->setGenre($genre);
          $user->setEmail($email);
          $em->persist($user);
	        $em->flush();
	        echo $this->twig->render('index.html',[]);
	        
      }
      
      //faire la fonction de hash
      
        
      ///////////////////////////////////////////////////////////////
      
      //$existe = password_verify($password , $hash );
      /*
      $url = "https://www.lemonde.fr/rss/une.xml";
      $rss = simplexml_load_file($url);
      echo '<ul>';
      foreach ($rss->channel->item as $item){
       $datetime = date_create($item->pubDate);
       $date = date_format($datetime, 'd M Y, H\hi');
       echo '<li><a href="'.$item->link.'">'.utf8_decode($item->).'</a> ('.$date.')</li>'.;
      }
      echo '</ul>';
      */
  }
  
  public function login(){
    
    echo $this->twig->render('formLogin.twig', []);
    
  }


  public function openSession($params)
  {
    $em=$params["em"];
    if (isset($_SESSION['username'])) {  //Si une session utilisateur existe déja
          $username = $_SESSION['username'];
          
          //recuperation des donnees de l'utilisateur (groupes...)
          $qb=$em->createQueryBuilder(); 
          $qb->select('u')
            ->from('Entity\User', 'u')
            ->where('u.username =:username')
            ->setParameter('username', $username)
            ->setMaxResults(1);
          $query = $qb->getQuery();
          $user = $query->getOneOrNullResult();
          
          echo $this->twig->render('userPage.twig',['username' =>$username, 'user' =>$user]);
           
    } 
    
    else { //Sinon crée la Session
      
      //récupère les données du formulaire
      //$em=$params["em"];
      $username = $_POST["username"];
      $password = $_POST["password"];
      
      //vérifier si le compte existe dans la table
      $qb=$em->createQueryBuilder();
        $qb->select('u')
          ->from('Entity\User', 'u')
          ->where('u.username =:username')
          ->setParameter('username', $username)
          ->setMaxResults(1)
        ;
        
      $query = $qb->getQuery();
      $user = $query->getOneOrNullResult();
      
      //var_dump($user);die;
      $alertLogin = false; //false si le username ou le mdp est correcte 
    
      if($user){  //si le user existe
        if (password_verify($password,$user->getPassword())) {  //et si le mot de passe correspond
          session_destroy();
        
          session_start(); //creation de la session
        
          $_SESSION["username"]=$username;
   
          echo $this->twig->render('userPage.twig',['username' =>$username, 'user' =>$user ]);
        } 
        else { //si le mdp est incorrecte
          $alertLogin = true; 
          echo $this->twig->render('formLogin.twig',['alertLogin' =>$alertLogin]);
          
        }
      }
      else { //si le user n'existe pas 
        $alertLogin = true;
        echo $this->twig->render('formLogin.twig',['alertLogin' =>$alertLogin]);
      }
      
    }
    
  }
  
  public function closeSession()
  {
    
    //var_dump($_SESSION["username"]);
    unset($_SESSION['username']);
    //var_dump($_SESSION);
    echo $this->twig->render('disconnect.twig',[]);  
  }
  
  /*
   public function testURL()
  {
    $url = "https://www.lemonde.fr/rss/une.xml";
    if (filter_var($url, FILTER_VALIDATE_URL)) { //vérifier si c'est une URL
        if(strrchr($url, '.') == ".xml"){  //vérifier si c'est bien un XML
  	      echo("$url is a valid URL");
        }
        else{
          echo("url is not a valid URL");die;
        }
        
    } else {
        echo("$url is not a valid URL");
    }
    die;
  }
  */
  
}