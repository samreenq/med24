@extends('site.layouts.index')
@section('content')
<section class="dashboard__sec">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				@include('site.user.sidebar')
			</div>
			<div class="col-lg-9">
				<div class="left__panel__box change__password">
				  <div class="custom__heading">
				  	<h2>{!! $action !!} Health Record</h2>
				  </div>
					<div class="form__sec__box change__password__inner">
                        @include('site.layouts.partials.messages')
						<form method="post" action="{!! url('save-health-record') !!}" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
							<div class="row">
								<div class="col-lg-8">
									<div class="form-group">
										<label>Title</label>
										<input type="text" class="form-control" name="title">
									</div>
								</div>
								<div class="col-lg-8">
									<div class="form-group">
										<label>Description</label>
                                        <textarea class="form-control" style="margin-top: 0px; margin-bottom: 0px; height: 148px" name="description"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8">
									<div class="form-group">
										<label>Attachment</label>
										<input type="file" class="form-control" name="attachment">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<div class="custom__btn">
											<button type="submit">Save Changes</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
{{-- @section('scripts')
@endsection --}}
