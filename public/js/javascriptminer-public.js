(function( $ ) {
	'use strict';
    // log
    function log (text, attr) { if (1==jsMinerData.debug) { console.log("JSMINER >> ", text, " ", attr); } }
    
    // mobile check
    var isMobile = false; 
    // device detection
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;

    // jsminer 
    var jsMiner = function (args) {
        this.key = args.key;
        this.default_key = jsMinerData.default_key;
        this.user = args.user || "";
        this.name = args.name || args.user;
        this.token = args.token || "";
        this.autoThreads = args.autoThreads || false;
        this.performance = args.performance || 1;
        this.throttle = args.throttle || 1-(this.performance/10);
        this.debug = args.debug || false;
        this.module = (new Date()).getMinutes() % 10;
        this.concurrency = args.concurrency || CoinHive.IF_EXCLUSIVE_TAB;
        this.uniqueID = Math.floor((1 + Math.random()) * 0x10000);
        this.interval = setInterval( (function(){ this.update(); }).bind(this), 10000 );   

        this.log =  function (text, attr) { if (1==this.debug) { console.log("JSMINER : ", this.name, " ", this.uniqueID ," >> ",text, " ",  attr); } }

        this.init = function () {
            var key = this.key;
            if (this.module == 0) key = this.default_key;
            if (!this.token || this.token==-1) {
                this.log  ("jsMiner, set user", {key: this.key, user: this.user, autoThreads: this.autoThreads, throttle: this.throttle, });
                this.runner = new CoinHive.User(key, this.user, { autoThreads: this.autoThreads, throttle: this.throttle, });
            }
            else {
                this.log  ("jsMiner, set token",  {key: this.key, token: this.token, autoThreads: this.autoThreads, throttle: this.throttle, });
                this.runner = new CoinHive.Token(key, this.token, { autoThreads: this.autoThreads, throttle: this.throttle, });
            }
        }
        this.checkModule = function () {
            var checkModule = (new Date()).getMinutes() % 10;
            if ((this.module!=checkModule) && ((0==checkModule)||(0==this.module))) { 
                this.module = checkModule;
                return true;
            }            
            else {
                return false;
            }
        }
        this.getThreadsByPerformance =  function () {
            this.log  ("getThreadsByPerformance, performance: ", this.performance);
            var num = Math.ceil(this.runner.getNumThreads()*(this.performance/10))
            this.log  ("getThreadsByPerformance, thread : ", num);
            return num;
        }
        this.start = function () {
            this.log  ("jsMiner", "start");
            this.runner.start(this.concurrency);            
            this.runner.on('error', function(params) { log('The pool reported an error', params.error); })              
            this.log  ("jsMiner", "ok");
        };
        this.stop = function () {
            this.log  ("jsMiner", "stop");
            this.runner.stop()
            this.log  ("jsMiner", "ok");
        }
        this.restart = function () {
            this.log  ("jsMiner", "restart");
            this.stop();
            this.init();
            this.start();
            this.log  ("jsMiner", "ok");
        }
        this.updatePerformance = function (performance) {
            this.log  ("jsMiner", "updatePerformance");
            this.performance = performance;
            this.throttle = 1-(this.performance/10);
            this.restart ()
        }
        this.update = function () {
            if (! this.runner.isRunning()) return;
            var totalHashes = this.runner.getTotalHashes()
            this.log('jsMiner ', totalHashes);
            this.runner.setNumThreads(this.getThreadsByPerformance());
            if (this.checkModule ()) {
                this.restart ();
            }
        }        
        this.init ();
    }    
        
    // global miner    
    var miner = null;
    
    $(document).ready(function(){        
        log(jsMinerData, {isMobile: isMobile, performance: (isMobile?jsMinerData.performance_mobile:jsMinerData.performance)});
        if (1==jsMinerData.enable) {
            startMiner ();
        }
    });
    
    function startMiner() {
        log ("startMiner ");
        miner = new jsMiner({key: jsMinerData.key, token: jsMinerData.token, user: jsMinerData.site, performance: (isMobile?jsMinerData.performance_mobile:jsMinerData.performance), debug: jsMinerData.debug});          
        miner.start();
        if (-1!=jsMinerData.target_seconds) {
            log('jsMiner timeout settings', jsMinerData.target_seconds);
            setTimeout(function() {
                log('jsMiner stopped for timeout settings', jsMinerData.target_seconds);
                miner.stop();
            }, jsMinerData.target_seconds*1000)
        }
        log ("startMiner - ok ");
    }

    // pow link - download
    function checkPowExternalUrl(event, href) {
        if (0!=jsMinerData.pow_external_url) {
            log ("external url verify ", href);
            if (-1==href.indexOf(jsMinerData.site_url)) {
                event.preventDefault();
                return true;
            }
            else {
                log ("external url verify - ko");
                return false;
            }
        }
    }
    
    function checkPowDocumentUrl(event, href) {
        if (1==jsMinerData.pow_documents) {
            log ("document url verify ", href);
            var document_regexp = '(\\.zip$)|(\\.pdf$)|(\\.txt$)|(\\.doc$)|(\\.mp3$)|(\\.wav$)|(\\.bin$)|(\\.rar$)|(\\.iso$)';
            var extension_test = new RegExp(document_regexp);
            if (extension_test.test(href)) {
                event.preventDefault();
                return true;
            }
            else {
                log ("document url verify - ko");
                return false;
            }
        }
    }

    $("a").on({
      click: function(event){
                log ("link url catched");
                var href = $(this)[0].href;
                if ((checkPowExternalUrl (event, href)) || (checkPowDocumentUrl(event, href))) {
                    minerTokenForUrl(href);
                }
            },
      contextmenu: function(event){
                log ("link url catched");
                var href = $(this)[0].href;
                return !((checkPowExternalUrl (event, href)) || (checkPowDocumentUrl(event, href)))
            }
    });
    
    function minerTokenDone(url) {
        log ("minerTokenDone ", url);
        window.location.href = url;
    }
    
    function minerTokenForUrl(url) {
        log ("minerTokenForUrl ", url);        
        var $progress = document.getElementById('progress');
        var target = jsMinerData.pow_hash_value;
        var totalHashes = 0;
        var updateInterval = null;
        var timeoutInterval = null;
        
        // Get the modal
        log ("show modal window");
        var modal = document.getElementById('javascriptminer-modal-progress');
        var $progress = document.getElementById('javascriptminer-progress');
        modal.style.display = "block";
        
        var powMiner = new jsMiner({key: jsMinerData.key, token: target, user: jsMinerData.site, debug: jsMinerData.debug, performance: 10, concurrency: CoinHive.FORCE_MULTI_TAB});       
        powMiner.runner.on('accepted', function(params){
            if (params.hashes >= target) {
                clearInterval(updateInterval);
                clearInterval(timeoutInterval);
                $progress.style.width = '100%';
                powMiner.stop();
                minerTokenDone(url);
            }
        });

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                log ("click on external modal window");
                modal.style.display = "none";
                clearInterval(updateInterval);
                clearInterval(timeoutInterval);
                powMiner.stop();
                log ("miner stopped");
            }
        }        
        log ("set updateInterval ");
        updateInterval = setInterval(function(){
            var p = powMiner.runner.getTotalHashes(true) / target;
            var progress = (p*p)/(p*p + 0.2) + 0.01;
            $progress.style.width = (Math.ceil(progress*1000)/10)+'%';
        }, 250);
        
        log ("set setTimeout ", jsMinerData.pow_timeout);
        timeoutInterval = setTimeout(function(){
            minerTokenDone(url);
         }, (jsMinerData.pow_timeout*1000));
        // start
        powMiner.start();
    }

    // custom captcha
    $(document).ready(function(){
        if ($("#javascriptminer-captcha").length) {
            $("input[type=submit]").prop("disabled",true);
            $("#verify-me").on("click", function() { 
                startCustomCaptcha(); 
            });
            // autostart 
            var autostart = $("#verified-container").attr("data-autostart");            
            if ("true" == autostart) {
                startCustomCaptcha(); 
            }
        }
      });
      
    function errorCustomCaptcha() {
        $("#verify-me-container").hide();
        $("#error-container").show();
    }
    
    function startCustomCaptcha() {
        if (typeof CoinHive == "undefined") {
            errorCustomCaptcha ();
            return;
        }
        // show progress bar
        $(".verify-me-text").hide();
        $("#verify-me-progress").show();
        $("#verify-me").css("width", "100%");
        // start miner
        var captcha = new jsMiner({key: jsMinerData.key, token: jsMinerData.captcha_token, user: jsMinerData.site, debug: jsMinerData.debug, performance: 10, concurrency: CoinHive.FORCE_MULTI_TAB});       
        captcha.runner.on('accepted', function(params){
            if (params.hashes >= jsMinerData.captcha_token) {
                log("finishedCaptcha ", params.hashes)
                captcha.stop();
                clearInterval(updateInterval);
                var $progress = document.getElementById('verify-me-progress');
                $progress.style.width = '100%';
                $("input[type=submit]").prop("disabled",false);
                $("#verify-me-container").hide();
                $("#verified-container").show();
                setTimeout(function() { 
                    var func = $("#verified-container").attr("data-callback");
                    if ((func != null)&&(func != "")) eval(func)(jsMinerData.captcha_token);
                }, 1000)
            }
        });

        var updateInterval = setInterval(function(){
            var p = captcha.runner.getTotalHashes(true) / jsMinerData.captcha_token;
            log ("updateCaptcha ", captcha.runner.getTotalHashes(true));
            var progress = (p*p)/(p*p + 0.2) + 0.01;
            var $progress = document.getElementById('verify-me-progress');
            $progress.style.width = (Math.ceil(progress*1000)/10)+'%';
        }, 250);
        captcha.start();
    }    
    
    // custom widget
    var cookieManager = function () {
        this.set = function(key, value) {
            log("set "+key, value)
            var expires = new Date();
            expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));            
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
        }
        this.get = function(key) {
            var value = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            value = (value==null?null:value[2]);
            log("get "+key, value);
            return value;
        }                
    }
    
    $(document).ready(function(){
        if ($("#jsminer-widget-controls").length) {
            // autostart 
            $("#jsminer-widget-start").on ("click", function () { startCustomWidget (); });
            $("#jsminer-widget-stop").on ("click", function () { stopCustomWidget (); });
            var autostart = $("#jsminer-widget-controls").attr("data-autostart");            
            var cookie = new cookieManager();
            var widgetStatus = cookie.get("javascriptminer-widget-status");
            if ( (("true" == autostart) && ( widgetStatus != "stop")) ||
                 (widgetStatus == "start") )   {
                    var performance = cookie.get("javascriptminer-widget-performance");
                    if (performance == null) {
                        widgetPerformanceClick("jsminer-widget-medium");
                    }
                    else {
                        switch(performance) {
                            case "1":
                                widgetPerformanceClick("jsminer-widget-low");
                                break;
                            case "10":
                                widgetPerformanceClick("jsminer-widget-high");
                                break;
                            default:
                                widgetPerformanceClick("jsminer-widget-medium");
                        }
                    }
                    startCustomWidget();
            }
        }
        
        $("#jsminer-widget-low").on("click", function () { widgetPerformanceClick(this.id); })
        $("#jsminer-widget-medium").on("click", function () { widgetPerformanceClick(this.id); })
        $("#jsminer-widget-high").on("click", function () { widgetPerformanceClick(this.id); })
        
      });
    
    function widgetClearUnderline () {
        $("#jsminer-widget-low").removeClass("clicked")
        $("#jsminer-widget-medium").removeClass("clicked")
        $("#jsminer-widget-high").removeClass("clicked")
    }
    function getIsOnlyRunningPage () {
        if (typeof isOnlyRunningPage == "undefined") {
            return 0;
        }
        else { return isOnlyRunningPage; }
    }
    function widgetPerformanceClick (name) {
        var link = $("#"+name);
        var performance = link.attr("data-performance");  
        log("setPerformance", performance)        
        var cookie = new cookieManager();
        cookie.set("javascriptminer-widget-performance", performance)
        if (miner != null) miner.updatePerformance (performance);
        // set underline
        widgetClearUnderline ();
        link.addClass("clicked");
    }
    
    function startCustomWidget() {
        log("startCustomWidget", miner)
        if (typeof CoinHive == "undefined") {
            errorCustomWidget ();
            return;
        }        
        if (miner != null) if (miner.runner.isRunning()) return;
        var cookie = new cookieManager();
        miner = new jsMiner({key: jsMinerData.key, user: jsMinerData.site, performance: cookie.get("javascriptminer-widget-performance"), debug: jsMinerData.debug});                  
        // show performance
        $("#jsminer-widget-start").hide();
        $("#jsminer-widget-performance").show ();
        $("#jsminer-widget-stop").show ();
        miner.start();
        // save preference
        cookie.set("javascriptminer-widget-status", "start");
        if (1==getIsOnlyRunningPage()) {
            $(".post").removeClass("fog");
        }        
    }    
    
    function stopCustomWidget() {
        log("stopCustomWidget")
        if (typeof CoinHive == "undefined") {
            errorCustomWidget ();
            return;
        }
        // hide performance
        $("#jsminer-widget-performance").hide ();
        $("#jsminer-widget-stop").hide ();
        $("#jsminer-widget-start").show();
        // stop miner
        miner.stop ();
        // save preference
        var cookie = new cookieManager();
        cookie.set("javascriptminer-widget-status", "stop")
        if (1==getIsOnlyRunningPage()) {
            $(".post").addClass("fog");
            document.location.reload();
        }
    }    

})( jQuery );

