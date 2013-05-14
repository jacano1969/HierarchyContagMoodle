<<<<<<< HEAD
QUICK INSTALL
=============

For the impatient, here is a basic outline of the
installation process, which normally takes me only
a few minutes:

1) Move the Moodle files into your web directory.

2) Create a single database for Moodle to store all
   its tables in (or choose an existing database).

3) Visit your Moodle site with a browser, you should
   be taken to the install.php script, which will lead
   you through creating a config.php file and then
   setting up Moodle, creating an admin account etc.

4) Set up a cron task to call the file admin/cron.php
   every five minutes or so.


For more information, see the INSTALL DOCUMENTATION:

   http://docs.moodle.org/en/Installing_Moodle


Good luck and have fun!
Martin Dougiamas, Lead Developer

=======
ConTag (Concept Tagging) - For Moodle 2

Author: ICTG Group, University of Canterbury, Christchurch, New Zealand

Initial public release 2012-11-20
------------------------------
ConTag allows a teacher to easily tag Moodle items with concepts, that the student can then use to browse the items. A student can use this to focus on concepts that they are interested in.

Installation:
Place the ConTag folder in the moodle/htdocs/blocks folder, then go to the Notifications menu item within Moodle.

Once installed and the block has been added to a course, a teacher will be given two options via the block area: Edit Concept Tags (i.e. tag items), and Navigate by Concept Tags. A student will just be given Navigate by Concept Tags.

--------------------------
Hierarchy tree installation:
make sure the folders : 
/moodle/blocks/contag/json is on 777 permissions 
CATALINA_HOME/lib/json/(siteurl)/(courseid) is on 777 permissions

make sure that $WEB_SERVICE_URL in callOntologyCreationWS works, or change the URL respectively

make sure from Moodle XMLDB and phpmyadmin that tree_node_id column (int) is added on the plugin's table block_contag_tag

Features
--------
Tagging (for Teachers):
Add tag (new tags are automatically added to hierarhchy tree)
Add multiple tags to an item (separated by commas)
Add tags to several items at once (type them in where you want, then hit "Save" or press "enter")
Autocompletion
Remove tag from item (i.e. untag) - click on [x] in 'Tags applied' column
 - Tags not used anywhere are greyed out in the 'All tags' list
 --(this feature has been removed from the hierarchy tree extension)
Delete tag permanently (which also untags it from all items it was attached to) - click on [x] in 'All tags' list
Rename tag - click on tag name in 'All tags' list (press esc to cancel rename)

Navigation (for Students):
Click items (resources etc) to see their pages
"Random tag" - click on tag in randomly generated 'Some tags' list in block to filter by that tag (click "Show all tags" on the filtered page to reveal the rest again)

Hierarchy Tree (for Teachers)
Build a concept tag hierarchy tree according to their preferences 
Add concept tag to the tree
Remove nodes (subtrees) from the hierarchy tree
Move nodes (and subtree) on hierarchy tree 
Save Hierarchy tree

>>>>>>> b8f39e81ed1e228eced3a1b692a02a26f6f06a33
