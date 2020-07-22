<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>Havtech EventsHUB</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{ mix("assets/stylesheets/styles.css") }}" />
	<link href="{{ asset('/css/style.css') }}" rel="stylesheet">
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js">		</script>!-->
	<script src="//code.jquery.com/jquery-3.3.1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>	<!-- fancy box!-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
</head>
<body>
	@yield('body')


	@yield('css')
	<script src="{{ mix("assets/scripts/frontend.js") }}" type="text/javascript"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!-- #region datatables files -->

	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css " />
	
	<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

	<script src="//cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js "></script>

	<!-- #endregion -->
	<!-- include summernote css/js -->
	<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
	<script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
	<!-- include timepickers !-->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

	
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	@yield('js')
</body>
</html>
