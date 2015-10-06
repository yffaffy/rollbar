<?php
/**
 * Helper Functions
 *
 * @package     RollbarWP\Functions
 * @since       1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Libs
//if( !class_exists( 'Rollbar_WP' ) && !class_exists( 'RollbarNotifier' ) && !class_exists( 'Ratchetio' ) ) {
    require_once ROLLBAR_WP_DIR . 'includes/lib/rollbar.php';
//}

/*
 * PHP logging
 */
function rollbar_wp_initialize_php_logging() {

    $options = get_option( 'rollbar_wp' );

    // Return if logging is not enabled
    $php_logging_enabled = (!empty($options['php_logging_enabled'])) ? 1 : 0;

    if ( $php_logging_enabled === 0 ) {
        return;
    }

    // Return if access token is not set
    $server_side_access_token = (!empty($options['server_side_access_token'])) ? $options['server_side_access_token'] : '';

    if ($server_side_access_token == '')
        return;

    // Finish config parameters
    $environment = (!empty($options['environment'])) ? $options['environment'] : '';
    $logging_level = (!empty($options['logging_level'])) ? $options['logging_level'] : 1024;

    // Config
    $config = array(
        // required
        'access_token' => $server_side_access_token,
        // optional - environment name. any string will do.
        'environment' => $environment,
        // optional - path to directory your code is in. used for linking stack traces.
        'root' => ABSPATH,
        'max_errno' => $logging_level
    );

    // installs global error and exception handlers
    Rollbar::init($config);
}

/*
 * JS Logging
 */
