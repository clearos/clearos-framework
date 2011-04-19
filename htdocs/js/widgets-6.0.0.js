///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2011 ClearFoundation
//
///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//
///////////////////////////////////////////////////////////////////////////////
// FIXME: add minified version

function anchorEdit(url, importance, id) {
// FIXME: need to escape URL
   $.ajax({
       url: '/app/widgets/anchor_edit/' + url + '/' + importance + '/' + id,
       method: 'GET',
       dataType: 'html',
       success : function(html) {
           $('#' + id).html(html);
       },
       error: function(xhr, text, err) {
           // Do something
       }
   });
}
