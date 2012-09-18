//enableMiniScroll以扩展形式弄进jQuery里
(function($){ 
 $.extend($.fn, {
	enableMiniScroll: (function(){
		var bMouseDown = false; //对于scroll而言,任意时候只可能对一个dom有mousedown
		return function(conf) {
			conf = $.extend({
				scrollWidth: "15",
				wheelMeta: "30",
				defaultColor: "#999",
				hoverColor: "#aaa",
				downColor: "#777",
				margin: 3,
				minHeight: 40,
				fault: 0   //误差,指因为padding,border,margin而产生的高度差值
				}, conf);

			return this.each(function(){ //为jQuery对象中的每一个元素处理一遍
				var elContent = this,
					ndContent =  $(this),
					ndThis = $(this);
				var ndContainer = $(this).parent();
				var ndScroll = $("<div class='scrollbar' style='position:absolute;background-color:#999; border-radius:2px;'></div>"),
					elScroll = ndScroll[0];
				var eventNamespace = "." + ndThis.attr('id');

				if ( ndContainer.css("position") === "static") {
					ndContainer.css("position", "relative");
				}
				ndScroll.appendTo(ndContainer);
				ndScroll.css({width: conf.scrollWidth + 'px', right: 3 + 'px'});

				var preY = null;
				var minTop = conf.margin - 0, maxTop;//maxTop可能在contentChange后会有改变
				var containerHeight = ndContainer.height();
				var listTotalHeight,//列表的整个高度
					listHeight,//列表的可视高度
					scrollDistance,//列表高度减掉滚动条高度，再减掉margin
					totalScrollTop,//列表的可滚动高度
					scrollHeight;//滚动条高度

				//根据内容定义滚动条的高度
				//console.log(scrollHeight, listTotalHeight, listHeight);
				$(this).bind('contentChange', init);
				//init初始化函数
				function init() {
					listTotalHeight = elContent.scrollHeight - conf.fault;
					listHeight = ndContent.height();
					scrollHeight = listTotalHeight - listHeight;
					//console.log(listTotalHeight,listHeight,scrollHeight);
					//scroll高度
					if (scrollHeight > listHeight - conf.minHeight) {
						ndScroll.show();
						scrollHeight = conf.minHeight;
					} else if (scrollHeight <= 0) {
						ndScroll.hide();
					} else{
						ndScroll.show();
						scrollHeight = listHeight - scrollHeight;
					}
					ndScroll.height(scrollHeight);
					totalScrollTop = listTotalHeight - listHeight;//列表总共可以卷掉的长度
					maxTop = containerHeight - scrollHeight - conf.margin;
					scrollDistance = maxTop - minTop;
					var top = Math.ceil(elContent.scrollTop * scrollDistance / totalScrollTop) + minTop;
					top = (top <= minTop) ? minTop : (top > maxTop) ? maxTop : (top);
					ndScroll.css('top', top + "px");
				}
				//init执行初始化
				init();
				//鼠标悬停时的效果
				ndScroll.hover(function(){
					ndScroll.css('background-color', conf.hoverColor);
				}, function(){
					ndScroll.css('background-color', conf.defaultColor);
				});

				//鼠标按下时的效果
				ndScroll.bind('mousedown' + eventNamespace, function(e){
					ndScroll.css('background-color', conf.downColor);
					bMouseDown = true;
					e.preventDefault(); //必须加上这一句，否则firefox异常
				});

				//鼠标放开时的效果，因为不一定在当前控件松开
				//因此绑定全局
				$(document).bind('mouseup' + eventNamespace, function(e){
					ndScroll.css('background-color', conf.defaultColor);
					if (bMouseDown) {
						bMouseDown = false;
						e.preventDefault();
					}
				});

				//鼠标移动的事件，借此来控制内容
				$(document).bind('mousemove' + eventNamespace, function(e){
					var distance;
					var top = (ndScroll.css('top') + "").slice(0, -2) - 0;
					if (bMouseDown) {
						if (preY) {
							distance = e.pageY - preY;
							ndScroll.trigger("move", [distance, top ]);
							e.preventDefault();
						}
					}
					preY = e.pageY;
				});

				//自定义事件，控制scrollbar的走动，及listapp的滚动
				ndScroll.bind("move" + eventNamespace, function(e, distance, top) {
					top = top ? top : (ndScroll.css('top') + "").slice(0, -2) - 0;
					distance = (distance ? distance : 0) - 0;
					//console.log(distance,"  ", top);
					top = top + distance;
					top = (top <= minTop) ? minTop : (top > maxTop) ? maxTop : (top);
					ndScroll.css('top', top + 'px');
					elContent.scrollTop = Math.ceil((top - minTop) * totalScrollTop / scrollDistance);
					//console.log('scrollTop:', elContent.scrollTop, 'top:', top , 'totalST:', totalScrollTop ,'dis:', scrollDistance);
					//console.log("listHeight:", listHeight, "scrollHeight:", scrollHeight, "margin", conf.margin);
				});

				//鼠标滚轮事件
				var mousewheel = jQuery.browser.mozilla ? 'DOMMouseScroll' : 'mousewheel';//垃圾firefox
				ndContent.bind(mousewheel + eventNamespace, function(e){
					e.preventDefault();
					e.stopPropagation();
					//console.log(e);
					e = e.originalEvent; //jQuery 1.7真蛋疼
					var isUp = (e.wheelDelta ? e.wheelDelta : e.detail) > 0 ? true : false;
					isUp = jQuery.browser.mozilla ? !isUp : isUp; //垃圾火狐和其他浏览器是反过来的
					var counts = conf.wheelMeta;
					if (jQuery.browser.msie || jQuery.browser.mozilla) {
						//IE和firefox就这样好了,谁让它们性能都那么烂
						ndScroll.trigger('move', isUp ? [-conf.wheelMeta] : [conf.wheelMeta]);
					} else {
						//保证平滑且能滑动到最底（顶）端
						setTimeout(function(){
							if (counts > 0) {
								ndScroll.trigger('move', isUp ? [-1] : [1]);
								counts--;
								setTimeout(arguments.callee, 10);
							}
						},0);
					}
				});
			});
		};
	})()//自执行函数
});
})(jQuery);

