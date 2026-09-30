<!doctype html>
<html lang="th">
	<head>
		<meta http-equiv="Content-Language" content="th">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- View Port -->
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<!-- Robots -->
		<meta name="robots" content="files,pic,nofollow" />

		<!-- Meta Title -->
		<title>@yield('title', 'Personal Accident Insurance') | Sahamongkhon</title>
		<!-- Favicon Group -->
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/saha_logo.png') }}">

		<link href="{{ asset('sitesaha/assets/css/vendor.min.css') }}?v={{ filemtime(public_path('sitesaha/assets/css/vendor.min.css')) }}" rel="stylesheet" />

		<!-- Bootstrap 5.0.2 -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
		
		
		<!-- Main CSS -->
		<link href="{{ asset('css/style.min.css') }}?v={{ filemtime(public_path('css/style.min.css')) }}" rel="stylesheet" />
		<link href="{{ asset('sitesaha/assets/css/style.css') }}?v={{ filemtime(public_path('sitesaha/assets/css/style.css')) }}" rel="stylesheet" />
		<link href="{{ asset('sitesaha/assets/css/header.css') }}?v={{ filemtime(public_path('sitesaha/assets/css/header.css')) }}" rel="stylesheet" />
		<link href="{{ asset('sitesaha/assets/css/feather.css') }}?v={{ filemtime(public_path('sitesaha/assets/css/feather.css')) }}" rel="stylesheet" />
		<link href="{{ asset('sitesaha/assets/css/footer.css') }}?v={{ filemtime(public_path('sitesaha/assets/css/footer.css')) }}" rel="stylesheet" />

		<!-- Google Font APIs -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">

		<!-- Toastr -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		
			<!-- Select 2 -->
			<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

			<!-- JQuery UI -->
			<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

		<!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
	</head>
	@php
		$companyWebsiteUrl = 'http://192.6.1.199:8000/';
		$lang = 'th';
	@endphp
	<body class="d-flex flex-column min-vh-100">
		<!-- Bootstrap 5.0.2 -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		
			<!-- JQuery JS -->
			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
			<script src="{{ asset('sitesaha/assets/js/bootstrap/js/bootstrap.bundle.min.js') }}?v={{ filemtime(public_path('sitesaha/assets/js/bootstrap/js/bootstrap.bundle.min.js')) }}"></script>
			<script src="{{ asset('sitesaha/assets/js/metismenu/metisMenu.min.js') }}?v={{ filemtime(public_path('sitesaha/assets/js/metismenu/metisMenu.min.js')) }}"></script>
			<script src="{{ asset('sitesaha/assets/js/feather-icons/feather.min.js') }}?v={{ filemtime(public_path('sitesaha/assets/js/feather-icons/feather.min.js')) }}"></script>

			<!-- JQuery UI -->
			<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

			<!-- Select 2 -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<!-- Toastr Alert -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<script>
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                            'X-CSRF-TOKEN': token
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
                            'X-CSRF-TOKEN': token
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
		<!-- Header / Navigation (Bootstrap 5) -->
		<!-- topbar (Sarabun) -->
			<div class="topbar font-sarabun">
				<div class="container custom-container d-flex" id="top-nav">
					<nav class="nav nav-lang">
						<a title="Facebook" class="nav-link pe-2 ps-0" href="https://www.facebook.com/UnionProspersInsurance/" target="_blank">
							<img src="{{ asset('sitesaha/assets/img/iconfacebook3.png') }}" width="30" alt="Facebook image">
						</a>
						<a title="Line" class="nav-link pe-2 ps-0" href="https://lin.ee/y2PaZts" style="color:#73F781" target="_blank">
							<img src="{{ asset('sitesaha/assets/img/iconline3.png') }}" width="30" alt="Line image">
						</a>
						<a title="TikTok" class="nav-link pe-2 ps-0" href="https://www.tiktok.com/@uppthailand" target="_blank">
							<img src="{{ asset('sitesaha/assets/img/icontt.png') }}" width="30" alt="Tiktok image">
						</a>
					</nav>
                    <nav class="nav nav-lang ms-auto d-flex align-items-center">
                        <a class="nav-link" href="https://vlnonline.sahainsurance.co.th/" target="_blank">สมัครใจ Online</a>
                        <a class="nav-link" style="color:white">|</a>
                        <a class="nav-link" href="https://cplonline.sahainsurance.co.th/" target="_blank">พ.ร.บ. Online</a>
                        <a class="nav-link" style="color:white">|</a>
                        <div class="nav-item dropdown dropdown-hover text-white" id="language">
                            <a class="nav-link dropdown-toggle forwardable" data-toggle="dropdown" id="languageDropDownBtn" href="javascript:void(0);" role="button" aria-haspopup="true" aria-expanded="false">
                                Language - TH <i data-feather="chevron-down" class="text-white"></i>
                            </a>
                            <div class="dropdown-menu" id="languageDropDownMenu">
                                <a class="dropdown-item" href="http://192.6.1.94:8003/proc.php?action=products&amp;lang=th">
                                    <img src="https://flagsapi.com/TH/flat/32.png" alt="Thai flag" class="mx-1">Thai
                                </a>
                                <a class="dropdown-item" href="http://192.6.1.94:8003/proc.php?action=products&amp;lang=en">
                                    <img src="https://flagsapi.com/GB/flat/32.png" alt="English flag" class="mx-1">English
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        <!-- header (Sarabun) -->
        <header class="font-sarabun">
            <div class="container custom-container">
                <a class="nav-link nav-icon ml-ni nav-toggler mr-3 d-flex d-lg-none" href="#" data-toggle="modal" data-target="#menuModal" aria-label="Toggle navigation">
                    <i data-feather="menu"></i>
                </a>
                <div class="clearfix">
                    <div class="logo-img">
                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/" rel="home" title="บริษัท สหมงคลประกันภัย จำกัด (มหาชน)" class="active">
                            <img class="nav-logo" src="{{ asset('sitesaha/assets/img/logo-upp-header.png') }}" alt="บริษัท สหมงคลประกันภัย จำกัด (มหาชน)" id="logo">
                        </a>
                    </div>
                </div>
                <ul class="nav nav-main ms-auto d-none d-lg-flex">
                    <li class="nav-item dropdown dropdown-hover">
                        <a class="nav-link dropdown-toggle forwardable" data-toggle="dropdown" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts" role="button" aria-haspopup="true" aria-expanded="false">
                            รู้จักเรา <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision">วิสัยทัศน์และพันธกิจ</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus">ฐานะทางการเงิน</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo">งบการเงิน</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/report">รายงานประจำปี</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts">เกี่ยวกับเรา</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa">การคุ้มครองข้อมูลส่วนบุคคล</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover dropdown-mega">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            บริการลูกค้า
                        <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>บริการด้านสินไหมรถยนต์</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance" class="list-group-item list-group-item-action">- เอกสารเบิกค่าสินไหมทดแทน</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/58" class="list-group-item list-group-item-action">- บริการด้านสินไหมรถยนต์</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/59" class="list-group-item list-group-item-action">- ขั้นตอนดำเนินการด้านสินไหม</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>บริการด้านสินไหม Non-motor</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim" class="list-group-item list-group-item-action">- เอกสารเบิกค่าสินไหมทดแทน Non-motor</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/60" class="list-group-item list-group-item-action">- วิธีปฎิบัติเมื่อเกิดอุบัติเหตุหรือเกิดความเสียหาย</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/61" class="list-group-item list-group-item-action">- ขั้นตอนดำเนินการด้านสินไหม</a>
                                    </div>
                                </div>
                                <div class="col-lg-4 border-start">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage" class="list-group-item list-group-item-action"><strong>รายชื่ออู่ซ่อมรถ</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/hospital" class="list-group-item list-group-item-action"><strong>รายชื่อสถานพยาบาล, คลินิก</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/accident" class="list-group-item list-group-item-action"><strong>ศูนย์รับแจ้งอุบัติเหตุ</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/Service_Level_Agreement" class="list-group-item list-group-item-action"><strong>ระยะเวลาการให้บริการของบริษัทประกันวินาศภัย (Service Level Agreement : SLA)</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover dropdown-mega">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            ผลิตภัณฑ์
                        <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>ประกันรถยนต์</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=1" class="list-group-item list-group-item-action">ประกันภัยรถยนต์ ประเภท 1,3,5</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=2" class="list-group-item list-group-item-action">ประกันภัยรถยนต์ (พ.ร.บ.)</a>
                                        <a href="http://192.6.1.94:8003/proc.php?action=products&lang=th" class="list-group-item list-group-item-action">ซื้อประกันภัยรถยนต์ พ.ร.บ.</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>ประกันภัยอื่นๆ</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance" class="list-group-item list-group-item-action">การประกันอัคคีภัย</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance" class="list-group-item list-group-item-action">การประกันภัยเบ็ดเตล็ด</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance" class="list-group-item list-group-item-action">การประกันภัยทางทะเลและขนส่ง</a>
                                    </div>
                                </div>
                                <div class="col-lg-4 border-start">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop" class="list-group-item list-group-item-action"><strong>สินค้าจำหน่ายตัวแทน</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads" class="list-group-item list-group-item-action"><strong>ดาวน์โหลด</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy" class="list-group-item list-group-item-action"><strong>การตรวจสอบเลขกรมธรรม์</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover">
                        <a class="nav-link dropdown-toggle forwardable" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            ตัวแทน/นายหน้า                        <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/agent">การสมัครตัวแทน</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62">การชำระค่าเบี้ยประกัน</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts">
                        ติดต่อเรา                    </a>
                    </li>
                </ul>
            </div>
        </header>
        <!-- end header -->

		<main class="content">
			@yield('content')
        </main>

		<!-- Loading Overlay -->
		<div id="loadingOverlay" class="position-fixed d-none text-center" style="top:50%; left:50%; transform:translate(-50%, -50%); background:#e0e0e0; border-radius:8px; padding:16px 20px; z-index:1055;">
			<div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
			<div class="mt-2 small text-muted">Loading...</div>
		</div>

		{{-- Menu Model --}}
		<div class="footer mt-auto font-sarabun" id="footerSection">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-sm-6 col-lg-3 text-center px-3">
						<img src="{{ asset('sitesaha/assets/img/logo-upp2025.svg') }}" alt="บริษัท สหมงคลประกันภัย จำกัด (มหาชน)" height="200">
					</div>
					<div class="col-6 col-lg-3">
						<h6 class="bold">เกี่ยวกับเรา UPP</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts" class="list-group-item list-group-item-action">- ประวัติศาสตร์</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision" class="list-group-item list-group-item-action">- วิสัยทัศน์และพันธกิจ</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus" class="list-group-item list-group-item-action">- ฐานะทางการเงิน</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo" class="list-group-item list-group-item-action">- งบการเงิน</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/report" class="list-group-item list-group-item-action">- รายงานประจำปี</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa" class="list-group-item list-group-item-action">- การคุ้มครองข้อมูลส่วนบุคคล</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/career" class="list-group-item list-group-item-action">- สมัครงาน</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts" class="list-group-item list-group-item-action">- ที่ตั้งสำนักงาน</a>
							<a href="https://outlook.office.com/" target="_blank" class="list-group-item list-group-item-action">- เว็บเมล</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads" class="list-group-item list-group-item-action">- ดาวน์โหลด</a>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<h6 class="bold">ผลิตภัณฑ์</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance" class="list-group-item list-group-item-action">ประกันรถยนต์</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance" class="list-group-item list-group-item-action">การประกันอัคคีภัย</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance" class="list-group-item list-group-item-action">การประกันภัยเบ็ดเตล็ด</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance" class="list-group-item list-group-item-action">การประกันภัยทางทะเลและขนส่ง</a>
						</div>
						<h6 class="bold">บริการลูกค้า</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-5">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance" class="list-group-item list-group-item-action">- เอกสารด้านสินไหมรถยนต์</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim" class="list-group-item list-group-item-action">- เอกสารด้านสินไหม Non-motor</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/appeal" class="list-group-item list-group-item-action">- รับเรื่องร้องเรียน</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage" class="list-group-item list-group-item-action">- รายชื่ออู่ซ่อมรถ</a>
						</div>
					</div>
					<div class="col-sm-6 col-lg-3">
						<h6 class="bold">ข่าวสารและโปรโมชั่น</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/news" class="list-group-item list-group-item-action">- ข่าวประชาสัมพันธ์</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop" class="list-group-item list-group-item-action">- สินค้าจำหน่ายตัวแทน</a>
						</div>
						<h6 class="bold">ตัวแทน/นายหน้า</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/agent" class="list-group-item list-group-item-action">- การสมัครตัวแทน</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62" class="list-group-item list-group-item-action">- การชำระค่าเบี้ยประกัน</a>
						</div>
						<a href="https://smart.oic.or.th/EService/Menu1" target="_blank" class="hightlighed"><h6>ตรวจสอบข้อมูลของตัวแทน/นายหน้า</h6></a>
						<a href="{{ $companyWebsiteUrl }}{{ $lang }}/tax" target="_blank" class="hightlighed"><h6>หนังสือรับรองการหักภาษี ณ ที่จ่าย</h6></a>
						<a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy" target="_blank" class="hightlighed"><h6>ตรวจสอบเลขกรมธรรม์ พ.ร.บ</h6></a>
						<a href="http://192.6.1.94:8003/proc.php?action=check_policy_form&lang=th" target="_blank" class="hightlighed">
							<h6>
								ตรวจสอบกรมธรรม์ภาคบังคับอีกครั้ง
							</h6>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="copyright font-sarabun">
			<p>บริษัท สหมงคลประกันภัย จำกัด (มหาชน) เลขที่ 7 ซอยสาทร 11 ถนนสาทร แขวงยานนาวา เขตสาทร กรุงเทพ ฯ 10120 โทรศัพท์ 02-68-77777</p>
			<p>SAHAMONGKHON INSURANCE PUBLIC COMPANY LIMITED No.7 Soi Sathorn 11, Sathorn Road, Yannawa Sub - district, Sathorn District, Bangkok 10120 Tel. 02-68-77777</p>
			<a class="nav-link pr-2 pl-0" href="https://www.facebook.com/UnionProspersInsurance/" target="_blank">
				<i data-feather="facebook"></i>
				Official Facebook
			</a>
		</div>
		<div class="modal modal-left modal-menu font-sarabun" id="menuModal" tabindex="-1" role="dialog" aria-label="menuModel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header shadow">
						<a class="h5 mb-0 d-flex align-items-center" href="{{ $companyWebsiteUrl }}{{ $lang }}/">
							<strong>
								เมนู
							</strong>
						</a>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body shadow">
						<ul class="menu" id="menu">
							<li class="no-sub mm-active">
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/">
									<i class="feather-home"></i> 
									หน้าหลัก
								</a>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-at-sign"></i> 
									รู้จักเรา
								</a>
								<ul>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts">
										เกี่ยวกับเรา
										</a>
									</li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision">วิสัยทัศน์และพันธกิจ</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus">ฐานะทางการเงิน</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo">งบการเงิน</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa">การคุ้มครองข้อมูลส่วนบุคคล</a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-shopping-bag"></i> 
									ผลิตภัณฑ์
								</a>
								<ul>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance">ประกันรถยนต์</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance">การประกันอัคคีภัย</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance">การประกันภัยเบ็ดเตล็ด</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance">การประกันภัยทางทะเลและขนส่ง</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop">สินค้าจำหน่ายตัวแทน</a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-layers"></i> 
									บริการลูกค้า
								</a>
								<ul>
									<li>
										<a href="#" class="has-arrow">
											<i class="feather-layers"></i> 
											บริการด้านสินไหมรถยนต์
										</a>
										<ul>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance">-
													เอกสารเบิกค่าสินไหมทดแทน
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/ขั้นตอนดำเนินการด้านสินไหม_58.html">-
													บริการด้านสินไหมรถยนต์
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/บริการด้านสินไหมรถยนต์_59.html">-
													ขั้นตอนดำเนินการด้านสินไหม
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="#" class="has-arrow">
											<i class="feather-layers"></i> 
											บริการด้านสินไหม Non-motor
										</a>
										<ul>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim">-
													เอกสารเบิกค่าสินไหมทดแทน Non-motor
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/วิธีปฎิบัติเมื่อเกิดอุบัติเหตุหรือเกิดความเสียหาย_60.html">
													- วิธีปฎิบัติเมื่อเกิดอุบัติเหตุหรือเกิดความเสียหาย
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/ขั้นตอนดำเนินการด้านสินไหม_61.html">
													- ขั้นตอนดำเนินการด้านสินไหม
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-users"></i> 
									ตัวแทน/นายหน้า
								</a>
								<ul>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/agent">
											การสมัครตัวแทน
										</a>
									</li>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62">
											การชำระค่าเบี้ยประกัน
										</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/appeal">
									<i class="feather-radio"></i> 
									รับเรื่องร้องเรียน
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage">
									<i class="feather-settings"></i> 
									รายชื่ออู่ซ่อมรถ
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/hospital">
									<i class="feather-settings"></i> 
									รายชื่อสถานพยาบาล, คลินิก
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/accident">
									<i class="feather-settings"></i> 
									ศูนย์รับแจ้งอุบัติเหตุ
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/Service_Level_Agreement">
									<i class="feather-settings"></i>
									ระยะเวลาการให้บริการของบริษัทประกันวินาศภัย  (Service Level Agreement : SLA)
								</a>
							</li>
							<li>
								<a href="https://smart.oic.or.th/EService/Menu1" target="_blank">
									<i class="feather-corner-up-right"></i> 
									ตรวจสอบข้อมูลของตัวแทน/นายหน้า
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts">
									<i class="feather-at-sign"></i> 
									ติดต่อเรา
								</a>
							</li>
							<li>
								<a href="https://outlook.office.com/" target="_blank">
									<i class="feather-pocket"></i> 
									เว็บเมล
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads">
									<i class="feather-download-cloud"></i> 
									ดาวน์โหลด
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/tax">
									<i class="feather-corner-up-right"></i> 
									หนังสือรับรองการหักภาษี ณ ที่จ่าย ( จ่าย )
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy">
									<i class="feather-corner-up-right"></i> 
									การตรวจสอบเลขกรมธรรม์
								</a>
							</li>
							<li>
								<a href="http://192.6.1.94:8003/proc.php?action=check_policy_form&lang=th">
									<i class="feather-corner-up-right"></i> 
									ตรวจสอบกรมธรรม์ภาคบังคับอีกครั้ง
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /Menu Modal -->
		<script>
			jQuery(function () {
				if (jQuery.fn.metisMenu) {
					jQuery('#menu').metisMenu().on('show.metisMenu', function () {
						jQuery('.no-sub').removeClass('mm-active');
					});
				}

				jQuery('.dropdown-hover').hover(function () {
					if (jQuery(window).width() >= 992) {
						jQuery(this).addClass('show');
					}
				}, function () {
					if (jQuery(window).width() >= 992) {
						jQuery(this).removeClass('show');
					}
				});

				if (window.feather) {
					feather.replace();
				}
			});
		</script>
	</body>
</html>	
