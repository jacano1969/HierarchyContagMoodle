//loads tree's functions
$(function() {
	$('#tt').tree({
		dnd : true,
		/*url: difficult to take - not needed?*/
		/* url: 'tree_data.json', */
		animate : true,
		dnd : true,
		onDblClick : function(node) {
			$(this).tree('beginEdit', node.target);

		}
	});
});
function append() {
	var t = $('#tt');
	var node = t.tree('getSelected');
	//can it be improved???
	var nextId = getMaxId();
	//get the tree's maxID
	var newNode = new Object();
	newNode = {
		parent : ( node ? node.target : null),
		data : [{
			id : ++nextId, //insert the nextID
			text : 'newItem'
		}]
	};

	t.tree('append', newNode);
}

function remove() {

	var root = $('#tt').tree('getRoot');
	var node = $('#tt').tree('getSelected');
	var rootStr = JSON.stringify(root);
	var nodeStr = JSON.stringify(node);

	var subtree_to_remove = JSON.stringify($('#tt').tree('getData', node.target));
	var node_id = node.id;
	var node_text = node.text;
	if (rootStr != nodeStr) {
		if (confirm("ATTENTION! \n" + " If you delete this node all the instances that are associated to this node and it's children will be deleted." + "\n Are you sure that you want to delete this node?")) {
			// Delete it!
			$('#tt').tree('remove', node.target);

			$.ajax({
			type : 'POST',
			url : "ajax_calls.php",
			data : {
				"subtree_to_remove" : subtree_to_remove
			}
			});
		} else {
			// Do nothing!
		}

	} else {
		alert("Sorry. \n You can not delete the tree's root element!");
	}

}

function collapse() {
	var node = $('#tt').tree('getSelected');
	$('#tt').tree('collapse', node.target);
}

function expand() {
	var node = $('#tt').tree('getSelected');
	$('#tt').tree('expand', node.target);
}

/**
 * Returns a string with all the children of a selected node
 * Does not contain hierarchy information
 *
 * @returns s - a string with all the children of a selected node
 */
function printSelectedNodeChildren() {
	var selected = $('#tt').tree('getSelected');
	if (selected) {
		var children = $('#tt').tree('getChildren', selected.target);

		if (children) {
			var s = '';
			for (var i = 0; i < children.length; ++i) {

				if (s != '')
					s += ',';
				s += children[i].text;
			}
			return (s);
		}
	}
}

/**
 * Returns the tree's max id
 * @returns maxID - int with the max ID
 */

function getMaxId() {
	var root = $('#tt').tree('getRoot');
	//gets tree's root
	if (root) {
		var children = $('#tt').tree('getChildren', root.target);

		if (children) {
			if (children.length > 0) {
				var maxId = children[0].id;
				for (var i = 1; i < children.length; ++i) {
					if (children[i].id > maxId)
						maxId = children[i].id;
				}
				return (maxId);
			} else {
				return (root.id);
			}
		}
	}
}

/**
 * Functions for AJAX - sending the JSON object to callOntologyCreationWS.php
 */

/**
 * The function creates the AJAX object (XMLHttpRequest Object)
 */

function getRequest() {
	var req = false;
	try {
		// most browsers
		req = new XMLHttpRequest();
	} catch (e) {
		// IE
		try {
			req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			// try an older version
			try {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				return false;
			}
		}
	}
	return req;
}

var courseIdJs;

function setCourseId(courseId) {
	courseIdJs = courseId;
}

var urlJs;

function setURL(url) {

	urlJs = url;
}

/**
 * Function is called when the user wants to submit the hierarchy tree
 *
 */

function tree_submitted() {
	var root = $('#tt').tree('getRoot');
	//this was commented out because I don't need the []
	//var JSONstring = "[" + JSON.stringify($('#tt').tree('getData', root.target)) + "]";
	var JSONstring = JSON.stringify($('#tt').tree('getData', root.target));

	$.ajax({
		type : 'POST',
		url : "submit_tree.php",
		// data : "json=" + JSONstring
		data : {
			"json" : JSONstring,
			"courseId" : courseIdJs,
			"url" : urlJs
		},
		success : function(data) {
			//alert("Concepts Hierarchy tree was saved successfully!");
			alert(data);
		},
		error : function(result) {
			alert('Oops!! Something went wrong!');
		}
	});

}

