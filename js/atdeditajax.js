/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var tempid='';
var tempuid='';
var displayspan=false;
var temp = Array();
//work





//
function span_show(obj,id , uid, ty){
    
    if($('#pub_'+id).html()==''||displayspan){
        return false;
    }
    tempid=id;
    tempuid=uid;
    if(ty=='work'){
        var span=document.getElementById("spanid_work");
    }else{
        var span=document.getElementById("spanid");
    }
    
    var crmx=findposx(obj);
    var crmy=findposy(obj);
    
    span.style.left=(crmx +obj.offsetWidth -span.offsetWidth-2)+"px";
    span.style.top=crmy+"px";
    span.style.display="block";
    
    return true;
}

function fnshow(objid){
    var id = document.getElementById(objid);
    id.style.display = 'block';
}

function fnhide(objid){
    var id = document.getElementById(objid);
    id.style.display = 'none';
}

function span_hide(ty){
    if(ty=='work'){
        var span=document.getElementById("spanid_work");
    }else{
        var span=document.getElementById("spanid");
    }
    
    span.style.display="none";
}

function show(obj,b){
    var s=obj.lable;
    var span=document.getElementById("title_pad");
    var ifTop=1;
    try{
        if(window.parent.frames("ifsummary")!=null)
            ifTop=window.parent.frames("ifsummary").document.body.scrollTop;
    }catch(e){

    }
    span.style.left=event.x+10+document.body.scrollLeft;
    span.style.top=event.y+ifTop+document.body.scrollTop;
    span.innerHTML=s;
    if (b)
        span.style.display="block";
    else
        span.style.display="none";
}
/**
 * 
 */
function edit_check(id,uid)
{
    
  var rand=Math.random()*100000;
  var pub = $('#edit_pub_'+id).val();
  if (id==''||pub ==''||isNaN(pub)||parseInt(pub)<0)
  {
      alert('��������ȷ�Ľڼ���������');
      return false;
  }
  $.post('ajax.php',{model:'attendance',action:'update_hols_stat',rand:rand,id:id,uid:uid,pub:pub},
      function (data)
      {
          if (data=='e')
          {
              alert('�Ƿ�ID��������������');
          }else{
              try{
                var spd=data.split('_');
                if(!isNaN(spd[1])){
                  displayspan=false;
                  $('#rel_'+id).html($('#rel_'+id).html()-spd[1]);
                  $('#tol_'+spd[0]+'_'+uid).html($('#tol_'+spd[0]+'_'+uid).html()-spd[1]);
                  $('#pub_'+id).html(pub);
                  temp[tempid]='';
                }else{
                  alert('�Ƿ�ID��������������');
                }
              }catch(e){
                alert('����ʧ��');
              }
          }
      }
    )
    return true;
}
/**
 * 
 */
function edit_check_work(id,uid)
{
    
  var rand=Math.random()*100000;
  var pub = $('#edit_pub_'+id).val();
  if (id==''||pub ==''||isNaN(pub)||parseInt(pub)<0)
  {
      alert('��������ȷ��������');
      return false;
  }
  $.post('ajax.php',{model:'attendance',action:'update_hols_stat',rand:rand,id:id,uid:uid,pub:pub,ty:'work'},
      function (data)
      {
          if (data=='e')
          {
              alert('�Ƿ�ID��������������');
          }else{
              try{
                location.reload();
                var spd=data.split('_');
                if(!isNaN(spd[1])){
                  displayspan=false;
                  $('#rel_'+id).html($('#rel_'+id).html()-spd[1]);
                  $('#tol_'+spd[0]+'_'+uid).html($('#tol_'+spd[0]+'_'+uid).html()-spd[1]);
                  $('#work_'+id).html(pub);
                  temp[tempid]='';
                }else{
                  alert('�Ƿ�ID��������������');
                }
              }catch(e){
                alert('����ʧ��');
              }
          }
      }
    )
    return true;
}

function edit_work(){
    span_hide('work');
    displayspan=true;
    temp[tempid] = $('#work_'+tempid).html();
    var pub = $('#work_'+tempid).html();
    var html='';
    html ='<input type="text" id="edit_pub_'+tempid+'" name="pub" value="'+pub+'" size="6" class="BigInput" />'
        +'<br><input type="button" id="btn" onclick="edit_check_work(\''+tempid+'\',\''+tempuid+'\')" value="����" class="btn" /> '
        +'<input type="button" id="btn" onclick="edit_exit(\'work\')" value="ȡ��" class="btn" />';
    $('#work_'+tempid).html(html);
    html = '';
}
function edit()
{
    span_hide();
    displayspan=true;
    temp[tempid] = $('#pub_'+tempid).html();
    var pub = $('#pub_'+tempid).html();
    var html='';
    html ='<input type="text" id="edit_pub_'+tempid+'" name="pub" value="'+pub+'" size="6" class="BigInput" />'
        +'<br><input type="button" id="btn" onclick="edit_check(\''+tempid+'\',\''+tempuid+'\')" value="����" class="btn" /> '
        +'<input type="button" id="btn" onclick="edit_exit()" value="ȡ��" class="btn" />';
    $('#pub_'+tempid).html(html);
    html = '';
}
function edit_exit(ty)
{
    
    displayspan=false;
    if(ty=='work'){
        $('#work_'+tempid).html(temp[tempid]);
    }else{
        $('#pub_'+tempid).html(temp[tempid]);
    }
}

function handupstat(){
    if(confirm('ȷ���ύ�������ݼ�ͳ�ƣ�')){
        var rand=Math.random()*100000;
        $.post('?model=salary&action=hr_hols_hd',{rand:rand},
          function (data)
          {
              if (data)
              {
                  alert('�Ƿ�ID��������������'+data);
              }else{
                  alert('�ύ�ɹ�');
                  location.reload(true);
              }
          }
        )
    }
}
function dept_edit()
{
    span_hide();
    displayspan=true;
    temp[tempid] = $('#pub_'+tempid).html();
    var pub = $('#pub_'+tempid).html();
    var html='';
    html ='<input type="text" id="edit_pub_'+tempid+'" name="pub" value="'+pub+'" size="6" class="BigInput" />'
        +'<br><input type="button" id="btn" onclick="dept_edit_check(\''+tempid+'\',\''+tempuid+'\')" value="����" class="btn" /> '
        +'<input type="button" id="btn" onclick="edit_exit()" value="ȡ��" class="btn" />';
    $('#pub_'+tempid).html(html);
    html = '';
}
function dept_edit_check(id,uid)
{

  var rand=Math.random()*100000;
  var pub = $('#edit_pub_'+id).val();
  if (id==''||pub ==''||isNaN(pub)||parseInt(pub)<0)
  {
      alert('��������ȷ��������');
      return false;
  }
  $.post('?model=salary&action=dp_hols_hd',{rand:rand,id:id,uid:uid,pub:pub},
      function (data)
      {
          if (data!=''&&data)
          {
              alert('�Ƿ�ID��������������:');
          }else{
              try{
                displayspan=false;
                $('#pub_'+id).html(pub);
                temp[tempid]='';
                alert('�޸ĳɹ�');
              }catch(e){
                alert('����ʧ��');
              }
          }
      }
    )
    return true;
}