<?php
/**
 * Mini2SAE - PHP framework for SAE
 *
 * @author Caleng Tan <tcm1024@gmail.com>
 * @copyright Copyright(C)2010, Caleng Tan
 * @link http://code.google.com/p/fabos/
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version Mini2SAE v0.2
 */
require_once 'config/prepend.php';
require_once 'include/App.php';
App::init();

//特殊处理的路径
App::loader('GET /', "IndexAction::actDefault");

App::run();

//App::loader('GET /', function(){echo 'HelloWorld!';});
//App::run();
?>
