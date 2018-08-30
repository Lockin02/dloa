$(document).ready(function() {

			$("#rentTable").yxeditgrid({
					objName:'rent[rentitem]',
		    	    url:'?model=asset_daily_rentitem&action=listJson',
		    	    param:{rentId:$("#rentId").val(),assetId:$("#assetId").val()},
		    	    delTagName : 'isDelTag',
						type:'view',

						colModel : [{
							display:'卡片编号',
							name : 'assetCode'
						}, {
							display:'资产名称',
							name : 'assetName'
						},{
							display:'购入日期',
							name : 'buyDate',
							type:'date'
						}, {
							display:'规格型号',
							name : 'spec',
							tclass : 'txtshort'
						}, {
							display:'单位',
							name : 'unit',
							tclass : 'txtshort',
							readonly:true
						}, {
							display:'原值',
							name : 'origina',
							tclass : 'txtshort',
							readonly:true
						}, {
							display:'出租价格',
							name : 'rentValue',
							tclass : 'txtshort'
						},  {
							display:'备注',
							name : 'remark',
							tclass : 'txt'
						}]
					})


		});