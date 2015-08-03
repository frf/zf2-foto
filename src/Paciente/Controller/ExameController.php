<?php
 
namespace Paciente\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Propel;
 
class ExameController extends AbstractActionController
{
 
    // GET /transportadoras
    public function indexAction()
    {
        
        //$aTransportadora = \TransportadoraQuery::create()->find();
       
        return array('message' => $this->getFlashMessenger(),
                    'aTransportadora'=>$aTransportadora);
    }
    // GET /transportadoras
    public function exameAction()
    {
        
        // obtém a requisição
        $request = $this->getRequest();
        
         if ($request->isPost()) {
                // obter e armazenar valores do post
                $postData = $request->getPost()->toArray();
          
                try{
                 
                    \AntropometriaPeer::gravarPeso($postData);
                    
                    return $this->redirect()->toRoute('balanca');
                    
                }  catch (Exception $e){
                     // adicionar mensagem de sucesso
                    #$this->flashMessenger()->addSuccessMessage($e->getMessage());
                    #$this->logger()->info($e->getMessage());
                    
                    return $this->redirect()->toRoute('balanca');
                }
                
         }
        
        $aBalanca = \AntropometriaQuery::create()
                ->filterByCoPaciente(CO_PACIENTE)
                ->filterByCoTipoAnt(CO_TIPO_ANT_PESO)
                ->find();
       
        
        return array('message' => $this->getFlashMessenger(),
                    'aBalanca'=>$aBalanca);
    }

    // DELETE /transportadoras/deletar/id
    public function excluirExameAction()
    {
        // filtra id passsado pela url
        $id = $this->params()->fromRoute('id', 0);

        if($id != "" && is_numeric($id)){
            // se id = 0 ou não informado redirecione para transportadoras
            if (!$id) {
                // adicionar mensagem de erro
                $this->flashMessenger()->addErrorMessage("Registro não encontrado");

            } else {
                // aqui vai a lógica para deletar o contato no banco
                // 1 - solicitar serviço para pegar o model responsável pelo delete
                // 2 - deleta contato
                try{
                 $oAntropometria = \AntropometriaQuery::create()
                         ->filterByCoAntropometria($id)
                         ->findOne();
                 #$oPessoa = \PessoaQuery::create()->filterByCoPessoa($id)->findOne();
                 
                 $oAntropometria->delete();
                 #$oPessoa->delete();
                 
                }  catch (Exception $e){
                     // adicionar mensagem de sucesso
                    $this->flashMessenger()->addSuccessMessage($e->getMessage());
                    #$this->logger()->info($e->getMessage());
                    
                     return $this->redirect()->toRoute('balanca');
                }
                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Registro excluído com sucesso");

            }
        }else{
             $this->flashMessenger()->addErrorMessage("Registro não encotrado");
        }
        // redirecionar para action index
        return $this->redirect()->toRoute('balanca');
    }
    // GET /transportadoras/novo
    public function novoAction()
    {
        return array('message' => $this->getFlashMessenger());
    }
 
