<?php
/**
 */ 
?>
<form id="search_user" method="post" action="<?php echo admin_url('admin-ajax.php?action=secure_resource_search_user');?>" style="background: #f1f1f1;padding: 10px;margin-top: 10px;">
    <label for="user">Search User</label>
    <input type="text" id="user" name="user" value="" style="padding:8px;width:300px;" required>
    <input type="submit" name="search" value="Search">
</form>
<div id="search_container" style="margin-top:20px;"></div>
<script>
    jQuery(function(){
        $container = jQuery("#search_container");
        jQuery("#search_user").on('submit',function(e){
            e.preventDefault();
            $form = jQuery(this);
            jQuery.ajax({
                url: $form.attr('action'),
                data: {user: $form.find('#user').val()},
                type: 'POST'
            }).done(function(response){
                if(response.success){
                    if(response.data.users && response.data.users.length > 0){
                        html = '<div><strong>'+response.data.users.length+' results found</strong></div>';
                        html += '<table class="widefat striped fixed"><thead><tr><th width="80">ID</th><th>Name</th><th>Email</th><th width="70">&nbsp;</th></tr></thead><tbody>';
                        jQuery.each(response.data.users, function(key,data){
                            html += '<tr><td>'+data.id+'</td><td>'+data.name+'</td><td>'+data.email+'</td><td><a href="javascript:void" class="button" onclick="sr_select_user('+data.id+',<?php echo $_GET['rg']; ?>)">Select</a></td></tr>';
                        });
                        html += '</tbody></table>';
                        $container.html(html);
                    }
                    else{
                        $container.html('<span style="color:red;">No users found!</span>');
                    }
                }
            });
        });
    });
    function sr_select_user(user_id, resource_group_id){
        $url = '<?php echo admin_url('admin-ajax.php');?>?action=secure_resource_show_user_downloads&user='+user_id+'&rg='+resource_group_id;
        $container = jQuery("#search_container");
        jQuery.ajax({
            url: $url,
            type: 'GET'
        }).done(function(response){
            if(response.success){
                if(response.data.length){
                    html = '<div><strong>Files downloaded</strong></div>';
                    html += '<table class="widefat striped fixed"><thead><tr><th>File</th><th>Date Downloaded</th><th width="70">&nbsp;</th></tr></thead><tbody>';
                    jQuery.each(response.data, function(key,row){
                        html += '<tr><td>'+row.file+'</td><td>'+row.date+'</td><td><a href="javascript:void" class="button" onclick="sr_reset_download(this,'+user_id+',\''+row.file+'\','+resource_group_id+')">Reset</a></td></tr>';
                    });
                    html += '</tbody></table>';
                    $container.html(html);
                }
                else{
                    $container.html('<span style="color:red;">No files downloaded</span>');
                }
            }
        });
    }
    
    function sr_reset_download(element, user_id, file, resource_group_id){
        if(!confirm('This will allow user to download file again. Do you want to continue?')){
            return;
        }
        $url = '<?php echo admin_url('admin-ajax.php');?>?action=secure_resource_reset_user_downloads&user='+user_id+'&rg='+resource_group_id+'&file='+file;
        jQuery.ajax({
            url: $url,
            type: 'GET'
        }).done(function(response){
            if(response.success){
                alert('Download has been reset');
                $ele = jQuery(element);
                $ele.parents('tr').remove();
            }
        });
    }
</script>