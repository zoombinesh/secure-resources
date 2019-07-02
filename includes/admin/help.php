<?php
/**
 * Help document
 */ 
?>
<style>
    .wrap ul{list-style: lower-roman;margin-left: 20px;font-size:14px;}
    .wrap ul li{padding-top:5px;padding-bottom:5px;}
    .wrap .highlight{padding: 2px 3px 1px;background: #f7f7f9;color: #e01e5a;border: 1px solid #e1e1e8;}
    .wrap img{margin-top:10px;margin-bottom:5px;}
    .wrap h4{font-size: 1.2em;background: #00b9eb1f;padding: 5px 10px;max-width: 900px;}
</style>
<div class="wrap">
	<h2>Secure Resources Help Document</h2>
	
	<h3>Setting up secure resource page</h3>
	<div>
	    <h4>1. Upload files</h4>
	    <p>
	        <ul>
	            <li>Login to <span class='highlight'>Cpanel</span>. <a href="https://naft.org.au/cpanel/">https://naft.org.au/cpanel/</a></li>
	            <li>Go to <span class='highlight'>File Manager</span> and go to <span class='highlight'>secure_resources</span> folder</li>
	            <li>Create a folder with a name relevant to resource group that you are going to create. You can create sub-folder structure.<br>
	            For example, if the event is Bac Blanc 2019, create a folder called bac_blanc and inside create a sub-folder called 2019 so that all annual Bac Blanc files like 2018, 2019, 2020 etc live in one top level folder called bac_blanc</li>
	            <li>Upload files to the newly created folder. All your files should live in one single folder.</li>
	        </ul>
	    </p>
	    <h4>2. Create User Role</h4>
	    <p>
	        <ul> 
	            <li>In the admin left hand menu, hover over Users and click on <span class='highlight'>Add New Role</span> <br><img src="https://naft.org.au/wp-content/uploads/2019/07/add_new_role.jpg" width="250px"></li>
	            <li>Enter role name. For example: Bac Blanc 2019</li>
	            <li>Make sure <span class='highlight'>Read</span> capability is checked.<br><img src="https://naft.org.au/wp-content/uploads/2019/07/read_cap.jpg" width="400px"></li>
	        </ul>
	    </p>
	    <h4>3. Create Secure Resource Group</h4>
	    <p>
	        <ul> 
	            <li>In the admin left hand menu, click on <span class='highlight'>Secure Resources</span> <br><img src="https://naft.org.au/wp-content/uploads/2019/07/secure_resources.jpg" width="130px"></li>
	            <li>Click on <span class='highlight'>Add New Resource Group</span> button</li>
	            <li>In the popup, give a name to the resource group. For example: Bac Blanc 2019<br><img src="https://naft.org.au/wp-content/uploads/2019/07/add_resource_group.jpg" width="500px"></li>
	            <li>Give the path where the files live in the Path field. This path is relative to top level folder secure_resources. For example, /bac_blanc/2019/ </li>
	            <li>In User Role field, select the user role you created for this resource group (in above example Bac Blanc 2019) otherwise leave it empty for now. It can be updated later. User Role makes sure only users/members with this role can access files in the current resource group that is being created.</li>
	            <li>Active checkbox is to activate or deactivate the resource group. Users cannot access files of an inactive resource group. This checkbox can be used to make the resource group active for certain period only.</li>
	        </ul>
	    </p>
	    <h4>4. Edit Secure Resource Group</h4>
	    <p>
	        <ul>
	            <li>Click on the Edit icon in the Resoure Group Row that you want to edit<br><img src="https://naft.org.au/wp-content/uploads/2019/07/edit_resource_group.jpg" width="500px"></li>
	            <li>It opens a form in popup. Make necessary changes and submit the form</li>
	        </ul>
	    </p>
	    <h4>5. Create a protected page with links to resources</h4>
	    <p>
	        <ul>
	            <li>Hover over <span class="highlight">Pages</span> menu and click on <span class="highlight">Add New</span></li>
	            <li>Enter title of the page</li>
	            <li>Go to the bottom of the page and in <span class="highlight">Content Permissions</span> section check the User Role you created before. For example: Bac Blanc 2019</li>
	            <li>For the content of the page, go to the editor and to add the link of the secure resource first go to Secure Resources page</li>
	            <li>click on the <span class="highlight">Show Files</span> icon<br><img src="https://naft.org.au/wp-content/uploads/2019/07/show_files.jpg" width="500"></li>
	            <li>It opens a popup with a list of files in the resource group along with the <span class="highlight">shortcode</span> and link of the file</li>
	            <li>Shortcodes are Wordpress way to generate HTML for an element. In this case, when embedded in a page, it generates a secure link for the resource</li>
	            <li>Copy the shortcode of the file you want to add to the page<br><img src="https://naft.org.au/wp-content/uploads/2019/07/shortcode.jpg" width="500"></li>
	            <li>Paste it in the editor and where it says <span class="highlight">Enter text here</span> replace it with whatever text you want to appear on the page. For example: Click here to download xxxxx.zip file</li>
	            <li>Do this for all files that you want to include in the page and save the page</li>
	            <li>This is the page you will be sending to members to allow them to download files</li>
	            <li>Members need to be logged in and have proper role/permission to be able to download resources</li>
	            <li>Resources can be downloaded only once.</li>
	        </ul>
	    </p>
	    <h4>6. Reset File Downloads</h4>
	    <p>
	        <ul>
	            <li>In situations where you need to reset the downloads for members, follow below steps</li>
	            <li>Go to <span class="highlight">Secure Resources</span> admin page</li>
	            <li>In the <span class="highlight">Resource Group</span> row that you are interested in, click the <span class="highlight">Reset download</span> icon<br><img src="https://naft.org.au/wp-content/uploads/2019/07/reset_downloads.jpg" width="500"></li>
	            <li>In the popup, search for the user for whom you want to reset download<br><img src="https://naft.org.au/wp-content/uploads/2019/07/search_user.jpg" width="500"></li>
	            <li>Click the <span class="highlight">Select</span> button of the user you are interested in.<br><img src="https://naft.org.au/wp-content/uploads/2019/07/select_user.jpg" width="500"></li>
	            <li>It will show list of files that were downloaded by the selected member<br><img src="https://naft.org.au/wp-content/uploads/2019/07/reset_download_files.jpg" width="500"></li>
	            <li>click on <span class="highlight">Reset</span> button next to the files, that you want to reset. It will remove those files from downloaded list and the member should be able to download the file again.</li>
	        </ul>
	    </p>
	</div>
</div>