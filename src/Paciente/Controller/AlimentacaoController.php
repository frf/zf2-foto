<?php

namespace Paciente\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Propel;

class AlimentacaoController extends AbstractActionController
{
 
    // GET /transportadoras
    public function indexAction()
    {
        
        //$aTransportadora = \TransportadoraQuery::create()->find();
       
        return array('message' => $this->getFlashMessenger(),
                    'aTransportadora'=>$aTransportadora);
    }
    // GET /transportadoras
    public function pratoCadastroAction()
    {
        // obtém a requisição
        $request = $this->getRequest();
        
        $oCategoria = \AlimentacaoCategoriaQuery::create()->orderByCoCategoria()->find();
        
        $writer = new \Zend\Log\Writer\Stream('data/log/log-'.date('d-m-Y').".log");
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
           
         if ($request->isPost()) {
                
                $logger->info("Enviando imagem");
                
                // obter e armazenar valores do post
                $postData = $request->getPost()->toArray();
                $File = $request->getFiles()->toArray();
                
                try{                   
                    $aD = explode("/",$postData['dt_cadastro']);
                    $aD2 = explode("-",$postData['dt_cadastro']);
                    
                    if(count($aD) == 1){
                        $aD = $aD2;
                    }
                    
                    $date = new \DateTime($aD['0'].'-'.$aD['1'].'-'.$aD['2'].' '.date('H:i:s'));
                    
                    $dt_cad = $date->format("Y-m-d H:i:s");
                    $dt_cadSoData = $date->format("Y-m-d");
                    
                    $postData['dt_cadastro'] = $dt_cad;
                    $postData['dt_cadastro_f'] = $dt_cadSoData;
		
                    if(is_file($_UP['pasta'] . $nome_final)){
                        unlink($_UP['pasta'] . $nome_final);                       
                    }

		if (isset($_POST['imgBase64'])) {
                    $logger->info("Enviando imagem");
                    
		    if (strpos($_POST['imgBase64'], "data:image/png;base64,") === 0) {
                    	$nome_final = md5(CO_PACIENTE ."_". $dt_cadSoData . "_" . $postData['tipo_refeicao']).'.png';
        		$data = base64_decode(substr($_POST['imgBase64'], strlen("data:image/png;base64,")));
		    } else if (strpos($_POST['imgBase64'], "data:image/jpg;base64,") === 0) {
                    	$nome_final = md5(CO_PACIENTE ."_". $dt_cadSoData . "_" . $postData['tipo_refeicao']).'.jpg';
			$data = base64_decode(substr($_POST['imgBase64'], strlen("data:image/jpg;base64,")));
		    }
			
		    $fd = fopen(PATH_IMAGEM . "/".$nome_final, "wb");

                    $postData['no_file'] = $nome_final;

		    if ($fd) {
        		fwrite($fd, $data);
		        fclose($fd);

                        \AlimentacaoPeer::gravarAlimentacao($postData);
		        echo "Imagem enviada com sucesso!";
		    } else {
		        echo "Erro ao enviar tente novamente!";
		    }
		    die();
		}
#                    return $this->redirect()->toRoute('meus-pratos');
                    
                }  catch (Exception $e){                   
                    $logger->info("ERRO: " . $e->getMessage());
                    #return $this->redirect()->toRoute('balanca');
                }
                
         }
        
        
        return array('message' => $this->getFlashMessenger(),
                    'oCategoria'=>$oCategoria);
    }

    public function listarPratoAction()
    {
        // obtém a requisição
        $request = $this->getRequest();
        
        $aPrato = \AlimentacaoQuery::create()
                ->filterByCoPaciente(CO_PACIENTE)
                ->orderByDtCadastro('desc')
                ->find();
        
        return array('message' => $this->getFlashMessenger(),
                    'aPrato'=>$aPrato);
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
