/**询价单列表
 *2010-12-28 can
 */

var show_page = function(page) {
	$("#inquirysheetyGrid").yxgrid("reload");
};
$(function() {
	$("#inquirysheetyGrid").yxgrid_inquirysheet({
		action:"pageJson",
		isTitle:true,
		title:'采购询价单',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,

		param:{"states":"1,2"},

		comboEx:[{
			text:'询价单状态',
			key:'state',
			data:[{
			   text:'待指定',
			   value:'1'
			},{
			   text:'已指定',
			   value:'2'
			}]
		}],

		//扩展右键
		menusEx:[
			{  text:'查看',    //待指定的查看
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
				text:'指定供应商',
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
			{  text:'查看',    //已指定的查看
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