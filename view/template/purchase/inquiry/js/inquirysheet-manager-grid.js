/**ѯ�۵��б�
 *2010-12-28 can
 */

var show_page = function(page) {
	$("#inquirysheetyGrid").yxgrid("reload");
};
$(function() {
	$("#inquirysheetyGrid").yxgrid_inquirysheet({
		action:"pageJson",
		isTitle:true,
		title:'�ɹ�ѯ�۵�',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,

		param:{"states":"1,2"},

		comboEx:[{
			text:'ѯ�۵�״̬',
			key:'state',
			data:[{
			   text:'��ָ��',
			   value:'1'
			},{
			   text:'��ָ��',
			   value:'2'
			}]
		}],

		//��չ�Ҽ�
		menusEx:[
			{  text:'�鿴',    //��ָ���Ĳ鿴
			   icon:'view',
			   showMenuFn:function(row){
					if(row.state==1){
						return true;
					}
					return false;
				},
			   action:function(row,rows,grid){
			   		if(row){
						location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id;
			   		}
			   }
			},{
				text:'ָ����Ӧ��',
				icon:'edit',
				showMenuFn:function(row){
					if(row.state==1){
						return true;
					}
					return false;
				},
				action:function(row,rows,grid){
					if(row){
						 	location = "?model=purchase_inquiry_inquirysheet&action=toAssignSupp&id="+ row.id;
						}
				}
			},
			{  text:'�鿴',    //��ָ���Ĳ鿴
			   icon:'view',
			   showMenuFn:function(row){
					if(row.state==2){
						return true;
					}
					return false;
				},
			   action:function(row,rows,grid){
			   		if(row){
						location = "?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id;
			   		}
			   }
			}]
	});
});