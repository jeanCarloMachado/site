<?php
/**
 * perfil de usuário
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
use \AckCore\HtmlElements\Link;
use \AckCore\Utils\Encryption;
use \AckCore\Utils\String;
use \AckKnow\Model\Lores;
use \AckMvc\Controller\TableRowAutomatorAbstract;
/**
 * perfil de usuário
 *
 * @category Business
 * @package  AckDefault
 * @author   Jean Carlo Machado <j34nc4rl0@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 3 2013
 * @link     http://github.com/zendframework/zf2 for the canonical source repository
 */
class PerfilController extends TableRowAutomatorAbstract
{
    /**
     * [$models description]
     * @var array
     */
    protected $models = array("default"=>"\AckUsers\Model\Users");
    protected $title = "Meu perfil";
    /**
     * @author j34nc4rl0@gmail.com
     * configurações do controlador do ack
     * é dividido por sessões que repesentam as actions do sistema, sendo que todas as informações
     * nessas sessões serão enviados para a action em questão e poderão ser acessadas diretamente,
     * a sessão "global" é a excessão, esta enviará o seu conteúdo para todas as actions
     *
     *
     * as opções disponíveis para as sessões são:
     * @var  boolen disableLoadMore desabilita o carregar mais para páginas index
     * @var  boolean disableAutoParentFull ???
     * @var  boolean disableSuperiorListMenu desabilita o menu superior com os botões de adicionar e remover elementos
     *
     * Nota: esta documentação pode não refletir a relidade da última versão da biblioteca,
     * sendo possível haver novas opções de configuração ainda não documentadas
     */
    protected $config = array(
        "global"=>array(
            "disableLoadMore"=>true,
            "disableAutoParentFull" => true,
            "disableSuperiorListMenu" => true,
            "disableBackButtons"=>true,
            "disableTitlePluralizer" => true,
        )
    );

    /**
     * estas classes implementam o design pattern observer,
     * servindo para prover recursos em momentos que o controlador
     * notifica aos observadores quando algo importante ocorreu
     * @var array
     */
    protected $observers = array(
                    "\AckMvc\Controller\Observer\Authenticator",
                    "\AckMvc\Controller\Observer\FacadeSyncer",
                    "\AckCore\Observer\MetatagsManager",
                    //adiciona o observer de permissão de usuaŕio devil
                    "\AckUsers\Observer\DevilPermissionTester",
    );

    /**
     * pega os dados das categorias pra mandar para a renderização
     * @param unknown $config
     */
    protected function beforeReturn(&$config=null)
    {
        //###################################################################################
        //################################# pega o conhecimento atual ###########################################
        //###################################################################################
        $modelLores = new Lores;
        $total = $modelLores->count();
        $lore = $modelLores->toObject()->onlyAvailable()->get(array(),array("limit"=>array("count"=>1),"order"=>"total_lido ASC"));
        $lore = reset($lore);
        $config["know"] = Link::Factory($lore->vars["titulo"])->setName("lore")->setTitle($lore->getTitulo()->getVal())->setValue(strip_tags(String::showNChars(Encryption::decode($lore->getAbstrato()->getVal()))))->setPermission("+rw")->setUrl("/ack/conhecimentos/editar/".$lore->getId()->getVal());
        //###################################################################################
        //################################# END pega o conhecimento atual ########################################
        //###################################################################################
        //###################################################################################
        //################################# da merge dos lembretes ###########################################
        //###################################################################################
        $modelReminders = new \AckAgenda\Model\Reminders;
        $reminders = $modelReminders->toObject()->onlyAvailable()->get();
        $config["reminderStr"] =  "";

        foreach ($reminders as $reminder) {
            $config["reminderStr"].="<a href='/ack/lembretes/editar/".$reminder->getId()->getBruteVal()."'>".$reminder->getNome()->getVal()."</a> ";
        }

        //###################################################################################
        //################################# END da merge dos lembretes ########################################
        //###################################################################################
        //###################################################################################
        //################################# da merge dos tarefas ###########################################
        //###################################################################################
        $modelTasks = new \AckAgenda\Model\Tasks;
        $tasks = $modelTasks->toObject()->onlyNotDeleted()->get();
        $config["tasksStr"] =  "";

        foreach ($tasks as $task) {
            $config["tasksStr"].="<a href='/ack/tarefas/editar/".$task->getId()->getBruteVal()."'>".$task->getNome()->getVal()."</a> ";
        }

        //###################################################################################
        //################################# END da merge dos tarefas ########################################
        //###################################################################################
    }
}