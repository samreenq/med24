@extends('site.layouts.index')
@section('content')
    <section class="mein__offer__sec">
        <div class="offer__banner__sec">
            <div class="offer__banner__box">
                <img class="img-fluid" src="images/offer-banner.png" alt="" />
                <div class="offer__caption">
                    <div class="offer__search__box">
                        <form action="" method="">
                            <input type="" class="form-control" name="" placeholder="What are you looking for?">
                        </form>
                    </div>
                    <div class="inner__offer__box">
                        <h1>25% OFF</h1>
                        <h2>Dental Services</h2>
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(count($offers)>0)
        <section class="offer__category__sec">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="custom__heading text-center">
                            <h1>Offers</h1>
                            <h2 class="black">Contrary to popular belief, Lorem Ipsum is not simply random text.</h2>
                        </div>
                    </div>
                </div>
                <?php $size = count($offers); ?>
                @foreach($offers as $key => $offer)

                    <?php
                    if($key%3 == 0){ ?>
                    <?php if($key != 0 && $key != $size-1){ ?>
            </div>
            <?php } ?>
            <div class="row">
                <?php } ?>
                <div class="col-lg-4">
                    <div class="category__figure new__offer__figure">
                        <a href="{!! url('offer/'.$offer->id) !!}">
                            @if(!empty($offer->image))
                                <img class="img-fluid" src="{!! asset('public/uploads/images/'.$offer->image) !!}" alt="" />
                            @else
                                <img class="img-fluid" src="images/cate1.png" alt="" />
                            @endif
                        </a>
                        <div class="category_caption">
                            <h3>{!! $offer->name !!}</h3>
                        </div>
                    </div>
                </div>
                <?php if($key == ($size-1)){ ?>
            </div>
            <?php } ?>
            @endforeach

                {{ $paginator->links() }}

            </div>
        </section>
    @endif

@endsection
{{-- @section('scripts')
@endsection --}}
