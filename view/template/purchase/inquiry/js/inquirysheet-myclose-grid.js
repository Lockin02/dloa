/**�ҵ�ѯ�۵� �����б�
 *2010-12-28 can
 */

var show_page = function(page) {
	$("#inquirysheetyMyCloseGrid").yxsubgrid("reload");
};

//ɾ���ظ������IE�ļ��������⣩
function uniqueArray(a){
	temp = new Array();
	for(var i = 0; i < a.length; i ++){
		if(!contains(temp, a[i])){
			temp.length+=1;
			temp[temp.length-1] = a[i];
		}
	}
	return temp;
	}
	function contains(a, e){
	for(j=0;j<a.length;j++)if(a[j]==e)return true;
	return false;
}

$(function() {
	$("#inquirysheetyMyCloseGrid").yxsubgrid({
		isTitle:true,
		title:'�ѹر�ѯ�۵�',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		action : 'myPageJson',
		param:{states:'3,5'},

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
				sortable : true,
				width:60
			}, {
				display : 'ѯ�۵�״̬',
				name : 'stateName',
				sortable : false,
				width:65
			},{
				display : 'ָ����Ӧ��',
				name : 'suppName',
				sortable : true,
				width:180
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
				sortable : true,
				hide : true
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
						 parent.location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
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
						parent.location = "?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���'|| row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_inquiry&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
				}
			}
		},
			{
			    text:'�˻�����',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.state==3&&row.ExaStatus=="���"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("ȷ��Ҫ�˻�?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=backToTask",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('�˻سɹ�!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			}]
	});
});