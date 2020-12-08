
function clearTxtBoxs() {
	$('#txtAName').val('');
	$('#arDesc').val('');
	$('#txtPrice').val('');
	$('#txtNumOf').val('');
	$('#txtTotal').val('');
	$('#txtSpendBy').val('');
}

function execAddRec(){
	var txtAName = $('#txtAName').val();
    var arDesc   = $('#arDesc').val();
    var selType  = $('#selType').val();
    var txtPrice = $('#txtPrice').val();
    var txtNumOf = $('#txtNumOf').val();
    var txtTotal = $('#txtTotal').val();
    var txtSpendBy   = $('#txtSpendBy').val();
    var txtDate  = $('#txtDate').val();
		$.ajax ({
		 type: 'post',
		 url: './ecalc_main.php',
		 data: {
			 tag: 'addExp',
			 aname:txtAName,
			 desc:arDesc,
			 type: selType,
			 price: txtPrice,
			 numof: txtNumOf,
			 total: txtTotal,
			 spendby: txtSpendBy,
			 sdate: txtDate
		 },
		 success: function(feedback){
		  $('#messages').html(feedback);
		 }
	 });
}