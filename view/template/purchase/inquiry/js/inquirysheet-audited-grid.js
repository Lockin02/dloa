/**�������Ĳɹ�ѯ�۵� �б�
 *2010-5-14 can
 */

var show_page = function(page) {
	$("#inquiryAuditedGrid").yxsubgrid("reload");
};
$(function() {
	$("#inquiryAuditedGrid").yxsubgrid({
		isTitle:true,
		title:'�������Ĳɹ�ѯ�۵�',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		param:{"ExaStatus":"���"},
		action : 'pageJson',

			// ����Ϣ
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : 'ѯ�۵����',
				name : 'inquiryCode',
				sortable : true,
				process : function(v, row) {
					if(row.state=="4"||row.state=="3"){
						return v;
					}else{
						return "<font color=blue>" +v+"</font>";
					}
				}
			}, {
				display : '����״̬',
				name : 'ExaStatus',
//				sortable : true,
				width:60
			}, {
				display : 'ѯ�۵�״̬',
				name : 'stateName',
				sortable : true,
				width:60
			}, {
				display : 'ָ����Ӧ��',
				name : 'suppName',
				sortable : true
			},
			{
				display : '��Ӧ��ID',
				name : 'suppId',
				sortable : true,
				hide : true
			},
			{
				display : 'ָ��������',
				name : 'amaldarName',
				sortable : true
			}, {
				display : '�ɹ�Ա',
				name : 'purcherName',
				sortable : true
			}, {
				display : 'ѯ������',
				name : 'inquiryBgDate',
				sortable : true
			}, {
				display : '���۽�ֹ����',
				name : 'inquiryEndDate',
				sortable : true
			}, {
				display : '��Ч����',
				name : 'effectiveDate',
				sortable : true
			}, {
				display : 'ʧЧ����',
				name : 'expiryDate',
				sortable : true
			}],
			sortname : 'c.state asc,c.createTime',
			// ���ӱ������
			subGridOptions : {
				url : '?model=purchase_inquiry_equmentInquiry&action=pageJson',
				param : [{
							paramId : 'parentId',
							colId : 'id'
						}],
				colModel : [{
							name : 'productNumb',
							display : '���ϱ��'
						}, {
							name : 'productName',
							width : 200,
							display : '��������'
						},{
							name : 'pattem',
							display : "����ͺ�"
						},{
							name : 'units',
							display : "��λ"
						},{
							name : 'amount',
							display : "ѯ������"
						},{
							name : 'purchTypeCn',
							display : "�ɹ�����"
						}]
			},
			searchitems : [{
				display : 'ѯ�۵����',
				name : 'inquiryCode'
			},{
				display : 'ָ����Ӧ��',
				name : 'suppName'
			},{
				display : '��������',
				name : 'productName'
			},{
				display : '���ϱ��',
				name : 'productNumb'
			}],

		comboEx:[{
			text:'ѯ�۵�״̬',
			key:'state',
			data:[{
			   text:'��ָ��',
			   value:'2'
			},{
			   text:'�ѹر�',
			   value:'3'
			},{
			   text:'�����ɶ���',
			   value:'4'
			}]
		}],

		//��չ�Ҽ�
		menusEx:[
			{  text:'�鿴',
			   icon:'view',
			   showMenuFn:function(row){
			   		if(row.ExaStatus=="δ�ύ"|row.ExaStatus=="��������"|row.ExaStatus=="���"){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		if(row){
						 location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},
			{  text:'�鿴',    //��ָ���Ĳ鿴
			   icon:'view',
			   showMenuFn:function(row){
					if(row.ExaStatus=="���"){
						return true;
					}
					return false;
				},
			   action:function(row,rows,grid){
			   		if(row){
						location = "?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_inquiry&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		},
				{
				text:'ָ����Ӧ��',
				icon:'edit',
				showMenuFn:function(row){
					if(row.state=="2"||row.state=="1"&&row.ExaStatus == '���'){//row.suppId==""&&
						return true;
					}
					return false;
				},
				action:function(row,rows,grid){
					if(row){
						 	location = "?model=purchase_inquiry_inquirysheet&action=toAssignSupp&id="+ row.id+"&type=todiff";
						}
				}
			},
			/**	{
				text:'���ɲɹ�����',
				icon:'add',
				showMenuFn:function(row){

					//��ʽ������ʱ�䷽��
					Date.prototype.format = function(format){
							var o = {
								"M+" : this.getMonth()+1, //month
								"d+" : this.getDate(), //day
								"h+" : this.getHours(), //hour
								"m+" : this.getMinutes(), //minute
								"s+" : this.getSeconds(), //second
								"q+" : Math.floor((this.getMonth()+3)/3), //quarter
								"S" : this.getMilliseconds() //millisecond
							}
							if(/(y+)/.test(format)) {
								format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
							}

							for(var k in o) {
								if(new RegExp("("+ k +")").test(format)) {
									format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
								}
							}
							return format;

					}

				    var date1=new Date();
					var date2=date1.format("yyyy-MM-dd");
					if(row.ExaStatus=="���"&&row.state==2){
					   return true;
					}
					return false;
				},
				action:function(row,rows,grid){

				   if(row){
	                    location = "?model=purchase_contract_purchasecontract&action=toAddPurchaseContract&inquiryId="+ row.id + "&suppId=" + row.suppId+"&skey="+row['skey_'];
				   }
				}
			},*/
				{
			   text:'�ر�',       //�ڱ���ʹ�ָ����״̬�¹ر�ѯ�۵�����ԭ�ɹ������豸����
			   icon:'delete',
			   showMenuFn:function(row){
			   		if(row.state!=3&&row.ExaStatus!="��������"){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		 if(row){
						if(confirm("ȷ��Ҫ�ر���")){
							$.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=closeMyInquiry",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('�رճɹ�!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
//						 	location = "?model=purchase_inquiry_inquirysheet&action=closeMyInquiry&id="+ row.id;
						}
					}
			   }
			}
		]
	});
});