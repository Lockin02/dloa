var invoiceTypeArr = [];

$(function() {
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});

	//是否需要邮寄
	if($("#isMail").val() == 1){
		$("#isMailYes").attr('checked',true);
	}else{
		$("#isMailNo").attr('checked',true);
	}

	//开票类型
	invoiceTypeArr = getData('CPFWLX');

	changeInvType("invoiceType");

	thisCount = $("#invnumber").val() ;
	if( thisCount != 0 ){
		for(i = 1;i<= thisCount ; i++ ){
			$("#invoiceEquName"+ i).yxcombogrid_datadict({
				hiddenId :  'invoiceEquId'+ i,
				gridOptions : {
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
					}
				}
			});

		}
	}
});



/**********************动态添加列表*************************/
function detailAdd(tablelist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var tablelist = document.getElementById(tablelist);
	i=tablelist.rows.length;
	oTR =tablelist.insertRow([i]);
	oTR.align="center";
	oTR.height="30px";
	oTL0=oTR.insertCell([0]);
	oTL0.innerHTML=i;
	oTL1=oTR.insertCell([1]);
	oTL1.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][productName]' id='invoiceEquName"+mycount+"' onblur='isEmpty(this,\"" + mycount +"\")' readonly='readonly' class='txtmiddle'/><input type='hidden' name='invoiceapply[invoiceDetail]["+mycount+"][productId]' id='invoiceEquId"+mycount+"'>";
	oTL2=oTR.insertCell([2]);
	oTL2.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][amount]' id='amount"+mycount+"' class='txtshort'/>";
    oTL3=oTR.insertCell([3]);
    oTL3.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][softMoney]' id='softMoney"+mycount+"' class='txtshort'/>";
    oTL4=oTR.insertCell([4]);
    oTL4.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][hardMoney]' id='hardMoney"+mycount+"' class='txtshort'/>";
    oTL5=oTR.insertCell([5]);
    oTL5.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][repairMoney]' id='repairMoney"+mycount+"' class='txtshort'/>";
    oTL6=oTR.insertCell([6]);
    oTL6.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][serviceMoney]' id='serviceMoney"+mycount+"' class='txtshort'/>";
    oTL7=oTR.insertCell([7]);
    oTL7.innerHTML="<select id='psTyle"+mycount+"' name='invoiceapply[invoiceDetail]["+mycount+"][psTyle]' class='txtmiddle'></select>";
    oTL8=oTR.insertCell([8]);
    oTL8.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][remark]' class='txtmiddle'/>";
    oTL9=oTR.insertCell([9]);
    oTL9.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='删除行'/>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;

    addDataToSelect(invoiceTypeArr, 'psTyle'+mycount);

    createFormatOnClick('amount'+mycount);
    //绑定统计事件
    $("#amount" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('softMoney'+mycount);
    //绑定统计事件
    $("#softMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('hardMoney'+mycount);
    //绑定统计事件
    $("#hardMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('repairMoney'+mycount);
    //绑定统计事件
    $("#repairMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('serviceMoney'+mycount);
    //绑定统计事件
    $("#serviceMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });

	$("#invoiceEquName"+ mycount).yxcombogrid_datadict({
		hiddenId :  'invoiceEquId'+ mycount,
		gridOptions : {
			param : {"parentCode":"KPXM"},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						var thisObj = $("#invoiceEquName"+ mycount);
						$("#invoiceEquId" + mycount).val(data.dataCode);
						$("#invoiceEquName" + mycount).val(data.dataName);
						thisObj.attr('readonly',"readonly");
						if(data.dataCode == "QT"){
							thisObj.attr('readonly',"");
							thisObj.val("");
							thisObj.focus();
						}
					};
				}(mycount)
			}
		}
	});
}

//判断是其他状态,是的话将输入的值附到productId中
function isEmpty(obj,thisKey){
	underObj = $("#invoiceEquId" + thisKey);
	if(obj.value != "" && underObj.val() == "QT"){
		underObj.val(obj.value);
	}
}

/**********************删除动态表单*************************/
function mydel(obj,mytable)
{
	if(confirm('确定要删除该行？')){
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

//发票类型对应字段
function changeInvType(thisVal){
	innerInvType = $("#" + thisVal).find("option:selected").attr("e2");
	switch(innerInvType){
		case 'ZZSFP':
			$("#taxpayerIdNeed").html("[*]");
			$("#bankNeed").html("[*]");
			$("#bankCountNeed").html("[*]");
			break;
		default :
			$("#taxpayerIdNeed").html("");
			$("#bankNeed").html("");
			$("#bankCountNeed").html("");
			break;
	}
}

function audit(){
	document.getElementById('form1').action="?model=finance_invoiceapply_invoiceapply&action=reAdd&act=audit";
}

/********************************邮件控制段***********************/
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
	if(obj.value=="y"){
	 	addressdiv.style.display="";
	}else{
		 addressdiv.style.display="none";
	}
}

//是否开票邮寄
function checkMail(t){
	if(t.checked){
		$("#mailForm").show();
	}else{
		$("#mailForm").hide();
	}
}
/*****************************邮件控制端***************************/