//enablePlaceholder
(function($){
	$.extend($.fn, {
		enablePlaceholder: function(conf) {
			//判断是否原生支持placeholder
			var inputEle = document.createElement('input');
			if ('placeholder' in inputEle) {
				return;
			}
			return this.each(function(index, ele){
				var nd = $(ele);
				var text = nd.attr('placeholder');
				if (!text) return;

				var originalColor = nd.css('color');
				var isHolder = true;
				nd.focus(function(){
					if (isHolder) {
						nd.css('color', originalColor);
						nd.val('');
					}
				});

				nd.blur(function(){
					if (!nd.val()) {
						nd.val(text);
						nd.css('color', '#ccc');
						isHolder = true;
					}
				});

				nd.keyup(function(){
					isHolder = nd.val() ? false : true;
				});
				nd.blur();
			});
		}
	});
})(jQuery);

/**
 * receiveJSON,因为getJSON不好用，如果返回的不是JSON的话会直接不调用回调函数 
 * 用法：
 * $.receiveJSON(url, {param1:xx, param2:yy}, function(res) {
 *		if ( res.status) {
 *			console.log(res.data);
 *		} else {
 *			console.log(res.msg);
 *		}
 * });
 */
(function($){
	$.extend({
		receiveJSON: function(action, param, func) {
			//如果只传入了两个参数
			if (typeof param === 'function') {
				func = param;
				param = '';
			}

			$.get(action, param, function(res) {
				try{
					res = $.parseJSON(res);
				} catch(e){} finally {
					//如果parseJSON失败的话，则将其改装成{status: {st}, msg:{msg}}格式
					res = (typeof res === "string" || !res) ? {'status':false, 'msg': res} : res;
					res.status = res.status - 0;//因为js的死bug,虽然'0' == false, 但在if('0')中却是相当于if('true')
					func(res);
				}
			});
		}
	});
})(jQuery);

