<?php
/**
 * Shows a list of resources in a given resource group
 */
if( count( $resources ) ) {
    $shortcode_template = "[secure_resource resource_group='{$rg->get_id()}' file='%s']Enter text here[/secure_resource]";
?>
<table class="wp-list-table widefat fixed striped" id="resource-group-resources" style="margin-top:10px;">
    <thead>
        <tr>
            <th width="150px"><strong>File</strong></th>
            <th><strong>Shortcode / URL</strong><br>
            <span style="font-style:italic;">(Copy the shortcode below and paste it in the page to show download link)</span></th>
        </tr>
    </thead>
    <tbody>
	    <?php
	        foreach( $resources as $resource ) {
	            $shortcode = sprintf( $shortcode_template, $resource );
	    ?>
	        <tr>
	            <td><?php echo $resource; ?></td>
	            <td>
	                <?php echo $shortcode; ?><br>
	                <?php echo $rg->get_resource_link( $resource ); ?>
	            </td>
	        </tr>
	    <?php
	        }
	    ?>
	    </tbody>
	</table>
<?php
}
else {
    echo '<h2>No files found</h2>';
}