$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'keep[item]',
    			url:'?model=asset_daily_keepitem&action=listJson',
    			type:'view',
    			param:{keepId:$("#keepId").val()},
       		colModel : [
       			{
					display:'卡片编号',
					name : 'assetCode'
				}, {
					display:'资产名称',
					name : 'assetName'
				}, {
					display : '维修金额',
					name : 'amount',
					tclass : 'txtmiddle',
               		 //列表格式化千分位
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : '使用人',
					name : 'userName',
					//type : 'date',
					tclass : 'txtshort'
				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]
		   })

		   // 对select项的处理--报废原因
			if ($("#keepType").text() == '1') {
				$("#keepType").text("日常维修");
			}
			if ($("#keepType").text() == '2') {
				$("#keepType").text("普通维修");
			}
			if ($("#keepType").text() == '3') {
				$("#keepType").text("重大维修");
			}
          //提交审批后查看单据时隐藏关闭按钮
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}


		});