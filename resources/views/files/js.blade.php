    <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/popper.js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-scrollLock/jquery-scrollLock.min.js') }}"></script>

    <script src="{{ asset('assets/vendors/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <script src="{{ asset('assets/vendors/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jqvmap/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/vendors/easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/salvattore/salvattore.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/fullcalendar/fullcalendar.min.js') }}"></script>

    <!-- Charts and maps-->
    <script src="{{ asset('assets/demo/js/flot-charts/curved-line.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/dynamic.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/line.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/chart-tooltips.js') }}"></script>
    <script src="{{ asset('assets/demo/js/other-charts.js') }}"></script>
    <script src="{{ asset('assets/demo/js/jqvmap.js') }}"></script>

    <!-- App functions and actions -->
    <script src="{{ asset('assets/js/jquery.form.js') }}"></script>
    <script src="{{ asset('assets/fontawesome-free-7.2.0-web/js/all.js') }}"></script>
    <script src="{{ asset('assets/js/xlsx.full.min.js') }}"></script>
     <!-- <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script> -->
    <script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/trumbowyg/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/rateyo/jquery.rateyo.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-text-counter/textcounter.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/autosize/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.select-multiple.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script>
     $("#_deconnexion").click(function(e){
            e.preventDefault();
            $("#_deconnexion").html("<i class='zmdi zmdi-refresh zmdi-hc-spin'></i>");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/deconnexion",
                data: {},
                success:function(response)
                {
                    window.location.replace('/' + response);
                }
            })
        })
</script>
