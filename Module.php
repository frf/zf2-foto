<?php

# namespace no qual se encontra Module.php
namespace Paciente;
use Zend\ModuleManager\ModuleManager;
use Zend\Session\Container;

class Module
{

     public function init(ModuleManager $moduleManager){
         
           
        define("CO_TIPO_ANT_PESO", 1);
        define("CO_TIPO_ANT_ALTURA", 2);
        
        $session = new Container('base');
        $oPessoa = $session->offsetGet('user');
        
        if($oPessoa->id){
            define("CO_PESSOA", $oPessoa->id);
            define("CO_PACIENTE", $oPessoa->id);
            define("NO_PACIENTE", $oPessoa->nome);
            define("GENDER", $oPessoa->gender);
        }
        
        define("PATH_IMAGEM", realpath(dirname(__FILE__) . '/../../public/img_alimento'));
    }
     /**
     * Executada no bootstrap do módulo
     * 
     * @param MvcEvent $e
     */
    public function onBootstrap($e) {
           
	if(defined('CO_PESSOA')){
            
            $oAntropometria = \AntropometriaQuery::create()
                    ->filterByCoPaciente(CO_PESSOA)
                    ->filterByCoTipoAnt(CO_TIPO_ANT_ALTURA)
                    ->orderByCoAntropometria(\Criteria::DESC)
                    ->findOne();
            
              $oPeso = \AntropometriaQuery::create()
                ->filterByCoPaciente(CO_PACIENTE)
                ->filterByCoTipoAnt(CO_TIPO_ANT_PESO)
                ->orderByDtCadastro(\Criteria::ASC)
                ->find();
        
            if($oPeso->count()){
                define("PESO", true);  
            }else{
                define("PESO", false);
            }
            
            if($oAntropometria){
                define("ALTURA_PACIENTE", $oAntropometria->getDsValor());                
            }else{
                define("DADOS_INCOMPLETOS", true);
            }
        
        }
        
    }
    # include de arquivo para outras configuracoes
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    # autoloader para namespaces
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}