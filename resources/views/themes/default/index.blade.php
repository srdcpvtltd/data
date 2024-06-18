@php
    $title = '';
    if (isset($q)) {
        $title = trans('search.title|:count items found for ":q"', ['count' => $products->total(), 'q' => $q]);
    } elseif (isset($term)) {
        $title = $term->name;
    }
@endphp

@extends('themes.default.app', ['title' => $title])

@section('content')
    <div id="content" class="container archive">
        <div class="row row-flex">
            @isset($term)
                <div class="col-12">
                    <h1>{{$term->name}}</h1>
                    <hr>
                </div>
            @endisset

            @isset($q)
                @if($products->total())
                    <div class="col-md-12 mb-5">
                        <h1>@lang('search.heading|:count items found for ":q"', ['count' => $products->total(), 'q' => $q])</h1>
                        <hr>
                    </div>
                @else
                    <div class="col-md-12 text-center" style="padding: 180px 0;">
                        <h1>@lang('search.No, results found for this term.')</h1>
                        <p><a class="btn btn-primary" href="{{site_url('/')}}">@lang('search.Browse Products')</a></p>
                    </div>
                @endif
            @endisset

            @forelse( $products as $product )

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="service-box">
                        <a href="{{route('ch_product_single', [$product->slug])}}">
                            <figure class="featured-image" style="background-image: url('{{$product->getFeaturedImage()}}')"></figure>
                        </a>
                        <div class="service-content clearfix">
                            <p class="price float-end">
                                <span class="label">
                                    @if($product->hasPlans())
                                        @lang('product_detail.Starting At')
                                    @else
                                        @lang('product_detail.Price')
                                    @endif
                                </span>
                                <br>
                                <span class="price">{!! ch_format_price($product->startingPrice) !!}</span>
                            </p>
                            <h3><a href="{{route('ch_product_single', [$product->slug])}}">{{$product->name}}</a></h3>
                        </div>
                    </div>
                </div>

            @empty

                @isset($term)
                    <div class="col-md-12" style="min-height: 400px;">
                        <p>@lang('general.No, Products found in this category.')</p>
                        <p><a class="btn btn-primary" href="{{site_url('/')}}">@lang('general.Browse Products')</a></p>
                    </div>
                @endisset

            @endforelse


            <div class="col-12 pagination-center">
                {{$products->links()}}
            </div>

        </div>
    </div>
@endsection