function rollbar_wp_initialize_js_logging () {

    $options = get_option( 'rollbar_wp' );

    // Return if logging is not enabled
    $js_logging_enabled = (!empty($options['js_logging_enabled'])) ? 1 : 0;

    if ( $js_logging_enabled === 0 ) {
        return;
    }

    // Return if access token is not set
    $client_side_access_token = (!empty($options['client_side_access_token'])) ? $options['client_side_access_token'] : '';

    if ($client_side_access_token == '')
        return;

    $environment = (!empty($options['environment'])) ? $options['environment'] : '';

    echo '
<script>
    var _rollbarConfig = {
        accessToken: "' . $client_side_access_token . '",
        captureUncaught: true,
        payload: {
            environment: "' . $environment . '"
        }
    };
    // Rollbar Snippet
    !function(r){function t(o){if(e[o])return e[o].exports;var n=e[o]={exports:{},id:o,loaded:!1};return r[o].call(n.exports,n,n.exports,t),n.loaded=!0,n.exports}var e={};return t.m=r,t.c=e,t.p="",t(0)}([function(r,t,e){"use strict";var o=e(1).Rollbar,n=e(2);_rollbarConfig.rollbarJsUrl=_rollbarConfig.rollbarJsUrl||"https://d37gvrvc0wt4s1.cloudfront.net/js/v1.7/rollbar.min.js";var a=o.init(window,_rollbarConfig),i=n(a,_rollbarConfig);a.loadFull(window,document,!_rollbarConfig.async,_rollbarConfig,i)},function(r,t){"use strict";function e(){var r=window.console;r&&"function"==typeof r.log&&r.log.apply(r,arguments)}function o(r,t){return t=t||e,function(){try{return r.apply(this,arguments)}catch(e){t("Rollbar internal error:",e)}}}function n(r,t,e){window._rollbarWrappedError&&(e[4]||(e[4]=window._rollbarWrappedError),e[5]||(e[5]=window._rollbarWrappedError._rollbarContext),window._rollbarWrappedError=null),r.uncaughtError.apply(r,e),t&&t.apply(window,e)}function a(r){this.shimId=++u,this.notifier=null,this.parentShim=r,this.logger=e,this._rollbarOldOnError=null}function i(r){var t=a;return o(function(){if(this.notifier)return this.notifier[r].apply(this.notifier,arguments);var e=this,o="scope"===r;o&&(e=new t(this));var n=Array.prototype.slice.call(arguments,0),a={shim:e,method:r,args:n,ts:new Date};return window._rollbarShimQueue.push(a),o?e:void 0})}function l(r,t){if(t.hasOwnProperty&&t.hasOwnProperty("addEventListener")){var e=t.addEventListener;t.addEventListener=function(t,o,n){e.call(this,t,r.wrap(o),n)};var o=t.removeEventListener;t.removeEventListener=function(r,t,e){o.call(this,r,t&&t._wrapped?t._wrapped:t,e)}}}var u=0;a.init=function(r,t){var e=t.globalAlias||"Rollbar";if("object"==typeof r[e])return r[e];r._rollbarShimQueue=[],r._rollbarWrappedError=null,t=t||{};var i=new a;return o(function(){if(i.configure(t),t.captureUncaught){i._rollbarOldOnError=r.onerror,r.onerror=function(){var r=Array.prototype.slice.call(arguments,0);n(i,i._rollbarOldOnError,r)};var o,a,u="EventTarget,Window,Node,ApplicationCache,AudioTrackList,ChannelMergerNode,CryptoOperation,EventSource,FileReader,HTMLUnknownElement,IDBDatabase,IDBRequest,IDBTransaction,KeyOperation,MediaController,MessagePort,ModalWindow,Notification,SVGElementInstance,Screen,TextTrack,TextTrackCue,TextTrackList,WebSocket,WebSocketWorker,Worker,XMLHttpRequest,XMLHttpRequestEventTarget,XMLHttpRequestUpload".split(",");for(o=0;o<u.length;++o)a=u[o],r[a]&&r[a].prototype&&l(i,r[a].prototype)}return r[e]=i,i},i.logger)()},a.prototype.loadFull=function(r,t,e,n,a){var i=function(){var t;if(void 0===r._rollbarPayloadQueue){var e,o,n,i;for(t=new Error("rollbar.js did not load");e=r._rollbarShimQueue.shift();)for(n=e.args,i=0;i<n.length;++i)if(o=n[i],"function"==typeof o){o(t);break}}"function"==typeof a&&a(t)},l=!1,u=t.createElement("script"),s=t.getElementsByTagName("script")[0],c=s.parentNode;u.src=n.rollbarJsUrl,u.async=!e,u.onload=u.onreadystatechange=o(function(){if(!(l||this.readyState&&"loaded"!==this.readyState&&"complete"!==this.readyState)){u.onload=u.onreadystatechange=null;try{c.removeChild(u)}catch(r){}l=!0,i()}},this.logger),c.insertBefore(u,s)},a.prototype.wrap=function(r,t){try{var e;if(e="function"==typeof t?t:function(){return t||{}},"function"!=typeof r)return r;if(r._isWrap)return r;if(!r._wrapped){r._wrapped=function(){try{return r.apply(this,arguments)}catch(t){throw t._rollbarContext=e()||{},t._rollbarContext._wrappedSource=r.toString(),window._rollbarWrappedError=t,t}},r._wrapped._isWrap=!0;for(var o in r)r.hasOwnProperty(o)&&(r._wrapped[o]=r[o])}return r._wrapped}catch(n){return r}};for(var s="log,debug,info,warn,warning,error,critical,global,configure,scope,uncaughtError".split(","),c=0;c<s.length;++c)a.prototype[s[c]]=i(s[c]);r.exports={Rollbar:a,_rollbarWindowOnError:n}},function(r,t){"use strict";r.exports=function(r,t){return function(e){if(!e&&!window._rollbarInitialized){var o=window.RollbarNotifier,n=t||{},a=n.globalAlias||"Rollbar",i=window.Rollbar.init(n,r);i._processShimQueue(window._rollbarShimQueue||[]),window[a]=i,window._rollbarInitialized=!0,o.processPayloads()}}}}]);
    // End Rollbar Snippet
</script>
';

}

/*
 * Filter
 */
function rollbar_wp_filter_php_errors ( $errfile ) {

    if (strpos($errfile,'rollbar') !== false) {
        return true;
    }

    return false;
}

/*
 * Helper
 */
function rollbar_wp_debug($args) {
    echo '<pre>';
    print_r($args);
    echo '</pre>';
}