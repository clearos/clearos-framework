<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2011 ClearFoundation
//
///////////////////////////////////////////////////////////////////////////////
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
///////////////////////////////////////////////////////////////////////////////

/**
 * Displays a help box.
 *
 * The available data for display:
 * - $name - app name
 * - $category - category
 * - $subcategory - subcategory
 * - $description - description
 * - $tooltip -  tooltip
 * - $user_guide_url - URL to the User Guide
 * - $support_url - URL to support
 */

// FIXME: translate
$tooltip = empty($tooltip) ? '' : '<p><b>Tooltip -- </b>' . $tooltip . '</p>';
echo theme_dialogbox_info("
        <h3>Help Box</h3>
        <p>" . $category . " &gt; " . $subcategory . " &gt; $name</p>
        <p>" . $description . "</p>
        $tooltip
        <ul>
            <li><a target='_blank' href='" . $user_guide_url . "'>User Guide</a></li>
            <li><a target='_blank' href='" . $support_url . "'>ClearCenter Support</a></li>
        </ul>
");
