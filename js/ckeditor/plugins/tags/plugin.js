/**
 * 绑定工具
 */
CKEDITOR.plugins.add('tags', //核心函数
{
    init: function(editor)
    {
		var pluginName = 'tags'; 
        //CKEDITOR.dialog.add(pluginName, this.path + 'dialogs/firstindex.js'); //注册对话框
       // editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName));    //注册命令
        editor.ui.addRichCombo('tags',{
        		label:'系统标签',
        		className : 'cke_format',
        		panel: 
        	       { 
        			css : editor.skin.editor.css.concat( editor.config.contentsCss ),
        	        multiSelect: false
        	       // attributes: { 'aria-label': this.members.LanguagesTitleId } 
        	       }, 
        		init : function()
				{
					//this.startGroup( 'select' );

						// Add the tag entry to the panel list.
        	    	   this.add( 'cycle', '考核周期', '考核周期标签' );
        	    	   this.add('count_percentage','合计百分比','合计百分比标签');
        	    	   this.add('','=====被考核人======','分割线');
        	    	   this.add( 'user_name', '被考核人姓名', '被考核人姓名标签' );
        	    	   this.add( 'jobs_name', '被考核人职位', '被考核人职位标签' );
        	    	   this.add( 'my_fraction', '被考核人自评分', '被考核人自评分标签' );
        	    	   this.add( 'my_remark', '被考核人自评分说明', '被考核人自评分说明标签' );
        	    	   this.add( 'count_my_fraction', '统计被考核人自评分', '统计被考核人自评分标签' );
        	    	   //this.add( 'average_assess_fraction', '最终考核评分（百分比）', '最终考核评分（百分比）标签' );
        	    	   this.add('upfile','附件上传','附件上传标签');
        	    	   this.add( 'my_opinion', '被考核人意见', '被考核人意见标签' );
        	    	   this.add('','=====考核人=======','分割线');
        	    	   this.add( 'assess_user_name', '考核人姓名', '考核人姓名标签' );
        	    	   this.add( 'assess_fraction', '考核人评分', '考核人评分标签' );
        	    	   this.add( 'count_assess_fraction', '统计考核人评分', '统计考核人评分标签' );
        	    	   this.add( 'average_assess_fraction', '最终考核评分（百分比）', '最终考核评分（百分比）标签' );
        	    	   this.add( 'assess_opinion', '考核人意见', '考核人意见标签' );
        	    	   this.add('','=====审核人========','分割线');
        	    	   this.add( 'audit_user_name', '审核人姓名', '审核人姓名标签' );
					   this.add( 'audit_fraction', '审核人评分', '审核人平分标签' );
					   this.add( 'count_audit_fraction', '统计审核人评分', '统计审核人平分标签' );
					   this.add( 'average_audit_fraction', '最终审核评分（百分比）', '最终考核评分（百分比）标签' );
					   this.add( 'audit_opinion', '审核人意见', '审核人意见标签' );
					   this.add('','=====评价人========','分割线');
					   this.add('evaluate_fraction','评价分数','评价分数标签');
					   this.add('evaluate_remark','评价分说明','评价分说明标签');
					   this.add('count_evaluate_fraction','统计评价分数','统计评价总分数标签');
				},
				onClick : function( value )
				{
					var str='';
					dom_id = Math.random(1,999999);
					switch (value) {
					case 'cycle':
						str='{cycle}';
						break;
					case 'count_percentage':
						str ='{count_percentage}';
						break;
					case 'user_name':
						str='{user_name}';
						break;
					case 'jobs_name':
						str='{jobs_name}';
						break;
					case 'my_fraction':
						//str=fraction_option('text_'+tmp,'my_fraction');
						str = '<input type="text" size="5" class="my_fraction" name="my_fraction[]" onKeyUp="value=this.value.replace(/[^\\d\\.]/g,\'\');set_count(this,\'my_fraction\');" value="" />';
						break;
					case 'count_my_fraction':
						str += '<span id="show_count_my_fraction" style="color:red;">0</span>';
						str += '<input type="hidden" id="count_my_fraction" name="count_my_fraction" value="" />';
						break;
					case 'my_remark':
						str='<textarea class="my_remark" name="my_remark[]" style="overflow:auto;" id="text_'+dom_id+'"></textarea>';
						break;
					case 'my_opinion':
						str='<textarea class="my_opinion"  name="my_opinion" style="overflow:auto;" id="text_'+dom_id+'"></textarea>';
						break;
					case 'upfile':
						str = '<span><input type="file" name="upfile[]" onchange="file_input(this);" value="" /></span>';
						var upfile_func = editor.document.getById('upfile_func');
						if (upfile_func == null)
						{
							str +='<span id="upfile_func">';
							str +='<script>';
							str +='function file_input(obj){';
							str +='if(obj.parentNode.getElementsByTagName("a").length == 0){$(obj.parentNode).append(\'<a href="javascript:void(0);" onclick="del_input(this);">删除</a>\')};';
							str +='$(obj.parentNode.parentNode).append(\'<span><br /><input type="file" name="upfile[]" onchange="file_input(this);" value="" /></span>\');';
							str +='}';
							str +='function del_input(obj){$(obj.parentNode).remove();}';
							str +='</script></span>';
						}
						break;
					case 'assess_user_name':
						str='{assess_user_name}';
						break;
					case 'assess_fraction':
						//str=fraction_option('text_'+tmp,'assess_fraction');
						str = '<input type="text" size="5" class="assess_fraction" name="assess_fraction[]" onKeyUp="value=this.value.replace(/[^\\d\\.]/g,\'\');set_count(this,\'assess_fraction\');" value="" />';
						break;
					case 'count_assess_fraction':
						str += '<span id="show_count_assess_fraction" style="color:red;">0</span>';
						str += '<input type="hidden" id="count_assess_fraction" name="count_assess_fraction" value="" />';
						str += '<input type="hidden" id="average_assess_fraction_value" name="average_assess_fraction" value="" />';
						break;
					case 'average_assess_fraction':
						str='<span id="average_assess_fraction">{average_assess_fraction}</span>';
						break;
					case 'assess_opinion':
						str='<textarea class="assess_opinion" name="assess_opinion" style="overflow:auto;" id="text_'+dom_id+'"></textarea>';
						break;
					case 'audit_user_name':
						str='{audit_user_name}';
						break;
					case 'audit_fraction':
						str = '<input type="text" size="5" class="audit_fraction" name="audit_fraction[]" onKeyUp="value=this.value.replace(/[^\\d\\.]/g,\'\');set_count(this,\'audit_fraction\');" value="" />';
						break;
					case 'count_audit_fraction':
						str += '<span id="show_count_audit_fraction" style="color:red;">0</span>';
						str += '<input type="hidden" id="count_audit_fraction" name="count_audit_fraction" value="" />';
						str += '<input type="hidden" id="average_audit_fraction_value" name="average_audit_fraction" value="" />';
						break;
					case 'average_audit_fraction':
						str='<span id="average_audit_fraction">{average_audit_fraction}</span>';
						break;
					case 'audit_opinion':
						str='<textarea class="audit_opinion" name="audit_opinion" style="overflow:auto;" id="text_'+dom_id+'"></textarea>';
						break;
						
					case 'evaluate_fraction':
						str = '<input type="text" size="5" class="evaluate_fraction" name="evaluate_fraction[]" onKeyUp="value=this.value.replace(/[^\\d\\.]/g,\'\');set_count(this,\'evaluate_fraction\');" value="" />';
						break;
					case 'count_evaluate_fraction':
						str += '<span id="show_count_evaluate_fraction" style="color:red;">0</span>';
						str += '<input type="hidden" id="count_evaluate_fraction" name="count_evaluate_fraction" value="" />';
						break;
					case 'evaluate_remark':
						str='<textarea class="evaluate_remark" name="evaluate_remark[]" style="overflow:auto;" id="text_'+dom_id+'"></textarea>';
						break;
					default:
						break;
					}
					
					editor.focus();
					editor.insertHtml( str );
					if (value.indexOf('remark')!=-1 || value.indexOf('opinion')!=-1)
					{
						var obj = editor.document.$.getElementById('text_'+dom_id);
						if (obj.nodeName == 'textarea' || obj.nodeName == 'TEXTAREA')
						{
							var w = obj.parentNode.style.width ? obj.parentNode.style.width : obj.parentNode.offsetWidth-10;
							var h = obj.parentNode.style.height ? obj.parentNode.style.height : obj.parentNode.offsetHeight-10;
							obj.style.width = w;
							obj.style.height = h;
						}
					}
				}
        } );           
     }
});
