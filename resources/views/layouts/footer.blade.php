</div>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.fiverr.com/s/WErpwd5">STEM Foundation</a>.</strong>
    All rights reserved.
</footer>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js')}} "></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}} "></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>



<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>

<script src="{{ asset('dist/js/demo.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2-theme-bootstrap-4.js') }}"></script>

<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>



{{-- Add Doughnut Chart --}}
@if(Route::currentRouteName() === 'admin.student.profile')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Attendance stats for different timeframes
        window.stats7          = @json($stats7  ?? []);
        window.statsYear       = @json($statsYear ?? []);
        window.statsAll        = @json($statsAll  ?? []);
        window.attendanceData  = @json($attendanceData ?? []);
    </script>
    <script src="{{ asset('js/doughnut.js') }}"></script>
    <script src="{{ asset('js/attendance-bar-chart.js') }}"></script>
@endif



{{-- Add Attendance directly --}}
{{-- @if(Route::currentRouteName() === 'admin.attendance.add')
    <script src="{{ asset('js/add_attendance.js') }}"></script>
@endif --}}

<script> 

$(document).ready(function() {

	
	$("#attendanceFormID").validate();
    $('#groupSelect').on('change', function() {
        let groupId = $(this).val();

        if (groupId) {
            $.ajax({
       url: "{{ url('get-courses-by-group') }}/" + groupId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#courseSelect').empty();
                    $('#courseSelect').append('<option value="">Select Course</option>');
                    $.each(data, function(key, course) {
                        $('#courseSelect').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                    });
                },
                error: function(xhr) {
                    alert('Failed to load courses.');
                    console.log(xhr.responseText);
                }
            });
        } else {
            $('#courseSelect').empty();
            $('#courseSelect').append('<option value="">Select Course</option>');
        }
    });
	
	
	
	  $('#courseSelect').on('change', function() {
		   let groupId = $('#groupSelect').val();
        let courseId = $(this).val();

        if (courseId) {
            $.ajax({
       url: "{{ url('get-courses-by-course-and-group') }}/" + groupId+"/"+courseId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#courseStudents').empty();
                    $('#courseStudents').append('<option value="">All</option>');
                    $.each(data, function(key, student) {
                        $('#courseStudents').append('<option value="' + student.id + '">' + student.full_name + '</option>');
                    });
                },
                error: function(xhr) {
                    alert('Failed to load courses.');
                    console.log(xhr.responseText);
                }
            });
        } else {
            $('#courseSelect').empty();
            $('#courseSelect').append('<option value="">Select Course</option>');
        }
    });
});



  new Chart(ctx, {
  type: 'pie',
  data: { /* … */ },
  options: {
    // allow the canvas element’s width/height to take effect
    responsive: true,
    maintainAspectRatio: false
  }
});

</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // const ctx = document.getElementById('myChart');

    const config = {
  type: 'bar',
  data: data,
  options: {
    plugins: {
      title: {
        display: true,
        text: 'Chart.js Bar Chart - Stacked'
      },
    },
    responsive: true,
    scales: {
      x: {
        stacked: true,
      },
      y: {
        stacked: true
      }
    }
  }
  
};




    const DATA_COUNT = 7;
const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

const labels = Utils.months({count: 7});
const data = {
  labels: labels,
  datasets: [
    {
      label: 'Dataset 1',
      data: [15, 23, 10, 12, 50],
      backgroundColor: Utils.CHART_COLORS.red,
    },
    {
      label: 'Dataset 2',
      data: Utils.numbers(NUMBER_CFG),
      backgroundColor: Utils.CHART_COLORS.blue,
    },
    {
      label: 'Dataset 3',
      data: Utils.numbers(NUMBER_CFG),
      backgroundColor: Utils.CHART_COLORS.green,
    },
  ]
};


