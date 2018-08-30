var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};
//��Ʒ����
function proNumCount(docId,type){
    var proNumCount = 0
     $.ajax({
          type : 'POST',
          url : '?model=common_contract_allsource&action=hasProduct',
          data : { id : docId,
                   type : type
                 },
          async: false,
          success : function (data){
                proNumCount = data;
                return false ;
          }
     })
     return proNumCount ;
}

$(function() {
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'assignmentJson',
		param : {
			'states' : '0,2,4',
//			'ExaStatusArr' : "���,���������",

			'isSell' : '1'
		},

		title : '����ȷ������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractAssignInfo',
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].changeTips==1 ){
							$('#row' + data.collection[i].id).css('color', 'red');
						}
					}
				}
			}
		},
		// ��չ�Ҽ��˵�
		/*event:{
			row_success : function(){
			var options = {
				max : 5,
				value	: $(),
				image	: 'js/jquery/raterstar/star2.gif',
				width	: 16,
				height	: 16,
			    }
				$('.sign').rater(options);
		}
		},*/
		menusEx : [{
			text : '�鿴��ͬ',
			icon : 'view',
			action : function(row) {
				if(row.oldId != '' ){
					if(typeof(row.oldId) == "undefined"){
					   var cid = row.id;
					}else{
					   var cid = row.oldId;
					}
				}else{
				  var cid = row.id;
				}
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
						+ cid + "&linkId=" + row.lid + "&skey=" + row['skey_']);
			}
		},{
			text : '����鿴',
			icon : 'view',
			showMenuFn : function(row) {
				// if (row && row.becomeNum != '0' && row.becomeNum != '') {
				if(row.isBecome != '0' || row.isSubAppChange == '1'){
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.oldId != '' ){
					if(typeof(row.oldId) == "undefined"){
						var cid = row.id;
					}else{
						var cid = row.oldId;
					}
				}else{
					var cid = row.id;
				}
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
					+ cid + "&linkId=" + row.lid + "&viewType=change&skey=" + row['skey_']);
			}
		},{
//			text : '�鿴��������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (row.linkId) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showModalWin('?model=contract_contract_equ&action=toViewTab&id='
//						+ row.linkId + "&skey=" + row['skey_']);
//			}
//		}, {
			text : 'ȷ�Ϸ�������',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.lExaStatus == '' && row.dealStatus == '0'
						&&( row.ExaStatus == '���' || row.ExaStatus == 'δ����' || row.ExaStatus == '���') ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=contract_contract_equ&action=toEquAdd&id='
						+ row.id + "&skey=" + row['skey_'],'contractassign');
			}
		},{
			text : '����ȷ��',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.lExaStatus != '' && (row.ExaStatus == 'δ����' || row.ExaStatus == '���') && row.dealStatus == '1') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ������ȷ����?"))) {
                           $.ajax({
							type : "POST",
							url : "?model=contract_contract_contract&action=ajaxSubApp",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg) {
								   alert("�����ɹ�����ѡ��δ��������ȷ�����ϣ�");
								   $("#contractGrid").yxsubgrid("reload");
								}
							}
						});
                         }
			}
		}, {
			text : '�༭��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0
						&& (row.lExaStatus == 'δ�ύ' || row.lExaStatus == '���')
						&&( row.ExaStatus == '���' || row.ExaStatus == 'δ����' || row.ExaStatus == '���') ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=contract_contract_equ&action=toEquEdit&id='
						+ row.id + "&skey=" + row['skey_'],'contractassign');
			}
		}, {
			text : '�������ϱ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != 0 && row.dealStatus != 4
						&& row.ExaStatus == '���' && (row.lExaStatus != '' || row.isSubAppChange == '1')
//						 && proNumCount( row.id,'oa_contract_contract' )!= 0
						) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=contract_contract_equ&action=toEquChange&id='
						+ row.id
						+ "&oldId=" + row.oldId
						+ "&isSubAppChange="
						+ row.isSubAppChange
						+ "&linkId="
						+ row.linkId
						+ "&skey=" + row['skey_'],'contractassign');
			}
		}, {
			text : "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != '1'&&row.dealStatus != '3' && row.ExaStatus == '���' && row.dealStatus != 4) {
					return false;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫ�رո�����ȷ������')) {
					$.ajax({
						type : 'POST',
						url : '?model=common_contract_allsource&action=closeConfirm&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							docType : 'oa_contract_contract'
						},
						// async: false,
						success : function(data) {
							if( data==1 ){
								alert('�رճɹ��������󽫷ŵ��Ѵ��������С�')
								show_page();
							}else{
								alert('�ر�ʧ�ܣ�����ϵ����Ա��')
							}
							return false;
						}
					});
				}
			}
