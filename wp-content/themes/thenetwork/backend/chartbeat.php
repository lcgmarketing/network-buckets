<!-- CHARTBEAT -->
<script type="text/javascript">
var _sf_async_config={};
/** CONFIGURATION START **/
_sf_async_config.uid = 44820;
_sf_async_config.domain = "moneybizlife.com"; /** CHANGE THIS **/
/** CONFIGURATION END **/
(function(){
  function loadChartbeat() {
    window._sf_endpt=(new Date()).getTime();
    var e = document.createElement("script");
    e.setAttribute("language", "javascript");
    e.setAttribute("type", "text/javascript");
    e.setAttribute('src', '//static.chartbeat.com/js/chartbeat.js');
    document.body.appendChild(e);
  }
  var oldonload = window.onload;
  window.onload = (typeof window.onload != "function") ?
     loadChartbeat : function() { oldonload(); loadChartbeat(); };
})();
</script>