<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>PortalEkampus v5.0 - LOGIN</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		<link href="{!! asset('/css/icons/icomoon/styles.min.css') !!}" rel="stylesheet" type="text/css">
		<link href="{!! asset('/css/all.min.css') !!}" rel="stylesheet" type="text/css">
	</head>

	<body class="bg-primary">	
		<div class="navbar navbar-expand-lg navbar-dark navbar-static">
			<div class="navbar-brand ml-2 ml-lg-0">
				<a href="{!! route('frontend-dashboard.index') !!}" class="d-inline-block">
					PortalEkampus
				</a>
			</div>
			<div class="d-flex justify-content-end align-items-center ml-auto">
				<ul class="navbar-nav flex-row">				
					<li class="nav-item">
						<a href="{!! route('frontend-dashboard.index') !!}" class="navbar-nav-link">
							<i class="icon-home4"></i>
							<span class="d-none d-lg-inline-block ml-2">Home</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{!! route('register') !!}" class="navbar-nav-link">
							<i class="icon-user-plus"></i>
							<span class="d-none d-lg-inline-block ml-2">Register</span>
						</a>
					</li>					
				</ul>
			</div>
		</div>	
		<div class="page-content">		
			<div class="content-wrapper">			
				<div class="content-inner">				
					<div class="content d-flex justify-content-center align-items-center">											
						{!! Form::open(['url'=>route('login'), 'method'=>'post', 'class'=>'login-form', 'id'=>'frmlogin', 'name'=>'frmlogin']) !!}
							<div class="card mb-0">
								<div class="card-body">
									<div class="text-center mb-3">
										<i class="icon-reading icon-2x text-secondary border-secondary border-3 rounded-pill p-3 mb-3 mt-1"></i>
										<h5 class="mb-0">Login ke PortalEkampus</h5>
										<span class="d-block text-muted">Masukan username dan password</span>
									</div>

									<div class="form-group form-group-feedback form-group-feedback-left">										
										{{ Form::text('username', old('username'), ['class'=>'form-control', 'id'=>'login-username', 'placeholder'=>'Username', 'aria-describedby'=>'login-username', 'autofocus'=>'', 'tabindex'=>1]) }}
										<div class="form-control-feedback">
											<i class="icon-user text-muted"></i>
										</div>
									</div>

									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="password" class="form-control" placeholder="Password">
										<div class="form-control-feedback">
											<i class="icon-lock2 text-muted"></i>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-block">Login</button>
									</div>

									<div class="text-center">
										<a href="login_password_recover.html">Forgot password?</a>
									</div>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
		
		<script src="{!! asset('/js/main/jquery.min.js') !!}"></script>
		<script src="{!! asset('/js/main/bootstrap.bundle.min.js') !!}"></script>
		<script src="{!! asset('/js/app.js') !!}"></script>
		<script src="{!! asset('/js/portalekampus5.js') !!}"></script>		
	</body>
</html>

