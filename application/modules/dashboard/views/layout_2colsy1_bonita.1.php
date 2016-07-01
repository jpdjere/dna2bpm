<?php 
/* 
 *  Header : CSS Load & some body
 * 
 */
include('_header.php')

?>
<header>
	<style type="text/css">
		body {overflow-x: scroll;}
	</style>
</header>
	<div class="wrapper row-offcanvas row-offcanvas-left hidden-print" style="overflow-x: scroll">
		<!-- Wrapper -->

		<!-- ======== MENU LEFT ======== -->
		<aside class="left-side sidebar-offcanvas">
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
		<aside class="right-side">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>{title}</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa {icon}"></i> Home</a></li>
					<li class="active">{title}</li>
				</ol>
			</section>

			<section class="content">
				<div class="full-height">
				{tiles}
				</div>

				{alerts}


				<div id="tiles_after">
					<section class="col-lg-12 connectedSortable ui-sortable">
						{tiles_after}</section>
				</div>


				<section class="col-lg-10 connectedSortable ui-sortable" id="col1">
					{col1}</section>
				<section class="col-lg-4 connectedSortable ui-sortable" id="col2">
					{col2}</section>
				</section>
                        			
                        <section class="col-lg-12 connectedSortable ui-sortable" id="col3" style="margin-top: 10px">
					{col3}
                      </section>
		
	
	</div>


	</aside>
	<!-- ++++++++ CENTRO  -->
	</div>
	<!-- /Wrapper -->
	
{config_panel} 
	
<?php 
/* 
 *  FOOTER 
 * 
 */
include('_footer.php')

?>
