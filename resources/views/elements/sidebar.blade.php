<div class="deznav">
            <div class="deznav-scroll">
				<ul class="metismenu" id="menu">
                    @if(in_array(config('permisos.panelcontrol'), session('permisos_usuario')))
                    <li><a href="{!! url('/panel'); !!}" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-networking"></i>
                            <span class="nav-text">Panel de Control</span>
                        </a>
                    </li>
                    @else
                    <li><a href="{!! url('/panel'); !!}" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-networking"></i>
                            <span class="nav-text">Estado Cuenta</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(config('permisos.mantenimientos'), session('permisos_usuario')))
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-settings"></i>
							<span class="nav-text">Mantenimientos</span>
						</a>
                        <ul aria-expanded="false">
                            @if(in_array(config('permisos.torres'), session('permisos_usuario')))
                            <li><a href="{!! url('/torres'); !!}">Torres</a></li>
                            @endif
                            @if(in_array(config('permisos.tipoconcepto'), session('permisos_usuario')))
                            <li><a href="{!! url('/tipoconceptos'); !!}">Tipo Concepto</a></li>
                            @endif
                            @if(in_array(config('permisos.conceptos'), session('permisos_usuario')))
                            <li><a href="{!! url('/conceptos'); !!}">Conceptos</a></li>
                            @endif
                            @if(in_array(config('permisos.propietarios'), session('permisos_usuario')))
                            <li><a href="{!! url('/propietarios'); !!}">Propietarios</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(in_array(config('permisos.pagos'), session('permisos_usuario')))
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-network"></i>
                            <span class="nav-text">Pagos</span>
                        </a>
                        <ul aria-expanded="false">
                            @if(in_array(config('permisos.programacion'), session('permisos_usuario')))
                            <li><a href="{!! url('/programacion'); !!}">Programacion</a></li>
                            @endif
                            @if(in_array(config('permisos.registropagos'), session('permisos_usuario')))
                            <li><a href="{!! url('/pagos'); !!}">Registro</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(in_array(config('permisos.gastos'), session('permisos_usuario')))
                    <li><a href="{!! url('/gastos'); !!}" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-notepad"></i>
                            <span class="nav-text">Gastos</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(config('permisos.configuracion'), session('permisos_usuario')))
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-settings-1"></i>
                            <span class="nav-text">Configuracion</span>
                        </a>
                        <ul aria-expanded="false">

                            @if(in_array(config('permisos.usuarios'), session('permisos_usuario')))
                                <li><a href="{!! url('/usuarios'); !!}">Usuarios</a></li>
                            @endif
                            @if(in_array(config('permisos.permisos'), session('permisos_usuario')))
                                <li><a href="{!! url('/permisos'); !!}">Permisos</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(in_array(config('permisos.ingresos'), session('permisos_usuario')))
                    <li><a href="{!! url('/ingresos'); !!}" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-internet"></i>
                            <span class="nav-text">Ingresos</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(config('permisos.reportes'), session('permisos_usuario')))
                    <li><a href="#" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-internet"></i>
                            <span class="nav-text">Reportes</span>
                        </a>
                    </li>
                    @endif
                    {{--<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-networking"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                        <ul aria-expanded="false">
							<li><a href="{!! url('/index'); !!}">Dashboard</a></li>
							<li><a href="{!! url('/my-wallet'); !!}">My Wallet</a></li>
							<li><a href="{!! url('/coin-details'); !!}">Coin Details</a></li>
							<li><a href="{!! url('/portfolio'); !!}">Portfolio</a></li>
							<li><a href="{!! url('/transactions'); !!}">Transactions</a></li>
							<li><a href="{!! url('/market-capital'); !!}">Market Capital</a></li>
						</ul>
                    </li>--}}
                    {{--<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-television"></i>
							<span class="nav-text">Apps</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{!! url('/app-profile'); !!}">Profile</a></li>
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                                <ul aria-expanded="false">
                                    <li><a href="{!! url('/email-compose'); !!}">Compose</a></li>
                                    <li><a href="{!! url('/email-inbox'); !!}">Inbox</a></li>
                                    <li><a href="{!! url('/email-read'); !!}">Read</a></li>
                                </ul>
                            </li>
                            <li><a href="{!! url('/app-calender'); !!}">Calendar</a></li>
							<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Shop</a>
                                <ul aria-expanded="false">
                                    <li><a href="{!! url('/ecom-product-grid'); !!}">Product Grid</a></li>
									<li><a href="{!! url('/ecom-product-list'); !!}">Product List</a></li>
									<li><a href="{!! url('/ecom-product-detail'); !!}">Product Details</a></li>
									<li><a href="{!! url('/ecom-product-order'); !!}">Order</a></li>
									<li><a href="{!! url('/ecom-checkout'); !!}">Checkout</a></li>
									<li><a href="{!! url('/ecom-invoice'); !!}">Invoice</a></li>
									<li><a href="{!! url('/ecom-customers'); !!}">Customers</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-controls-3"></i>
							<span class="nav-text">Charts</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{!! url('/chart-flot'); !!}">Flot</a></li>
                            <li><a href="{!! url('/chart-morris'); !!}">Morris</a></li>
                            <li><a href="{!! url('/chart-chartjs'); !!}">Chartjs</a></li>
                            <li><a href="{!! url('/chart-chartist'); !!}">Chartist</a></li>
                            <li><a href="{!! url('/chart-sparkline'); !!}">Sparkline</a></li>
                            <li><a href="{!! url('/chart-peity'); !!}">Peity</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-internet"></i>
							<span class="nav-text">Bootstrap</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{!! url('/ui-accordion'); !!}">Accordion</a></li>
                            <li><a href="{!! url('/ui-alert'); !!}">Alert</a></li>
                            <li><a href="{!! url('/ui-badge'); !!}">Badge</a></li>
                            <li><a href="{!! url('/ui-button'); !!}">Button</a></li>
                            <li><a href="{!! url('/ui-modal'); !!}">Modal</a></li>
                            <li><a href="{!! url('/ui-button-group'); !!}">Button Group</a></li>
                            <li><a href="{!! url('/ui-list-group'); !!}">List Group</a></li>
                            <li><a href="{!! url('/ui-media-object'); !!}">Media Object</a></li>
                            <li><a href="{!! url('/ui-card'); !!}">Cards</a></li>
                            <li><a href="{!! url('/ui-carousel'); !!}">Carousel</a></li>
                            <li><a href="{!! url('/ui-dropdown'); !!}">Dropdown</a></li>
                            <li><a href="{!! url('/ui-popover'); !!}">Popover</a></li>
                            <li><a href="{!! url('/ui-progressbar'); !!}">Progressbar</a></li>
                            <li><a href="{!! url('/ui-tab'); !!}">Tab</a></li>
                            <li><a href="{!! url('/ui-typography'); !!}">Typography</a></li>
                            <li><a href="{!! url('/ui-pagination'); !!}">Pagination</a></li>
                            <li><a href="{!! url('/ui-grid'); !!}">Grid</a></li>

                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-heart"></i>
							<span class="nav-text">Plugins</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{!! url('/uc-select2'); !!}">Select 2</a></li>
                            <li><a href="{!! url('/uc-nestable'); !!}">Nestedable</a></li>
                            <li><a href="{!! url('/uc-noui-slider'); !!}">Noui Slider</a></li>
                            <li><a href="{!! url('/uc-sweetalert'); !!}">Sweet Alert</a></li>
                            <li><a href="{!! url('/uc-toastr'); !!}">Toastr</a></li>
                            <li><a href="{!! url('/map-jqvmap'); !!}">Jqv Map</a></li>
                        </ul>
                    </li>
                    <li><a href="{!! url('/widget-basic'); !!}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-settings-2"></i>
							<span class="nav-text">Widget</span>
						</a>
					</li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-notepad"></i>
							<span class="nav-text">Forms</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{!! url('/form-element'); !!}">Form Elements</a></li>
                            <li><a href="{!! url('/form-wizard'); !!}">Wizard</a></li>
                            <li><a href="{!! url('/form-editor-summernote'); !!}">Summernote</a></li>
                            <li><a href="{!! url('/form-pickers'); !!}">Pickers</a></li>
                            <li><a href="{!! url('/form-validation-jquery'); !!}">Jquery Validate</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-network"></i>
							<span class="nav-text">Table</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{!! url('/table-bootstrap-basic'); !!}">Bootstrap</a></li>
                            <li><a href="{!! url('/table-datatable-basic'); !!}">Datatable</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-layer-1"></i>
							<span class="nav-text">Pages</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{!! url('/page-register'); !!}">Register</a></li>
                            <li><a href="{!! url('/page-login'); !!}">Login</a></li>
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                                <ul aria-expanded="false">
                                    <li><a href="{!! url('/page-error-400'); !!}">Error 400</a></li>
                                    <li><a href="{!! url('/page-error-403'); !!}">Error 403</a></li>
                                    <li><a href="{!! url('/page-error-404'); !!}">Error 404</a></li>
                                    <li><a href="{!! url('/page-error-500'); !!}">Error 500</a></li>
                                    <li><a href="{!! url('/page-error-503'); !!}">Error 503</a></li>
                                </ul>
                            </li>
                            <li><a href="{!! url('/page-lock-screen'); !!}">Lock Screen</a></li>
                        </ul>
                    </li>--}}
                </ul>

				{{--<div class="add-menu-sidebar">
					<p>Generate Monthly Credit Report</p>
					<a href="javascript:void(0);">
					<svg width="24" height="12" viewBox="0 0 24 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M23.725 5.14889C23.7248 5.14861 23.7245 5.14828 23.7242 5.148L18.8256 0.272997C18.4586 -0.0922062 17.865 -0.0908471 17.4997 0.276184C17.1345 0.643169 17.1359 1.23675 17.5028 1.602L20.7918 4.875H0.9375C0.419719 4.875 0 5.29472 0 5.8125C0 6.33028 0.419719 6.75 0.9375 6.75H20.7917L17.5029 10.023C17.1359 10.3882 17.1345 10.9818 17.4998 11.3488C17.865 11.7159 18.4587 11.7172 18.8256 11.352L23.7242 6.477C23.7245 6.47672 23.7248 6.47639 23.7251 6.47611C24.0923 6.10964 24.0911 5.51414 23.725 5.14889Z" fill="white"/>
					</svg>
					</a>
				</div>--}}
				<div class="copyright">
					<p><strong> {{ config('app.name') }} </strong> © {{ now()->year }} Todos los derechos reservados</p>
					<p>Desarrollado por<i class="fa fa-heart"></i>  Agapito De la cruz</p>
				</div>
			</div>
        </div>
