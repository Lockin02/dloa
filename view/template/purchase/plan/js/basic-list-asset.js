// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#assetApplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#assetApplyGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'assetListPageJson',
		title : '�ʲ��ɹ������б�',
		isToolBar : false,
		showcheckbox : false,
		param:{'purchType':'assets',"ExaStatusArr":"δ�´�,���"},

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ�����',
					name : 'purchTypeCName',
					sortable : false
				}, {
					display : '�ɹ�������',
					name : 'planNumb',
					sortable : true,
					width : 150
				},  {
					display : '״̬',
					name : 'ExaStatus',
					sortable : true,
					process : function(v, row) {
						if (row.ExaStatus == '���') {
							return "���´�";
						} else {
							return "δ�´�";
						}
					}
				},{
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width:180
				},{
					display : '�Ƿ�Ԥ����',
					name : 'isPlan',
					sortable : true,
					process : function(v, row) {
						if (row.isPlan == '0') {
							return "��";
						} else {
							return "��";
						}
					}
				}, {
					display : '������',
					name : 'createName',
					sortable : true
				}, {
					display : '����ʱ�� ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [  {
						name : 'productCategoryName',
						display : '�������',
						width:50
					},{
						name : 'productNumb',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������',
						process:function(v,data){
							if(v==""){
								return data.inputProductName;
							}
							return v;
						}
					},{
						name : 'pattem',
						display : "����ͺ�"
					},{
						name : 'unitName',
						display : "��λ",
						width : 50
					},{
						name : 'amountAll',
						display : "��������",
						width : 70
					}, {
						name : 'dateIssued',
						display : "��������"
					},{
						name : 'dateHope',
						display : "ϣ���������"
					}]
		},

		comboEx:[{
			text:'�ɹ�����״̬',
			key:'ExaStatus',
			data:[{
			   text:'δ�´�',
			   value:'δ�´�'
			},{
			   text:'���´�',
			   value:'���'
			}]
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location="?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		},{
			    text:'�´�ɹ�',
			    icon:'add',
			    showMenuFn:function(row){
			    	if(row.ExaStatus=="δ�´�"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("ȷ��Ҫ�´���?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_plan_basic&action=pushPurch",
			    		         data:{
			    		         	id:row.id,
			    		         	applyNumb:row.planNumb
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
			    		                alert('�´�ɹ�!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="���"||row.ExaStatus=="���")&&(row.purchType=="assets"||row.purchType=="rdproject"||row.purchType=="produce")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_plan_basic&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		// ��������
		searchitems : [{
					display : '�ɹ�������',
					name : 'seachPlanNumb'
				},{
					display : '������',
					name : 'createName'
				},{
					display : '���ϱ��',
					name : 'productNumb'
				},{
					display : '��������',
					name : 'productName'
				}
		],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});