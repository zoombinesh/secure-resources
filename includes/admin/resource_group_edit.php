<?php
/**
 * Resource group edit form
 */ 
 
$data = [
    'id' => '',
    'name' => '',
    'path' => '',
    'user_role' => '',
    'active' => 0
];

if( isset( $rg ) && $rg instanceof Secure_Resource_Group ) {
    $data['id'] = $rg->get_id();
    $data['name'] = $rg->get_name();
    $data['path'] = $rg->get_path();
    $data['user_role'] = $rg->get_user_role();
    $data['active'] = $rg->get_active();
}
?>
<style>
    #resource-group-edit-form input[type=text], #resource-group-edit-form  select{ max-width:100%;width:100%;}
</style>
<form id="resource-group-edit-form" method="post">
    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
    <table class="form-table">
	    <tbody>
	        <tr class="form-field form-required">
        		<th scope="row"><label for="name">Name <span class="description">(required)</span></label></th>
        		<td><input name="name" type="text" id="name" value="<?php echo $data['name'];?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" required></td>
        	</tr>
        	<tr class="form-field">
        		<th scope="row"><label for="path">Path <span class="description">(required)</span></label></th>
        		<td><input name="path" type="text" id="path" value="<?php echo $data['path'];?>" aria-required="true" maxlength="60" required><p class="description">Path where resources live</p></td>
        	</tr>
        	<tr class="form-field">
        		<th scope="row"><label for="user_role">User Role </label></th>
        		<td>
            		<select name="user_role" id="user_role">
            		    <option value="">-Select-</option>
            		<?php
    		            foreach ( $roles as $role => $role_name ) {
    					    $selected = $role == $data['user_role'] ? 'selected' : '';
    				?>
    				    <option value="<?php echo esc_attr( $role ); ?>" <?php echo $selected;?>><?php echo esc_html( $role_name ); ?></option>
    				<?php
    						
    					}
            		?>  
            		</select>
            		<p class="description">User role allowed to access these resources. If left blank the resource will not be accessible by anyone.</p>
        		</td>
        	</tr>
        	<tr class="form-field">
        		<th scope="row"><label for="path">Active</th>
        		<td><input name="active" type="checkbox" id="active" value="1" <?php echo 1 == $data['active'] ? 'checked' : '';?>></td>
        	</tr>
	    </tbody>
	</table>
	<p class="submit"><input type="submit" name="edit_resource_group" id="edit_resource_group" class="button button-primary" value="Submit"></p>
</form>
<script>
    (function ($) {
        $.fn.serializeFormJSON = function () {
    
            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
    })(jQuery);
    
    jQuery(function(){
        jQuery("#resource-group-edit-form").on("submit",function(e){
            e.preventDefault();
            $form = jQuery(this);
            var data = $form.serializeFormJSON();
            
            jQuery.ajax({
              method: "POST",
              url: "<?php echo admin_url( 'admin-ajax.php?action=secure_resources_save' ); ?>",
              data: data
            }).done(function(response){
                if(response.success){
                    <?php
                        if(!empty($data['id'])){
                    ?>
                    row = jQuery("table#resource-groups tr[data-id=<?php echo $data['id'];?>]");
                    row.find("td.cell-name").html(data.name);
                    row.find("td.cell-path").html(data.path);
                    role = data.user_role == '' ? '' : jQuery("#resource-group-edit-form #user_role").find("option[value="+data.user_role+"]").text();
                    row.find("td.cell-user-role").html(role);
                    row.find("td.cell-active").html(1 == data.active ? "<span style='color:green;'>Active</span>" : "<span style='color:red;'>Inactive</span>" );
                    <?php
                        }
                    ?>
                    alert('Data submitted successfully.');
                    <?php
                        if(empty($data['id'])){
                    ?>
                    document.location.href=document.location.href;
                    <?php
                        }
                    ?>
                }
                else{
                    msg = '';
                    if(response.data.error){
                        jQuery.each(response.data.error,function(key,value){
                            msg += value + '\n';
                        });
                    }
                    if(msg){
                        msg = 'Problem submitting data: \n' + msg;
                        alert(msg);
                    }
                }
            });
        });
    });
</script>