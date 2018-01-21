<?php
/** \file
 *  Springy.
 *
 *  \brief      Classe para gerenciamento de permissões de identidades autenticadas na aplicação.
 *  \copyright  Copyright (c) 2007-2016 Fernando Val
 *  \author     Allan Marques - allan.marques@ymail.com
 *  \warning    Este arquivo é parte integrante do framework e não pode ser omitido
 *  \version    0.2.2
 *  \ingroup    framework
 */

namespace Springy\Security;

use Springy\Kernel;
use Springy\URI;

/**
 * \brief Classe para gerenciamento de permissões de identidades autenticadas na aplicação.
 */
class AclManager
{
    /// Nome do módulo no qual o usuário se encontra no request atual
    protected $module;
    /// Nome do controller no qual o usuário se encontra no request atual
    protected $controller;
    /// Nome da action na qual o usuário se encontra no request atual
    protected $action;
    /// Prefixo dos módulos
    protected $modulePrefix = '';
    /// Usuário atualmente autenticado no sistema
    protected $user;
    /// Caracter separador utilizado para concatenar o nome da permissão
    protected $separator = '|';
    /// Nome do módulo padrão, usado quando não estiver em nenhum módulo
    protected $defaultModule = 'default';

    /**
     *  \brief Construtor da classe.
     *  \param [in] (\Springy\Security\AclUserInterface) $user.
     */
    public function __construct(AclUserInterface $user)
    {
        $this->user = $user;

        $this->setupCurrentAclObject();
    }

    /**
     *  \brief Define em qual ação de permissão o usuário se encontra atualmente.
     */
    public function setupCurrentAclObject()
    {
        $this->module = substr(Kernel::controllerNamespace(), strlen($this->modulePrefix)) or $this->defaultModule;
        $this->controller = URI::getControllerClass();
        $this->action = URI::getSegment(0);
    }

    /**
     *  \brief Retorna o módulo sendo acessado no request atual.
     *  \return (string).
     */
    public function getCurrentModule()
    {
        return $this->module;
    }

    /**
     *  \brief Retorna o controller sendo acessado no request atual.
     *  \return (string).
     */
    public function getCurrentController()
    {
        return $this->controller;
    }

    /**
     *  \brief Retorna a ação sendo acessada no request atual.
     *  \return (string).
     */
    public function getCurrentAction()
    {
        return $this->action;
    }

    /**
     *  \brief Seta o prefixo dos módulos.
     *  \param [in] (string) $modulePrefix.
     */
    public function setModulePrefix($modulePrefix)
    {
        $this->modulePrefix = $modulePrefix;
    }

    /**
     *  \brief Retorna o prefixo dos módulos.
     *  \return (string).
     */
    public function getModulePrefix()
    {
        return $this->modulePrefix;
    }

    /**
     *  \brief Seta o separador do nome da permissão.
     *  \param [in] (string) $separator.
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     *  \brief Retorna o separador do nome da permissão.
     *  \return (string).
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     *  \brief Seta o nome do módulo padrão.
     *  \param [in] (string) $module.
     */
    public function setDefaultModule($module)
    {
        $this->defaultModule = $module;
    }

    /**
     *  \brief Retorna o nome do módulo padrão.
     *  \return (string).
     */
    public function getDefaultModule()
    {
        return $this->defaultModule;
    }

    /**
     *  \brief Seta o usuário.
     *  \param [in] (\Springy\Security\AclUserInterface) $user.
     */
    public function setAclUser(AclUserInterface $user)
    {
        $this->user = $user;
    }

    /**
     *  \brief Retorna o usuário.
     *  \return (\Springy\Security\AclUserInterface).
     */
    public function getAclUser()
    {
        return $this->user;
    }

    /**
     *  \brief Verifica se o usuário atual tem permissão à acessar o recurso atual.
     *  \return (boolean).
     */
    public function isPermitted()
    {
        return (bool) $this->user->getPermissionFor($this->getAclObjectName());
    }

    /**
     *  \brief Retorna o nome do recurso atual, equivalente à permissão.
     *  \return (string).
     */
    public function getAclObjectName()
    {
        return implode($this->separator, [$this->module, $this->controller, $this->action]);
    }
}