Tabstage = function(id, cfg) {
		//如果不是以#开始的选择器，则在前面加上# 
		id = /^#/.test(id) ? id : '#' + id;
		var prevId = id + " "; //加上一个空格

		var ndStage = $(id);
		if (!ndStage.length) return false;

		var ndHead = $(prevId + '.hd ul');
		var ndTabs = $(prevId + '.hd ul li');
		var ndPages = $(prevId + '.body .page');

		cfg = !!cfg ? cfg : {}; //传入的设置
		var config = $.extend({
		}, cfg);

		//点击tab时的反应
		/*
		ndHead.click(function(e){
			if (/li|LI/.test(e.target.tagName)) {
			*/
		ndHead.delegate('li', 'click', function(e) {
				ndTabs.removeClass('active');
				$(this).addClass('active');
				
				var page = this.getAttribute('data-page');
				ndPages.removeClass('active');
				for(var i = 0; i < ndPages.length; i++) {
					elem = ndPages[i];
					if (elem.getAttribute('data-name') == page) {
						$(elem).addClass('active');
					}
				}
		});

}
