(function($){
ResizedWindow = function(conf) { 
	//id不让传进来，id必须是自己随机生成的
	//不然的话在多实例的情况下可能会出现问题
	conf = $.extend({
		'width': 400,
		'height': 400,
		'title': 'testApp',
		'resizable': true,
		'min': null,
		'max': null,
		'url': 'http://www.google.com.hk',
		'content': 'hello world',
		'class': ''
	}, conf);
	var appId = "w" + (new Date() - 0) + "" + Math.round(Math.random() * 10000000);
	conf['id'] = appId;
	var ndWindow = create();
	var ndFrame = queryNode("iframe"),
		ndTitle = queryNode(".window_titlebar"),
		ndBody = queryNode(".window_body"),
		ndFrameOverlay = queryNode(".iframe_overlay"),
		ndContentWindow = queryNode(".window_content"),
		ndResizeBar = queryNode(".resizebar"),
		ndClose = queryNode(".window_close");

	ndContentWindow.width(conf.width + "px");
	if (ndFrame.length) {
		ndFrame.height(conf.height + "px");
		ndWindow.enableDrag(ndTitle, ndFrameOverlay);
		conf.resizable && ndWindow.enableResize(ndResizeBar, {'ndHorizon': ndContentWindow, 'ndVertical': ndFrame, 'ndFrameOverlay': ndFrameOverlay});//指示改变高度时改变那个node
	} else {
		ndBody.height(conf.height + "px");
		ndWindow.enableDrag(ndTitle);
		conf.resizable && ndWindow.enableResize(ndResizeBar, {'ndHorizon': ndContentWindow, 'ndVertical': ndBody});//指示改变高度时改变那个node
	}

	ndWindow['close'] = function(){
		ndWindow.fadeOut(500);
		setTimeout(function(){
			ndWindow.remove();
			ndResizeBar.unbind();
			ndTitle.unbind();
			$(document).unbind("." + appId);
		}, 1000);
	}

	ndClose.one('mousedown', function(e) {  //无法绑定click事件，真不知道为什么那么蛋疼
		ndWindow.close();
		e.preventDefault();
		e.stopPropagation();
	});

	//ndWindow.css('top', (Math.round($(window).height() / 2 - (conf.height / 2) - 100) + 'px'));
	ndWindow.css('top', '80px');
	ndWindow.css('left', (Math.round($(window).width() / 2 - (conf.width / 2) ) + 'px')) ;
	return ndWindow;

	//获得当前appWindow下的节点
	function queryNode(selector){
		return $('#' + appId + " " + selector);
	}

	function create(){
		var xtmpl = new Xtemplate('resized-window-template');
		xtmpl.setVar(conf);
		return $(xtmpl.getText()).appendTo($('body'));
	}


 }
$.extend(ResizedWindow.prototype, {
});
})(jQuery);
