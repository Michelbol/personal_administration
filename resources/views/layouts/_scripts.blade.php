<!-- Scripts -->
<script type="text/javascript" src="{{ asset('plugin/jquery/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/select2/dist/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/bootstrap-notify-3.1.3/dist/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/datatables/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/datatables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugin/moment/min/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/jquery-mask/dist/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/bootstrap/dist/js/bootstrap.min.js') }}"></script>
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
<script type="text/javascript" src="{{ asset('/js/utils.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
@yield('scripts')
@stack('scripts')