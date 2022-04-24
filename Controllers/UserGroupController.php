<?php

namespace Controllers;
use Entity\UserGroup;
use Entity\User;


class UserGroupController extends Controller
{
    public function create(){  //renvoi au formulaire de creation de groupe
        echo $this->twig->render('formCreateGroup.twig', []);
    }
    
    public function add($params){ //recuperation donnees du formulaire de creation de groupe et crée le groupe dans la table UserGroup
        $em=$params["em"];
        $name = $_POST["name"]; //recuperation du titre du groupe
        $username = $_SESSION["username"];
        
        //recuperation de l'entite de l'utilisateur pour la creation du groupe 
        $qb=$em->createQueryBuilder();
        $qb->select('u')
          ->from('Entity\User', 'u')
          ->where('u.username =:username')
          ->setParameter('username', $username)
          ->setMaxResults(1)
        ;
        
        $query = $qb->getQuery();
        $user = $query->getOneOrNullResult();
        
        
        
        $qb=$em->createQueryBuilder();
        $qb->select('u')
            ->from('Entity\UserGroup', 'u')
            ->where('u.name =:name')
            ->setParameter('name', $name )
            ->setMaxResults(1)
        ;
      
      $query = $qb->getQuery();
      $group = $query->getOneOrNullResult();
      
      $alertCreateGroup = false; //false si le groupe n'existe pas
      if($group){
          //le nom du groupe existe deja
          $alertCreateGroup = true;
          echo $this->twig->render('formCreateGroup.twig',['alertCreateGroup' =>$alertCreateGroup]);  
      }
      else{
          //le nom du groupe n'existe pas, je le cree
          //var_dump("création du compte");
          $group = new UserGroup;
          $group->setName($name);
          $group->setUser($user);
          $em->persist($group);
	      $em->flush();
	     
	     //faire renvoyer vers la page de l'utilisateur
	     echo $this->twig->render('userPage.twig',['username' =>$username, 'user' =>$user]);
      }
    }
    
    public function formDelete($params) //renvoi au formulaire de suppression d'un groupe
    {
        $em=$params["em"];
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
          
        
        echo $this->twig->render('formDeleteGroup.twig', ['user'=>$user]);
    }
    
    public function deleteGroup($params)  //supprime le ou les groupe(s) selectionne(s)
    {
        $em=$params["em"];
        $username = $_SESSION['username'];
        
        foreach ($_POST as $name =>$groupId){
            
            //recherche par son Id le groupe dans Entity\UserGroup
            $UserGroupRepository = $em->getRepository('Entity\UserGroup');
            $group = $UserGroupRepository->find($groupId);
            if(!$group)
            {
              echo "erreur !";
            }
            $em->remove($group); //le supprime de la table
            
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
    
    public function groupDetail($params) // récupère l'id du groupe et renvoi les adresses RSS correspondant
    {
        $em=$params["em"];
        $idGroup = $_POST["idGroup"];
        
        $groupRepository = $em->getRepository('Entity\UserGroup');
        $group = $groupRepository->find($idGroup);
        if(!$group)
        {
          echo "erreur !";
        }
        //var_dump($group->getName());die;
        $name = $group->getName();
        $rssArray = array(); //tableau qui va contenir tous les elements 
        foreach ($group->getFeeds() as $feed){
            $addressRss = $feed->getFeedRSS();
            
            $rss = simplexml_load_file($addressRss); //Convertit le fichier RSS (XML) en objet
            //var_dump($rss);die;
            
            
            foreach ($rss->channel as $value){
                foreach ($rss->channel->item as $item) {
        		    $title  	 = (string) $item->title; // Title
        		    $link   	 = (string) $item->link; // Url Link
        		    $description = (string) $item->description; //Description
        		    $rssArray[] = [  "title" => $title,
                		             "link" => $link,
                		             "description" =>$description
        		                   ];
                }
            }
        }
        echo $this->twig->render('userGroup.twig', ['idGroup' =>$idGroup ,'name' =>$name, 'rssArray' =>$rssArray, 'group' =>$group]);
    }
    
}