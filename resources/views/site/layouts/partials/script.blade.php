{{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAstiE9UTEubIpvmEkzN846MkKDZRttTsk&libraries=places&callback=initAutocomplete" ></script>--}}
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp&amp;libraries=places&amp;key=AIzaSyAstiE9UTEubIpvmEkzN846MkKDZRttTsk" async defer></script>

<script src="js/popper.min.js"></script>
<script src="js/auto-complete.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/pignose.calendar.full.min.js"></script>
<script src="js/pignose.calendar.min.js"></script>
<script src="js/intlTelInput.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="slider/slick/slick.min.js"></script>
<script src="js/custom.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<script>
    $(document).ready(function(){

         $( "#datepicker" ).datepicker();
        $('#timepicker1').timepicker({
            //"format": "H:i:s A"
        });

        var default_lat = 24.871641;
        var default_long = 67.059906;

        <?php if(isset($doctor_id)){ ?>
       if($('#add-fav-doctor').length>0) {

           $('#add-fav-doctor').on('click', function () {

               var doctor_id = "{!! $doctor_id !!}";
               var fav_type = $(this).data('type');
               addDoctorFav(doctor_id,fav_type);
           });
       }

        <?php } ?>

       function addDoctorFav(doctor_id,fav_type)
       {
           $.ajax({
               type: "POST",
               url: "{!! url('add-to-fav') !!}",
               dataType: "json",
               data: {"doctor_id": doctor_id,
                   "type": fav_type,
                   "_token": "{{ csrf_token() }}"},
               beforeSend: function () {
               }
           }).done(function (data) {

               if(data.error == 1){
                   alert(data.message);
               }else{
                   window.location.href = "<?php echo url('doctor-profile')  ?>/"+doctor_id;
               }
           });

       }


        if($('#add-fav-hospital').length>0) {

            $('#add-fav-hospital').on('click', function () {

                var hospital_id = $('#hospital_id').val();
                var fav_type = $(this).data('type');

                $.ajax({
                    type: "POST",
                    url: "{!! url('add-to-fav') !!}",
                    dataType: "json",
                    data: {"hospital_id": hospital_id,
                        "type": fav_type,
                        "_token": "{{ csrf_token() }}"},
                    beforeSend: function () {
                    }
                }).done(function (data) {
                    if(data.error == 1){
                        alert(data.message);
                    }else{
                        window.location.href = "<?php echo url('hospital')  ?>/"+hospital_id;
                    }
                });

            });
        }

        if($('.hospital-review').length >0){

            $('.hospital-review').on('click',function(){

                var hospital_id = $('#hospital_id').val();
                $('#errorWrap').addClass('hide');
                $('#errorWrap').text('');

                $.ajax({
                    type: "POST",
                    url: "{!! url('add-hospital-review') !!}",
                    dataType: "json",
                    data: $('#hospitalReviewForm').serialize(),
                    beforeSend: function () {
                    }
                }).done(function (data) {
                    if(data.error == 1){
                        //alert(data.message);
                        $('#errorWrap').removeClass('hide');
                        $('#errorWrap').text(data.message);
                    }else{
                        window.location.href = "<?php echo url('hospital')  ?>/"+hospital_id;
                    }
                });
            });

        }

        @if(isset($is_book) && $is_book == 1)
           //alert('sam');
            $('#one').addClass('hide');
            $('#three').removeClass('hide');
            $('#three').addClass('active');
            $('#tab__list li[data-tag="three"]').addClass('active');
            $('#tab__list li[data-tag="one"]').removeClass('active');

        @endif

        if($('#venue_map').length > 0){
            showPointsOnMap($('#latitude').val(),$('#longitude').val(),'venue_map');
        }

        $('.addReply').on('click',function (){

           var review_id = $(this).data('id');
           $('#remarks-'+review_id).toggle('slow');
           // $('#remarks-'+review_id).focus();

        });

        if($('#searchForm').length > 0){
            searchForm($('#search_type:selected').val());
        }

        $('#search_type').on('change',function (){
            searchForm($(this).val());
        });

        $('#example').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "serverSide": true,
            "stateSave": true,
            "sAjaxSource": "{!! url('ajax-family-member') !!}"
        });


       /* $('.dataTables_filter input').unbind().keyup(function(e) {
            var value = $(this).val();
            if (value.length>3) {
                alert(value);
                table.search(value).draw();
            } else {
                //optional, reset the search if the phrase
                //is less then 3 characters long
                table.search('').draw();
            }
        });*/

        $('#appointment-select').on('change',function(){
            var redirect_url = $(this).val();
            window.location.href = redirect_url;
        });


    });

    function searchForm(value)
    {
        if(value == 'hospital'){
            $('#search-appointment-date').hide();
            $('#search-location').show();
        }
        else{
            $('#search-appointment-date').show();
            $('#search-location').hide();
        }
    }

    function showPointsOnMap(lat,long,divID)
    {
       // console.log(lat);
       // console.log(long,divID);
        var map = new google.maps.Map(document.getElementById(divID), {
            zoom: 12,
            center: new google.maps.LatLng(lat, long),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var myMarker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, long),
            draggable: true
        });

        google.maps.event.addListener(myMarker, 'dragend', function (evt) {
            document.getElementById('current_admin').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(7) + ' Current Lng: ' + evt.latLng.lng().toFixed(7) + '</p>';
            document.getElementById('latitude').value = evt.latLng.lat().toFixed(7);
            document.getElementById('longitude').value = evt.latLng.lng().toFixed(7);
        });

        map.setCenter(myMarker.position);
        myMarker.setMap(map);
    }

    if($('#searchTextField').length > 0){
        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    }

    function initAutocomplete() {
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            var lat = place.geometry.location.lat();
            var long = place.geometry.location.lng();

            var lat = document.getElementById('latitude').value = lat;
            var long = document.getElementById('longitude').value = long;
            //showPointsOnMap(lat,long,'address_map_canvas_admin');
        });
    }



</script>
