(function ( $ ) {
    /* To work around IE's lack of Array.indexOf. Stolen from underscore.js. */
    var indexOf = function ( arr, item ) {
        var i, l;
        if ( Array.prototype.indexOf ) return arr.indexOf( item );
        for ( i = 0, l = arr.length; i < l; i++ ) if ( arr[ i ] === item ) return i;
        return -1;
    };

    // CSS that will always be applied to the selected, wizard-ized element.
    var baseCSS = {
            overflow: "hidden",
            position: "relative"
        };

    // Add the wizard to jQuery's UI widget scheme.
    $.widget( "ui.wizard", {
        // Defaults.  Pass in an object to override these.
        options: {
            autoButtons: true, // Set to false to not auto generate buttons for steps not specified in buttons.
            autoSize: true, // Set to false to disable auto resizing when browser window is resized.
            buttons: {}, // Object containing the buttons for the specified step(s).
            disableButtons: false, // Set to true to disable buttons on step switch.  This will also disable buttons until any running ajax request have completed.
            height: "80%",
            width: "90%"
        },

        _create: function () {
            this.containers = [];
            var self = this,
                defBtns = {},
                ids = [],
                containerContents = this.element.find( "ul" ).remove().find( "li > a" ),
                lastContainer = containerContents.length - 1;

            containerContents.each( function ( i, t ) {
                var titleEl = $( t ),
                    title = titleEl.html(),
                    id = titleEl.attr( "href" ).replace( /^#/, "" ),
                    step = self.element.find( "#" + id ),

                    titleDiv = $( "<div class='ui-widget ui-state-active ui-wizard-title ui-corner-all'>" + title + "</div>" ),
                    content = $( "<div class='ui-widget-content ui-wizard-content-container ui-corner-all'/>" )
                        .append( titleDiv )
                        .append( step.remove().addClass( "ui-widget ui-wizard-content" ) ),
                    container = $( "<div id='" + id + "-container' class='ui-widget-content ui-wizard-container ui-corner-all'/>" )
                        .append( content )
                        .appendTo( self.element );

                if ( self.options.autoButtons ) {
                    ids.push( { id: id, title: title } );
                    defBtns[ id ] = {};
                }

                self.containers.push( id );
            });

            if ( self.options.autoButtons ) {
                $.each( ids, function ( i, o ) {
                    if ( i > 0 ) {
                        defBtns[ ids[ i - 1 ].id ][ o.title ] = { dir: "forward" };
                    }

                    if ( i < lastContainer ) {
                        defBtns[ ids[ i + 1 ].id ][ o.title ] = { dir: "back" };
                    }
                });

                $.each( defBtns, function ( k, v ) {
                    if ( !self.options.buttons[ k ] ) self.options.buttons[ k ] = v;
                });
            }

            self._createButtons();

            if ( this.options.autoSize ) {
                this._bind_resize();
            }

            if ( this.options.disableButtons ) {
                this._setupDisableButtons();
            }

            this.element.css( baseCSS );
            this._size( true );
            this.select.call( this, this.containers[ 0 ], true );
        },

        _createButtons: function () {
            var self = this;

            $.each( this.options.buttons, function ( container, buttons ) {
                $( "#" + container + "-container .ui-wizard-buttons" ).remove();
                var buttonContainer = $( "<div class='ui-wizard-buttons'/>" ),
                    cIdx = indexOf( self.containers, container ),
                    pIdx = cIdx - 1, nIdx = cIdx + 1;

                $.each( buttons, function ( name, props ) {
                    props = $.isFunction ( props ) ?
                        { dir: "", click: props } :
                        props;

                    var buttonStr = props.dir === "back" ?
                        "<button class='ui-wizard-button' type='button' style=''><span>" +
                            "<span class='ui-icon ui-icon-carat-1-w ui-wizard-icon'></span>" +
                            "<span>" + name + "</span>" +
                            "</span></button>" :
                        props.dir === "forward" ?
                        "<button class='ui-wizard-button' type='button'>" + name +
                            "<span class='ui-icon ui-icon-carat-1-e ui-wizard-icon'></span></button>" :
                        "<button class='ui-wizard-button' type='button'>" + name + "</button>";

                        button = $( buttonStr );

                    if ( $.isFunction ( props.click ) ) {
                        button.bind( "click.wizard", function () {
                            props.click.apply( self.element[0], arguments );
                        })
                    }
                    else if ( props.dir === "back" ) {
                        button.bind( "click.wizard", function () {
                            self.select.call( self, self.containers[ pIdx ] );
                        });
                    }
                    else if ( props.dir === "forward" ) {
                        button.bind( "click.wizard", function () {
                            self.select.call( self, self.containers[ nIdx ] );
                        });
                    }

                    if ( $.fn.button ) {
                        button.button();
                    }

                    buttonContainer.append( button );
                });
                $( "#" + container + "-container" ).append( buttonContainer );
            });
        },

        _size_body: function () {
            var wHeight = $( window ).height(), wWidth = $( window ).width();
            $( "body" ).css({
                height: wHeight - 30,
                width: wWidth
            });
        },

        _bind_resize: function () {
            var self = this;
            $( window ).bind( "resize.wizard", function () {
                self._size_body();
                self._size();
            });

            this._size_body();
        },

        _set_real_sizes: function () {
            var wHeight = $( window ).height(),
                wWidth = $( window ).width(),
                isStrH = typeof( this.options.height ) === "string",
                isStrW = typeof( this.options.width ) === "string",
                nHeight = isStrH ? parseInt( this.options.height.replace( /[%px]/, "" ), 10 ) : this.options.height,
                nWidth = isStrW ? parseInt( this.options.width.replace( /[%px]/, "" ), 10 ) : this.options.width,
                isPctH = nHeight <= 100 ? true : false,
                isPctW = nWidth <= 100 ? true : false;

            if ( this.options.autoSize && isPctH ) {
                this.realHeight = ( nHeight / 100 ) * wHeight;
            }
            else {
                this.realHeight = this.options.height;
            }

            if ( this.options.autoSize && isPctW ) {
                this.realWidth = ( nWidth / 100 ) * wWidth;
            }
            else {
                this.realWidth = this.options.width;
            }
        },

        _size: function ( isSetup ) {
            this._set_real_sizes();

            var self = this;

            this.element.css({
                height: this.realHeight,
                width: this.realWidth
            });

            if ( isSetup ) {
                this.element.find( ".ui-wizard-container" ).css({
                    height: this.realHeight - 25,
                    left: this.realWidth,
                    width: this.realWidth - 26
                });
            }
            else {
                this.element.find( ".ui-wizard-container" ).each( function ( i, e ) {
                    var cLeft = $(this).position().left,
                        nLeft = cLeft < 0 ? -self.realWidth : cLeft > 0 ? self.realWidth : 0;
                    $( this ).css({
                        height: self.realHeight - 25,
                        left: nLeft,
                        width: self.realWidth - 26
                    });
                });
            }

            this.element.find( ".ui-wizard-content-container" ).css({
                height: this.realHeight - 60
            });

            this.element.find( ".ui-wizard-content" ).each( function () {
                var tHeight = $( this ).siblings().outerHeight(),
                    tHeightFixed = tHeight > 0 ? tHeight : 26,
                    newHeight = $( this ).parent().height() - tHeightFixed;

                $( this ).css({
                    height: newHeight
                });
            });
        },

        _setOption: function ( key, value ) {
            if ( key === "buttons" ) {
                $.extend( this.options.buttons, value );
                this._createButtons();
            }
            else if ( key === "height" || key === "width" ) {
                this.options[ key ] = value;
                this._size( false );
            }
            else {
                this.options[ key ] = value;
            }
        },

        _setupDisableButtons: function () {
            var self = this;

            $( document ).ajaxStart( function () {
                self.ajaxReqRunning = true;

                if ( self.options.disableButtons ) {
                    $( ".ui-wizard-button", self.element ).button( "disable" );
                }
            });

            $( document ).ajaxStop( function () {
                self.ajaxReqRunning = false;

                if ( self.options.disableButtons ) {
                    $( ".ui-wizard-button", self.element ).button( "enable" );
                }
            });
        },

        addStepSkip: function ( stepName ) {
            var stepIndex = indexOf( this.containers, stepName ),
                stepExists = stepIndex !== -1,
                stepPreviouslySkipped = stepExists && this.savedSteps && this.savedSteps[ stepName ],
                prevStep = stepExists ? this.containers[ stepIndex - 1 ] : "",
                nextStep = stepExists ? this.containers[ stepIndex + 1 ] : "",
                skippedButtons = {},
                newButtons = {},
                buttonObj;

            if ( !stepPreviouslySkipped ) {
                this.savedSteps = this.savedSteps || {};
                this.savedSteps[ stepName ] = {};
                this.savedSteps[ stepName ][ prevStep ] = this.options.buttons[ prevStep ];
                this.savedSteps[ stepName ][ nextStep ] = this.options.buttons[ nextStep ];

                for ( button in this.options.buttons[ stepName ] ) {
                    buttonObj = this.options.buttons[ stepName ][ button ];

                    if ( !buttonObj.click ) {
                        if ( buttonObj.dir === "back" ) {
                            buttonObj.click = function () {
                                $( this ).wizard( "select", prevStep );
                            };
                        }
                        else if ( buttonObj.dir === "forward" ) {
                            buttonObj.click = function () {
                                $( this ).wizard( "select", nextStep );
                            };
                        }
                    }

                    skippedButtons[ buttonObj.dir ] = {
                        name: button,
                        button: buttonObj
                    };
                }

                newButtons[ prevStep ] = {};
                for ( button in this.options.buttons[ prevStep ] ) {
                    buttonObj = this.options.buttons[ prevStep ][ button ];

                    if ( buttonObj.dir === "back" ) {
                        newButtons[ prevStep ][ button ] = buttonObj;
                    }
                    else if ( buttonObj.dir === "forward" && skippedButtons.forward ) {
                        newButtons[ prevStep ][ skippedButtons.forward.name ] = skippedButtons.forward.button;
                    }
                }

                newButtons[ nextStep ] = {};
                for ( button in this.options.buttons[ nextStep ] ) {
                    buttonObj = this.options.buttons[ nextStep ][ button ];

                    if ( buttonObj.dir === "back"  && skippedButtons.back ) {
                        newButtons[ nextStep ][ skippedButtons.back.name ] = skippedButtons.back.button;
                    }
                    else if ( buttonObj.dir === "forward" ) {
                        newButtons[ nextStep ][ button ] = buttonObj;
                    }
                }

                this._setOption( "buttons", newButtons );
            }
        },

        delStepSkip: function ( stepName ) {
            var stepIndex = indexOf( this.containers, stepName ),
                stepExists = stepIndex !== -1,
                stepPreviouslySkipped = stepExists && this.savedSteps && this.savedSteps[ stepName ];

            if ( stepPreviouslySkipped ) {
                this._setOption( "buttons", this.savedSteps[ stepName ] );

                delete this.savedSteps[ stepName ];
            }
        },

        select: function ( container, immediate, callback ) {
            this.currContainer = this.currContainer || "";
            callback = $.isFunction( immediate ) ? immediate : callback;
            immediate = $.isFunction( immediate ) ? false : immediate;

            var self = this,
                currIdx = indexOf( this.containers, this.currContainer ),
                toIdx = indexOf( this.containers, container ),
                dir = currIdx < toIdx ? "-=" : "+=",
                timeout = immediate ? 0 : 500,
                afterSelect = function () {
                    if ( $.isFunction( callback ) ) {
                        callback.call( this );
                    }

                    if ( self.options.disableButtons && !self.ajaxReqRunning ) {
                        $( ".ui-wizard-button", self.element ).button( "enable" );
                    }
                };

            if ( self.options.disableButtons ) {
                $( ".ui-wizard-button", this.element ).button( "disable" );
            }

            if ( this.currContainer === container ) {
                afterSelect.call( $( "#" + container + "-container" ).get(0) );
            }
            else {
                $.each( this.containers, function ( i, c ) {
                    if ( ( dir === "-=" && i > currIdx && i < toIdx ) || ( dir === "+=" && i > toIdx && i < currIdx ) ) {
                        $( "#" + c + "-container" ).animate(
                            { left: dir + ( self.realWidth * 2 ) },
                            0
                        );
                    }
                    else if ( i === currIdx ) {
                        $( "#" + c + "-container" ).animate(
                            { left: dir + ( self.realWidth * 1 ) },
                            timeout
                        );
                    }
                    else if ( i === toIdx ) {
                        $( "#" + c + "-container" ).animate(
                            { left: dir + ( self.realWidth * 1 ) },
                            timeout,
                            afterSelect
                        );
                    }
                });

                this.currContainer = container;
            }
        }
    });
}( jQuery ));
