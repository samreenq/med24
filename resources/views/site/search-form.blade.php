
        <div class="custom__heading">
            <h4>{!! $search_title !!}:</h4>
        </div>
        <form action="{!! url('search') !!}" method="get" id="searchForm">
            <div class="form-group">
                <div class="icon__wrapper">
                    {{--<input type="text" class="form-control" name="" placeholder="Type of Healthcare Practitioner">--}}
                    <label>Healthcare Practitioner</label>
                    <select id="search_type" name="search_type" class="selectpicker">
                        <option @if(app('request')->input('search_type') && app('request')->input('search_type') == 'hospital') selected @endif value="hospital">Hospital</option>
                        <option @if(app('request')->input('search_type') && app('request')->input('search_type') == 'doctor') selected @endif value="doctor">Doctor</option>
                    </select>
                    <div class="field__icon">
                        <img src="images/type-of-practitioner.svg" alt="" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="icon__wrapper">
                    {{--<input type="text" class="form-control" name="" placeholder="Speciality">--}}
                    @if(isset($speciality))
                        <label>Speciality</label>
                        <select name="speciality_id" class="selectpicker">
                            @foreach($speciality as $sp)
                                <option @if(app('request')->input('speciality_id') && app('request')->input('speciality_id') == $sp->id) selected @endif value="{!! $sp->id !!}">{!! ucwords(strtolower($sp->name)) !!}</option>
                            @endforeach
                        </select>
                    @endif
                    <div class="field__icon">
                        <img src="images/speciality.svg" alt="" />
                    </div>
                </div>
            </div>
            <div class="form-group" id="search-location">
                <div class="icon__wrapper">
                    <input type="hidden" name="latitude" id="latitude" value="" />
                    <input type="hidden" name="longitude" id="longitude" value="" />
                    <input id="searchTextField" placeholder="Location"  type="text" class="form-control">
                    <div class="field__icon">
                        <img src="images/location.svg" alt="" />
                    </div>
                </div>
            </div>
            <div class="date__time__box" id="search-appointment-date">
                <div class="form-group">
                    <div class="icon__wrapper">
                        <input type="text" value="@if(app('request')->input('appointment_date')) {!! app('request')->input('appointment_date') !!}  @endif" id="datepicker" name="appointment_date" class="form-control" placeholder="Date">
                        <div class="field__icon">
                            <img src="images/date.svg" alt="" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="icon__wrapper">
                        <input type="text" value="@if(app('request')->input('appointment_time')) {!! app('request')->input('appointment_time') !!}  @endif" id="timepicker1" name="appointment_time" class="form-control" placeholder="Time">
                        <div class="field__icon">
                            <img src="images/time.svg" alt="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
               {{-- <button type="submit" class="custom__btn">Search</button>--}}
                <div class="custom__btn">
                    <button type="submit">Search</button>
                </div>
            </div>
        </form>
