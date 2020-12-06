<div class="sidebar__sec">
	<div class="profile__figure">
        @if(empty($login_user->image))
		<img class="img-fluid" src="images/profile-img.png" alt="" />
        @else
		<img class="img-fluid" width="170px" height="170px" src="{!! asset('public/uploads/images/'.$login_user->image) !!}" alt="" />
	    @endif
    </div>
	<div class="custom__heading">
		<h1>Basic Settings</h1>
	</div>
	<div class="sidebar__list">
		<ul>
			<li><a href="{!! url('edit-profile') !!}">Edit Profile</a></li>
			<li><a href="{!! url('change-password') !!}">Change Password</a></li>
			<li><a href="{!! url('emirate') !!}">Emirates ID</a></li>
			<li><a href="{!! url('insurance-card') !!}">Insurance Card</a></li>
			<li><a href="{!! url('list-family-member') !!}">Family Members</a></li>
		</ul>
	</div>
	<div class="sidebar__list">
		<h4>Medical Information</h4>
		<ul>
			<li><a href="{!! url('health-record') !!}">Health Record</a></li>
			<li><a href="{!! url('medical-info') !!}">Medical Condition</a></li>
			<li class="singout__list"><a href="{!! url('logout') !!}"><span>Signout</span> <i class="fa fa-sign-out"></i></a></li>
		</ul>
	</div>
</div>
