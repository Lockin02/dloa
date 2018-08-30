var invoiceTypeArr = [];

$(function() {
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'invoiceapply'
	});

	//�Ƿ���Ҫ�ʼ�
	if($("#isMail").val() == 1){
		$("#isMailYes").attr('checked',true);
		changeIsMail(1);
	}else{
		$("#isMailNo").attr('checked',true);
		changeIsMail(0);
	}
	
	//�Ƿ����
	if($("#isNeedStamp").val() == 1){
		$("#isNeedStampYes").attr('checked',true);
		changeIsStamp(1);
	}else{
		$("#isNeedStampNo").attr('checked',true);
		changeIsStamp(0);
	}
	
	//��Ʊ����
	invoiceTypeArr = getData('CPFWLX');

	changeInvType("invoiceType");

	thisCount = $("#invnumber").val() ;
	if( thisCount != 0 ){
		for(i = 1;i<= thisCount ; i++ ){
			$("#invoiceEquName"+ i).yxcombogrid_datadict({
				height : 250,
				hiddenId :  'invoiceEquId'+ i,
				gridOptions : {
					isTitle : true,
					param : {"parentCode":"KPXM"},
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i){
							return function(e, row, data) {
								var thisObj = $("#invoiceEquName"+ i);
								$("#invoiceEquId" + i).val(data.dataCode);
								$("#invoiceEquName" + i).val(data.dataName);
								thisObj.attr('readonly',"readonly");
								if(data.dataCode == "QT"){
									thisObj.attr('readonly',"");
									thisObj.val("");
									thisObj.focus();
								}
							};
						}(i)
					},
					// ��������
					searchitems : [{
						display : '����',
						name : 'dataName'
					}]
				}
			});

		}
	}

	//�Ƿ���Ҫ�ʼ�
	var isOffSite = $("#isOffSite").val();
	if(isOffSite == '1'){
		$("#isOffSiteYes").attr('checked',true);
	}else{
		$("#isOffSiteNo").attr('checked',false);
	}
	//�Ƿ���ؿ�Ʊ��ʼ��
	changeMailInfo(isOffSite);
});

//�ж�������״̬,�ǵĻ��������ֵ����productId��
function isEmpty(obj,thisKey){
	underObj = $("#invoiceEquId" + thisKey);
	if(obj.value != "" && underObj.val() == "QT"){
		underObj.val(obj.value);
	}
}

/**********************ɾ����̬��*************************/
function mydel(obj,mytable)
{
	if(confirm('ȷ��Ҫɾ�����У�')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1 - 1;
		var mytabletemp = document.getElementById(mytable);
   		mytabletemp.deleteRow(rowNo);
   		var myrows=mytabletemp.rows.length;
   		for(i=1;i<myrows;i++){
			mytabletemp.rows[i].childNodes[0].innerHTML=i;
		}
		countFun(this);
	}
}

function audit(){
	document.getElementById('form1').action="?model=finance_invoiceapply_invoiceapply&action=add&act=audit";
}

function auditEdit(){
	document.getElementById('form1').action="?model=finance_invoiceapply_invoiceapply&action=edit&act=audit";
}

/********************************�ʼ����ƶ�***********************/
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
	if(obj.value=="y"){
	 	addressdiv.style.display="";
	}else{
		 addressdiv.style.display="none";
	}
}

//�Ƿ�Ʊ�ʼ�
function checkMail(t){
	if(t.checked){
		$("#mailForm").show();
	}else{
		$("#mailForm").hide();
	}
}
/*****************************�ʼ����ƶ�***************************/