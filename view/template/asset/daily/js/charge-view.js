$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'charge[item]',
    			url:'?model=asset_daily_chargeitem&action=listJson',
    			type:'view',
    			param:{allocateID:$("#allocateID").val(),
    			       assetId:$("#assetId").val()},
       		colModel : [
//       			{
//				display:'序号',
//				name : 'sequence'
//				},
				{
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
				}

				, {
					display : '预计使用期间数',
					name : 'estimateDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '已经使用期间数',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '剩余使用期间数',//等于卡片的预计使用期间数减去已使用期间数
					name : 'residueYears',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt',
					readonly:true
				}]
		   })


          //提交审批后查看单据时隐藏关闭按钮
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}

		});