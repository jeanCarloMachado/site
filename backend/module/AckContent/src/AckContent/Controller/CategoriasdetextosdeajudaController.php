<?php
/**
 * gerenciamento CategoriasdetextosdeajudaController
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
namespace AckContent\Controller;
use AckMvc\Controller\AbstractTableRowController as Controller;
/**
 * gerenciamento CategoriasdetextosdeajudaController
 *
 * @category Business
 * @package  AckDefault
 * @author   Jean Carlo Machado <j34nc4rl0@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 3 2013
 * @link     http://github.com/zendframework/zf2 for the canonical source repository
 */
class CategoriasdetextosdeajudaController extends Controller
{
	protected $models = array('default'=>'\AckContent\Model\AjudaCategorias');

	protected $title = 'Categoriasdetextosdeajuda';
	protected $config = array (
		'global' => array(
			//'columnSpacing' => 230,
			'showId' => true,
			'elementsSettings'=>array('nome'=>array('columnSpacing'=>600,'orderSelector'=>true)),
			'blacklist'=>array('id','ordem','status','visivel')
		),
		'index' => array(
			'whitelist'=>array('id','fakeid','nome','visivel')
		)
	);
}