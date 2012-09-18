Tablelist = function(id, cfg) {
		//如果不是以#开始的选择器，则在前面加上# 
		id = /^#/.test(id) ? id : '#' + id;
		var prevId = id + " "; //加上一个空格

		var ndTablelist = $(id);
		if (!ndTablelist.length) return false;

		var ndTable = $(prevId + '.table');
		var ndMenu = $(prevId + '.menu');
		var ndMenuTitle = $(prevId + '.menu .title');
		var ndListItems = $(prevId + 'li.item');
		var ndInputSearch = $(prevId + 'input');

		cfg = !!cfg ? cfg : {}; //传入的设置
		var config = $.extend({
			menuBackgroundColor: ndMenu.css('background-color'),
			fnClickItem: null //当选中一个item时，触发的事件，模板是function({id:string, name:string})
		}, cfg);

		//将此实例放入其instances列表里
		//注:Tablelist.prototype.instances = []
		this['instances'].push(this);
		this['release'] = function() {
			ndMenu.css('background-color', config['menuBackgroundColor']);
			ndTable.fadeOut(300);
		}
		var _this = this;

		ndMenu.click(function(e){
			e.stopPropagation();
			e.preventDefault();
			_this.releaseAll(); //默认首先隐藏所有菜单（多实例模式下）
			//如果已经显示，则隐藏之（上面的release函数已经做到了)
			if (!/none/.test(ndTable.css('display'))) {
				return;
			}
			var offset = ndMenu.offset();
			ndMenu.css('background-color', '#aae');
			ndTable.fadeIn(300);
			ndTable.css('top', offset['top'] + 30);
			ndTable.css('left', offset['left']);
		});


		$.addDocumentClick({'name': 'hide-' + id + '-table', 'fn': function(){
			_this['release']();
		}});
		
		ndTable.click(function(e){
			e.preventDefault();
			var ndTarget = $(e.target);
			var id = null;
			if (ndTarget.hasClass('item')) {
				!!config.fnClickItem && config.fnClickItem(ndTarget.attr('data-info')); 
				_this['release']();
				ndMenuTitle.html(ndTarget.text());
			}
		});

		/**
		 * 如果ndInputSearch存在的话（如果用户在页面上没有这个input也不影响插件的正常运行）
		 * 检查list-item的innerHTML，jianpin 和 quanpin三项属性
		 * 符合输入要求的就显示出来
		 */
		!!ndInputSearch && ndInputSearch.keyup(function(e){
			var text = ndInputSearch.val();
			text = $.trim(text);
			text.length ? ndListItems.hide() : ndListItems.show();

			$.each(ndListItems, function(index, elem) {
				var searchText = elem.getAttribute('data-search') ? elem.getAttribute('data-search') : '';
				if (searchText.indexOf(text) != -1 || elem.innerHTML.indexOf(text) != -1 ) {
					elem.style.display = '';//使其显示出来
				}
			});
		});
		!!ndInputSearch && ndInputSearch.click(function(e){
			e.stopPropagation();
		});
}

$.extend(Tablelist.prototype, {
	'instances': [], //存储实例
	'releaseAll': function() { //调用所有实例的release函数
		$.each(this['instances'], function(index, val){
			val.release();
		});
	}
});
