$(document).ready(function() {

			$("#rentTable").yxeditgrid({
					objName:'rent[rentitem]',
		    	    url:'?model=asset_daily_rentitem&action=listJson',
		    	    param:{rentId:$("#rentId").val(),assetId:$("#assetId").val()},
		    	    delTagName : 'isDelTag',
						type:'view',

						colModel : [{
							display:'��Ƭ���',
							name : 'assetCode'
						}, {
							display:'�ʲ�����',
							name : 'assetName'
						},{
							display:'��������',
							name : 'buyDate',
							type:'date'
						}, {
							display:'����ͺ�',
							name : 'spec',
							tclass : 'txtshort'
						}, {
							display:'��λ',
							name : 'unit',
							tclass : 'txtshort',
							readonly:true
						}, {
							display:'ԭֵ',
							name : 'origina',
							tclass : 'txtshort',
							readonly:true
						}, {
							display:'����۸�',
							name : 'rentValue',
							tclass : 'txtshort'
						},  {
							display:'��ע',
							name : 'remark',
							tclass : 'txt'
						}]
					})


		});