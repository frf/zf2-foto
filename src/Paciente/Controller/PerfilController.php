<?php
 
namespace Paciente\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Propel;
 
class PerfilController extends AbstractActionController
{

    // GET /transportadoras
    public function indexAction()
    {
        
        // obtém a requisição
        $request = $this->getRequest();
        
         if ($request->isPost()) {
                // obter e armazenar valores do post
                $postData = $request->getPost()->toArray();
          
                try{
                 
                    if(isset($postData['altura'])){
                        \AntropometriaPeer::gravarAltura($postData);
                    }
                    
                    #\AntropometriaPeer::gravarPeso($postData);
                    
                    return $this->redirect()->toRoute('paciente/perfil');
                    
                }  catch (Exception $e){
                     // adicionar mensagem de sucesso
                    #$this->flashMessenger()->addSuccessMessage($e->getMessage());
                    #$this->logger()->info($e->getMessage());
                    
                    return $this->redirect()->toRoute('paciente/perfil');
                }
                
         }
        
        $oPaciente = \PacienteQuery::create()
                ->filterByCoPaciente(CO_PACIENTE)
                ->findOne();
       
       
        
        $aAltura = \AntropometriaQuery::create()
                ->filterByCoPaciente(CO_PACIENTE)
                ->filterByCoTipoAnt(CO_TIPO_ANT_ALTURA)
                ->find();
       
        
        return array('message' => $this->getFlashMessenger(),
                    'oPaciente'=>$oPaciente,
                     'aAltura'=>$aAltura);
    }

    // DELETE /transportadoras/deletar/id
    public function excluirAlturaAction()
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
                    
                     return $this->redirect()->toRoute('paciente/perfil');
                }
                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Registro excluído com sucesso");

            }
        }else{
             $this->flashMessenger()->addErrorMessage("Registro não encotrado");
        }
        // redirecionar para action index
        return $this->redirect()->toRoute('paciente/perfil');
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