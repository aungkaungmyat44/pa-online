<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Language" content="en"> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- View Port -->
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<!-- Robots -->
		<meta name="robots" content="files,pic,nofollow" />

		<!-- Meta Title -->
		<title>@yield('title', 'Personal Accident Insurance') | Sahamongkhon</title>
		<!-- Favicon Group -->
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/saha_logo.png') }}">

		<!-- Bootstrap 5.0.2 -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
		
		
		<!-- Main CSS -->
		<link href="{{ asset('css/style.min.css') }}?v={{ filemtime(public_path('css/style.min.css')) }}" rel="stylesheet" />

		<!-- Google Font APIs -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&family=Kanit:wght@400;600;700&family=Mitr:wght@400;500;700&display=swap" rel="stylesheet">	

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
		<main class="content flex-grow-1">
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
                        <a class="nav-link" href="https://vlnonline.sahainsurance.co.th/" target="_blank"><?= t('voluntary_online') ?></a>
                        <a class="nav-link" style="color:white">|</a>
                        <a class="nav-link" href="https://cplonline.sahainsurance.co.th/" target="_blank"><?= t('online_act') ?></a>
                        <a class="nav-link" style="color:white">|</a>
                        <div class="nav-item dropdown dropdown-hover text-white" id="language">
                            <a class="nav-link dropdown-toggle forwardable" id="languageDropDownBtn" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                Language - <?= strtoupper(htmlspecialchars($lang, ENT_QUOTES, 'UTF-8')) ?> <i data-feather="chevron-down" class="text-white"></i>
                            </a>
                            <?php
                                $query = $_GET;
                                $query['action'] = $query['action'] ?? 'products';

                                $thaiQuery = $query;
                                $thaiQuery['lang'] = 'th';

                                $englishQuery = $query;
                                $englishQuery['lang'] = 'en';
                            ?>
                            <div class="dropdown-menu" id="languageDropDownMenu">
                                <a class="dropdown-item" href="#">
                                    <img src="https://flagsapi.com/TH/flat/32.png" alt="Thai flag" class="mx-1">Thai
                                </a>
                                <a class="dropdown-item" href="#">
                                    <img src="https://flagsapi.com/GB/flat/32.png" alt="English flag" class="mx-1">English
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </main>
        <!-- header (Sarabun) -->
        <header class="font-sarabun">
            <div class="container custom-container">
                <a class="nav-link nav-icon ml-ni nav-toggler mr-3 d-flex d-lg-none" href="#" data-toggle="modal" data-target="#menuModal" aria-label="Toggle navigation">
                    <i data-feather="menu"></i>
                </a>
                <div class="clearfix">
                    <div class="logo-img">
                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/" rel="home" title="<?= t('site_title') ?>" class="active">
                            <img class="nav-logo" src="{{ asset('sitesaha') }} assets/img/logo-upp-header.png" alt="<?= t('site_title') ?>" id="logo">
                        </a>
                    </div>
                </div>
                <ul class="nav nav-main ms-auto d-none d-lg-flex">
                    <li class="nav-item dropdown dropdown-hover">
                        <a class="nav-link dropdown-toggle forwardable" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts" role="button" aria-haspopup="true" aria-expanded="false">
                            <?= t('get_to_know_us') ?> <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision"><?= t('vision_and_mission') ?></a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus"><?= t('financial_status') ?></a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo"><?= t('financial_statements') ?></a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/report"><?= t('annual_report') ?></a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts"><?= t('about_us') ?></a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa"><?= t('personal_data_protection') ?></a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover dropdown-mega">
                        <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <?= t('customer_service') ?> <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong><?= t('car_insurance_services') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance" class="list-group-item list-group-item-action">- <?= t('documents_required_for_a_compensation_claim') ?></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/58" class="list-group-item list-group-item-action">- <?= t('car_insurance_services') ?></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/59" class="list-group-item list-group-item-action">- <?= t('claims_processing_procedures') ?></a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong><?= t('non_motor_claims_services') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim" class="list-group-item list-group-item-action">- <?= t('non_motor_claim_required_documents') ?></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/60" class="list-group-item list-group-item-action">- <?= t('what_to_do_after_accident') ?></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/61" class="list-group-item list-group-item-action">- <?= t('claims_processing_procedures') ?></a>
                                    </div>
                                </div>
                                <div class="col-lg-4 border-start">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage" class="list-group-item list-group-item-action"><strong><?= t('list_of_car_repair_shops') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/hospital" class="list-group-item list-group-item-action"><strong><?= t('list_of_hospitals_and_clinics') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/accident" class="list-group-item list-group-item-action"><strong><?= t('accident_reporting_center') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/Service_Level_Agreement" class="list-group-item list-group-item-action"><strong><?= t('non_life_insurance_service_level_agreement') ?></strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover dropdown-mega">
                        <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <?= t('products') ?> <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong><?= t('car_insurance') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=1" class="list-group-item list-group-item-action"><?= t('car_insurance_types_1_3_5') ?></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=2" class="list-group-item list-group-item-action"><?= t('motor_vehicle_act') ?></a>
                                        <a href="{{ asset('sitesaha') }} proc.php?action=products" class="list-group-item list-group-item-action"><?= t('buy_compulsory_vehicle_insurance_online') ?></a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong><?= t('other_insurance') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance" class="list-group-item list-group-item-action"><?= t('fire_insurance') ?></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance" class="list-group-item list-group-item-action"><?= t('miscellaneous_insurance') ?></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance" class="list-group-item list-group-item-action"><?= t('marine_and_transportation_insurance') ?></a>
                                    </div>
                                </div>
                                <div class="col-lg-4 border-start">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop" class="list-group-item list-group-item-action"><strong><?= t('products_sold_by_agents') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads" class="list-group-item list-group-item-action"><strong><?= t('download') ?></strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy" class="list-group-item list-group-item-action"><strong><?= t('policy_number_verification') ?></strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover">
                        <a class="nav-link dropdown-toggle forwardable" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <?= t('agent_broker') ?> <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/agent"><?= t('apply_to_become_an_agent') ?></a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62"><?= t('insurance_premium_payment') ?></a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts"><?= t('contact_us') ?></a>
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
			<div class="container custom-container">
				<div class="row g-0">
					<div class="col-sm-6 col-lg-3 text-center px-3">
						<img src="{{ asset('sitesaha/assets/img/logo-upp2025.svg') }}" alt="<?= t('site_title') ?>" height="200">
					</div>
					<div class="col-6 col-lg-3">
						<h6 class="bold"><?= t('about_upp') ?></h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts" class="list-group-item list-group-item-action">- <?= t('historical_background') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision" class="list-group-item list-group-item-action">- <?= t('vision_and_mission') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus" class="list-group-item list-group-item-action">- <?= t('financial_status') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo" class="list-group-item list-group-item-action">- <?= t('financial_statements') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/report" class="list-group-item list-group-item-action">- <?= t('annual_report') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa" class="list-group-item list-group-item-action">- <?= t('personal_data_protection') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/career" class="list-group-item list-group-item-action">- <?= t('apply_for_work') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts" class="list-group-item list-group-item-action">- <?= t('office_location') ?></a>
							<a href="https://outlook.office.com/" target="_blank" class="list-group-item list-group-item-action">- <?= t('webmail') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads" class="list-group-item list-group-item-action">- <?= t('download') ?></a>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<h6 class="bold"><?= t('footer_products') ?></h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance" class="list-group-item list-group-item-action"><?= t('car_insurance') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance" class="list-group-item list-group-item-action"><?= t('fire_insurance') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance" class="list-group-item list-group-item-action"><?= t('miscellaneous_insurance') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance" class="list-group-item list-group-item-action"><?= t('marine_and_transportation_insurance') ?></a>
						</div>
						<h6 class="bold"><?= t('customer_service') ?></h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-5">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance" class="list-group-item list-group-item-action">- <?= t('car_insurance_claim_documents') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim" class="list-group-item list-group-item-action">- <?= t('non_motor_insurance_claim_documents') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/appeal" class="list-group-item list-group-item-action">- <?= t('receive_complaints') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage" class="list-group-item list-group-item-action">- <?= t('list_of_car_repair_shops') ?></a>
						</div>
					</div>
					<div class="col-sm-6 col-lg-3">
						<h6 class="bold"><?= t('news_and_promotions') ?></h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/news" class="list-group-item list-group-item-action">- <?= t('press_release') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop" class="list-group-item list-group-item-action">- <?= t('products_sold_by_agents') ?></a>
						</div>
						<h6 class="bold"><?= t('agent_broker') ?></h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/agent" class="list-group-item list-group-item-action">- <?= t('apply_to_become_an_agent') ?></a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62" class="list-group-item list-group-item-action">- <?= t('insurance_premium_payment') ?></a>
						</div>
						<a href="https://smart.oic.or.th/EService/Menu1" target="_blank" class="hightlighed"><h6><?= t('verify_the_agent_brokers_information') ?></h6></a>
						<a href="{{ $companyWebsiteUrl }}{{ $lang }}/tax" target="_blank" class="hightlighed"><h6><?= t('withholding_tax_certificate') ?></h6></a>
						<a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy" target="_blank" class="hightlighed"><h6><?= t('check_the_compulsory_insurance_policy_number') ?></h6></a>
						<a href="<?= BASE_URL ?>proc.php?action=check_policy_form" target="_blank" class="hightlighed">
							<h6>
								<?= t('double_check_your_mandatory_insurance_policy') ?>
							</h6>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="copyright font-sarabun">
			<p><?= t('sahamongkol_head_office_full_address_phone') ?></p>
			<p>SAHAMONGKHON INSURANCE PUBLIC COMPANY LIMITED No.7 Soi Sathorn 11, Sathorn Road, Yannawa Sub - district, Sathorn District, Bangkok 10120 Tel. 02-68-77777</p>
			<a class="nav-link pe-2 ps-0" href="https://www.facebook.com/UnionProspersInsurance/" target="_blank">
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
								<?= t('menu') ?>
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
									<?= t('home_page') ?>
								</a>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-at-sign"></i> 
									<?= t('get_to_know_us') ?>
								</a>
								<ul>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts">
										<?= t('about_us') ?>
										</a>
									</li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision"><?= t('vision_and_mission') ?></a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus"><?= t('financial_status') ?></a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo"><?= t('financial_statements') ?></a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa"><?= t('personal_data_protection') ?></a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-shopping-bag"></i> 
									<?= t('products') ?>
								</a>
								<ul>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance"><?= t('car_insurance') ?></a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance"><?= t('fire_insurance') ?></a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance"><?= t('miscellaneous_insurance') ?></a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance"><?= t('marine_and_transport_insurance') ?></a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop"><?= t('products_sold_by_agents') ?></a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-layers"></i> 
									<?= t('customer_service') ?>
								</a>
								<ul>
									<li>
										<a href="#" class="has-arrow">
											<i class="feather-layers"></i> 
											<?= t('car_insurance_services') ?> 
										</a>
										<ul>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance">-
													<?= t('documents_required_for_a_compensation_claim') ?>
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/ขั้นตอนดำเนินการด้านสินไหม_58.html">-
													<?= t('car_insurance_services') ?>
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/บริการด้านสินไหมรถยนต์_59.html">-
													<?= t('claims_processing_procedures') ?>
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="#" class="has-arrow">
											<i class="feather-layers"></i> 
											<?= t('non_motor_claims_services') ?>
										</a>
										<ul>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim">-
													<?= t('non_motor_claim_required_documents') ?>
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/วิธีปฎิบัติเมื่อเกิดอุบัติเหตุหรือเกิดความเสียหาย_60.html">
													- <?= t('what_to_do_after_accident') ?>
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/ขั้นตอนดำเนินการด้านสินไหม_61.html">
													- <?= t('claims_processing_procedures') ?>
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-users"></i> 
									<?= t('agent_broker') ?>
								</a>
								<ul>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/agent">
											<?= t('agent_application') ?>
										</a>
									</li>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62">
											<?= t('payment_of_insurance_premiums') ?>
										</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/appeal">
									<i class="feather-radio"></i> 
									<?= t('receive_complaints') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage">
									<i class="feather-settings"></i> 
									<?= t('list_of_car_repair_shops') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/hospital">
									<i class="feather-settings"></i> 
									<?= t('list_of_medical_facilities_and_clinics') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/accident">
									<i class="feather-settings"></i> 
									<?= t('accident_reporting_center') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/Service_Level_Agreement">
									<i class="feather-settings"></i>
									<?= t('non_life_insurance_service_level_agreement') ?>
								</a>
							</li>
							<li>
								<a href="https://smart.oic.or.th/EService/Menu1" target="_blank">
									<i class="feather-corner-up-right"></i> 
									<?= t('verify_the_agent_brokers_information') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts">
									<i class="feather-at-sign"></i> 
									<?= t('contact_us') ?>
								</a>
							</li>
							<li>
								<a href="https://outlook.office.com/" target="_blank">
									<i class="feather-pocket"></i> 
									<?= t('webmail') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads">
									<i class="feather-download-cloud"></i> 
									<?= t('download') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/tax">
									<i class="feather-corner-up-right"></i> 
									<?= t('withholding_tax_certificate_paid') ?>
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy">
									<i class="feather-corner-up-right"></i> 
									<?= t('checking_the_policy_number') ?>
								</a>
							</li>
							<li>
								<a href="http://192.6.1.94:8003/proc.php?action=check_policy_form">
									<i class="feather-corner-up-right"></i> 
									<?= t('double_check_your_mandatory_insurance_policy') ?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /Menu Modal -->
	</body>
</html>	
