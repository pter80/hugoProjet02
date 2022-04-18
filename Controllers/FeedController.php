<?php
namespace Controllers;

use Entity\User;
use Entity\UserGroup;
use Entity\Feed;

class FeedController extends Controller

{
  
  public function create() //renvoi au formulaire de creation de feed
  {
      $idGroup = $_POST["idGroup"]; //on recupere l'id du groupe dans lequel on est
      echo $this->twig->render('formCreateFeed.twig', ['idGroup' =>$idGroup]);
  
  }
  
  public function add($params)//recuperation donnees du formulaire d'ajout de flux RSS et crée l'élément dans la table Feed
  {
    $em=$params["em"];
    
    $idGroup = $_POST["idGroup"];//on recupere l'id du groupe dans lequel on est
    $feedRSS = $_POST["url"]; //on recupere le flux Rss
    $username = $_SESSION["username"];
    
    $alertURL = false; //false si l'URL est correct
    
    //verifie si l'URL est correct et si c'est bien un fichier XML
    $rss = @simplexml_load_file($feedRSS); //@ = n'affiche pas les messages d'erreur
    if($rss == false ){
      $alertURL = true;
      echo $this->twig->render('formCreateFeed.twig', ['idGroup' =>$idGroup, 'alertURL' =>$alertURL]); //renvoie au formulaire avec message d'erreur
      die;
    }
    

    //recuperation de l'entite de l'utilisateur pour renvoi userPage
    $qb=$em->createQueryBuilder();
    $qb->select('u')
      ->from('Entity\User', 'u')
      ->where('u.username =:username')
      ->setParameter('username', $username)
      ->setMaxResults(1)
    ;
    
    $query = $qb->getQuery();
    $user = $query->getOneOrNullResult();
    
    
    //On recupere l'entite du groupe
    $UserGroupRepository = $em->getRepository('Entity\UserGroup');
    $group = $UserGroupRepository->find($idGroup);
    if(!$group)
    {
      echo "erreur !";
    }
    
    
    //entite feed
    $qb=$em->createQueryBuilder();
    $qb->select('f')
        ->from('Entity\Feed', 'f')
        ->where('f.feedRSS =:feedRSS')
        ->setParameter('feedRSS', $feedRSS )
        ->setMaxResults(1)
    ;
  
   $query = $qb->getQuery();
   $feed = $query->getOneOrNullResult();
 
   $alertCreateFeed = false; //false si le feed n'existe pas
   if($feed){
      //le feed existe deja dans le groupe
      $alertCreateFeed = true;
      echo $this->twig->render('formCreateFeed.twig',['alertCreateFeed' =>$alertCreateFeed]);  
   }
   else{
      //le flux n'existe pas dans le groupe, je le cree
      $feed = new Feed;
      foreach ($rss->channel as $value){
        $titleRss = (string) $value->title;
        $feed->setTitle($titleRss);
        
      }
      $feed->setFeedRSS($feedRSS);
      $feed->setGroup($group);
      $em->persist($feed);
      $em->flush();
    }  
    
      
      
      
     
     //faire renvoyer vers la page de l'utilisateur
     echo $this->twig->render('userPage.twig',['username' =>$username, 'user' =>$user]);
     
  }
    
  
  public function formDelete($params) //renvoi au formulaire de suppression d'un fluxRSS
  {
    $em=$params["em"];
    $username = $_SESSION['username'];
    
    $idGroup = $_POST["idGroup"]; //on recupere l'id du groupe dans lequel on est
    
    //recupere l'entite du group pour par la suite afficher les différents flux RSS qu'il contient dans le form
    $UserGroupRepository = $em->getRepository('Entity\UserGroup');
    $group = $UserGroupRepository->find($idGroup);
    if(!$group)
    {
      echo "erreur !";
    }
    
    echo $this->twig->render('formDeleteFeed.twig', ['group'=>$group]);

  }
  
  public function deleteFeed($params)//supprime le ou les fluxRSS selectionne(s)
  {
    $em=$params["em"];
    $username = $_SESSION['username'];
   
    foreach ($_POST as $feedRSS =>$feedId){
     
      //recherche par son Id le feed dans Entity\Feed
      $FeedRepository = $em->getRepository('Entity\Feed');
      $feed = $FeedRepository->find($feedId);
      if(!$feed)
      {
      echo "erreur !";
      }
      $em->remove($feed); //le supprime de la table
      
    }
    
    $em->flush(); //valide la suppression
          
    //recuperation des donnees de l'utilisateur pour le renvoyer a la vue
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
  
  
  public function feedDetail(){
    var_dump($_POST);die;
  }
  
}