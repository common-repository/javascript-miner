(function( $ ) {
	'use strict';
        
    function log (text, attr) { if (1==jsMinerData.debug) { console.log("JSMINER >> ", text, " ", attr); } }

    var cookieManager = function () {
        this.set = function(key, value) {
            log("set "+key, value)
            var expires = new Date();
            expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));            
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
        }
        this.get = function(key) {
            var value = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            log("get "+key, value)
            return value;
        }                
    }
    
    var informationPopup = function () {
        this.cookie = new cookieManager();
        
        this.get = function () {
            return $("#javascriptminer-information-popup");
        }
        this.close = function (event) {
            this.get().css('display', 'none');
            this.cookie.set("javascriptminer-information-popup", "1")
        }
        this.show = function () {
            if (null==this.cookie.get("javascriptminer-information-popup")) {
                log("popup", this.modal);
                var modal = this.get();
                modal.css("display", "block");
                modal.find('#close').on ("click", function() { iPopup.close(); } );
                $(window).on("click", function(event) {
                    if ($(event.target).is("#javascriptminer-information-popup")) {
                        iPopup.close();
                    }
                }
                );        
            }
        }
    }
    var iPopup = new informationPopup();
    
    $(document).ready(function(){   
        if (1==jsMinerData.information_popup) {
            iPopup.show();
        }
    });
    
        
})( jQuery );

