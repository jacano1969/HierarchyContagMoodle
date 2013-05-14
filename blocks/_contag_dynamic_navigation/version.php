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

$plugin->version =  2013121201;
$plugin->requires = 2011033003.01;

$plugin->cron = 86400; // 86400 seconds = run once a day
$plugin->maturity = MATURITY_STABLE;
$plugin->release = '2.0.3+ (Build: 20110511)';
$plugin->component = 'block_contag_dynamic_navigation';
//$plugin->dependencies = array('block_contag' => ANY_VERSION);