    // POST /transportadoras/adicionar
    public function adicionarAction()
    {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // obter e armazenar valores do post
            $postData = $request->getPost()->toArray();
            
            $formularioValido = true;

            // verifica se o formulário segue a validação proposta
            if ($formularioValido) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - solicitar serviço para pegar o model responsável pela adição
                // 2 - inserir dados no banco pelo model
                try{
                 
                    \TransportadoraPeer::gravaTransportadora($postData);
                }  catch (Exception $e){
                     // adicionar mensagem de sucesso
                    $this->flashMessenger()->addSuccessMessage($e->getMessage());
                    #$this->logger()->info($e->getMessage());
                    
                     return $this->redirect()->toRoute('transportadoras');
                }
                

                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Transportadora adicionada com sucesso");
                #$this->logger()->info('Transportadora adicionada com sucesso');

                // redirecionar para action index no controller transportadoras
                return $this->redirect()->toRoute('transportadoras');
            } else {
                // adicionar mensagem de erro
                $this->flashMessenger()->addErrorMessage("Erro ao adicionar transportadora");
                #$this->logger()->err('Erro ao adicionar cliente');

                // redirecionar para action novo no controllers transportadoras
                return $this->redirect()->toRoute('transportadoras', array('action' => 'novo'));
            }
        }
    }
 
    // GET /transportadoras/detalhes/id
    public function detalhesAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para transportadoras
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addErrorMessage("Transportadora não encotrada");

            
            // redirecionar para action index
            return $this->redirect()->toRoute('transportadoras');
        }

        // aqui vai a lógica para pegar os dados referente ao contato
        // 1 - solicitar serviço para pegar o model responsável pelo find
        // 2 - solicitar form com dados desse contato encontrado

        // formulário com dados preenchidos
         $cliente = \TransportadoraQuery::create()->filterByCoTransportadora($id)->findOne();


        // dados eviados para detalhes.phtml
        return array('id' => $id, 'oTransportadora' => $cliente, 'message' => $this->getFlashMessenger());
    }
 
    // GET /transportadoras/editar/id
    public function editarAction()
    {
        #print "<pre>";
        #print_r($this->params()->fromRoute());
        
        #exit;
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para transportadoras
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addErrorMessage("Contato não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('transportadoras');
        }

        // aqui vai a lógica para pegar os dados referente ao contato
        // 1 - solicitar serviço para pegar o model responsável pelo find
        // 2 - solicitar form com dados desse contato encontrado

        // formulário com dados preenchidos
        
        $oTransportadora = \TransportadoraQuery::create()
                ->filterByCoTransportadora($id)
                ->findOne();

        // dados eviados para editar.phtml
        return array('id' => $id, 
            'oTransportadora' => $oTransportadora, 
            'message' => $this->getFlashMessenger());
    }
 
    // PUT /transportadoras/editar/id
    public function atualizarAction()
    {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // obter e armazenar valores do post
            $postData = $request->getPost()->toArray();
            $formularioValido = true;

            // verifica se o formulário segue a validação proposta
            if ($formularioValido) {
                // aqui vai a lógica para editar os dados à tabela no banco
                // 1 - solicitar serviço para pegar o model responsável pela atualização
                // 2 - editar dados no banco pelo model

                try{
                 
                    \TransportadoraPeer::gravaTransportadora($postData);
                }  catch (Exception $e){
                     // adicionar mensagem de sucesso
                    $this->flashMessenger()->addSuccessMessage($e->getMessage());
                    #$this->logger()->info($e->getMessage());
                    
                     return $this->redirect()->toRoute('transportadoras');
                }
                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Transportadora editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('transportadoras', array("action" => "detalhes", "id" => $postData['co_cliente'],));
            } else {
                // adicionar mensagem de erro
                $this->flashMessenger()->addErrorMessage("Erro ao editar cliente");

                // redirecionar para action editar
                return $this->redirect()->toRoute('transportadoras', array('action' => 'editar', "id" => $postData['co_cliente'],));
            }
        }
    }
 
    // DELETE /transportadoras/deletar/id
    public function deletarAction()
    {
        // filtra id passsado pela url
        $id = $this->params()->fromRoute('id', 0);

        if($id != "" && is_numeric($id)){
            // se id = 0 ou não informado redirecione para transportadoras
            if (!$id) {
                // adicionar mensagem de erro
                $this->flashMessenger()->addErrorMessage("Transportadora não encotrada");

            } else {
                // aqui vai a lógica para deletar o contato no banco
                // 1 - solicitar serviço para pegar o model responsável pelo delete
                // 2 - deleta contato
                try{
                 $oTransportadora = \TransportadoraQuery::create()
                         ->filterByCoTransportadora($id)
                         ->findOne();
                 #$oPessoa = \PessoaQuery::create()->filterByCoPessoa($id)->findOne();
                 
                 $oTransportadora->delete();
                 #$oPessoa->delete();
                 
                }  catch (Exception $e){
                     // adicionar mensagem de sucesso
                    $this->flashMessenger()->addSuccessMessage($e->getMessage());
                    #$this->logger()->info($e->getMessage());
                    
                     return $this->redirect()->toRoute('transportadoras');
                }
                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Transportadora deletado com sucesso");

            }
        }else{
             $this->flashMessenger()->addErrorMessage("Transportadora não encotrado");
        }
        // redirecionar para action index
        return $this->redirect()->toRoute('transportadoras');
    }
 
    // Filter Flash Messenger
    private function getFlashMessenger()
    {
        $messenger = array();
        $flashMessenger = $this->flashMessenger();

        if ($flashMessenger->hasSuccessMessages())
            $messenger['alert-success'] = array_shift($flashMessenger->getSuccessMessages());

        if ($flashMessenger->hasErrorMessages())
            $messenger['alert-error'] = array_shift($flashMessenger->getErrorMessages());
        
        if ($flashMessenger->hasInfoMessages())
            $messenger['alert-info'] = array_shift($flashMessenger->getInfoMessages());

        return $messenger;
    }
}