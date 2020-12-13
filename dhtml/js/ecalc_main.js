
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
		 url: 'ecalc_main.php',
		 data: ({
			 tag: "addExp",
			 aname:txtAName,
			 desc:arDesc,
			 type: selType,
			 price: txtPrice,
			 numof: txtNumOf,
			 total: txtTotal,
			 spendby: txtSpendBy,
			 sdate: txtDate
		 }),
		 cache: false,
		 success: function(feedback){
		  $('#messages').html(feedback);
		 }
	 });
}

//this function is used for opening a new dialog
function showPrompt(){
	
		$.ajax ({
		 type: 'post',
		 url: 'ecalc_main.php',
		 data: ({
			 tag: "addNGrp",
		 }),
		 cache: false,
		 success: function(feedback){
		  NewGroupWindowDiv.html(feedback);
		 }
	 });
	  NewGroupWindowDiv = $('#addNItem').dialog({
			 autoOpen: false,
             modal: true,
             draggable: false,
             resizable: false,
             width: 350,
             height: 260,
             dialogClass: 'frd-dialog-titled centered',
             title: 'ازدیاد کتگوری',
             close: function() {
				 $('#messages').text('');
             }
		 });
	 NewGroupWindowDiv.dialog("open");
}

// add new category
function addCtg(){
	var txtCtg = $('#txtCtg').val();
    var txtDateCtg  = $('#txtDateCtg').val();
		$.ajax ({
		 type: 'post',
		 url: 'ecalc_main.php',
		 data: ({
			 tag: "insCtg",
			 ctgName:txtCtg,
			 sdate: txtDateCtg
		 }),
		 cache: false,
		 success: function(feedback){
		  $('#messages').html(feedback);
		 }
	 });
}










