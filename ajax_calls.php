<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Constants declaration
 */
 
require_once('../../config.php');
require_once ('lib.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

//ajax calls
//if we have chosen to delete at list one node
//if (isset($_POST['remove_node_id']) && isset($_POST['remove_node_text'])) {
if (isset($_POST['subtree_to_remove'])){
	//if the first node to delete
	
	//create_to_delete_nodes_list($_POST['remove_node_id'],$_POST['remove_node_text']);
	
	
	create_to_delete_nodes_list($_POST['subtree_to_remove']);
	//node is deleted from js
	//the new json will not have the node ?? - replace without saving?????????
	
	//
	
}
?>