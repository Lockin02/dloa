//初始化
$(function() {
	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	//加载选择卡片信息按钮
	$("#purchaseProductTable").find("tr:first td").append("<input type='button' value='选择卡片信息' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
	// 选择人员组件
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	// 选择人员组件
	$("#payer").yxselect_user({
		hiddenId : 'payerId',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#TO_NAME').val($('#payer').val());
					$('#TO_ID').val($('#payerId').val());
				}
			}
		}
	});
	/**
	 * 验证信息
	 */
	validate({
		"scrapDate" : {
			required : true
		},
		"proposer" : {
			required : true
		},
		"amount_v" : {
			required : true
		},
		"reason" : {
			required : true
		},
		"scrapDeal" : {
			required : true
		},
//		"hasAccount" : {
//			required : true
//		},
		"payer" : {
			required : true
		}
	});
});
//选择卡片后自动带出规格，原值等信息
function selectAssetFn(g, rowNum, rowData) {
	g.setRowColValue(rowNum,'assetId',rowData.id);
	g.setRowColValue(rowNum,'assetCode',rowData.assetCode);
	g.setRowColValue(rowNum,'assetName',rowData.assetName);
	g.setRowColValue(rowNum,'spec',rowData.spec);
	g.setRowColValue(rowNum,'origina',rowData.origina,true);
	g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
	g.setRowColValue(rowNum,'netValue',rowData.netValue,true);
	g.setRowColValue(rowNum,'buyDate',rowData.buyDate);
	g.setRowColValue(rowNum,'depreciation',rowData.depreciation,true);
	g.setRowColValue(rowNum,'remark',rowData.remark);
}
// 根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum");
	//报废总数
	$("#scrapNum").val(curRowNum);
	var rowsalvageVa = 0;
	var rownetValueVa = 0;
	var salvages = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	salvages.each(function() {
		//$(this).val()获取不到值
		rowsalvageVa = accAdd(rowsalvageVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	var netValues = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "netValue");
	netValues.each(function() {
		//$(this).val()获取不到值
		rownetValueVa = accAdd(rownetValueVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	//总残值
	$("#salvage").val(rowsalvageVa);
	$("#salvage_v").val(moneyFormat2(rowsalvageVa));
	//总净值
	$("#netValue").val(rownetValueVa);
	$("#netValue_v").val(moneyFormat2(rownetValueVa));
	return true;
}
//选择卡片信息
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=scrap"
			,1,500,900);
}
//设置卡片内容
function setDatas(rows){
	var objGrid = $("#purchaseProductTable");
	for(var i = 0; i < rows.length ; i++){
		//判断卡片编码是否已存在
		var assetCodeArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetCodeArr.length > 0){
			assetCodeArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("请不要选择相同的资产" );
					return false;
				}
			});
		}
		//如果已经重复了，就不能继续选择
		if(isExist){
			return false;
		}
		//重新获取行数
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//新增行
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"origina",rows[i].origina,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"netValue",rows[i].netValue,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",rows[i].depreciation,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"remark",rows[i].remark);
	}
	countAmount();
}
//提交或保存时，验证卡片信息合法性
function checkCard(){
	var objGrid = $("#purchaseProductTable");
	//从表不允许为空
	if(objGrid.yxeditgrid("getCurShowRowNum") == 0){
		alert("请选择要报废的卡片信息!");
		return false;
	}
	//验证是否存在非闲置状态的卡片，存在不允许提交
	var assetIds = objGrid.yxeditgrid("getCmpByCol", "assetId");
	var assetIdArr = [];
	assetIds.each(function() {
		assetIdArr.push($(this).val());
	});
	var responseText = $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=checkCardStatus',
		data : {
			'assetIdArr' : assetIdArr
		},
		async : false
	}).responseText;
	var data = eval("(" + responseText + ")");
	if(data.length != 0){
		alert("卡片编号为【"+data+"】的卡片已做过报废处理，请勿重新提交！");
		return false;
	}
	return true;
}