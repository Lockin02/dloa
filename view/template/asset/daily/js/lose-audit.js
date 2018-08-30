$(document).ready(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'lose[item]',
		url : '?model=asset_daily_loseitem&action=listJson',
		type : 'view',
		param : {
			loseId : $("#loseId").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '资产名称',
			name : 'assetName'
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '购置日期',
			name : 'buyDate',
			// type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '所属部门',// orgName
			name : 'orgName',
			tclass : 'txt',
			readonly : true
		}, {
			display : '使用部门',// useOrgName
			name : 'useOrgName',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '附属设备',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='
						+ data.assetCode + '\')">详细</a>'
			}
		}, {
			display : '购进原值',
			name : 'origina',
			tclass : 'txt',
			readonly : true
		}, {
			display : '已经使用期间数',
			name : 'alreadyDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '累计折旧金额',
			name : 'depreciation',
			tclass : 'txtmiddle',
			readonly : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '残值',
			name : 'salvage',
			tclass : 'txtmiddle',
			readonly : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	// 提交审批后查看单据时隐藏关闭按钮
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
	}

	var appendHtml=' <input type="hidden" id="id" name="lose[id]" value="'+$('#loseId').val()+'"/>'
				+ ' <input type="hidden" id="realAmount" name="lose[realAmount]" value="'+$("#realAmount").val()+'"/>';
	if($(window.parent.document.getElementById("appendHtml")).html()!=""){   //重新选择刚先把前一次追加的内容清空
		$(window.parent.document.getElementById("appendHtml")).html("");
	}
	$(window.parent.document.getElementById("appendHtml")).append(appendHtml);

	$(window.parent.document.getElementById("sub")).bind("click", function() { // 审批提交时，判断是否指定了供应商
				var pattern=/^[0-9]*(\.[0-9]{1,2})?$/;
				var realAmount = $('#realAmount').val();
				if (realAmount == "" || !pattern.test(realAmount)){
					alert('请输入正确的金额');
					return false;
				}
			});
});
	/*遗失审批时，添加备注到父窗口*/
	function addRemark(){
		var realAmount=$("#realAmount").val();
		if($(window.parent.document.getElementById("realAmount")).length>0){
			$(window.parent.document.getElementById("realAmount")).val(realAmount);
		}else{
			var realAmountHtml='<input type="hidden" id="realAmount" name="lose[realAmount]" value="'+realAmount+'"/>';
			$(window.parent.document.getElementById("realAmount")).append(realAmountHtml);
		}
	}
