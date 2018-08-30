/** �ʲ�ά����Ϣ�б�
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_keep',
		title : '�ʲ�ά��',
		showcheckbox : false,
//		isToolBar : true,
		//isViewAction : false,
		//isEditAction : false,
		//isAddAction : false,
		isDelAction:false,


		colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            },
            {
                display : 'ά�������',
                name : 'billNo',
                sortable : true,
				width : 120
            },
//            {
//                display : 'ά����������',
//                name : 'applyDate',
//                sortable : true
//            },
            {
                display : 'ά������Id',
                name : 'deptId',
                sortable : true,
                hide : true
            },
            {
                display : 'ά����������',
                name : 'deptName',
                sortable : true
            },
            {
                display : 'ά��������Id',
                name : 'keeperId',
                sortable : true,
                hide : true
            },
            {
                display : 'ά��������',
                name : 'keeper',
                sortable : true
            },{
                display : 'ά���ܽ��',
                name : 'keepAmount',
                sortable : true,
                //�б��ʽ��ǧ��λ
                process : function(v){
					return moneyFormat2(v);
				}
            },{
                display : 'ά������',
                name : 'keepType',
                sortable : true,
                 process : function(val) {
				  if (val == "1") {
					return "�ճ�ά��";
				   }
                  if(val=="2"){
					return "��ͨά��";
				   }
                   if(val=="3") {
				    return "�ش�ά��";
				   }
			      }

            },{
                display : 'ά��ʱ��',
                name : 'keepDate',
                sortable : true
            },{
                display : '����״̬',
                name : 'ExaStatus',
                sortable : true
            },
            {
                display : '����ʱ��',
                name : 'ExaDT',
                sortable : true
            },
            {
                display : '��ע',
                name : 'remark',
                sortable : true
            }],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_daily_keepitem&action=pageJson',
			param : [{
				paramId : 'keepId',
				colId : 'id'
			}],
			colModel : [{
			display : '��Ƭ���',
			name : 'assetCode',
			width : 130
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : 'ά�޽��',
			name : 'amount',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : 'ʹ����',
			name : 'userName',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
		},
            toAddConfig : {
								formWidth : 900,
								formHeight : 400
							},
			toEditConfig : {
								formWidth : 900,
								formHeight : 400,
								showMenuFn : function(row) {
				   					 if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
										return true;
											}
				  						return false;
			      					}
							},
            toViewConfig : {
								formWidth : 900,
								formHeight : 300
							},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
						showThickboxWin('controller/asset/daily/ewf_index_keep.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			name : 'cancel',
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_keep'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
						    	show_page();
								return false;
							}else{
								if(confirm('ȷ��Ҫ����������')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/daily/ewf_index_keep.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="���"||row.ExaStatus=="���"|| row.ExaStatus == "��������")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_keep&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "GET",
						url : "?model=asset_daily_keep&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : 'ά�������',
			name : 'billNo'
		},{
			display : 'ά��������',
			name : 'keeper'
		},{
			display : 'ά����������',
			name : 'deptName'
		}],
		comboEx : [{
					text : '����״̬',
					key : 'ExaStatus',
					data : [{
								text : '��������',
								value : '��������'
							}, {
								text : '���ύ',
								value : '���ύ'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '���',
								value : '���'
							}]
				}],
		// Ĭ�������ֶ���
			sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
			sortorder : "DESC"


	});
});
