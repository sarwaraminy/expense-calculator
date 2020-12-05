"use strict";
/* Module: jquery.imask
 *
 * Handles filtering characters out of an input on key presses.
 * Use a normal jQuery selector and call .imask on it.
 */
(function ($) {
    var setup = function () {
        // Default settings.
        var defaults = {
            callback: undefined, // Function to call on valid input.
            doLive: false, // If true, bind with "live", else use "bind".
            forceGlobal: true, // Force mask RE to be global. Should be left true unless you have a very good reason to change it.
            mask: /.*/, // Characters to filter out.
            timeout: 0 // Time out (in ms) to wait before running callback function.
        };

        $.fn.imask = function (options) {
            var
                settings = $.extend({}, defaults, options), // Mash defaults and passed in options together.

                /* Function: init
                 *
                 * Initialize instance of plugin.
                 * Handles binding events to selected element(s).
                 */
                init = function () {
                    var bObj = this.length === 0 ? this.selector : this,
                        reFlags;

                    // If we are forcing global and the RE the user passed does not
                    // have global set, enforce the use of global.
                    if (settings.forceGlobal && !settings.mask.global) {
                        reFlags = "g";
                        reFlags += settings.mask.ignoreCase ? "i" : "";
                        reFlags += settings.mask.multiline ? "m" : "";
                        settings.mask = new RegExp(settings.mask.source, reFlags);
                    }

                    // Call cleaner plugin.
                    $(bObj).cleaner({
                        callback: settings.callback,
                        cleaner: function (v) {
                            if (v.search(settings.mask) === -1) {
                                return v;
                            }
                            else {
                                return "";
                            }
                        },
                        doLive: settings.doLive,
                        timeout: settings.timeout
                    });

                    // Return JQ object for chaining.
                    return this;
                };

            // Run the init function and return "this" for chaining.
            return init.call(this);
        };
    };

    if (!$.fn.cleaner) {
        $.ajax({
            url: sHomePath + "/jQuery/js/jquery.cleaner.js",
            async: false,
            dataType: "script",
            success: function () {
                setup();
            }
        });
    }
    else {
        setup();
    }
}(jQuery));
