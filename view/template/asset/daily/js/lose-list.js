/** �ʲ���ʧ��Ϣ�б�
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_lose',
		title : '�ʲ���ʧ',
		isToolBar : true,
		//isViewAction : false,
		//isEditAction : false,
		//isAddAction : false,
		  isDelAction : false,
		  showcheckbox : false,

		colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            },{
                display : '��ʧ�����',
                name : 'billNo',
                sortable : true,
				width : 120
            },{
                display : '��������',
                name : 'loseDate',
                sortable : true
            },{
                display : '���벿��id',
                name : 'deptId',
                sortable : true,
                hide : true
            },{
                display : '���벿������',
                name : 'deptName',
                sortable : true
            },{
                display : '������Id',
                name : 'applicatId',
                sortable : true,
                hide : true
            },{
                display : '������',
                name : 'applicat',
                sortable : true
            },{
                display : 'Ӧ�⸶����',
                name : 'loseNum',
                sortable : true
            },{
                display : 'Ӧ�⸶���',
                name : 'loseAmount',
                sortable : true,
                //�б��ʽ��ǧ��λ
                process : function(v){
					return moneyFormat2(v);
				}
            },{
                display : 'ȷ���⸶���',
                name : 'realAmount',
                sortable : true,
                //�б��ʽ��ǧ��λ
                process : function(v){
					return moneyFormat2(v);
				}
            },{
                display : '��ʧԭ��',
                name : 'reason',
                width : 200,
                hide : true,
                sortable : true
            },{
                display : '����״̬',
                name : 'ExaStatus',
                sortable : true
            },{
                display : '����ʱ��',
                name : 'ExaDT',
                sortable : true
            },{
                display : '��ע',
                name : 'remark',
                sortable : true
            }],
            // �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_daily_loseitem&action=pageJson',
			param : [{
				paramId : 'loseId',
				colId : 'id'
			}],
			colModel : [
				{
					display:'��Ƭ���',
					name : 'assetCode',
					width : 130
				}, {
					display:'�ʲ�����',
					name : 'assetName'
				}, {
					display : '����ͺ�',
					name : 'spec',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '��������',
					name : 'buyDate',
					//type : 'date',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '��������',//orgName
					name : 'orgName',
					tclass : 'txt',
					readonly:true
				}, {
					display : 'ʹ�ò���',//useOrgName
					name : 'useOrgName',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'�����豸',
					name : 'equip',
					type:'statictext',
                    process : function(e, data){
                    return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='+data.assetCode+'\')">��ϸ</a>'
				     }
				}, {
					display : '����ԭֵ',
					name : 'origina',
					tclass : 'txt',
					readonly:true
				}, {
					display : '�Ѿ�ʹ���ڼ���',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '�ۼ��۾ɽ��',
					name : 'depreciation',
					tclass : 'txtmiddle',
					readonly:true,
               		 //�б��ʽ��ǧ��λ
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : '��ֵ',
					name : 'salvage',
					tclass : 'txtmiddle',
					readonly:true,
               		 //�б��ʽ��ǧ��λ
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
		},
            toAddConfig : {
								formWidth : 1050,
								formHeight :700
							},
			toEditConfig : {
								formWidth : 1050,
								formHeight : 700,
								showMenuFn : function(row) {
				   					 if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
										return true;
											}
				  						return false;
			      					}
							},
            toViewConfig : {
								formWidth : 1050,
								formHeight : 350
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
						showThickboxWin('controller/asset/daily/ewf_index_lose.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}

		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="���"||row.ExaStatus=="���" || row.ExaStatus == "��������")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_lose&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		},{
			name : 'aduit',
			text : '��д�����ʲ�',
			icon : 'add',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="���")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_disposal_scrap&action=toAdd&loseId="
							+ row.id
							+"&type=lose"
							+"&loseBillNo="
							+row.billNo
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000");
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
						url : "?model=asset_daily_lose&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '��ʧ�����',
			name : 'billNo'
		},{
			display : '������',
			name : 'applicat'
		},{
			display : '���벿��',
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
		// ҵ���������
		//	boName : 'ȫ��',
		// Ĭ�������ֶ���
			sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
			sortorder : "DESC"


	});
});
