$(function(){
	var ndBtnFromLocalFile = $('#btn-from-local-file');
	var ndBtnFromUrl = $('#btn-from-url');
	var ndBtnClearImgInfo = $('#btn-clear-image-info');
	var ndInputFromLocalFile = $('#input-from-local-file');
	var ndFormUpload = $('form[name=form-upload-file]');
	var ndImgPreview = $('#img-preview');
	var currentStep = 1;

	var sigConf = {
		'url':'',//指定待签名图片的url
		'method':'', //指定签名方式
		'key':'',//指定key
	};

	//点击'浏览本地文件'时
	ndBtnFromLocalFile.click(function(){
		ndInputFromLocalFile.fadeIn(500);
	});

	//点击'选择文件'时
	ndInputFromLocalFile.change(function(){
		var ndSubmit = ndFormUpload.find('input[type=submit]');
        var file = ndInputFromLocalFile[0].files[0];
        var fileSize = 0;
        if (file.size > 1024 * 1024)
            fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        else
			fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
		$('#fileInfo').show();
		$('#fileName').html('Name:'+file.name);
		$('#fileSize').html('Size:'+fileSize);
		$('#fileType').html('Type:'+file.type);
		uploadFile();
	});

	var bytesUploaded = 0;
	var bytesTotal = 0;
	var previousBytesLoaded = 0;
	var intervalTimer = 0;
	var ndProgressModal = $("#modal-uploading-img");
	var ndProgressPercent = ndProgressModal.find('#progressIndicator .bar');
	//开始上传文件
	function uploadFile() {
		previousBytesLoaded = 0;

		console.log(ndProgressModal);
		ndProgressModal.modal();
		/* If you want to upload only a file along with arbitary data that
		   is not in the form, use this */
		var fd = new FormData();
		fd.append("fileToUpload", ndInputFromLocalFile[0].files[0]);

		/* If you want to simply post the entire form, use this */
		//var fd = document.getElementById('form1').getFormData();

		var xhr = new XMLHttpRequest();        
		xhr.upload.addEventListener("progress", uploadProgress, false);
		xhr.addEventListener("load", uploadComplete, false);
		xhr.addEventListener("error", uploadFailed, false);
		xhr.addEventListener("abort", uploadCanceled, false);
		xhr.open("POST", "/upload");
		xhr.send(fd);

		intervalTimer = setInterval(updateTransferSpeed, 50);
	}
	//检测当前上传速度
	function updateTransferSpeed(evt) {
		var currentBytes = bytesUploaded;
		var bytesDiff = currentBytes - previousBytesLoaded;
		if (bytesDiff == 0) return;
		previousBytesLoaded = currentBytes;
		bytesDiff = bytesDiff * 2;
		var bytesRemaining = bytesTotal - previousBytesLoaded;
		var secondsRemaining = bytesRemaining / bytesDiff;

		var speed = "";
		if (bytesDiff > 1024 * 1024)
		  speed = (Math.round(bytesDiff * 100/(1024*1024))/100).toString() + 'MBps';
		else if (bytesDiff > 1024)
		  speed =  (Math.round(bytesDiff * 100/1024)/100).toString() + 'KBps';
		else
		  speed = bytesDiff.toString() + 'Bps';
		document.getElementById('transferSpeedInfo').innerHTML = speed;
		document.getElementById('timeRemainingInfo').innerHTML = secondsToString(secondsRemaining);
	}
	function secondsToString(seconds) {        
		var h = Math.floor(seconds / 3600);
		var m = Math.floor(seconds % 3600 / 60);
		var s = Math.floor(seconds % 3600 % 60);
		return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s);
	}
	
	function uploadProgress(evt) {
		if (evt.lengthComputable) {
		  bytesUploaded = evt.loaded;
		  bytesTotal = evt.total;
		  var percentComplete = Math.round(evt.loaded * 100 / evt.total);
		  var bytesTransfered = '';
		  if (bytesUploaded > 1024*1024)
			bytesTransfered = (Math.round(bytesUploaded * 100/(1024*1024))/100).toString() + 'MB';
		  else if (bytesUploaded > 1024)
			bytesTransfered = (Math.round(bytesUploaded * 100/1024)/100).toString() + 'KB';
		  else
			bytesTransfered = (Math.round(bytesUploaded * 100)/100).toString() + 'Bytes';

		  document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
		  document.getElementById('transferBytesInfo').innerHTML = bytesTransfered;
		  ndProgressPercent.css('width', percentComplete.toString() + '%');
		  if (percentComplete == 100) {
			  console.log('finished');
		  }
		}
	}
	//文件上传完成
	function  uploadComplete(evt){
        clearInterval(intervalTimer);
		ndProgressModal.find('.btn.cancel').hide();
		ndProgressModal.find('.btn.sure').show();
		ndProgressModal.find('h3').html('上传成功');
		ndImgPreview.attr('src', '/uploaded/'+ evt.target.responseText);
		sigConf['imgurl'] = '/uploaded/'+ evt.target.responseText; //设置sigConf的url
		//sigConf['imgurl'] = 'http://'+ document.domain + '/uploaded/'+ evt.target.responseText; //设置sigConf的url
	}
	function  uploadFailed(evt){
        clearInterval(intervalTimer);
		ndProgressModal.find('h3').html('<red>上传失败</red>');
	}
	function  uploadCanceled(){
        clearInterval(intervalTimer);
        alert("The upload has been canceled by the user or the browser dropped the connection.");  
	}

	//点击通过“输入图像网址"
	ndBtnFromUrl.click(function(){
		$("#modal-img-url").modal();
	});

	//通过url选择图片
	$("#modal-img-url .sure").click(function(){
		var url = $('input[name=img-url]').val();
		ndImgPreview.attr('src', url);
		sigConf['imgurl'] =  url;
	});

	//监听下一步按钮
	$(document).delegate('.btn-next-step', 'click', function(){
		$('.page'+currentStep).hide('normal');
		currentStep++;
		$('.page'+currentStep).slideDown('fast');
	});

	//监听上一步按钮
	$(document).delegate('.btn-prev-step', 'click', function(){
		$('.page'+currentStep).hide('normal');
		currentStep--;
		$('.page'+currentStep).slideDown('fast');
	});

	//监听开始计算按钮
	$(document).delegate('.btn-compute', 'click', function(){
		$.getJSON('/signature/compute', sigConf, function(json) {
			console.log(json);
		});
	});

	$('.nav-stacked').delegate('li', 'click', function(){
		var ndThis = $(this);
		ndThis.parent().find('li').removeClass('active');
		ndThis.addClass('active');
	});

	/*第二步：选择签名算法*/
	$('.nav-stacked').delegate('li', 'click', function(){
		var ndThis = $(this);
		if (ndThis.has('#btn-method-dwtdct').length){
			sigConf['method'] = 'dwtdct';
			return;
		}

		if (ndThis.has('#btn-method-svdsvd').length){
			sigConf['method'] = 'svdsvd';
			return;
		}
	});

	/*第三步：选择密钥*/
	$('.nav-stacked').delegate('li a.sig-key', 'click', function(){
		var ndThis = $(this);
		var keyId = ndThis.attr('data-id');
		sigConf['key'] = keyId;
	});

	//处理tab，与业务无关，仅前端
	$('.ssnau-tab').delegate('.nav li a', 'click', function(){
		var ndThis = $(this);
		var dataId = ndThis.attr('data-id');
		var ndSsnauTab = ndThis.parents('.ssnau-tab');
		var ndAccordion = ndSsnauTab.find(".accordion-inner" + ' ' +  '.ssnau-tab-content');
		console.log(ndAccordion);
		ndAccordion.hide();
		
		//只显示特定的那上条目
		var len = ndAccordion.length;
		for(var i =0; i<len; i++) {
			if (ndAccordion[i].getAttribute('data-id') == dataId) {
				$(ndAccordion[i]).show();
			}
		}
	});

});