const actions = [
  {
    name: 'Randomize',
    handler(chart) {
      chart.data.datasets.forEach(dataset => {
        dataset.data = Utils.numbers({count: chart.data.labels.length, min: -100, max: 100});
      });
      chart.update();
    }
  },
];
</script>









<script type="text/javascript">
function confirm(e)
    {
        e.preventDefault();
        var urlToRedirect = e.currentTarget.getAttribute('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!',
            confirmButtonClass: 'btn btn-outline-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    window.location.href = urlToRedirect;
                    Swal.fire({
                        type: "success",
                        title: 'Deleted!',
                        text: 'Your file has been deleted.',
                        confirmButtonClass: 'btn btn-success',
                })
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
            title: 'Cancelled',
            text: 'Your file is safe :)',
            type: 'error',
            confirmButtonClass: 'btn btn-success',
        });
    }
});
}
        </script>



@if(Session::has('success'))
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "swing",
        "showMethod": "slideDown",
        "hideMethod": "slideUp"
    }
        //toastr.success("{{ Session::get('success') }}", "Success!");
        toastr.success("{{ session::get('success') }}", "Success!");
</script>
@endif

@if(Session::has('error') )
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "slideDown",
            "hideMethod": "slideUp"
        }
            toastr.error("{{ Session::get('error') }}", "Delete!");
    </script>
    @endif

    @if(Session::has('info') )
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "slideDown",
            "hideMethod": "slideUp"
        }
            toastr.info("{{ Session::get('info') }}", "Information!");
    </script>
    @endif


    @foreach ($errors->all() as $error)
    <li>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "slideDown",
            "hideMethod": "slideUp"
        }
            toastr.warning("{{ $error }}", "Warning!");
    </script>
@endforeach




