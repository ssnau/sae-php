$(function(){
	var ndBtnNewKey = $("#btn-new-key");
	var ndModalNewKey = $("#modal-new-key"); 

	console.log(ndBtnNewKey);
	console.log(ndModalNewKey);
	ndBtnNewKey.click(function(){
		ndModalNewKey.modal();	
	});
});
