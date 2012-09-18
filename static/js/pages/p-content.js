$(function(){
	var xtmpl = { 
		view_hd : (new Xtemplate('view-hd-template')).getText(),
		view_description : (new Xtemplate('view-description-template')).getText(),
		view_substitute : (new Xtemplate('view-substitute-template')).getText(),
		view_sentence : (new Xtemplate('view-sentence-template')).getText(),
		view_content : (new Xtemplate('view-content-template')).getText(),
		view_philosophy : (new Xtemplate('view-philosophy-template')).getText(),
		view_example : (new Xtemplate('view-example-template')).getText(),
		view_comments : (new Xtemplate('view-comments-template')).getText(),
		view_history : (new Xtemplate('view-history-template')).getText(),
		edit_hd : (new Xtemplate('edit-hd-template')).getText(),
		edit_description : (new Xtemplate('edit-description-template')).getText(),
		edit_substitute : (new Xtemplate('edit-substitute-template')).getText(),
		edit_sentence : (new Xtemplate('edit-sentence-template')).getText(),
		edit_content : (new Xtemplate('edit-content-template')).getText(),
		edit_philosophy : (new Xtemplate('edit-philosophy-template')).getText(),
		edit_example : (new Xtemplate('edit-example-template')).getText(),
		edit_comments : (new Xtemplate('edit-comments-template')).getText(),
		edit_history : (new Xtemplate('edit-history-template')).getText(),
		edit_submit : (new Xtemplate('edit-submit-template')).getText()
	}

	var ndView = $(".w-tabstage.stage .page.view");
	var ndEdit = $(".w-tabstage.stage .page.edit");
	var ndForm = ndEdit.parent('form');

	//绑定提交按钮动作
	ndForm.submit(function(e){
		e.preventDefault();
		var args = Array.prototype.splice.apply(arguments,[1]);
		var success_callback = args[0] ? args[0] : $.nilfunc;//成功时的回调函数，默认是空回调函数
		var fail_callback = args[1] ? args[1] : $.nilfunc;//失败时的回调函数，默认是空回调函数
		var action = ndForm.attr('action');
		var formset = ndForm.serialize();
		!/&$/.test(action) && (action += '&'); //如果action不是以&结束的话，则加上&
		$.receiveJSON(action + formset, function(res){
			if (res.status) {
				success_callback();
				alert(res.data);
			} else {
				fail_callback();
				alert(res.msg);
			}
		});
	});

	ndForm.bind('reset', function(e){
		var isSure = window.confirm('真的要删除这个条目吗?');
		if (!isSure) { return false; }
		var orig_action = ndForm.attr('action');
		ndForm.attr('action', orig_action.replace('/update', '/delete'));
		//定义成功时的回调函数，使ndView和ndEdit均为空
		ndForm.trigger('submit',[function(){
			ndView.html('');
			ndEdit.html('');
		}]);
		ndForm.attr('action', orig_action);
	});

	//定义每个类型对应的模板
	var typeArray = {'feel': ['hd', 'description', 'comments', 'history', 'submit'],
					'action': ['hd', 'substitute', 'comments', 'history', 'submit'],
					'adjective': ['hd', 'substitute', 'sentence', 'comments', 'history', 'submit'],
					'philosophy': ['hd', 'philosophy', 'comments', 'history', 'submit'],
					'template': ['hd', 'content', 'example', 'comments', 'history', 'submit'],
					'creative': ['hd', 'content', 'comments', 'history', 'submit'],
					'tactics': ['hd', 'content', 'comments', 'history', 'submit']
					};

	/**
	 * 根据传入的模板数组，返回内容
	 * @param {array} nameArray
	 * @param {string} prefix
	 * @return {string}
	 */
	function getTmplsText(nameArray, prefix) {
		var res = [];
		res = $.map(nameArray, function(elem) {
			return  xtmpl[prefix + '_' + elem] ? xtmpl[prefix + '_' + elem] : '';
		});
		return res.join('\n');
	}

	//初始化每一个tablelist
	$.each(typeArray, function(type, val) {
		new Tablelist('#' + type + '-table-list', {
		'fnClickItem': function(info) {
						ndForm.attr('action', '/content/entity/update?type=' + type +'&id=' + info);
						var id = info;
						var tView = new Xtemplate();
						var tEdit = new Xtemplate();
						tView.setText(getTmplsText(val, 'view'));
						tEdit.setText(getTmplsText(val, 'edit'));
						$.receiveJSON('/content/getentity', {'type': type, 'id':id}, function(res) {
							if (res.status) {
								tView.setVar(res.data);
								tEdit.setVar(res.data);
								ndView.html(tView.getText());
								ndEdit.html(tEdit.getText());
							} else {
								alert(res.msg);
							}
						});
					}
		});
	});

	var tabstage = new Tabstage('.w-tabstage.stage', {});
	var tAdd = new Xtemplate();

	//不想用delegate的原因是想要得到当前tablelist的id
	//为[新增]按钮绑定事件
	$('.w-tablelist').click(function(e){
		if (/button|BUTTON/.test(e.target.tagName)) {
			var id = this.id;
			var type = id.substr(0, id.indexOf('-'));
			//去掉history这一栏
			var nameArray = $.grep(typeArray[type], function(val) {
				return val === 'history' ? false : true;
			});
			
			var add_xtmpl =  getTmplsText(nameArray, 'edit');
			tAdd.setText(add_xtmpl);
			tAdd.fill('');

			var ndWindow = new ResizedWindow({
				operation: 'add', //本页面特有参数，与插件无关
				content: tAdd.getText(),
				title: type,
				width:600,
				height:400
			});	
			ndWindow.fadeIn(300);

			//不用能delegate('form', 'submit', fn)
			//因此submit事件并不会冒泡到ndWindow上
			var window_id = ndWindow.attr('id');
			var ndForm = $('#' + window_id + ' form');
			ndForm.bind('submit', function(e){
				e.preventDefault();
				e.stopPropagation();
				var action = ndForm.attr('action') + '?type=' + type + '&';
				var formset = ndForm.serialize();
				$.receiveJSON(action + formset, function(res){
					if (res.status) {
						alert(res.data);
						ndWindow.close();
					} else {
						alert(res.msg);
					}
				});
			});
		}
	});
});
