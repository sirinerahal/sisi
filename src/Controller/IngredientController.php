<?php

namespace App\Controller;

use App\Entity\Ingredient; 
use App\Form\IngredientType;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;




class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
            $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients]);
    }
//ajouter
    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new( Request $request, EntityManagerInterface $manager ): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
            
            $ingredient=$form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash(
                'success',
                'mabrouk l"ingredient  mte3ek a ete cree avec succes!!'
            );
          return $this->redirectToRoute('ingredient.index');
           
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()]);
    }
    //modifier
#[Route('/ingredient/edition/{id}','ingredient.edit',methods:['GET','POST'])]
public function edit(IngredientRepository $repository,int $id, Request $request,EntityManagerInterface $manager, /*Ingredient $ingredient*/ ):response
{
    $ingredient = $repository->findOneBy(["id"=>$id]);
    $form = $this->createForm(IngredientType::class, $ingredient);
    $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
            
            $ingredient=$form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash(
                'success',
                'mabrouk l"ingredient  mte3ek a ete modifier  avec succes sa7it !!'
            );
          return $this->redirectToRoute('ingredient.index');
           
        }

    return $this->render('pages/ingredient/edit.html.twig',['form'=>$form->createView()]);
}
  //supprimer 
  #[Route('/ingredient/suppression/{id}','ingredient.delete',methods:['GET'])]

  public function delete(EntityManagerInterface $manager,int $id,Ingredient $ingredient ,IngredientRepository $repository ):response
  {
    $ingredient = $repository->findOneBy(["id"=>$id]);

   if (!$ingredient)
    {
        $this->addFlash(
            'success',
            'l"ingredient en question n"a pas la hhhhhh'
        );
        return $this->redirectToRoute('ingredient.index');

    }
    $manager->remove($ingredient);
    $manager->flush();
    $this->addFlash(
        'success',
        'mabrouk l"ingredient  mte3ek a ete supprime   avec succes sa7it !!'
    );
    return $this->redirectToRoute('ingredient.index');

}
}

