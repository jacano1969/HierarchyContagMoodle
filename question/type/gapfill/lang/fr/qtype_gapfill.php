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
/**
 * The language strings for component 'qtype_gapfill', language 'en' 
 *    
 * @copyright &copy; 2013 Marcus Green
 * @author marcusavgreen@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package qtype
 * @subpackage gapfill
 */

$string['addinggapfill']='Ajout de Gap Fill ';
$string['casesensitive']='Sensible à la casse';
$string['casesensitive_help']='Quand cette option est cochée, si la bonne réponse est CAT, cat sera considérée comme une mauvaise réponse';
$string['noduplicates']='Sans doublons';
$string['noduplicates_help']='Quand elle est cochée, chaque réponse doit être unique, utile, où chaque champ a un opérateur |, c\'est à dire quelles sont les couleurs des médailles olympiques et chaque champ est [d\'or | argent | bronze], si l\'élève entre l\'or dans tous les domaines que l\' première obtenir une marque (les autres seront toujours obtenir une tique cependant). Il est vraiment plus comme rejeter les doublons de réponses aux fins du marquage';
$string['delimitchars']='Délimiter les caractères ';
$string['pluginnameediting']='Modification de Gap Fill.';
$string['pluginnameadding']='Ajout d\'une question Gap Fill';
$string['gapfill']='Gapfill Cloze';
$string['displaygapfill'] = 'Gapfill';
$string['displaydropdown'] = 'Déroulant';
$string['displaydragdrop'] = 'Dragdrop';
$string['pluginname_help'] = 'Placez les mots pour être achevée dans les crochets, par exemple L\'[cat] s\'assit sur le [mat]. Modes de liste déroulante et permet DragDrop pour une liste de réponses mélangées à afficher qui peut inclure en option de mauvaises réponses / distracteur';
$string['pluginname_link']='/question/type/gapfill';
$string['pluginnamesummary'] = 'Un remplir le question de style lacunes. Moins de fonctionnalités que le type Cloze standard, mais plus simple syntaxe';
$string['delimitchars_help']='Changer les caractères qui délimitent un champ de la valeur par défaut [], utile pour programmer des questions sur la langue';
$string['answerdisplay']='Affiche Réponses';
$string['answerdisplay_help'] = 'Si elle est cochée cela tournera chaque champ dans une liste déroulante contenant toutes les réponses pour tous les domaines';
$string['pleaseenterananswer']='S\'il vous plaît entrer une réponse.';
$string['wronganswers']='Les mauvaises réponses.';
$string['wronganswers_help']='Liste des mots incorrects séparés par des virgules, ne s\'applique que dans dragdrop / mode de listes déroulantes';