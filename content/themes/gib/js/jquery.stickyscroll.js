(function($,window, undefined){

    var pluginName = "stickyScroll",
    defaults = {
        "class":"scrolled-off"
    }

    function Plugin( element, options ) {
        this.element = element;
        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;

        this.init();
    }

    Plugin.prototype.init = function(){
        var self = this,
        offset = $(this.element).offset();
        $(window).scroll(function(event){
            if(elementIsOutsideViewport(event.target, offset)){
                self.elementScrolledOff(); 
            } 
            else{
                self.elementScrolledOn();
            }
        });

    }

    Plugin.prototype.elementScrolledOn = function(){
        $(this.element).removeClass(this.options["class"]);        
    }

    Plugin.prototype.elementScrolledOff = function(){
        $(this.element).addClass(this.options["class"]);        
    }

    $.fn.stickyScroll = function(options){
        return this.each(function(){
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                    new Plugin( this, options ));
            }        
        });
    }

    function elementIsOutsideViewport(viewport,offset){
        var $viewport = $(viewport);
        return $viewport.scrollTop() > offset.top || $viewport.scrollLeft() > offset.left;
    }

})(window.jQuery,window)

// Tag Active Marker

jQuery(document).ready(function($){

    // Add Positions : Main

    var markerPositions = [];
    var currentPosition = 0;

    $('#main .grid_9 h2').each(function(index){
        $(this).addClass('list_' + index);
        var height = $(this).offset().top;
        markerPositions.push(height)
    });

    // Add Final Barrier (not a real value, just needs to be large)
    markerPositions.push(1000000000);

    // Add Positions : Side Navigation

    $('#sticky-sidebar a').each(function(index){
        $(this).addClass('list_' + ( index + 1));
    });

    // Function: Check Markers

    function checkMarkers(){
        var offset = $(window).scrollTop();
        var lower = 0;
        $.each(markerPositions, function(i, val) {

            if ( offset < val && val > lower ) {
                if ( currentPosition != val ) {
                    $('#sticky-sidebar a').css('font-weight','normal');
                    $('#sticky-sidebar a.list_' + i ).css('font-weight','bold');
                    currentPosition = val;
                }
                return false;
            }

            lower = val;

        });

    }

    // On Movement

    $(window).on('scroll resize',function(){
        checkMarkers();
    });

});
