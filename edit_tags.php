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

// "Create, edit and associate concept tags" page (viewable by teacher)
// List all items, each followed by what tags they have (and allow the user to edit them)

// A table

// Anything labelled CUSTOM ITEMS is for a future feature (it already works somewhat, but is disabled for now).

require_once ('../../config.php');
require_once ('lib.php');
require_once('hierarchy_tree_lib.php');

/*lines for debugging code*/
/*ini_set('display_errors',1);
 error_reporting(E_ALL);*/

// returns '"item1","item2","item3"' etc - or '' if the array is empty
function php_string_array_to_javascript_array_contents($string_array) {
	return $string_array ? '"' . implode('" , "', $string_array) . '"' : '';
}

// possible parameter names
$CONTAG_ASSOCIATE_BUTTON_NAME = "associate";
$CONTAG_UNASSOCIATE_KEY_NAME = "unassociate";
$CONTAG_DELETE_TAG_KEY_NAME = "delete_tag";
$CONTAG_TAG_FIELD_NAME = "tagstoadd";
$CONTAG_RENAME_TAG_KEY_NAME = "rename";
// CUSTOM ITEMS (feature currently disabled)
$CONTAG_ADD_CUSTOM_KEY_NAME = 'addcustom';
$CONTAG_CUSTOM_SELECT_KEY_NAME = 'custom_select';
$CONTAG_DELETE_CUSTOM_ITEM_KEY_NAME = 'del_custom_item';

//new tags added by *marigianna - C.S.D. U.O.C.
//$CONTAG_JSON_PATH_SERVER =  "/usr/share/tomcat7/lib/json";
//$CONTAG_JSON_PATH_LOCAL = getcwd().'/json/';
$CONTAG_JSON_URL = $CFG -> wwwroot . '/blocks/contag/json/';

//$WEB_SERVICE_URL = 'http://tsl7.csd.uoc.gr:8080/HierarchyServices/rest/createontology';

$an_error = "";

// Set up necessary parameters
$courseid = required_param('id', PARAM_INT);

$url = new moodle_url('/blocks/contag/edit_tags.php', array('id' => $courseid));
$PAGE -> set_url($url);

// SECURITY: Basic access control checks
if (!$course = $DB -> get_record('course', array('id' => $courseid))) {
	print_error('courseidunknown', 'block_contag');
}

require_login($course -> id);
// SECURITY: make sure the user has access to this course and is logged in

//tree - css requirements

// Start of AUTOCOMPLETE code - from http://tracker.moodle.org/browse/MDL-19865, http://developer.yahoo.com/yui/autocomplete/, and http://developer.yahoo.com/yui/examples/autocomplete/ac_basic_array.html
require_js(array('yui_yahoo', 'yui_dom-event', 'yui_connection', 'yui_datasource', 'yui_autocomplete'));

/* DISPLAY THE PAGE */

// Set up necessary strings
$formheader = get_string('hierarchyformheader', 'block_contag');

// Print page elements
$navigation = build_navigation($formheader);
?>
<link rel="stylesheet" type="text/css" href="css/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="css/themes/icon.css">
<link rel="stylesheet" type="text/css" href="css/demo.css">
<script type="text/javascript" src="javascript/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="javascript/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="javascript/jquery.easyui.min.js"></script>
<script type="text/javascript" src="javascript/tree_functions.js"></script>
<?
print_header_simple("$formheader", "", $navigation, "", "", true, "");
$OUTPUT->heading(get_string('editingformpageheading','block_contag'));

$context = get_context_instance(CONTEXT_COURSE, $courseid);


if (has_capability('block/contag:edit', $context)){ // can they edit hierarchy?

?>


<script type="text/javascript">
	var submitting = false;

	function askConfirm() {
		if (!submitting) {// if this is a non-submit event, but there are values to submit
			return "You have entered tags in the tagboxes, but haven't clicked 'Save' yet.";
		}
	}

	//uncomment at the end of debugging
  window.onbeforeunload = askConfirm;
</script>
<h2><? echo $course -> fullname . " " . get_string('tree_context_menu', 'block_contag'); ?></h2>
<?

/*print('
 <form name="hierarchytreeform" method="post" action="edit_tags.php?id=' . $courseid . '" onSubmit="submitting=true;return true;" >
 '); */
	?>
	<div class="demo-info">
		<!-- <div class="demo-tip icon-tip"></div> -->
		<div>
			<p>
				<?
				echo get_string('create_hierarchy_tree', 'block_contag');
 ?>
			</p>
			<p>
				<a href="css/themes/default/images/hierarchy_tree_tutorial.png" class="cssmouseover">
				<?
					echo get_string('create_hierarchy_tree_example', 'block_contag');
 				?></a>
			</p>
			<p>
				<? echo get_string('display_context_menu', 'block_contag'); ?>

			</p>
			<p>
				<? echo get_string('rename_menu', 'block_contag'); ?>
			</p>
		</div>
	</div>
	<div style="margin:10px 0;"></div>
	<?

	//define the json file
	//check how to make it in file??
	$normalized_url = normalize_url($CFG -> wwwroot);
	

	$json_file_path = get_json_file_path($normalized_url,$courseid);


	$json_file_url = $CONTAG_JSON_URL .'json_' . $courseid . '.json';
	//check if file exists or create
	//throw new Exception($normalized_url);
	
	if (!file_exists($json_file_path)) {
		$content = "[{\"id\":1,\"text\":\"" . $course -> fullname . "\"}]";
		write_to_json_file($content,$json_file_path, $course -> fullname);	//write to server
		
		copy_from_server_to_local($courseid); //write locally for synchronizing dirs
	}


/*url: '/marigianna/moodle/blocks/contag/json/tree_data.json'*/
	?> <ul id="tt" class="easyui-tree" data-options="
	url: ' <? echo $json_file_url; ?> ',
	animate: true,
	onContextMenu: function(e,node){
	e.preventDefault();
	$(this).tree('select',node.target);
	$('#mm').menu('show',{
	left: e.pageX,
	top: e.pageY
	});
	}
	"></ul>
	<div id="mm" class="easyui-menu" style="width:120px;">
		<div onclick="append()">
			<? echo get_string('append', 'block_contag'); ?>
		</div>
		<div onclick="remove()">
			<? echo get_string('remove', 'block_contag'); ?>
		</div>
		<div class="menu-sep"></div>
		<div onclick="expand()">
			<? echo get_string('expand', 'block_contag'); ?>
		</div>
		<div onclick="collapse()">
			<? echo get_string('collapse', 'block_contag'); ?>
		</div>
	</div>
	<div id="see_ajax_result"> </div>
	<div class="easyui-tree tree-submits">
		<input type="submit" value="Submit" onclick=" setURL( <? echo "'$normalized_url'"; ?>); setCourseId(<? echo $courseid; ?>); tree_submitted(<? echo $courseid; ?>);  submitting=true;return true;"> 
		
		<!-- Submits -->
	</div>
	

<!-- </form>  --> <!-- cannot have it in a form because it erases the post */ -->
<?

}  else { // end has_capability
print("You do not have permissions to edit concept tags.");
}
$OUTPUT->footer($course);
?>