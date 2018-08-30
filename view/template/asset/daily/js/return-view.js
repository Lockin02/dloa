$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'return[item]',
    			url:'?model=asset_daily_returnitem&action=listJson',
    			type:'view',
    			param:{allocateID :$("#allocateID").val(),
				       assetId:$("#assetId").val()},
       		       colModel : [{
					display:'卡片编号',
					name : 'assetCode'
				}, {
					display:'资产名称',
					name : 'assetName'
				}, {
					display : '规格型号',
					name : 'spec',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '购置日期',
					name : 'buyDate',
					//type : 'date',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '预计使用期间数',
					name : 'estimateDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '已经使用期间数',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				}
				, {
					display : '剩余使用期间数',
					name : 'residueYears',
					tclass : 'txtshort',
					readonly:true
				}
				, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]
		   });

		     // 对select项的处理--归还类型，已经带出相应的表单
		   switch($('#returnType').text()){
		   	case 'other' :
		   		$("#returnType").text("其它");
				$("#otherNo").hide();
				$("#chargeNo").hide();
				$("#borrowNo").hide();
		   		break;
		   	case 'oa_asset_borrow' :
		   		$("#returnType").text("借用归还");
				$("#chargeNo").hide();
		   		break;
		   	case 'oa_asset_charge' :
		   		$("#returnType").text("领用归还");
				$("#borrowNo").hide();
		   		break;
		   	case 'asset' :
		   		$("#returnType").text("单独归还");
				$("#borrowNo").hide();
				$("#chargeNo").hide();
		   		break;
		   }

		   //提交审批后查看单据时隐藏关闭按钮
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}

		});