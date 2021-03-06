Pychooser = function(id, conf) { 
	id = /^#/.test(id) ? id : '#' + id;
	var prevId = id + " "; //加上一个空格

	var ndPychooser = $(id);
	if (!ndPychooser.length) return false;

	var ndQueryInput = $(prevId + '.w-pychooser-text');	
	var ndQueryButton = $(prevId + '.w-pychooser-query-button');	

	var ndAddPyButton = $(prevId + '.w-pychooser-addpy-button');	
	var ndCanidateInput = $(prevId + '.w-pychooser-pycanidate');	

	var ndDisplay = $(prevId + '.canidate-list ul');	
	var ndPy = $(prevId + 'input.w-pychooser-py');	

	ndQueryInput.keydown(function(e) {
		if (e.keyCode == $.keyCode['ENTER']) {
			ndQueryButton.click();
		}
	});
	ndQueryButton.click(function(e){
		e.preventDefault();
		var text = $.trim(ndQueryInput.val());
		var content = '';
		if (!text) return;

		$.receiveJSON('/py/quanpin/with_yindiao', {'text':text}, function(res) {
			if (res.status) {
				console.log(res.data);
				content = $.map(res.data, function(val, index) {
					return "<li>" + val + "</li>";
				});	
				ndDisplay.html(content.join('\n'));
			} else {
				alert(res.msg);
			}
		});
	});

	ndAddPyButton.click(function(e) {
		e.preventDefault();
		var text = ndCanidateInput.val();
		text = $.trim(text);

		if (!text) {
			alert('请输入内容');
			return;
		}

		var ndLi = $("<li>" + text + "</li>");
		ndLi.appendTo(ndDisplay);
		var ndLis = $(prevId + '.canidate-list ul li');	
		ndLis.removeClass('active');
		select(ndLi);
	});

	ndCanidateInput.keyup(function(e){
		var text = this.value;
		text = $.trim(text);
		text_array = text.split(' ');
		var ndLis = $(prevId + '.canidate-list ul li');	

		ndLis.show();
		if (text) {
			$.each(ndLis, function(index, ele) {
				for(var i = 0;  i < text_array.length; i++) {
					if (ele.innerHTML.indexOf(text_array[i]) == -1) {
						ele.style.display = 'none';
						return;
					}
				}
			});
		}
	});
	ndCanidateInput.keydown(function(e){
		if (e.keyCode == $.keyCode['ENTER']) {
			ndAddPyButton.click();
		}
	});

	ndDisplay.delegate('li', 'click', function(e){
		//必须动态获得，因为li的内容会变化
		var ndThis = $(this);
		var ndLis = $(prevId + '.canidate-list ul li');	
		ndLis.removeClass('active');
		select(ndThis);
	});

	function select(nd) {
		nd.addClass('active');
		ndPy.val(nd.html());
	}

};
