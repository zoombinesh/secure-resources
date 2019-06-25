<?php
/**
 * Secure Resources group page
 * 
 */
$resource_groups = Secure_Resource_Group::get_resource_groups();
?> 
<div class="wrap">
	<h2>Secure Resources</h2><br>
	<a href="<?php echo admin_url( "admin-ajax.php?action=secure_resources_add" );?>" class="page-title-action thickbox" title="Add New Resource Group">Add New Resource Group</a><br><br>
	<table class="wp-list-table widefat fixed striped" id="resource-groups">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Path</th>
	            <th>User Role</th>
	            <th>Active</th>
	            <th width="150px">&nbsp;</th>
	        </tr>
	    </thead>
	    <tbody>
	    <?php
	        foreach( $resource_groups as $rg ) {
	            $show_files_link = admin_url( "admin-ajax.php?height=700&action=secure_resources&rg={$rg->get_id()}");
	            $edit_link = admin_url( "admin-ajax.php?action=secure_resources_edit&rg={$rg->get_id()}");
	            $downloads_link = admin_url( "admin-ajax.php?action=secure_resources_downloads&rg={$rg->get_id()}");
	    ?>
	        <tr data-id='<?php echo $rg->get_id();?>'>
	            <td class="cell-name"><?php echo $rg->get_name(); ?></td>
	            <td class="cell-path"><?php echo $rg->get_path(); ?></td>
	            <td class="cell-user-role"><?php echo $rg->get_user_role_display(); ?></td>
	            <td class="cell-active"><?php echo $rg->is_active() ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Inactive</span>'; ?></td>
	            <td align="right">
	                <a href="<?php echo $show_files_link;?>" class="dashicons dashicons-admin-page thickbox" title="Show Files" style="font-size:25px;"></a>
	                <a href="<?php echo $edit_link;?>" class="dashicons dashicons-edit thickbox" title="Edit Resource Group" style="font-size:25px;"></a>
	                <a href="<?php echo $downloads_link;?>" class="dashicons dashicons-download thickbox" title="Reset downloads" style="font-size:25px;"></a>
	            </td>
	        </tr>
	    <?php
	        }
	    ?>
	    </tbody>
	</table>
</div>