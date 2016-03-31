<?php 
/* 
 *  Header : CSS Load & some body
 * 
 */
include('_header.php')

?>


	<div class="wrapper row-offcanvas row-offcanvas-left hidden-print">
		<!-- Wrapper -->

		<!-- ======== MENU LEFT ======== -->
		<aside class="left-side sidebar-offcanvas collapse-left">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- /.search form -->
				<!-- sidebar menu: : style can be found in sidebar.less -->
				{menu}
			</section>
			<!-- /.sidebar -->
		</aside>
		<!-- ++++++++ MENU LEFT  -->

		<!-- ======== CENTRO ======== -->
		<aside class="right-side strech">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>{title}</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa {icon}"></i> Home</a></li>
					<li class="active">{title}</li>
				</ol>
			</section>

			<section class="content">
			
				<div id="tiles">
					<section class="col-lg-12">
						{tiles}</section>
				</div>
				
				{alerts}


				<div id="tiles_after">
					<section class="col-lg-12">
						{tiles_after}</section>
				</div>
			
				<section class="col-lg-6" id="col1">
					{col1}
				</section>
				<section class="col-lg-6" id="col2">
					{col2}
				</section>
				
				<div id="tiles_bottom">
					<section class="col-lg-12">
						{tiles_bottom}</section>
				</div>
				
			</section>
	
	</div>


	</aside>
	<!-- ++++++++ CENTRO  -->
	</div>
	<!-- /Wrapper -->

	
<?php 
/* 
 *  FOOTER 
 * 
 */
include('_footer.php')

?>
