<?php

return array(
    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'Paciente\Controller\Paciente' => 'Paciente\Controller\PacienteController',
            'Paciente\Controller\Alimentacao' => 'Paciente\Controller\AlimentacaoController',
            'Paciente\Controller\Antropometria' => 'Paciente\Controller\AntropometriaController',
            'Paciente\Controller\Perfil' => 'Paciente\Controller\PerfilController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'paciente' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/paciente',
                    'defaults' => array(
                        'controller' => 'Paciente\Controller\Paciente',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'msg' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/msg',
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Paciente',
                                'action' => 'msg',
                            ),
                        ),
                    ),
                    'balanca' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/balanca',
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Antropometria',
                                'action' => 'balanca',
                            ),
                        ),
                    ),
                    'excluir-balanca' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/excluir-balanca[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Antropometria',
                                'action' => 'excluir-balanca',
                            ),
                        ),
                    ),
                    'listar-prato' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/listar-prato',
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Alimentacao',
                                'action' => 'listar-prato',
                            ),
                        ),
                    ),
                    'cadastrar-prato' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/cadastrar-prato',
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Alimentacao',
                                'action' => 'prato-cadastro',
                            ),
                        ),
                    ),
                    'perfil' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/perfil',
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Perfil',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'excluir-altura' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/excluir-altura[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Perfil',
                                'action' => 'excluir-altura',
                            ),
                        ),
                    ),
                     'teste' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/teste',
                            'defaults' => array(
                                'controller' => 'Paciente\Controller\Paciente',
                                'action' => 'teste',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);