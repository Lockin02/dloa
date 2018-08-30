<style type="text/css">
#newuserForm label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
    color: red;
}
#rowedgrid tr td{
    padding-left: 1px;
    border-top: 0px;
    border-right: 1px solid white;
    border-left: 1px solid white;
    border-bottom: 1px solid white;
    background-color: #E7F5CB
}
a:link {   text-decoration:none;   color:blue;}
a:visited {   text-decoration:none;   color:blue;}
a:hover {   text-decoration:none;   color:blue;}
a:active {   text-decoration:none;   color:blue;}
</style>
<script type="text/javascript">
    function showbox(id){
        if($('.showbox_'+id).css('display')=='none'){
            $('#menu_'+id).attr('src', 'images/menu/tree_plus.gif');
            $('.showbox_'+id).show();
        }else{
            $('#menu_'+id).attr('src', 'images/menu/tree_minus.gif');
            $('.showbox_'+id).hide();
        }
    }
    function checkall(id){
    	if($('#clickall_'+id).attr("checked")==true){
            $('.showbox_'+id+' input').attr("checked",'true');
            cn=$('.showbox_'+id).find("input").get();
            for(var j=0;j<cn.length;j++)
			{
				checkall(cn[j].value);
			}
        }else{
            $('.showbox_'+id+' input').removeAttr("checked");
            cns=$('.showbox_'+id).find("input").get();
            for(var j=0;j<cns.length;j++)
			{
				 checkall(cns[j].value);
			}
        }
    }
</script>
<div id="costtypediv" style='margin-top:1px;'>
    <table id="rowedgrid" border="0" width="100%" cellpadding="0" cellspacing="0" style="text-align: left;">
        <tr>
            <td style="height: 26px;text-align: center;" ><input type="button" value="关闭" onclick="closeFun()"></input></td>
<?php
$sql="select costtypeid as id , costtypename as name , parentcosttypeid as pid
    from cost_type
    where parentcosttypeid <>0  ";
$query=$this->db->query($sql);
while($row=$this->db->fetch_array($query)){
    $carr[$row['pid']][$row['id']]=$row['name'];
}
$res='';
$checkarr=$_POST['ctids']?$_POST['ctids']:array();
foreach($carr['1'] as $key=>$val){
    if(empty($carr[$key])){//当前级
        $res.='<tr><td>
            <img src="images/menu/tree_blank.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
            <input type="checkbox" name="ctids[]" class="checkboxclass" value="'.$key.'" '.(in_array($key, $checkarr)?'checked':'').' />
            '.$val.'
            </td></tr>';
    }else{//含子级
        $res.='<tr><td>
            <img src="images/menu/tree_minus.gif" align="absbottom" class="outline" id="menu_'.$key.'" style="cursor:hand"
                onclick="showbox('.$key.')"/> 
                <input type="checkbox"name="ctids[]" id="clickall_'.$key.'"  value="'.$key.'" onclick="checkall('.$key.')" '.(in_array($key, $checkarr)?'checked':'').' class="checkboxclass"  />
            '.$val.'
            </td></tr>';
        foreach($carr[$key] as $key1=>$val1){
            if(empty($carr[$key1])){//当前级
                $res.='<tr style="display: none;" class="showbox_'.$key.'"><td>
                    <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                    <img src="images/menu/tree_blank.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                    <input type="checkbox" name="ctids[]" class="checkboxclass" value="'.$key1.'" '.(in_array($key1, $checkarr)?'checked':'').'/>
                    '.$val1.'
                    </td></tr>';
            }else{//含子级
                $res.='<tr style="display: none;" class="showbox_'.$key.'"><td>
                    <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                    <img src="images/menu/tree_minus.gif" align="absbottom" class="outline" id="menu_'.$key1.'" style="cursor:hand"
                        onclick="showbox('.$key1.')"/>' .
                        		'<input type="checkbox" name="ctids[]" id="clickall_'.$key1.'"  value="'.$key1.'" onclick="checkall('.$key1.')" '.(in_array($key, $checkarr)?'checked':'').' class="checkboxclass"  />
                    '.$val1.'
                    </td></tr>';
                foreach($carr[$key1] as $key2=>$val2){
                    $res.='<tr style="display: none;" class="showbox_'.$key1.'"><td>
                            <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                            <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                            <img src="images/menu/tree_blank.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                            <input type="checkbox" name="ctids[]" class="checkboxclass"  value="'.$key2.'" '.(in_array($key2, $checkarr)?'checked':'').' />
                            '.$val2.'
                            </td></tr>';
                }
            }
        }
    }
}
$res.='<tr><td>&nbsp;</td></tr>';
echo $res;
?>
        </tr>
    </table>
</div>