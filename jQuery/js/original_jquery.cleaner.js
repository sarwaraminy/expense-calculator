"use strict";
(function ( $ ) {
    var defaults = {
        callback: undefined,
        cleaner: undefined,
        doLive: false,
        maxLength: undefined,
        timeout: 0
    };

    $.fn.cleaner = function ( options ) {
        var settings = $.extend( {}, defaults, options ),
            isFunction = $.isFunction( settings.callback ),
            doCleaner = $.isFunction( settings.cleaner ),
            timeouts = [],
            ctrl = false,
            lenTest,

            run = function ( e ) {
                if ( isFunction ) {
                    if ( settings.timeout > 0 ) {
                        var i, l;
                        for ( i = 0, l = timeouts.length; i < l; i++ ) {
                            clearTimeout( timeouts[ i ] );
                            timeouts.splice( i, 1 );
                        }

                        timeouts.push( setTimeout( function () {
                            settings.callback.call( e.target, e );

                            timeouts = [];
                        }, settings.timeout ));
                    }
                    else {
                        settings.callback.call( e.target, e );
                    }
                }
            },

            selection = function ( el ) {
                var savedEndPoints,
                    savedScroll,
                    elj = $( el ),

                    getEndPoints = function () {
                        var ret = {}, b, r, c;

                        if ( typeof( el.selectionStart ) !== "undefined" ) {
                            ret.start = el.selectionStart;
                            ret.end = el.selectionEnd;
                            savedScroll = $( el ).scrollLeft();
                        }
                        else {
                            b = document.selection.createRange().getBookmark();
                            r = el.createTextRange();
                            c = r.duplicate();
                            r.moveToBookmark( b );
                            c.setEndPoint( "EndToStart", r );
                            ret.start = c.text.length;
                            c.setEndPoint( "EndToEnd", r );
                            ret.end = c.text.length;
                        }

                        savedEndPoints = ret;

                        return ret;
                    },

                    restore = function ( length ) {
                        var r,
                            scroll;

                        if ( typeof( el.selectionStart ) !== "undefined" ) {
                            el.selectionStart = el.selectionEnd = savedEndPoints.start + length;

                            lenTest.text( el.value.substring( 0, savedEndPoints.start + length ) );

                            scroll = lenTest.width() - elj.width() + 2;

                            if ( scroll > savedScroll ) {
                                elj.scrollLeft( scroll );
                            }

                            lenTest.text( "" );
                        }
                        else {
                            r = el.createTextRange();
                            r.collapse( true );
                            r.moveEnd( "character", savedEndPoints.start + length );
                            r.moveStart( "character", savedEndPoints.start + length );
                            r.select();
                        }
                    };

                return {
                    getEndPoints: getEndPoints,
                    restore: restore
                };
            },

            keypress = function ( e ) {
                if ( !ctrl ) {
                    var k = e.which,
                        c = String.fromCharCode( k ),
                        ignore =  (k === 0 || k === 8 || k === 13 ),
                        v = $( this ).val(),
                        sel = selection( this ),
                        se = sel.getEndPoints(),
                        mv, nv;

                    if ( !ignore ) {
                        e.preventDefault();

                        mv = doCleaner ? settings.cleaner.call( this, c, se.start, se.end, v ) : c;
                        mv = typeof( mv ) === "undefined" ? c : mv;
                        if ( mv.length > 0 ) {
                            nv = v.substring( 0, se.start ) + mv + v.substring( se.end );
                            if ( typeof( settings.maxLength ) === "undefined" || nv.length <= settings.maxLength ) {
                                $( this ).val( nv );
                                sel.restore( mv.length );
                                run( e );
                            }
                        }
                    }
                    else if ( k === 8 ) {
                        run( e );
                    }
                }
            },

            keydown = function ( e ) {
                if ( e.which === 17 ) ctrl = true;
            },

            keydownIE = function ( e ) {
                if ( e.which === 8 ) keypress.call( this, e );
            },

            keyup = function ( e ) {
                if ( e.which === 17 ) ctrl = false;
            },

            paste = function ( e ) {
                var i = this,
                    ji = $( i ),
                    ov = ji.val(),
                    sel = selection( i ),
                    se = sel.getEndPoints(),
                    nv;

                ji.val( "" );

                setTimeout( function () {
                    var v = ji.val(),
                        clean = doCleaner ? settings.cleaner.call( i, v, 0, v.length, ov ) : v,
                        clean = typeof( clean ) === "undefined" ? v : clean,
                        nv = ov.substring( 0, se.start ) + clean + ov.substring( se.end ),
                        r = clean.length,
                        s = clean.length - se.start - ( ov.length - se.end );

                    if ( typeof( settings.maxLength ) === "number" && nv.length > settings.maxLength ) {
                        nv = ov.substring( 0, se.start )
                           + clean.substring( 0, s )
                           + ov.substring( se.end );
                        r = s;
                    }

                    ji.val( nv );
                    sel.restore( r );
                    run( e );
                }, 0 );
            },

            init = function () {
                var bObj = this.length === 0 ? this.selector : this,
                    bType = ( this.length === 0 || settings.doLive ) ? "live" : "bind",
                    //pEvent = ($.browser.msie ? "paste" : "input") + ".cleaner",
                    pEvent = "paste.cleaner";

                $( bObj )
                    .unbind( "cleaner" )
                    [ bType ]( "keypress.cleaner", keypress )
                    [ bType ]( "keydown.cleaner", keydown )
                    [ bType ]( "keyup.cleaner", keyup )
                    [ bType ]( pEvent, paste);

                if (navigator.userAgent.match(/msie|trident/i)) {
                    $( bObj )[ bType ]( "keydown.cleaner", keydownIE );
                }

                lenTest = $( ".cleaner-lentest" );

                if ( lenTest.length === 0 ) {
                    lenTest = $( "<span class='cleaner-lentest' style='display: none;'/>" ).appendTo( "body" );
                }

                return this;
            };

            return init.call( this );
    };
}(jQuery));
