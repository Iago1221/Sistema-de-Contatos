<?php

namespace App\Controller;

use App\Entity\Contatos;
use App\Entity\Pessoas;
use App\Repository\ContatosRepository;
use App\Repository\PessoasRepository;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;

class TesteController extends AbstractController
{
    public function __construct(private ContatosRepository $contatosRepository, private PessoasRepository $pessoasRepository, private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/teste', name: 'app_teste', methods: ['GET'])]
    public function index(Request $request): Response
    {          
        $contatos_rep = $this->contatosRepository->findAll();

        $pessoas_rep =  $this->pessoasRepository->findAll();


        $session =  $request->getSession();
        $msg_remove_contato = $session->get("success_contato");
        $msg_remove_pessoa = $session->get("success_pessoa");
        $session->remove('success_contato');
        $session->remove('success_pessoa');


        return $this->render('teste/index.html.twig', [

            'contatos_rep' => $contatos_rep,
            'pessoas_rep' => $pessoas_rep,
            'msg_remove_contato' => $msg_remove_contato,
            'msg_remove_pessoa' => $msg_remove_pessoa,

        ]);

        
    }

    #[route('teste/contato_delete/{id}', methods: ['DELETE'])]
    public function deletar_contato(int $id, request $request): Response
    {
        
        $this->contatosRepository->removebyId($id);
        $session =  $request->getSession();
        $session->set("success_contato", "contato removido com sucesso");
        return new RedirectResponse("/teste?p=contatos");
    }

    #[route('teste/pessoa_delete', methods: ['DELETE'])]
    public function deletar_pessoa(request $request): Response 
    {
        
        $id_delete = $request->query->get('id');
        /** @var Pessoas $pessoa */
        $pessoa = $this->pessoasRepository->find("{$id_delete}");
    
        $this->pessoasRepository->remove($pessoa, true);
        $session =  $request->getSession();
        $session->set("success_pessoa", "pessoa removida com sucesso");
        return new RedirectResponse("/teste?p=pessoas");
    }

    #[Route("teste/create_contato", methods: ['GET'])]
    public function add_contato_form(Request $request): Response
    {

        $count = 0;

        $pessoas_rep =  $this->pessoasRepository->findAll();
        
        foreach ($pessoas_rep as $pessoa) {
            $count += 1;
        }

        if ($count == 0){
            $request->getSession()->set("success_contato", "É necessário ter uma pessoa adicionada para adicionar um contato!");
            return new RedirectResponse("/teste?p=contatos");
        }
        else{
            return $this->render("teste/form_contato.html.twig", [

                'pessoas_rep' => $pessoas_rep,
    
            ]);
        }

    }

    #[Route("teste/create_pessoa", methods: ['GET'])]
    public function add_pessoa_form(): Response
    {
        $pessoas_rep =  $this->pessoasRepository->findAll();

        return $this->render("teste/form_pessoa.html.twig", compact('pessoas_rep'));

    }

    #[Route("teste/create_pessoa", methods: ['POST'])]
    public function addPessoa(Request $request): Response 
    {

        $nome = $request->request->get('nome');
        $cpf = $request->request->get('cpf');
        $pessoa = new Pessoas($nome, $cpf);

        $this->pessoasRepository->save($pessoa, true);
        return new RedirectResponse("/teste?p=pessoas");

    }

    #[Route("teste/create_contato", methods: ['POST'])]
    public function addContato(Request $request): Response 
    {

        $pessoa1 = $request->request->get('select_pessoa');
        $pessoa = $this->pessoasRepository->find($pessoa1);
        $tipo = $request->request->get('type_contato');
        $ctt = $request->request->get('contato');
        $contato = new Contatos($tipo, $ctt);
        $pessoa->add_contatos($contato);

        $this->pessoasRepository->save($pessoa, true);
        return new RedirectResponse("/teste?p=contatos");

    }


    #[Route("teste/edit_pessoa/{pessoa}", methods: ['GET'])]
    public function edit_pessoa(Pessoas $pessoa): Response
    {
        $pessoas_rep =  $this->pessoasRepository->findAll();
        return $this->render("teste/editar_pessoa.html.twig", compact('pessoa', 'pessoas_rep'));
    }

    #[Route("teste/edit_contato/{contato}", name:'app_edit_contact', methods: ['GET'])]
    public function edit_contato(Contatos $contato): Response
    {
        $pessoas_rep =  $this->pessoasRepository->findAll();
        return $this->render("teste/editar_contato.html.twig", compact('contato', 'pessoas_rep'));
    }

    #[Route("teste/edit_pessoa/{pessoa}", methods: ['PATCH'])]
    public function save_edit_pessoa(Pessoas $pessoa, request $request): Response
    {

        $request->getSession()->set("success_pessoa", "Pessoa editada com sucesso");
        $pessoa->setName($request->request->get('nome'));
        $pessoa->setCpf($request->request->get('cpf'));
        $this->entityManager->flush();
        
        return new RedirectResponse("/teste?p=pessoas");
    }

    #[Route("teste/edit_contato/{contato}", methods: ['PATCH'])]
    public function save_edit_contato(Contatos $contato, request $request): Response
    {
        $request->getSession()->set("success_contato", "Contato editado com sucesso");
        $contato->setContact($request->request->get('contato'));
        $this->entityManager->flush();

        return new RedirectResponse("/teste?p=contatos");
    }


    #[Route("teste/search", name: 'app_search', methods: ['GET', 'POST'])]
    public function search(Request $request): Response
    {
        $input = $request->request->get('pesquisa');
        $contatos_rep = $this->contatosRepository->findAll();
        $pessoas_rep =  $this->pessoasRepository->findAll();

        return $this->render("teste/search.html.twig", compact('input', 'contatos_rep', 'pessoas_rep'));

    }

}
