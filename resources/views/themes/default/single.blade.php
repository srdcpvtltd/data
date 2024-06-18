@extends('themes.default.app')

@section('content')
    <div class="container page-ctn service-page">
        <div class="row">
            <div class="col-md-8 col-sm-7">
                <h1>{{$product->name}}</h1>

                @if ($product->hasMedia('gallery'))

                    @if($product->getMedia('gallery')->count() > 1)

                        <div id="main-slider" class="carousel slide" data-bs-ride="carousel">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                @foreach($product->getMedia('gallery') as $gallery)
                                    <div class="carousel-item @if ($loop->first) active @endif">
                                        <img src="{{$gallery->getUrl()}}" alt="">
                                    </div>
                                @endforeach
                            </div>

                            <!-- Controls -->
                            <a class="carousel-control-prev" href="#main-slider" role="button" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>

                            <a class="carousel-control-next" href="#main-slider" role="button" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                    @else
                        <div class="gallery-img-single">
                            <img src="{{$product->getMedia('gallery')->first()->getUrl()}}" class="img-responsive"
                                 alt="">
                        </div>
                    @endif
                @endif

                <div class="service-content">
                    {!! $product->description !!}
                </div>
            </div>

            <div class="col-md-4 col-sm-5">
                <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                    @foreach($product->plans as $plan)
                        <li class="nav-item plan-tab" role="presentation">
                            <button class="nav-link {{$loop->first ? 'active active-plan' : ''}}" id="plan-{{$plan->id}}-tab" data-bs-toggle="tab" data-bs-target="#plan-{{$plan->id}}" type="button" role="tab"
                                    aria-controls="plan-{{$plan->id}}" aria-selected="{{$loop->first ? 'true' : 'false'}}">{{$plan->name}}</button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content plan-content" id="myTabContent">
                    @if($product->hasPlans())
                        @foreach($product->plans as $plan)
                            <div class="tab-pane fade {{$loop->first ? 'show active' : ''}}" id="plan-{{$plan->id}}" role="tabpanel" aria-labelledby="plan-{{$plan->id}}-tab">
                                <p class="plan-price float-end">{!! ch_format_price($plan->price) !!}</p>
                                <h4 class="plan-description">{{$plan->description}}</h4>
                                <ul class="plan-features">
                                    @foreach($plan->features as $feature)
                                        <li><i class="fa fa-check"></i> {{$feature}}</li>
                                    @endforeach
                                </ul>

                                <form action="{{route('ch_cart_save')}}" class="order-form" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="plan_id" value="{{$plan->id}}">
                                    <div class="d-grid">
                                        <input type="submit" class="btn btn-primary btn-block order-btn" name="product_order"
                                               value="@lang('product_detail.Order now')">
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <div class="tab-pane fade show active" id="plan-1" role="tabpanel" aria-labelledby="plan-1-tab">
                            <p class="plan-price float-end">{!! ch_format_price($product->price) !!}</p>
                            <h4 class="plan-description">@lang('product_detail.Order Details')</h4>
                            <ul class="plan-features">
                                @foreach($product->features as $feature)
                                    <li><i class="fa fa-check"></i> {{$feature}}</li>
                                @endforeach
                            </ul>

                            <form action="{{route('ch_cart_save')}}" class="order-form" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <div class="d-grid">
                                    <input type="submit" class="btn btn-primary btn-block order-btn" name="product_order"
                                           value="@lang('product_detail.Order now')">
                                </div>
                            </form>
                        </div>
                    @endif

                        <div class="d-grid">
                            <button type="button" class="btn btn-secondary btn-block contact-admin-btn" data-bs-toggle="modal"
                                    data-bs-target="#preOrderModal">@lang('product_detail.Contact Seller')</button>
                        </div>
                </div>
            </div>
        </div>


        <div id="preOrderModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <form action="{{route('ch_product_pre_order_query')}}" method="post" id="pre-order-form">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">@lang('product_detail.Pre-Order Query')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <input type="hidden" name="item_id" value="{{$product->id}}">
                        <div class="modal-body">
                            @guest
                                <div class="form-group">
                                    <label for="name">@lang('product_detail.Name')</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">@lang('product_detail.Email address')</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            @endguest
                            <div class="form-group">
                                <label for="message">@lang('product_detail.Message')</label>
                                <textarea name="message" id="message" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal">@lang('product_detail.Close')</button>
                            <button type="submit"
                                    class="btn btn-sm btn-primary">@lang('product_detail.Send message')</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