(function($){
	$.extend($.fn, {
		enableDrag: (function(){
			var dragIndex = 10;
			return function(ndDrag, ndOverlay) {
				var bMouseDown = false;
				var ndThis = $(this),
					ndDocument = $(document);
				var eventNamespace = "." + ndThis.attr('id');
				ndOverlay = ndOverlay ? ndOverlay : {show:function(){}, hide:function(){}};

				var preX = null, preY = null;
				if (ndDrag.length) {
					//鼠标按下时的效果
					ndDrag.bind('mousedown' + eventNamespace,function(e){
						bMouseDown = true;
						ndThis.css('z-index', ++dragIndex);
						ndOverlay && ndOverlay.show();
						e.preventDefault(); //必须加上这一句，否则firefox异常
					});

					//鼠标放开时的效果，因为不一定在当前控件松开
					//因此绑定全局
					ndDocument.bind('mouseup' + eventNamespace, function(e){
						if (bMouseDown) {
							bMouseDown = false;
							ndOverlay && ndOverlay.hide();
							e.preventDefault();
						}
					});

					//鼠标移动的事件，借此来控制内容
					ndDocument.bind('mousemove' + eventNamespace,function(e){
						var tDistance, lDistance;
						var top = (ndThis.css('top') + "").slice(0, -2) - 0;
						var left = (ndThis.css('left') + "").slice(0, -2) - 0;
						if (bMouseDown) {
							if (preY) {
								tDistance = e.pageY - preY;
								lDistance = e.pageX - preX;
								e.preventDefault();
								ndThis.css('top', (top + tDistance) + 'px');
								ndThis.css('left', (left + lDistance) + 'px');
							}
						}
						preY = e.pageY;
						preX = e.pageX;
					});
				}
		}
	})()
	});
})(jQuery);

//enable resize
(function($){
	$.extend($.fn, {
		enableResize: function(ndResizeBar, conf) {
			var ndThis = $(this),
				ndDocument = $(document);
			var bMouseDown = false;
			var eventNamespace = "." + ndThis.attr('id');

			conf = $.extend({
				 ndHorizon: ndThis,  //改变水平宽度的node
				 ndVertical: ndThis, //改变竖直宽度的node
				 ndFrameOverlay: {show:function(){}} //遮罩层，当内嵌有iframe时管用
			}, conf);
			var ndHorizon = conf.ndHorizon,
				ndVertical = conf.ndVertical,
				ndFrameOverlay = conf.ndFrameOverlay;

			var preX = null, preY = null;

			ndResizeBar.bind('mousedown' + eventNamespace, function(e){
				bMouseDown = /\d/.exec(e.target.className) + "";
				ndTarget = $(e.target);
				ndFrameOverlay.show();
				e.preventDefault();
			});
			ndDocument.bind('mouseup'+ eventNamespace, function(e){
				if (bMouseDown) {
					bMouseDown = false;
					ndFrameOverlay.hide();
					e.preventDefault();
				}
			});

			ndDocument.bind('mousemove' + eventNamespace, function(e){
				var yDistance, xDistance;
				if (bMouseDown) {
					if (preY) {
						xDistance = e.pageX - preX;
						yDistance = e.pageY - preY;
						e.preventDefault();
						if ( yDistance || xDistance) {
							resize(xDistance, yDistance);
						}
					}
				}
				preY = e.pageY;
				preX = e.pageX;
			});

			function resize(x, y){
				if (!bMouseDown) return;
				var top = (ndThis.css('top') + "").slice(0, -2) - 0;
				var left = (ndThis.css('left') + "").slice(0, -2) - 0;
				var width = ndHorizon.width() - 0;
				var height = ndVertical.height() - 0;
				switch(bMouseDown - 0) {
					case 1:
						ndHorizon.width(width - x);
						ndVertical.height(height + y);
						ndThis.css('left', left + x + 'px');
						break;
					case 2:
						ndVertical.height(height + y);
						break;
					case 3:
						ndHorizon.width(width + x);
						ndVertical.height(height + y);
						break;
					case 4:
						ndThis.css('left', left + x + 'px');
						ndHorizon.width(width - x);
						break;
					case 6:
						ndHorizon.width(width + x);
						break;
					case 7:
						ndThis.css('top', top + y + 'px');
						ndThis.css('left', left + x + 'px');
						ndHorizon.width(width - x);
						ndVertical.height(height - y);
						break;
					case 8:
						ndThis.css('top', top + y + 'px');
						ndVertical.height(height - y);
						break;
					case 9:
						ndThis.css('top', top + y + 'px');
						ndHorizon.width(width + x);
						ndVertical.height(height - y);
						break;
				}
			}
			return ndThis;
		}
	});
})(jQuery);


