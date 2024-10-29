<div class="amazing-linker-wrap">

	<img src="<?php echo AMAZING_LINKER_PLUGIN_URL ?>admin/images/amazing-linker-logo.png" alt="amazing-linker-logo.png" style="width: 90px;margin-top: 25px;">
	
	<div><?php settings_errors(); ?></div>

	<div class="amazing-linker-tab">

		<ul class="amazing-linker-tabs">
			<li><a href="#"><span class="dashicons dashicons-id" style="vertical-align: sub;"></span>Credentials</a></li>
			<li><a href="#"><div class="amazing-linker-amazon-icon"></div> Amazon Associates</a></li>
			<li><a href="#"><span class="dashicons dashicons-admin-generic" style="vertical-align: sub;"></span> Settings</a></li>
			<li><a href="#"><span class="dashicons dashicons-clipboard" style="vertical-align: sub;"></span> Free VS Pro</a></li>
		</ul> <!-- / tabs -->

		<div class="amazing-linker-tab-content">

			<div class="amazing-linker-tabs-item">
				<form method="post" action="options.php" enctype="multipart/form-data">
					<?php 
						settings_fields( 'amazing_linker_credential_tab_section' );
						do_settings_sections( 'amazing_linker_credential_tab' );
						submit_button();
					?>
				</form>
			</div> <!-- / tabs_item -->

			<div class="amazing-linker-tabs-item">
				<form method="post" action="options.php" enctype="multipart/form-data">
					<?php 
						settings_fields( 'amazing_linker_associate_tab_section' );
						do_settings_sections( 'amazing_linker_associate_tab' );
						submit_button();
					?>
				</form>
			</div> <!-- / tabs_item -->

			<div class="amazing-linker-tabs-item">
				<form method="post" action="options.php" enctype="multipart/form-data">
					<?php 
						settings_fields( 'amazing_linker_settings_tab_section' );
						do_settings_sections( 'amazing_linker_settings_tab' );
						submit_button();
					?>
				</form>
			</div> <!-- / tabs_item -->
			<div class="amazing-linker-tabs-item">
				<table width="100%">
                    <tr >
                        <th style="padding: 20px 20px 20px 10px;font-size: 18px;text-align: left;" width="50%">Features</th>
                        <th width="25%" style="text-align: center;font-size:18px">Free</th>
                        <th width="25%" style="text-align: center;font-size:18px">PRO</th>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Horizontal List</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-yes"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Button Link</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-yes"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Image Link</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-yes"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Image Link with image width</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-yes"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Text Link</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-yes"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Product Widget</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-yes"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Horizontal List With Ribbon</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Vertical List</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Product Summary Box</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Product Feature Box</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Product Review Box</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Similar Products</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    
                    <tr>
                        <td class="amazing-linker-proFree-feature">Image Link with align</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    
                    <tr>
                        <td class="amazing-linker-proFree-feature">Product Box</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Product Box with align</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Horizontal New Release list</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Vertical New Release list</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Horizontal Bestseller list</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Vertical Bestseller list</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Super fast comparison table maker</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Advanced Search Widget</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    
                    <tr>
                        <td class="amazing-linker-proFree-feature">Bestseller Widget</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td class="amazing-linker-proFree-feature">Newrelease Widget</td>
                        <td class="amazing-linker-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
                        <td class="amazing-linker-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tfoot>
                        <tr>
                            <td class="amazing-linker-proFree-feature"></td>
                            <td class="amazing-linker-proFree-free"></td>
                            <td class="amazing-linker-proFree-pro"><a href="https://coderockz.com/downloads/amazing-linker/" target="_blank"><button class="amazing-linker-buy-now-btn">Buy Now</button></a></td>
                        </tr>
                    </tfoot>
                </table>
			</div> <!-- / tabs_item -->
		</div> <!-- / tab_content -->
	</div> <!-- / tab -->

</div>