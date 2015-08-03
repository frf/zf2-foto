<?php
 
namespace Paciente\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\ActionController;
use Propel;


class PacienteController extends AbstractActionController
{
 // GET /transportadoras
    public function msgAction()
    {
        
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        return $viewModel;
    }
    
    // GET /transportadoras
    public function indexAction()
    {
        
          $oPeso = \AntropometriaQuery::create()
                ->filterByCoPaciente(CO_PACIENTE)
                ->filterByCoTipoAnt(CO_TIPO_ANT_PESO)
                ->orderByDtCadastro(\Criteria::ASC)
                ->find();
        
        if($oPeso->count()){
            foreach ($oPeso as $Peso){
                $aPeso[$Peso->getCoAntropometria()]['dt_cadastro'] = $Peso->getDtCadastro('d/m/Y');
                $aPeso[$Peso->getCoAntropometria()]['ds_valor'] = $Peso->getDsValor();
            }                    
        }
        
        return new ViewModel(array(
            'aPeso' => $aPeso
        ));
    }
}
