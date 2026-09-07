<!doctype html>
<html lang="th">
	<head>
		<meta http-equiv="Content-Language" content="th"> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- View Port -->
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<!-- Robots -->
		<meta name="robots" content="files,pic,nofollow" />
		<?php 
			$isIndex = $isIndex ?? false;
		?>
		<!-- Meta Title -->
		<title>Sahamongkhon - <?php echo $title ?? 'Personal Accident Insurance' ?></title>
		<!-- Favicon Group -->
		<link rel="icon" type="image/png" sizes="32x32" href="images/LOGO-UPP2025_125x125.png">

		<!-- Bootstrap 5.0.2 -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
		
		
		<!-- Main CSS -->
		<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" />

		<!-- Google Font APIs -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&family=Kanit:wght@400;600;700&family=Mitr:wght@400;500;700&display=swap" rel="stylesheet">	

		<!-- Toastr -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		
		<!-- Select 2 -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

		<!-- CSRF Token -->
		<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
		<?php
			$csrf = $_SESSION['csrf_token'];
		?>
	</head>
	<body class="d-flex flex-column min-vh-100">
		<!-- Bootstrap 5.0.2 -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		
		<!-- JQuery JS -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- Select 2 -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<!-- Toastr Alert -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<script>
			function getData(url, data, hasLoadingOverlay = true) {
                if (hasLoadingOverlay) {
                    $('#loadingOverlay').removeClass('d-none');
                }
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': "{{ $csrf}}"
                        },
                        success: function(response, textStatus, jqXHR) {
                            resolve({
                                data: response.data,
                                status: jqXHR.status
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            reject({
                                status: jqXHR.status,
                                error: errorThrown,
                                response: jqXHR.responseJSON || jqXHR.responseText
                            });
                        },
                        complete: function() {
                            if (hasLoadingOverlay) { 
                                $('#loadingOverlay').addClass('d-none');
                            }
                        }
                    });
                });
            }

            function postData(url, data) {
                $('#loadingOverlay').removeClass('d-none');
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: JSON.stringify(data || {}),
                        headers: {
                            'X-CSRF-TOKEN': "{{ $csrf }}"
                        },
                        success: function (response, textStatus, jqXHR) {
                            resolve({
                                data: response.data ?? response,
                                status: jqXHR.status
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            reject({
                                status: jqXHR.status,
                                error: errorThrown,
                                response: jqXHR.responseJSON || jqXHR.responseText
                            });
                        },
                        complete: function () {
                            $('#loadingOverlay').addClass('d-none');
                        }
                    });
                });
            }
		</script>
		<main class="content flex-grow-1">
			<!-- Header / Navigation (Bootstrap 5) -->
			<header class="mi-header sticky-top bg-secondary">
				<nav class="navbar navbar-expand-lg navbar-light py-3">
					<div class="container">
						<!-- Logo (left) -->
						<a class="navbar-brand d-flex align-items-center gap-2" href="">
							<img src="images/LOGO-UPP2025_125x125.png" alt="Sahamongkhon Compulsory Motor Insurance" width="48" height="48" class="mi-logo">
							<div class="d-flex flex-column">
								<h3 class="mi-brand-text mb-0">CPL Online</h3>
								<span class="mi-brand-subtext">Sahamongkhon Public Company Limited</span>
							</div>
						</a>
	
						<button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
							aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
	
						<div class="collapse navbar-collapse" id="mainNav">
							<!-- Center links -->
							<ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-1">
								<li class="nav-item">
									<a class="nav-link mi-link active" href="#">บ้าน</a>
								</li>
								<li class="nav-item">
									<a class="nav-link mi-link" href="#">เกี่ยวกับ</a>
								</li>
								<li class="nav-item">
									<a class="nav-link mi-link" href="#">สินค้า</a>
								</li>
								<li class="nav-item">
									<a class="nav-link mi-link" href="#">ติดต่อ</a>
								</li>
								<li class="nav-item">
									<a class="nav-link mi-link mi-agent" href="index.php?op=agent-portal">ศูนย์ตัวแทน</a>
								</li>
							</ul>
	
							<!-- Right contact -->
							<div class="d-flex flex-column flex-lg-row flex-md-column align-items-lg-center gap-2 mt-3 mt-lg-0" id="navContact">
								<a href="tel:+026877777" class="mi-contact">โทรศัพท์. 02-68-77777</a>
								<a href="mailto:uwmotor@sahainsurance.co.th" class="mi-contact">อีเมล: uwmotor@sahainsurance.co.th</a>
							</div>
						</div>
					</div>
				</nav>
			</header>
	
			<section>
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							
						</div>
					</div>
				</div>
			</section>
        </main>

		<!-- Loading Overlay -->
		<div id="loadingOverlay" class="position-fixed d-none text-center" style="top:50%; left:50%; transform:translate(-50%, -50%); background:#e0e0e0; border-radius:8px; padding:16px 20px; z-index:1055;">
			<div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
			<div class="mt-2 small text-muted">Loading...</div>
		</div>

		<footer class="mi-footer mt-auto">
			<div class="container py-5">
				<div class="row g-4">
					<div class="col-md-5 col-sm-12">
						<div class="d-flex align-items-center gap-3 mb-3">
							<img src="images/LOGO-UPP2025_125x125.png" alt="Sahamongkhon Compulsory Motor Insurance" width="52" height="52" class="mi-footer-logo">
							<div>
								<h4 class="mi-footer-title mb-1">CPL Online</h4>
								<p class="mi-footer-text mb-0">Sahamongkhon Public Company Limited</p>
							</div>
						</div>
						<p class="mi-footer-text mb-0">
							ประกันภัยที่เชื่อถือได้ บริการรวดเร็ว คุ้มครองมั่นใจทุกการเดินทาง
						</p>
					</div>

					<div class="col-md-3">
						<h5 class="mi-footer-heading">เมนูลัด</h5>
						<ul class="list-unstyled mi-footer-list mb-0">
							<li><a href="#">บ้าน</a></li>
							<li><a href="#">เกี่ยวกับ</a></li>
							<li><a href="#">สินค้า</a></li>
						</ul>
					</div>

					<div class="col-md-4">
						<h5 class="mi-footer-heading">ติดต่อ</h5>
						<ul class="list-unstyled mi-footer-list mb-0">
							<li><a href="tel:+66026877777" target="_blank"><i class="bi bi-telephone me-2"></i>02-68-77777</a></li>
							<li><a href="mailto:uwmotor@sahainsurance.co.th" target="_blank"><i class="bi bi-envelope me-2"></i>uwmotor@sahainsurance.co.th</a></li>
							<li><a href="https://goo.gl/maps/1TgaPx9b9wNwqot67" target="_blank"><i class="bi bi-geo-alt me-2"></i>กรุงเทพฯ, ประเทศไทย</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="mi-footer-bottom">
				<div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 py-3">
					<p class="mb-0">&copy; <?php echo date('Y'); ?> Sahamongkhon Public Company Limited. All rights reserved.</p>
					<p class="mb-0">Developed by IT Team UPP.CO.TH</p>
				</div>
			</div>
		</footer>
	</body>
</html>	