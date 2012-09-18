/*
$(function(){
	var tabstage = new Tabstage('tabs'); 	 
	var pychooser = new Pychooser('page-insert'); 	
	var ndInsertForm = $('form[name=insert-form]');
	var ndQueryForm = $('form[name=query-form]');

	ndInsertForm.submit(function(e) {
		e.preventDefault();
		$.receiveJSON(ndInsertForm.attr('action'), ndInsertForm.serialize(), function(res) {
			if (res.status) {
				alert(res.data);
			} else {
				alert(res.msg);
			}
		});
	});

*/
$(function(){
if (document['isLessThanIE9']) { }
//调整左右两个按钮位置，自适应窗口高度和宽度
var ndPrevSign = $('.prev.switch-sign');
var ndNextSgin = $('.next.switch-sign');
var ndLoading = $("#g-loading");
var ndRangeIndct = $('.range-indicator');
var ndWindow = $(window);
var currentResult = null;
var queryConditon = '';
ndWindow.bind('resize', reset_nd_switch);
function reset_nd_switch(){
	var wHeight = ndWindow.height();
	var wWidth = ndWindow.width();
	ndPrevSign.css('top', (wHeight / 2 - 60 ) + 'px');
	ndNextSgin.css('top', (wHeight / 2 - 60 ) + 'px');
	if (wWidth >= 1000 ) {
		//IE9以下修正一个像素
		ndPrevSign.css('left',((wWidth - 960) / 2  - ndPrevSign.outerWidth() + (document['isLessThanIE9'] ? 1 : 0) + 'px'));	
		ndNextSgin.css('left', ((960 + wWidth) / 2 ) + 'px');	
	} else {
		//对于小于1000px宽度的窗口
		ndPrevSign.css('left','0px');	
		ndNextSgin.css('left',(wWidth - ndNextSgin.outerWidth()) + 'px');	
	}
}
reset_nd_switch();
//提交
var ndQueryForm = $("form[name=query-form]");
var ndResultList = $('section.result ul');
ndQueryForm.submit(function(e){
	e.preventDefault();
	queryConditon = ndQueryForm.serialize(); 
	ndLoading.fadeIn(500);
	queryResult([ndQueryForm.attr('action'),'?', queryConditon, '&start=', 0, '&cs=', 1].join(''));
});

var queryResult = (function(){
	var cache = {};
	var leadingStr = ndQueryForm.attr('action');
	return function(url) {
		var hashedUrl = hashing(url);
		if (cache[hashedUrl]) {
			setResult(cache[hashedUrl]);
		} else {
			$.receiveJSON(url, function(res){
				if (res.status) {
					res.data['cs'] = parseInt(res.data['cs']);
					cache[hashedUrl] = res.data;
					setResult(res.data);
				} else {
					alert(res.msg);
				}
			});
			ndLoading.fadeOut(300);
		}
		function hashing(url) {
			return url.replace(leadingStr, '').replace(/&prev_start=\d+/, ''); //忽略无关痛痒和action字符串和prev_start参数
		}
	}
})();
/*
 * 填充结果区
 * @param array info的格式为{'count': Number, 'data': Array, 'start': Number,'end': Number}
 */
function setResult(info){
	var data = info.data;
	currentResult = info; //为当前域变量currentResult赋值
	var content = '';
	$.each(data, function(index, val) {
		content += ["<li> <span class='yui3-u-1-5 word'>", val['word'],"</span><span class='yui3-u-1-5'>", val['py'], "</span><span class='yui3-u-3-5'>", val['definition'], "</span></li>"].join('');
	});
	ndResultList.empty();
	ndResultList.html(content);
	ndRangeIndct.html((info['cs']) + '-' + (parseInt(info['cs']) - 1 + parseInt(info['count'])));
	ndLoading.fadeOut(300);
	$(document).scrollTop(0); //回到顶部
	reset_nd_switch(); //出现滚动条时，内容内有偏移，故调整一下
}

ndNextSgin.click(function(e){
	e.preventDefault();
	if (!currentResult) return;
	if (currentResult['complete']) {
		alert('没有更多结果了....');
		return;
	}
	ndLoading.fadeIn(500);
	var start = currentResult['end'];
	var prev_start = currentResult['start'];
	var cs = currentResult['cs'] + currentResult['count'];
	//传过去的prev_start会自动传回来
	queryResult([ndQueryForm.attr('action'),'?', ndQueryForm.serialize(), '&start=', start, '&prev_start=', prev_start, '&cs=', cs].join(''));
});

ndPrevSign.click(function(e){
	e.preventDefault();
	if (!currentResult) return;
	if (currentResult['start'] == 0) {
		alert('已经是第一页了....');
		return;
	}
	var start = currentResult['prev_start'];
	var cs = currentResult['cs'] - 30;
	ndLoading.fadeIn(500);
	queryResult([ndQueryForm.attr('action'),'?', ndQueryForm.serialize(), '&', 'start=', start, '&cs=', cs].join(''));
});

});