//		}, {
//			text : '�������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (row.lExaStatus != '') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//
//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_equ_link&pid='
//						+ row.lid
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((( row.ExaStatus == '���' || row.ExaStatus == 'δ����' || row.ExaStatus == '���')) &&  (row.dealStatus=='0' || row.dealStatus=='2') ) {
					return true;
				}
				return false;
			},
			action : function(row) {
                if(row.isSubAppChange == '1'){
				   var cid = row.oldId;
				}else{
				   var cid = row.id;
				}
//				if (window.confirm(("ȷ��Ҫ���?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=contract_common_relcontract&action=ajaxBack",
//						data : {
//							id : cid
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('��سɹ���');
//								show_page();
//							}
//						}
//					});
//				}
				showThickboxWin("?model=contract_common_relcontract&action=toRollBack&docType=oa_contract_contract&id="
						+ cid
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
			}
		}],

		// ����Ϣ
		colModel : [{
			display : '����ʱ��',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
		}, {
			name : 'contractRate',
			display : '����',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_assignrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_contract_contract"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע��'
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '��ͬ��������״̬',
			name : 'lExaStatus',
			sortable : true
		}, {
			display : '��ͬ����������Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'oldId',
			name : 'oldId',
			sortable : true,
			hide : true,
			show : false  // Edit By zengzx, bug��897
		}, {
			name : 'contractType',
			display : '��ͬ����',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				if (row.isBecome != '0' || row.isSubAppChange == '1') {
					if(row.oldId != '' ){
						if(typeof(row.oldId) == "undefined"){
						   var cid = row.id;
						}else{
						   var cid = row.oldId;
						}
					}else{
					  var cid = row.id;
					}
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ cid
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v
							+ "</font>" + '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>" + '</a>';
				}
			}
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 150
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
		}, {
			name : 'customerId',
			display : '�ͻ�Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'dealStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				} else if (v == '2') {
					return "���δ����";
				} else if (v == '3') {
					return "�ѹر�";
				} else if (v == '4') {
					return "�ȴ�����ȷ��";
				}
			},
			width : 50
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 60
		}, {
			name : 'objCode',
			display : 'ҵ����',
			sortable : true,
			width : 120,
			hide : true
		}],
		comboEx : [{
			text : '����',
			key : 'contractType',
			data : [{
				text : '���ۺ�ͬ',
				value : 'HTLX-XSHT'
			}, {
				text : '���޺�ͬ',
				value : 'HTLX-ZLHT'
			}, {
				text : '�����ͬ',
				value : 'HTLX-FWHT'
			}, {
				text : '�з���ͬ',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '����״̬',
			key : 'dealStatusArr',
			data : [{
				text : 'δ����',
				value : '0,2'
			}, {
				text : '�Ѵ���',
				value : '1,3'
			}],
			value : '0,2'
		}],
		// ���ӱ������
		subGridOptions : {
			subgridcheck : true,
			url : '?model=contract_contract_product&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				'isTemp' : '0','isDel' : '0'
			}, {
				paramId : 'contractId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],

			// ��ʾ����
			colModel : [{
				name : 'conProductName',
				width : 200,
				display : '��Ʒ����',
				process : function(v, row) {
					if( row.changeTips==1 ){
						return '<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />' + v;
					}else if( row.changeTips==2 ){
						return '<img title="��������Ĳ�Ʒ" src="images/new.gif" />' + v;
					}else{
						return v;
					}
				}
			}, {
				name : 'conProductDes',
				width : 200,
				display : '��Ʒ����'
			}, {
				name : 'number',
				display : '����',
				width : 40
			}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}, {
			display : '��ͬ����',
			name : 'contractName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		},{
			display : '������',
			name : 'createName'
		}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC'
	});

});
