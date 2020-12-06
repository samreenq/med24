@extends('site.layouts.index')
@section('content')

<section class="privacy__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="custom__heading">
					<h1>About Us</h1>
				</div>
				<div class="inner__privacy__content">
                    {!! $data->value !!}
                </div>
			</div>
		</div>
	</div>
</section>

@endsection
{{-- @section('scripts')
@endsection --}}
