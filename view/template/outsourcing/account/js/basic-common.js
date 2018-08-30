/**
 * 重写从表表头
 */
function tableHead(){
	var trHTML =  '';
	var detailRows = ['开始日期','结束日期'];
	var detailArr = ['结算周期'];
	var trObj = $("#itemTable tr:eq(0)");
	var tdArr = trObj.children();
	var mark = 1;
	var m = 0;
	tdArr.each(function(i,n){
		if($.inArray($(this).text(),detailRows) != -1){
			if(mark == 1&&m<1){
				if(m==0){
					$(this).attr("colSpan",2).text(detailArr[m]);
				}
				mark = 1;
				m++;
			}else{
				$(this).remove();
				mark = 0;
			}
		}else{
			$(this).attr("rowSpan",2);
		}
	});

	trHTML+='<tr class="main_tr_header">';
	for(m=0;m<detailRows.length;m++){
		trHTML+='<th><div class="divChangeLine" style="min-width:80px;">'+detailRows[m]+'</div></th>';
	}
	trHTML+='</tr>';
	trObj.after(trHTML);
}

//变更外包类型
function outsourType(){
	var outsourcing = $("#outsourcing").val();
	if(outsourcing == "HTWBFS-02"){
		$("#personrental").show();
		//单选供应商
//		$("#suppName").yxcombogrid_outsupplier({
//			hiddenId : 'suppId',
//			gridOptions : {
//				showcheckbox : false,
//				event : {
//					'row_dblclick' : function(e,row,data) {
//	                        $("#suppCode").val(data.suppCode);
//					}
//				}
//			}
//		});
		itemDetail();
	}else{
		$("#personrental").hide();
	}
}

//计算天数
function countDate(thisKey,rowNum){
	var objGrid = $("#itemTable");
	//加入日期
	var planBeginDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"beginDate");
	//离开日期
	var planEndDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"endDate");

	if(planBeginDateObj.val() != "" && planEndDateObj.val() != ""){
		//实际天数
		var actDay = DateDiff(planBeginDateObj.val(),planEndDateObj.val()) + 1;

		//设置实际天数
		objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"totalDay").val(actDay);
	}
}
//计算人力成本
function countPerson(rowNum){
	var objGrid = $("#itemTable");

	//单价
	var price = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"inBudgetPrice").val();
	if(price != ""){
		var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"totalDay").val();

		if( numberTwo != ""){
			var amount = accMul(numberTwo,price,2); //成本
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"selfPrice").val(amount);
		}
	}
}


//计算外包人力成本
function countPersonOut(rowNum){
	var objGrid = $("#itemTable");

	//单价
	var price = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"outBudgetPrice").val();
	if(price != ""){
			var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"totalDay").val();

			if( numberTwo != ""){
			var trafficMoney = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"trafficMoney").val();
			var otherMoney = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"otherMoney").val();
			var customerDeduct = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"customerDeduct").val();
			var examinDuduct = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"examinDuduct").val();
			var amount = accMul(numberTwo,price,2); //成本
			var rentalPrice=accAddMore([amount,trafficMoney,otherMoney],2);
			if(customerDeduct>0){
				rentalPrice=accSub(rentalPrice,customerDeduct,2);
			}
			if(examinDuduct>0){
				rentalPrice=accSub(rentalPrice,examinDuduct,2);
			}
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"rentalPrice").val(rentalPrice);
		}
	}
}

	  // 计算结算金额
	function checkOrderMoney() {
		var totalNum = 0;
		var cmps = $("#itemTable").yxeditgrid("getCmpByCol", "rentalPrice");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),0);
		});
		$("#orderMoney").val(totalNum);
		$("#orderMoney_v").val(moneyFormat2(totalNum));

	}
		  // 计算扣款金额
	function checkDeductMoney() {
		var totalNum = 0;
		var customerDeduct = $("#itemTable").yxeditgrid("getCmpByCol", "customerDeduct");
		customerDeduct.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),0);
		});
		var examinDuductMoney = 0;
		var examinDuduct = $("#itemTable").yxeditgrid("getCmpByCol", "examinDuduct");
		examinDuduct.each(function() {
			examinDuductMoney = accAdd(examinDuductMoney, $(this).val(),0);
		});
		$("#deductMoney").val(accAdd(totalNum, examinDuductMoney,0));
		$("#deductMoney_v").val(moneyFormat2(accAdd(totalNum, examinDuductMoney,0)));

	}
	//选择工作量确认单
	function toSelectList(){
		var approvalId =$("#applyId").val();
//		showModalWin ("?model=outsourcing_workverify_workVerify&action=toSelect&approvalId="
//						+approvalId ,"1");
	window.open("?model=outsourcing_workverify_workVerify&action=toSelect&approvalId="
						+approvalId , '',"width=1000,height=600,top=50,left=50,toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,status=yes");
	}


