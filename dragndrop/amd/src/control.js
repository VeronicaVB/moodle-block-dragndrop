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
 * Provides the block_dragndrop/control module
 *
 * @package   block_dragndrop
 * @category  output
 * @copyright 2020 Veronica Bermegui
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * @module block_dragndrop/control
 */
define(['jquery'], function ($) {

    function init(instanceid) {
        var filelist = document.getElementById("dndlistfiles");
        var links = filelist.getElementsByTagName("li");

        Array.from(links).forEach(
                function (element, index, array) {
                    traversechildren(array[index].children);
                }
        );

        //Works in Chrome
        function traversechildren(children) {
            Array.from(children).forEach(
                    function (element, index, array) {
                        array[index].addEventListener("dragstart", function (evt) {

                            if (evt.dataTransfer !== null) {
//                                //evt.dataTransfer.setData("DownloadURL", array[index].getAttribute("data-download-url"));
//                                //var mtyped = (array[index].getAttribute("data-download-url")).split(":")[0];
//                                //evt.dataTransfer.setData(mtype,array[index].getAttribute("data-download-url"));
//                                evt.dataTransfer.effectAllowed = "copyMove";
//
//                                let myfile = {
//                                    path: array[index].getAttribute("data-url")
//                                };
//                                sessionStorage.setItem(index, JSON.stringify(myfile));


                                $.ajax({
                                    async: false,
                                    complete: function () {
                                        
                                        evt.dataTransfer.setData("DownloadURL", array[index].getAttribute("data-download-url"));
                                    },
                                    error: function (xhr) {
                                        if (xhr.status == 404) {
                                            xhr.abort();
                                        }
                                    },
                                    type: 'GET',
                                    timeout: 3000,
                                    url: array[index].getAttribute("data-url")
                                });
                            }

                        }, false);
                    });
        }
    }

    return {
        init: init
    };
});
