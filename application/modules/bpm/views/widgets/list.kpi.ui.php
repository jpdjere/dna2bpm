<div class="box box-info">
	<div class="box-header" style="cursor: move;">
		<i class="ion {kpi icon}"></i>
		<h3 class="box-title">{kpi title} ({qtty})</h3>
		<div class="box-tools pull-right">
			<button class="btn btn-default btn-sm" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
			<button class="btn btn-default btn-sm" data-widget="remove">
				<i class="fa fa-times"></i>
			</button>
		</div>

	</div>
	<!-- /.box-header -->
	<p>{kpi desc}</p>
	{if {showPager}}
	<ul class="pagination pagination-sm inline">
		<li><a href='#'> {start} {lang to} {top} </a></li> {pages}
		<li><a href="{url}" class="reload_widget {class}">{title}</a></li>
		{/pages}
		<li><a href="#">»</a></li>
	</ul>
	{/if}
	<div class="box-body">{content}</div>
	<!-- /.box-body -->
	<div class="box-footer clearfix no-border">
		{footer}
		<!--        <button class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>-->
	</div>
</div>