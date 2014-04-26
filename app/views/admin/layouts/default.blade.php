<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />

	<title>
	@section('title')
		{{{ Lang::get('core.administration') }}}
	@show
	</title>

	<meta name="keywords" content="@yield('keywords')"/>
	<meta name="author" content="@yield('author')"/>
	<meta name="description" content="@yield('description')"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<meta name="csrf-token" content="{{{ csrf_token() }}}"/>
	<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}"/>
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}"/>
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}"/>
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}"/>
	<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}"/>

	@include('admin/css')

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.0/jquery.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="//cdn.jsdelivr.net/bootstrap.wysiwyg/0.1/bootstrap-wysiwyg.min.js"></script>
	<script src="{{{ asset('assets/js/bootstrap-wysiwyg-start.js') }}}"></script>
	<script src="{{{ asset('assets/js/jquery.poller.js') }}}"></script>
	<script type="text/javascript">
		/* setup poller with the url */
		$.fn.poller({'url':'{{{ URL::to('admin/polling') }}}'});
	</script>

	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	@include('admin/navigation')

	<div class="visible-xs modal-content mobile-loading"><div class="">@include('admin/dt-loading')</div></div>

	@yield('breadcrumb')

	@include('notifications')
	
	<div class="container-fluid">
		@yield('content')
	</div>
	
	<footer class="clearfix">
		@yield('footer')
	</footer>

	@include('admin/js')

	<!-- default modal dialog -->
	<div id="site-modal" class="modal fade" tabindex="-2" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<!-- search modal dialog -->
	<div id="search-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel2" aria-hidden="true" style="z-index: 1041;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Search</h4>
				</div>
				<div class="modal-body">
					
					 <div class="input-group">
						  <input type="text" class="form-control search-input" placeholder="What are you searching for?">
						  <span class="input-group-btn">
							<button class="btn btn-default" type="button">
							<span class="fa fa-search"></span>
						 </button>
						 </span>
					</div>	
					<br/>
					<div id="site-search-results">

					</div>				
				</div>
				<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		$('.nav-search').on('click', function(e){
			e.preventDefault();    
			$('#search-modal').modal();
		});

		$("#search-modal").on('shown.bs.modal', function() {
			$('.search-input').focus();
		});
		
		$('.search-input').keyup(throttle(function(e){
			var _val=$(this).val();
			if(_val.length < 3) return false;
				$('#site-search-results').html('');
				$.ajax({
					type: 'GET',
					url:'{{{ URL::to('admin/search') }}}/'+_val
				}).done(function(msg) {
					if(msg){
						$('#site-search-results').html(msg);
					} else $('#site-search-results').html('<h3>No results</h3>');
				}).fail(function(jqXHR, textStatus) {
						console.log(jqXHR);
						$('#site-search-results').html('<h3>Unable to execute command</h3>');
				});
		}, 700));

	</script>
	<div id="poller"></div>

    <script type="text/javascript">

		/* call back from results */
		function fnUpdateGrowler(id, args){
			$.each(args, function(i,value){
				if(value != '') $.bootstrapGrowl(value.details, { type: value.description });
			});
		}

		$.fn.poller('add',{'id':'logs', 'type':'check_logs', 'ratio': '1'});
		$.fn.poller('run');

		$('.mobile-loading').removeClass('visible-xs').hide();
    </script>
</body>
</html>