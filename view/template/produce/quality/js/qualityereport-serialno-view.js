
$(function(){
			$("#serial").yxeditgrid({
				title:'���кű���',
				objName : 'serialno[items]',				
				url:'?model=produce_quality_serialno&action=listJson',
		        param:{
					relDocId:$("#relDocId").val(),
					relDocType:'oa_produce_quality_serialno'
				},
				isFristRowDenyDel:true,
				isAddOneRow : false,
				type:'view',
				colModel : [
				             {
				                 display : 'id',
				                 name : 'id',
				                 sortable : true,
				                 type:'hidden'
					             
				             },
				             {
				                 display : '���к�',
				                 name : 'sequence',						               
				                 validation : {
				     				required : true
				     			}		                
				             },
				             {
				                 display : '˵��',
				                 name : 'remark',
				                 type : 'textarea',	                
				                 validation:{
					             		require:true
					             }
				             }
				             ]
				});
			
			
			 validate({
			        "sequence" : {
			            required : true
			        },
			        "remark" : {
			            required : true
			            
			        }
			    });
		})
