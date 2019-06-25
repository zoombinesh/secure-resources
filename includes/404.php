<?php
global $wp_query;
header( 'HTTP/1.0 404 Not Found' );
$wp_query->set_404();
get_header();
?>
<div class="title_outer title_without_animation" data-height="140" style="
">
    <div class="title title_size_small  position_left " style="height:140px;">
        <div class="image not_responsive"></div>
		<div class="title_holder" style="/* padding-top:130px; *//* height:10px; */">
		    <div class="container">
			    <div class="container_inner clearfix"></div>
		    </div>
	    </div>
	</div>
</div>
<div class="container">
	<div class="container_inner default_template_holder" style="min-height:200px;">
	    <h2 style="font-size:22px;">Error !</h2>
	    <p style="font-size:18px;"><?php echo $error; ?></p>
	</div>
</div>
<?php
get_footer();