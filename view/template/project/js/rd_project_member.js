//保存成员
function save_member(){
	var pid = $('#id').val();
	$('#selected_user_list').find("option").attr("selected",true);
	var members = $('#selected_user_list').val();
	var table = $('#member_tab tbody');
	table.html("");
	if(members != null){
		var count = members.length;
		var len = table.length;
		for(var i = 0; i < count; i++){
			var total = len + i;
			var users = members[i].split("_");
			
			$.post(rootUrl + 'get_member_info',{pid:pid,member:users[1],memberAccount:users[0],total:i},function(data){
				$("#member_tab").append(data);
			});
		}
	} 
	closeDialog('show_list');
}

function getMemberInfo(pid, member){
	$.post(rootUrl + 'get_member_info',{pid:pid,member:member},function(data){
		return data;
	});
}

function memberSelectOperate(){
	$('#add_member_to').click(function(){  
        var $options = $('#user_list option:selected');
        var $remove = $options.remove();
        $remove.appendTo('#selected_user_list');
    });  
      
    $('#remove_member_to').click(function(){  
        var $removeOptions = $('#selected_user_list option:selected');  
        $removeOptions.appendTo('#user_list');
    });  
      
    $('#add_all_member_to').click(function(){  
        var $options = $('#user_list option');  
        $options.appendTo('#selected_user_list');  
    });  
      
    $('#remove_all_member_to').click(function(){  
        var $options = $('#selected_user_list option');  
        $options.appendTo('#user_list');  
    });  
      
    $('#user_list').dblclick(function(){  
        var $options = $('option:selected', this);
        $options.appendTo('#selected_user_list');  
    });  
      
    $('#selected_user_list').dblclick(function(){  
        $('#selected_user_list option:selected').appendTo('#user_list');  
    });
}

function removeMembers(account){
	var id = $('#id').val();
	if(id){
		$.post(rootUrl + 'remove_member',{account:account,id:id},function(data){
			if(data > 0){
				alert('删除成功');
				removeAllMemberInfo();
				$.post(rootUrl + 'get_dev_options',{id:id},function(data){
					$('#selected_user_list').append(data);
				});
			}else{
				alert('删除失败');
			}
			
			$.post(rootUrl + 'member_table',{id:id},function(data){
				$('#member_info_table').html(data);
			});
		});
	}
}

function removeAllMemberInfo(){
	$("#dept_for_member").find("option[text='请选择部门']").attr("selected",true);
	$('#selected_user_list').find("option").remove();
	$('#user_list').find("option").remove();
}

//动态获取个部门人员
function getDeptId(){
	var deptId = $('#dept_for_member').val();
	var id = $('#id').val();
	if(id == ''){
		id = 'NaN';
	}
	$.post(rootUrl + 'user_list',{deptId:deptId,id:id},function(data){
		$('#user_list').html(data);
	});
}

function showMemberOperate(){
	deptOptionForMemberOperate = '<option value="">请选择部门</option>' + deptOption;
	$('#dept_for_member').html(deptOptionForMemberOperate);
	openDialog('show_list');
}