<?php
/**
 * gestão de usuários
 *
 * AckDefault - Cub
 *
 * LICENSE:  This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 * PHP version 5
 *
 * @category  WebApps
 * @package   AckDefault
 * @author    Jean Carlo Machado <j34nc4rl0@gmail.com>
 * @copyright 2013 Copyright (C) CUB
 * @license   http://www.gnu.org/copyleft/lesser.html  LGPL License 3 2013
 * @version   GIT: <6.4>
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 */
namespace AckUsers\Controller;
use AckMvc\Controller\AbstractTableRowController;
use AckUsers\Traits\AuthenticatedController;

/**
 * controller de usuários
 *
 * @category Library
 * @package  AckDefault
 * @author   Jean Carlo Machado <j34nc4rl0@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 3 2013
 * @link     http://github.com/zendframework/zf2 for the canonical source repository
 */
class UsuariosController extends AbstractTableRowController
{
    use AuthenticatedController;

    protected $models =  array("default" => "\AckUsers\Model\Users");

    protected $config = array(
        "global"=>array(
            "blacklist" => array("senha","dtinc","status","primeirasenha","ultimoacesso",'salt','classname'),
        ),
        "lista" => array(
            "whitelist"=>array("nome", "email"),
        ),
    );

     /**
     * perfil do usuário
     * @return [type] [description]
     */
    public function perfilAction()
    {
        /** @var auth  */
        $auth = $this->getServiceLocator()->get('Auth');

        if (!$auth->hasIdentity()) {
            $this->redirect()->toRoute('login');
        }

        $user = $auth->getIdentity();

        $this->getEvent()->getRouteMatch()->setParam('id', $user->getId()->getBruteVal());
        $this->getEvent()->getRouteMatch()->setParam('action', 'editar');

        $result =  parent::editarAction();

        return $result;
    }

}