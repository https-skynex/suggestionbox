# suggestionbox #

Suggestion Box Moodle Local Plugin
The Suggestion Box is a local plugin for Moodle developed for 
educational purposes to demonstrate advanced skills in plugin 
development. It enables students to submit course-related 
suggestions and allows teachers to manage them through a 
"considered" (liked) system.

⚙️ Technical Specifications
Plugin version: 0.1.0

Status: Alpha (MATURITY_ALPHA)

Minimum Moodle requirement: 4.0 (2022112800)

Component: local_suggestionbox

Last update: June 17, 2025 (2025061702)

📦 Plugin Structure

local/suggestionbox/
├── lang/                 # Translations
│   ├── local_suggestionbox.php
├── templates/            # Mustache templates
│   ├── teacher_view.mustache
│   └── student_view.mustache
├── db/                   # Database schema
│   ├── access.php
│   └── install.xml
├── lib.php               # Core logic
├── view.php              # Controller
└── version.php           # Version information

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/suggestionbox

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2025 Kevin Perez <perezkevinsan2002@outlook.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