(function($){
	//为document绑定click_fn数组
	document['click_fn'] = [];
	$(document).click(function(){
		$.each(document['click_fn'], function(index, val){
			val.fn();
		});	
	});

	$.extend({
		/*
		 * @param obj {name: String, fn: Function}
		 */
		addDocumentClick: function(obj){
			document['click_fn'].push(obj);
		},
		/*
		 * @param obj String
		 */
		removeDocumentClick: function(name) {
			var click_fn = document['click_fn'];
			for ( var i = 0; i < click_fn.length; i++) {
				if (click_fn[i]['name'] == name) {
					click_fn.splite(i, 1);
				}
			}
		}
	});
})(jQuery);
/*
 * x-template
 */
(function($){
	Xtemplate = function(id) {
		!!id && (this['text'] = document.getElementById(id).innerHTML);
	}
	/**
	 * 设置模板里的变量
	 * @param {object} args
	 */
	Xtemplate.prototype.setVar = function(args) {
		var msg = this['text'];
		$.each(args, function(name, val) {
			msg = msg.replace("${" + name + "}", val);
		});
		this['text'] = msg;
	};

	/**
	 * 手动设置模板内容
	 * @param {object} args
	 */
	Xtemplate.prototype.setText = function(text) {
		this['text'] = text;
	};

	Xtemplate.prototype.getText = function(args) {
		return this['text'];
	};
	Xtemplate.prototype.fill = function(text) {
		var msg = this['text'];
		msg = msg.replace(/\${\w+}/g, text);
		this['text'] = msg;
	}
})(jQuery);

//为jQuery本身添加一些内容
(function($){
	$.extend({
		nilfunc: function(){},
		keyCode: {
				'ENTER': 13,
				'ESC': 18,
				'BACKSPACE': 8
				 }
	});
})(jQuery);
//为jQuery生成的对象添加一些内容
(function($){
	$.extend($.fn, {
		'findParent': function(str) {
				var type = null;
				if (/^\./.test(str)) {
					type = 'class';	
				} else if (/^#/.test(str)) {
					type = 'id';	
				}
				var identical = str.substr(1);
				var ret;
				this.each(function(index, val){ //为jQuery对象中的每一个元素处理一遍
					var nd = $(this).parent();
					while(nd.length) {
						if (type === 'id' && nd.attr('id') == identical) {
							ret = nd;
							return false;
						} else if (type === 'class' && nd.hasClass(identical)) {
							ret = nd;
							return false;
						}
						nd = nd.parent();
					}
				});
				return ret;
			}
		});
})(jQuery);
//IE中启动HTML5标签
$.each(['article','aside','details','figcaption','figure','footer','header','hgroup','menu','nav','section'], function(index,val) {
	document.createElement(val);
});
//绑定所有的close按钮
//关闭其父标签为.window的div
$(document).delegate('.close', 'click', function(e){
	var ndThis = $(this);
	var ndWindowDiv = ndThis.findParent('.window');	
	ndWindowDiv && ndWindowDiv.hide();
});

$(document).delegate('#ie-warning .close', 'click', function(e) {
	document.cookie = "already_know_ie=true";
	alert('cc');
});
