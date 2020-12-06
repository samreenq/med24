@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.sidebar')
			</div>
			<div class="col-lg-8 offset-1">
				<div class="left__panel__box change__password">
					<div class="custom__heading">
						<h2>Family Members</h2>
                        <div class="add__new">
                            <a href="{!! url('add-family') !!}"><i class="fa fa-plus"></i> Add New</a>
                        </div>
					</div>
					<div class="form__sec__box">
                        <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <td>First Name</td>
                                <td>Last Name</td>
                                <td>Relation</td>
                                <td>Gender</td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        {{--@if(count($family_members)>0)
                            <tbody>
                            @foreach($family_members as $member)
                                <tr>
                                    <td>{!! $member->first_name !!}</td>
                                    <td>{!! $member->last_name !!}</td>
                                    <td>{!! $member->relation !!}</td>
                                    <td>{!! $member->gender !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif--}}
                        </table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
