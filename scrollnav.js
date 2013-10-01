;(function(e){function n(e){return typeof e=="object"?e:{top:e,left:e}}var t=e.scrollTo=function(t,n,r){e(window).scrollTo(t,n,r)};t.defaults={axis:"xy",duration:parseFloat(e.fn.jquery)>=1.3?0:1,limit:true};t.window=function(t){return e(window)._scrollable()};e.fn._scrollable=function(){return this.map(function(){var t=this,n=!t.nodeName||e.inArray(t.nodeName.toLowerCase(),["iframe","#document","html","body"])!=-1;if(!n)return t;var r=(t.contentWindow||t).document||t.ownerDocument||t;return/webkit/i.test(navigator.userAgent)||r.compatMode=="BackCompat"?r.body:r.documentElement})};e.fn.scrollTo=function(r,i,s){if(typeof i=="object"){s=i;i=0}if(typeof s=="function")s={onAfter:s};if(r=="max")r=9e9;s=e.extend({},t.defaults,s);i=i||s.duration;s.queue=s.queue&&s.axis.length>1;if(s.queue)i/=2;s.offset=n(s.offset);s.over=n(s.over);return this._scrollable().each(function(){function h(e){u.animate(l,i,s.easing,e&&function(){e.call(this,r,s)})}if(r==null)return;var o=this,u=e(o),a=r,f,l={},c=u.is("html,body");switch(typeof a){case"number":case"string":if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(a)){a=n(a);break}a=e(a,this);if(!a.length)return;case"object":if(a.is||a.style)f=(a=e(a)).offset()}e.each(s.axis.split(""),function(e,n){var r=n=="x"?"Left":"Top",i=r.toLowerCase(),p="scroll"+r,d=o[p],v=t.max(o,n);if(f){l[p]=f[i]+(c?0:d-u.offset()[i]);if(s.margin){l[p]-=parseInt(a.css("margin"+r))||0;l[p]-=parseInt(a.css("border"+r+"Width"))||0}l[p]+=s.offset[i]||0;if(s.over[i])l[p]+=a[n=="x"?"width":"height"]()*s.over[i]}else{var m=a[i];l[p]=m.slice&&m.slice(-1)=="%"?parseFloat(m)/100*v:m}if(s.limit&&/^\d+$/.test(l[p]))l[p]=l[p]<=0?0:Math.min(l[p],v);if(!e&&s.queue){if(d!=l[p])h(s.onAfterFirst);delete l[p]}});h(s.onAfter);}).end()};t.max=function(t,n){var r=n=="x"?"Width":"Height",i="scroll"+r;if(!e(t).is("html,body"))return t[i]-e(t)[r.toLowerCase()]();var s="client"+r,o=t.ownerDocument.documentElement,u=t.ownerDocument.body;return Math.max(o[i],u[i])-Math.min(o[s],u[s])};})(jQuery)

/*
 known bugs:
    offset crap //fixed 09.04.2013 - tuesday morning
    hash crap
    current class crap //fixed 09.04.2013 - tuesday noon
 * */
jQuery(window).ready(function() {
    var         $           =           jQuery;
    var         el          =           $('.scrollnav');
    var         ul          =           $('.scrollnav > ul');
    var         plContent   =           $('#dynamic-content');
    var         parseC      =           '.scroll-header';
    var         pokeS       =           new Array();
    var         counter     =           0;

    var ScrollNav = function(options){
        this.options = options;
    };

    ScrollNav.prototype = {
        defaults: {
            scrollSpeed: 750,
            scrollOffset: 0,
            easing: 'swing',
            changeHash: false,
            currentClass: 'current',
        },
        init: function() {
            self = this;
            self.config = $.extend({},self.defaults,self.options);

            link = ul.find('a');
            link.on('click.scrollNav', $.proxy(self.handleClick, self));
        },
        handleClick: function(e){
            	self.clear(ul);
            	link = $(e.currentTarget);
            	$.scrollTo($(plContent.find("[data-scrollnav='" + link.attr('data-scrollnav-goto') + "']")),
                self.config.scrollSpeed, {
                    axis: 'y',
                    easing: self.config.easing,
                    offset: {top:+self.config.scrollOffset},
                    onAfter: function(){if (self.config.changeHash) window.location.hash = self.getHash()},
                    }
            	);
            	e.preventDefault();
            	self.setCurrent(link);
        },
        getHash: function(){
            /*
             * still buggy
             */
        },
        getOffSet: function(obj){
            return obj.top + self.config.scrollOffset;
        },
        setCurrent: function(link){
            link.addClass(self.config.currentClass);
        },
        clear: function(ul){
            ul.find('a').removeClass(self.config.currentClass);
        }
    };

    $.fn.scrollNav = function(options) {
    	if ($.browser.msie) { handleIE()} else
        return new ScrollNav(options).init();
    };

    if ($.browser.msie) {
		plContent.find(parseC).each(function(){
			var $me = $(this);
			var title = $me.prop('title'); //sad times for IE
			var parent = $me.parents().eq(3);
			parent.attr('name',$me.prop('title')); // like mentioned
			ul.append('<li><a class="scroll-nav-anchor" href="#'+title+'"><i></i><div class="sn-title">' + title + '</div></a></li>');
		});
	} else {
		plContent.find(parseC).each(function(){
			//parent = $(this).parents().eq(3);
            parent = $(this).closest('section');
			pokeS.push(parent.attr('data-scrollnav', ++counter));
			headerTxt = $(this).attr('title'); parent.attr('name', headerTxt);
			ul.append('<li><a class="scroll-nav-anchor" data-scrollnav-goto="' + counter +'" href="#' + headerTxt +'"><i></i><div class="sn-title">' + headerTxt + '</div></a></li>');
		});
	};


});

function handleIE(){
	jQuery('a[href^="#"]').click(function(e){
		var target = jQuery(this.hash);
		if (target.length == 0) target = jQuery('section[name="' + this.hash.substr(1) + '"]');
		jQuery('html, body').animate({ scrollTop: target.offset().top }, 500);
	});
}


