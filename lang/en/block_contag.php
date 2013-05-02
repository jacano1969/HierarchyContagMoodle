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


$string['pluginname'] = 'ConTag';
$string['contag:view'] = 'View ConTag Block and Navigate by Concept Tags';
$string['contag:edit'] = 'Edit and Associate Concept Tags';
$string['blockname'] = 'ConTag';

// for the links
$string['edit_concept_tags'] = 'Edit Concept Tags';
$string['navigate_by_concept_tags'] = 'Navigate by Concept Tags';
// errors
$string['warning_label']="Warning: ";
$string['courseidunknown'] = 'Course ID is unknown.';
$string['unknown_tag_name_left'] = 'The tag name \'';
$string['unknown_tag_name_right'] = '\' does not exist.';

//Strings for view.php
$string['viewingformheader'] = 'Navigate by Concept Tags';
$string['viewingformpageheading'] = 'Navigate by Concept Tags';
$string['table_concept_heading'] = "Concept";
$string['table_items_heading'] = "Items Tagged with Concept";

//Strings for attach_tags.php
$string['editingformheader'] = 'Edit and Associate Concept Tags';
$string['editingformpageheading'] = 'Edit and Associate Concept Tags';

//Strings for edit_tags.php
$string['hierarchyformheader'] = 'Create Conceprs Hierarchy Tree';
$string['hierarchyformpageheading'] = 'Create Concepts Hierarchy Tree';

//marigianna imports 
//for the links
$string['edit_hierarchy_tree'] = 'Edit Concept Tagging Hierarchy Tree';

//edit_tags.php
$string['tree_context_menu'] = 'Tree Context Menu';
$string['create_hierarchy_tree'] = 'Create the Hierarchy Concept Tree. Parent nodes represent more general/easy concepts.';
$string['create_hierarchy_tree_example'] = 'See here for help.';
$string['display_context_menu'] = 'Right click on a node to display context menu.';
$string['rename_menu'] = 'Double click on a node to rename context.';

$string['append'] = 'Append';
$string['remove'] = 'Remove';
$string['expand'] = 'Expand';
$string['collapse'] = 'Collapse';

//alert messages
$string['concept_imported_to_tree'] = 'The concept has also been added to Concept Tagging Hierarchy tree. Edit the tree for changing the concept\'s hierarchy!';
$string['ontology_saved'] = 'Concepts Hierarchy tree was saved successfully!';
$string['ontology_not_saved']='Oops!! Something went wrong! Ontology was not saved.';
$string['dublicates_not_allowed'] = 'Dublicate names are not allowed.Please change and save the ontology again';
$string['malformed_url'] = 'Malformed URL: course id , json object or url are missing.'; 

?>