$(document).ready(function() {
	//员工
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		formCode : 'userName',
		event : {
				select : function(e,row)
				{
					$.post('?model=hr_recontract_recontract&action=getUserInfo',{userAccount:row.val,rand:Math.random()*100000},
				  function (data)
				  {
					  if(data)
					  {  
				        data=eval(data);
			    		$("#companyName").val(data[0].companyName);
						$("#companyId").val(data[0].companyId);
			    		$("#deptId").val(data[0].deptId);
						$("#deptName").val(data[0].deptName);
			    		$("#comeinDate").val(data[0].comeinDate);
			    		$("#jobName").val(data[0].jobName);
						$("#jobId").val(data[0].jobId);
						$("#beginDate").val(data[0].beginDate);
						$("#closeDate").val(data[0].closeDate);
						a=data[0].beginDate.split('-');
						b=data[0].closeDate.split('-');
						$("#conNum").val(b[0]-a[0]);
						
					  }else{
					         alert('请求失败！');	  
					 }
				  
				  });
					
				}
			}
		
	});
	$("#jobName").yxcombogrid_jobs({
		hiddenId : 'jobId'
	});
    // 合同类型
	conTypeArr = getData('HRHTLX');
	addDataToSelect(conTypeArr, 'conType');
	// 合同状态
	conStateArr = getData('EMETH');
	addDataToSelect(conStateArr, 'conState');
	// 合同次数
	conNumArr = getData('LYEARS');
	addDataToSelect(conNumArr, 'conNum');
	
	$('#conNum').bind('click',function(){
	   var	beginDate=$('#beginDate').val();
	   if(beginDate){
		     next=DifferDate(beginDate,$(this).val(),1);
		    $('#closeDate').val(next);
	   }else{
		   var d = new Date();
		   var vYear = d.getFullYear();
		   var vMon = d.getMonth() + 1;
		   var vDay = parseInt(d.getDate()-1);
		   $('#beginDate').val(vYear+'-'+vMon+'-'+vDay);
		    next=DifferDate(vYear+'-'+vMon+'-'+vDay,$(this).val(),1);
		    $('#closeDate').val(next);		   
	   }
		
	} )
	
});

function DifferDate(now,numYear,days) { 
  if(numYear=='N'){
	  return '无固定期限'; 
   }else{ 
		// 参数表示在当前日期下要增加的天数  
		var now = new Date(now.replace(/-/ig,'/'));  
		// + 1 代表日期加，- 1代表日期减  
		now.setDate(now.getDate()- 1 * days);  
		var year = parseInt(now.getFullYear())+parseInt(numYear);  
		var month = now.getMonth() + 1;  
		var day = now.getDate();  
		if (month < 10) {  
			month = '0' + month;  
		}  
		if (day < 10) {  
			day = '0' + day;  
		}  
	   return year + '-' + month + '-' + day;  
   }
};  
