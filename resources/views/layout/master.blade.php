<!doctype html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta http-equiv="Content-Language" content="{{ app()->getLocale() }}">
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
		$companyWebsiteUrl = config('services.company_website.url');
		$lang = app()->getLocale();
		$currentRoute = request()->route();
		$languageUrl = function (string $locale) use ($currentRoute) {
			if (! $currentRoute?->getName()) {
				return url('/'.$locale);
			}

			$parameters = array_merge(
				request()->query(),
				$currentRoute->parameters(),
				['locale' => $locale],
			);

			return route($currentRoute->getName(), $parameters);
		};
		$cplUrl = config('services.company_website.cpl_url');
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
                        <a class="nav-link" href="https://vlnonline.sahainsurance.co.th/" target="_blank">{{ __('navigation.voluntary_online') }}</a>
                        <a class="nav-link" style="color:white">|</a>
                        <a class="nav-link" href="https://cplonline.sahainsurance.co.th/" target="_blank">{{ __('navigation.compulsory_online') }}</a>
                        <a class="nav-link" style="color:white">|</a>
                        <div class="nav-item dropdown dropdown-hover text-white" id="language">
                            <a class="nav-link dropdown-toggle forwardable" data-toggle="dropdown" id="languageDropDownBtn" href="javascript:void(0);" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ __('navigation.language') }} <i data-feather="chevron-down" class="text-white"></i>
                            </a>
                            <div class="dropdown-menu" id="languageDropDownMenu">
                                <a class="dropdown-item" href="{{ $languageUrl('th') }}">
                                    <img src="https://flagsapi.com/TH/flat/32.png" alt="Thai flag" class="mx-1">{{ __('navigation.thai') }}
                                </a>
                                <a class="dropdown-item" href="{{ $languageUrl('en') }}">
                                    <img src="https://flagsapi.com/GB/flat/32.png" alt="English flag" class="mx-1">{{ __('navigation.english') }}
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
                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/" rel="home" title="{{ __('navigation.logo_alt') }}" class="active">
                            <img class="nav-logo" src="{{ asset('sitesaha/assets/img/logo-upp-header.png') }}" alt="{{ __('navigation.logo_alt') }}" id="logo">
                        </a>
                    </div>
                </div>
                <ul class="nav nav-main ms-auto d-none d-lg-flex">
                    <li class="nav-item dropdown dropdown-hover">
                        <a class="nav-link dropdown-toggle forwardable" data-toggle="dropdown" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ __('navigation.about_us') }} <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision">{{ __('navigation.vision_mission') }}</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus">{{ __('navigation.financial_status') }}</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo">{{ __('navigation.financial_statements') }}</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/report">{{ __('navigation.annual_report') }}</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts">{{ __('navigation.about_us') }}</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa">{{ __('navigation.personal_data_protection') }}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover dropdown-mega">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ __('navigation.customer_service') }}
                        <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>{{ __('navigation.motor_claims') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance" class="list-group-item list-group-item-action">- {{ __('navigation.motor_claims_documents') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/58" class="list-group-item list-group-item-action">- {{ __('navigation.motor_claims_service') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/59" class="list-group-item list-group-item-action">- {{ __('navigation.claims_process') }}</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>{{ __('navigation.non_motor_claims') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim" class="list-group-item list-group-item-action">- {{ __('navigation.non_motor_claims_documents') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/60" class="list-group-item list-group-item-action">- {{ __('navigation.accident_instructions') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/61" class="list-group-item list-group-item-action">- {{ __('navigation.claims_process') }}</a>
                                    </div>
                                </div>
                                <div class="col-lg-4 border-start">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage" class="list-group-item list-group-item-action"><strong>{{ __('navigation.garage_list') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/hospital" class="list-group-item list-group-item-action"><strong>{{ __('navigation.hospital_list') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/accident" class="list-group-item list-group-item-action"><strong>{{ __('navigation.accident_center') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/Service_Level_Agreement" class="list-group-item list-group-item-action"><strong>{{ __('navigation.service_level_agreement') }}</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover dropdown-mega">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ __('navigation.products') }}
                        <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>{{ __('navigation.motor_insurance') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=1" class="list-group-item list-group-item-action">{{ __('navigation.motor_insurance_types') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=2" class="list-group-item list-group-item-action">{{ __('navigation.compulsory_motor') }}</a>
                                        <a href="{{ $cplUrl }}&lang={{ $lang }}" class="list-group-item list-group-item-action">{{ __('navigation.buy_compulsory_motor') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance/van" class="list-group-item list-group-item-action">{{ __('navigation.super_van') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance/voluntary" class="list-group-item list-group-item-action">{{ __('navigation.voluntary_motor') }}</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="#" class="list-group-item list-group-item-action"><strong>{{ __('navigation.other_insurance') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance" class="list-group-item list-group-item-action">{{ __('navigation.fire_insurance') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance" class="list-group-item list-group-item-action">{{ __('navigation.miscellaneous_insurance') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance" class="list-group-item list-group-item-action">{{ __('navigation.marine_insurance') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/personal_accident_insurance/pa888" class="list-group-item list-group-item-action">{{ __('navigation.pa888') }}</a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/personal_accident_insurance/unnamed" class="list-group-item list-group-item-action">{{ __('navigation.unnamed_pa') }}</a>
                                    </div>
                                </div>
                                <div class="col-lg-4 border-start">
                                    <div class="list-group list-group-flush list-group-no-border list-group-sm">
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop" class="list-group-item list-group-item-action"><strong>{{ __('navigation.shop') }}</strong></a>
                                        <a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads" class="list-group-item list-group-item-action"><strong>{{ __('navigation.downloads') }}</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover">
                        <a class="nav-link dropdown-toggle forwardable" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ __('navigation.agent_broker') }} <i data-feather="chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/agent">{{ __('navigation.agent_application') }}</a>
                            <a class="dropdown-item" href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62">{{ __('navigation.premium_payment') }}</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts">
                        {{ __('navigation.contact_us') }}
                        </a>
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
						<img src="{{ asset('sitesaha/assets/img/logo-upp2025.svg') }}" alt="{{ __('navigation.logo_alt') }}" height="200">
					</div>
					<div class="col-6 col-lg-3">
						<h6 class="bold">{{ __('navigation.footer_about_heading') }}</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts" class="list-group-item list-group-item-action">- {{ __('navigation.history') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision" class="list-group-item list-group-item-action">- {{ __('navigation.vision_mission') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus" class="list-group-item list-group-item-action">- {{ __('navigation.financial_status') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo" class="list-group-item list-group-item-action">- {{ __('navigation.financial_statements') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/report" class="list-group-item list-group-item-action">- {{ __('navigation.annual_report') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa" class="list-group-item list-group-item-action">- {{ __('navigation.personal_data_protection') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/career" class="list-group-item list-group-item-action">- {{ __('navigation.career') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts" class="list-group-item list-group-item-action">- {{ __('navigation.office_location') }}</a>
							<a href="https://outlook.office.com/" target="_blank" class="list-group-item list-group-item-action">- {{ __('navigation.webmail') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads" class="list-group-item list-group-item-action">- {{ __('navigation.downloads') }}</a>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<h6 class="bold">{{ __('navigation.product') }}</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance" class="list-group-item list-group-item-action">{{ __('navigation.motor_insurance') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance" class="list-group-item list-group-item-action">{{ __('navigation.fire_insurance') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance" class="list-group-item list-group-item-action">{{ __('navigation.miscellaneous_insurance') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance" class="list-group-item list-group-item-action">{{ __('navigation.marine_insurance') }}</a>
						</div>
						<h6 class="bold">{{ __('navigation.customer_service') }}</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-5">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance" class="list-group-item list-group-item-action">- {{ __('navigation.motor_insurance_documents') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim" class="list-group-item list-group-item-action">- {{ __('navigation.non_motor_documents_short') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/appeal" class="list-group-item list-group-item-action">- {{ __('navigation.complaints_center') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage" class="list-group-item list-group-item-action">- {{ __('navigation.garage_list') }}</a>
						</div>
					</div>
					<div class="col-sm-6 col-lg-3">
						<h6 class="bold">{{ __('navigation.news_promotions') }}</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/news" class="list-group-item list-group-item-action">- {{ __('navigation.news_announcements') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop" class="list-group-item list-group-item-action">- {{ __('navigation.shop') }}</a>
						</div>
						<h6 class="bold">{{ __('navigation.agent_broker') }}</h6>
						<div class="list-group list-group-flush list-group-no-border list-group-sm mb-4">
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/agent" class="list-group-item list-group-item-action">- {{ __('navigation.agent_application') }}</a>
							<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62" class="list-group-item list-group-item-action">- {{ __('navigation.premium_payment') }}</a>
						</div>
						<a href="https://smart.oic.or.th/EService/Menu1" target="_blank" class="hightlighed"><h6>{{ __('navigation.agent_broker_verification') }}</h6></a>
						<a href="{{ $companyWebsiteUrl }}{{ $lang }}/tax" target="_blank" class="hightlighed"><h6>{{ __('navigation.withholding_tax_certificate') }}</h6></a>
						<a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy" target="_blank" class="hightlighed"><h6>{{ __('navigation.verify_compulsory_policy') }}</h6></a>
						<a href="http://192.6.1.94:8003/proc.php?action=check_policy_form&lang={{ $lang }}" target="_blank" class="hightlighed">
							<h6>
								{{ __('navigation.recheck_compulsory_policy') }}
							</h6>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="copyright font-sarabun">
			<p>{{ __('navigation.address_th') }}</p>
			<p>{{ __('navigation.address_en') }}</p>
			<a class="nav-link pr-2 pl-0" href="https://www.facebook.com/UnionProspersInsurance/" target="_blank">
				<i data-feather="facebook"></i>
				{{ __('navigation.official_facebook') }}
			</a>
		</div>
		<div class="modal modal-left modal-menu font-sarabun" id="menuModal" tabindex="-1" role="dialog" aria-label="menuModel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header shadow">
						<a class="h5 mb-0 d-flex align-items-center" href="{{ $companyWebsiteUrl }}{{ $lang }}/">
							<strong>
								{{ __('navigation.menu') }}
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
									{{ __('navigation.home') }}
								</a>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-at-sign"></i> 
									{{ __('navigation.about_us') }}
								</a>
								<ul>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts">
										{{ __('navigation.about_us') }}
										</a>
									</li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/vision">{{ __('navigation.vision_mission') }}</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialstatus">{{ __('navigation.financial_status') }}</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/abouts/financialinfo">{{ __('navigation.financial_statements') }}</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/pdpa">{{ __('navigation.personal_data_protection') }}</a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-shopping-bag"></i> 
									{{ __('navigation.products') }}
								</a>
								<ul>
									<li>
										<a href="#" class="has-arrow">
											{{ __('navigation.motor_insurance') }}
										</a>
										<ul>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=1">{{ __('navigation.motor_insurance_types') }}</a></li>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance?show=2">{{ __('navigation.compulsory_motor') }}</a></li>
											<li><a href="http://192.6.1.94:8003/proc.php?action=products&lang={{ $lang }}">{{ __('navigation.buy_compulsory_motor') }}</a></li>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance/van">{{ __('navigation.super_van') }}</a></li>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/motor_insurance/voluntary">{{ __('navigation.voluntary_motor') }}</a></li>
										</ul>
									</li>
									<li>
										<a href="#" class="has-arrow">
											{{ __('navigation.other_insurance') }}
										</a>
										<ul>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/fire_insurance">{{ __('navigation.fire_insurance') }}</a></li>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/other_insurance">{{ __('navigation.miscellaneous_insurance') }}</a></li>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/marine_cargo_insurance">{{ __('navigation.marine_insurance') }}</a></li>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/personal_accident_insurance/pa888">{{ __('navigation.pa888') }}</a></li>
											<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/personal_accident_insurance/unnamed">{{ __('navigation.unnamed_pa') }}</a></li>
										</ul>
									</li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/shop">{{ __('navigation.shop') }}</a></li>
									<li><a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads">{{ __('navigation.downloads') }}</a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-layers"></i> 
									{{ __('navigation.customer_service') }}
								</a>
								<ul>
									<li>
										<a href="#" class="has-arrow">
											<i class="feather-layers"></i> 
											{{ __('navigation.motor_claims') }}
										</a>
										<ul>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/document-required-for-claiming-insurance">-
													{{ __('navigation.motor_claims_documents') }}
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/ขั้นตอนดำเนินการด้านสินไหม_58.html">-
													{{ __('navigation.motor_claims_service') }}
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/บริการด้านสินไหมรถยนต์_59.html">-
													{{ __('navigation.claims_process') }}
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="#" class="has-arrow">
											<i class="feather-layers"></i> 
											{{ __('navigation.non_motor_claims') }}
										</a>
										<ul>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/download/dl/steps-for-notifying-non-motor-claim">-
													{{ __('navigation.non_motor_claims_documents') }}
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/วิธีปฎิบัติเมื่อเกิดอุบัติเหตุหรือเกิดความเสียหาย_60.html">
													- {{ __('navigation.accident_instructions') }}
												</a>
											</li>
											<li>
												<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/ขั้นตอนดำเนินการด้านสินไหม_61.html">
													- {{ __('navigation.claims_process') }}
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li>
								<a href="#" class="has-arrow">
									<i class="feather-users"></i> 
									{{ __('navigation.agent_broker') }}
								</a>
								<ul>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/agent">
											{{ __('navigation.agent_application') }}
										</a>
									</li>
									<li>
										<a href="{{ $companyWebsiteUrl }}{{ $lang }}/service/view/62">
											{{ __('navigation.premium_payment') }}
										</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/appeal">
									<i class="feather-radio"></i> 
									{{ __('navigation.complaints_center') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/garage">
									<i class="feather-settings"></i> 
									{{ __('navigation.garage_list') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/hospital">
									<i class="feather-settings"></i> 
									{{ __('navigation.hospital_list') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/accident">
									<i class="feather-settings"></i> 
									{{ __('navigation.accident_center') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/Service_Level_Agreement">
									<i class="feather-settings"></i>
									{{ __('navigation.service_level_agreement') }}
								</a>
							</li>
							<li>
								<a href="https://smart.oic.or.th/EService/Menu1" target="_blank">
									<i class="feather-corner-up-right"></i> 
									{{ __('navigation.agent_broker_verification') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/contacts">
									<i class="feather-at-sign"></i> 
									{{ __('navigation.contact_us') }}
								</a>
							</li>
							<li>
								<a href="https://outlook.office.com/" target="_blank">
									<i class="feather-pocket"></i> 
									{{ __('navigation.webmail') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/downloads">
									<i class="feather-download-cloud"></i> 
									{{ __('navigation.downloads') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/tax">
									<i class="feather-corner-up-right"></i> 
									{{ __('navigation.withholding_tax_certificate_paid') }}
								</a>
							</li>
							<li>
								<a href="{{ $companyWebsiteUrl }}{{ $lang }}/policy">
									<i class="feather-corner-up-right"></i> 
									{{ __('navigation.policy_number_verification') }}
								</a>
							</li>
							<li>
								<a href="http://192.6.1.94:8003/proc.php?action=check_policy_form&lang={{ $lang }}">
									<i class="feather-corner-up-right"></i> 
									{{ __('navigation.recheck_compulsory_policy') }}
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

			@if(Session::has('success'))
				toastr.success("{{ Session::get('success') }}");
			@endif


			@if(Session::has('info'))
				toastr.info("{{ Session::get('info') }}");
			@endif


			@if(Session::has('warning'))
				toastr.warning("{{ Session::get('warning') }}");
			@endif


			@if(Session::has('error'))
				toastr.error("{{ Session::get('error') }}");
			@endif

			@if ($errors->any())
				@foreach ($errors->all() as $err)
					toastr.error(@json($err));
				@endforeach
			@endif
		</script>
		@stack('css')
		@stack('js')
	</body>
</html>	