<script>
    $('.swalDefaultInfo').click(function() {
        Toast.fire({
            icon: 'info',
            title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });
    $('.swalDefaultError').click(function() {
        Toast.fire({
            icon: 'error',
            title: 'I would like to keep a very long sentence.'
        })
    });
    $('.swalDefaultWarning').click(function() {
        Toast.fire({
            icon: 'warning',
            title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });
    $('.swalDefaultQuestion').click(function() {
        Toast.fire({
            icon: 'question',
            title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });

    $('.toastrDefaultSuccess').click(function() {
        toastr.success('Asif')
    });
    $('.toastrDefaultInfo').click(function() {
        toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultError').click(function() {
        toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultWarning').click(function() {
        toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });

    $('.toastsDefaultDefault').click(function() {
        $(document).Toasts('create', {
            title: 'Toast Title',
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });

    $('.toastsDefaultTopLeft').click(function() {
        $(document).Toasts('create', {
            title: 'Toast Title',
            position: 'topLeft',
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });

    $('.toastsDefaultBottomRight').click(function() {
        $(document).Toasts('create', {
            title: 'Toast Title',
            position: 'bottomRight',
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });

    $('.toastsDefaultBottomLeft').click(function() {
        $(document).Toasts('create', {
            title: 'Toast Title',
            position: 'bottomLeft',
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });

    $('.toastsDefaultAutohide').click(function() {
        $(document).Toasts('create', {
            title: 'Toast Title',
            autohide: true,
            delay: 750,
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });

    $('.toastsDefaultNotFixed').click(function() {
        $(document).Toasts('create', {
            title: 'Toast Title',
            fixed: false,
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });

    $('.toastsDefaultFull').click(function() {
        $(document).Toasts('create', {
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            icon: 'fas fa-envelope fa-lg',
        })
    });

    $('.toastsDefaultFullImage').click(function() {
        $(document).Toasts('create', {
            body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            image: '../../dist/img/user3-128x128.jpg',
            imageAlt: 'User Picture',
        })
    });

    $('.toastsDefaultSuccess').click(function() {
        $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            body: 'Asif'
        })
    });

    $('.toastsDefaultInfo').click(function() {
        $(document).Toasts('create', {
            class: 'bg-info',
            title: 'Toast Title',
            subtitle: 'Subtitle',
            body: 'Asif'
        })
    });
</script>











<!-- Page specific script -->
<script>
$(function () {
    // Initialize DataTable for example1
    var table1 = $('#example1').DataTable({
      responsive: true,
      paging: true,
      ordering: true,
      info: true,
      // load all rows, not just 10
      pageLength: -1,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      searching: true,
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });


    table1.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    // Initialize DataTable for example2
    var table2 = $('#example2').DataTable({
      responsive: true,
      paging: true,
      ordering: true,
      info: true,
      // load all rows, not just 10
      pageLength: -1,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      searching: true,
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    table2.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
});


    //     $("#example2").DataTable({
    //         "responsive": true, "lengthChange": false, "autoWidth": false,
    //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    //         }).buttons().container().appendTo('.example2_wrapper .col-md-6:eq(0)');
    //         $('.example2').DataTable({
    //             "paging": true,
    //             "lengthChange": false,
    //             "searching": false,
    //             "ordering": true,
    //             "info": true,
    //             "autoWidth": false,
    //             "responsive": true,
    //     });
    // });
</script>

<script type="text/javascript">

    $(document).ready(function (e) {
        $('#image').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
    </script>



    <script>
        $(function () {
          //Initialize Select2 Elements
          $('.select2').select2()

          //Initialize Select2 Elements
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          })

          //Datemask dd/mm/yyyy
          $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
          //Datemask2 mm/dd/yyyy
          $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
          //Money Euro
          $('[data-mask]').inputmask()

          //Date picker
          $('#reservationdate').datetimepicker({
              format: 'L'
          });

          //Date and time picker
          $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

          //Date range picker
          $('#reservation').daterangepicker()
          //Date range picker with time picker
          $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
              format: 'MM/DD/YYYY hh:mm A'
            }
          })
          //Date range as a button
          $('#daterange-btn').daterangepicker(
            {
              ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate  : moment()
            },
            function (start, end) {
              $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
          )

          //Timepicker
          $('#timepicker').datetimepicker({
            format: 'LT'
          })

          //Bootstrap Duallistbox
          $('.duallistbox').bootstrapDualListbox()

          //Colorpicker
          $('.my-colorpicker1').colorpicker()
          //color picker with addon
          $('.my-colorpicker2').colorpicker()

          $('.my-colorpicker2').on('colorpickerChange', function(event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
          })

          $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
          })

        })
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function () {
          window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template")
        previewNode.id = ""
        var previewTemplate = previewNode.parentNode.innerHTML
        previewNode.parentNode.removeChild(previewNode)

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
          url: "/target-url", // Set the url
          thumbnailWidth: 80,
          thumbnailHeight: 80,
          parallelUploads: 20,
          previewTemplate: previewTemplate,
          autoQueue: false, // Make sure the files aren't queued until manually added
          previewsContainer: "#previews", // Define the container to display the previews
          clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        })

        myDropzone.on("addedfile", function(file) {
          // Hookup the start button
          file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
        })

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
          document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
        })

        myDropzone.on("sending", function(file) {
          // Show the total progress bar when upload starts
          document.querySelector("#total-progress").style.opacity = "1"
          // And disable the start button
          file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        })

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
          document.querySelector("#total-progress").style.opacity = "0"
        })

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function() {
          myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
        }
        document.querySelector("#actions .cancel").onclick = function() {
          myDropzone.removeAllFiles(true)
        }
        // DropzoneJS Demo Code End
      </script>


<script>
document.querySelectorAll('.togglePassword').forEach(toggleButton => {
    toggleButton.addEventListener('click', () => {
        // Find the related input field
        const passwordField = toggleButton.previousElementSibling;

        // Toggle the type attribute
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Toggle the eye icon class
        // Toggle the icon class within the button
        const icon = toggleButton.querySelector('i');
        if (icon.classList.contains('fa-eye-slash')) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
});


</script>
