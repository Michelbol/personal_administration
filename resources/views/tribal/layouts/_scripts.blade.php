<!-- Scripts -->
<script type="text/javascript" src="{{ asset('plugin/jquery/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/select2/dist/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/bootstrap-notify-3.1.3/dist/bootstrap-notify.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugin/moment/min/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/jquery-mask/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugin/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/utils.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
</script>
@yield('scripts')
@stack('scripts')
