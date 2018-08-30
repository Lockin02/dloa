$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'sell[item]',
    			url:'?model=asset_disposal_sellitem&action=listJson',
    			type:'view',
    			param:{sellID:$("#sellID").val(),
				       assetId:$("#assetId").val()},
       		       colModel : [{
					display:'卡片编号',
					name : 'assetCode'
				}, {
					display:'资产名称',
					name : 'assetName'
				},{
					display:'英文名称',
					name : 'englishName',
					tclass : 'txtshort'
				}, {
					display:'购置日期',
					name : 'buyDate',
					type:'date',
					tclass : 'txtshort'
				}, {
					display:'规格型号',
					name : 'spec',
					tclass : 'txtshort'
				}, {
					display:'附属设备',
					name : 'equip',
					type:'statictext',
                    process : function(e, data){
                    return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='+data.assetCode+'\')">详细</a>'
				     }
				}
//				, {
//					display:'耐用年限',
//					name : 'estimateDay',
//					tclass : 'txtshort'
//				}
				, {
					display:'已经使用期间数',
					name : 'alreadyDay',
					tclass : 'txtshort'
				},{
					display:'售出部门',
					name : 'deptName',
					tclass : 'txtshort'
				}, {
					display:'售出前用途',
					name : 'beforeUse',
					tclass : 'txtshort'
				}, {
					display:'已折旧金额',
					name : 'depreciation',
					tclass : 'txtshort',
               		 //列表格式化千分位
               		 process : function(v){
					return moneyFormat2(v);
					}
				},{
					display:'残余价值',
					name : 'salvage',
					tclass : 'txtshort',
               		 //列表格式化千分位
               		 process : function(v){
					return moneyFormat2(v);
					}
				}
//				, {
//					display:'月折旧额',
//					name : 'monthDepr',
//					tclass : 'txtshort',
//               		 //列表格式化千分位
//               		 process : function(v){
//					return moneyFormat2(v);
//					}
//				}
				, {
					display:'备注',
					name : 'remark',
					tclass : 'txt'
				}]
		   });

		   //提交审批后查看单据时隐藏关闭按钮
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}

		});