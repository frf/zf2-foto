<?php
 
namespace Paciente\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Propel;
 
class AntropometriaController extends AbstractActionController
{
 
    // GET /transportadoras
    public function indexAction()
    {
        
        //$aTransportadora = \TransportadoraQuery::create()->find();
       
        return array('message' => $this->getFlashMessenger(),
                    'aTransportadora'=>$aTransportadora);
    }
    // GET /transportadoras
    public function balancaAction()
    {
        
        // obtém a requisição
        $request = $this->getRequest();
        
         if ($request->isPost()) {
                // obter e armazenar valores do post
                $postData = $request->getPost()->toArray();
          
                try{
                 
                    \AntropometriaPeer::gravarPeso($postData);
                     $this->flashMessenger()->addSuccessMessage("Registro adicionado com sucesso");
                    return $this->redirect()->toRoute("paciente/balanca");
                    
                }  catch (Exception $e){
                     // adicionar mensagem de sucesso
                    #$this->flashMessenger()->addSuccessMessage($e->getMessage());
                    #$this->logger()->info($e->getMessage());
                    
                    return $this->redirect()->toRoute("paciente/balanca");
                }
                
         }
        
        $aBalanca = \AntropometriaQuery::create()
                ->filterByCoPaciente(CO_PACIENTE)
                ->filterByCoTipoAnt(CO_TIPO_ANT_PESO)
                ->orderByDtCadastro(\Criteria::DESC)
                ->find();
       
        
        return array('message' => $this->getFlashMessenger(),
                    'aBalanca'=>$aBalanca);
    }

    // DELETE /transportadoras/deletar/id
    public function excluirBalancaAction()
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
                    
                     return $this->redirect()->toRoute("paciente/balanca");
                }
                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Registro excluído com sucesso");

            }
        }else{
             $this->flashMessenger()->addErrorMessage("Registro não encotrado");
             return $this->redirect()->toRoute("paciente/balanca");
        }
        // redirecionar para action index
        return $this->redirect()->toRoute("paciente/balanca");
    }
    // GET /transportadoras/novo
    public function novoAction()
    {
        return array('message' => $this->getFlashMessenger());
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