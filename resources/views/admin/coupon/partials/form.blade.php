<div class="col-md-8">
    <h4 class="c-grey-900 mT-10 mB-30">{{$submitLabel}}</h4>
    @include('admin.layouts.errors')

    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('name', 'Coupon Name:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '50% Discount offer']) !!}
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('code', 'Coupon Code:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => '50OFF']) !!}
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('type', 'Coupon Type:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::select('type', ['1' => 'Percentage', '2' => 'Flat amount'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('amount', 'Discount amount:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::number('amount', null, ['class' => 'form-control']) !!}
                <div class="text-muted">Enter discount percentage or flat amount. 10 = 10% or 10 = $10</div>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('start_date', 'Start Date:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
                <div class="text-muted">Enter the start date for this discount code in the format of yyyy-mm-dd. For
                    no start date, leave blank.
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('end_date', 'End Date:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::text('end_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
                <div class="text-muted">Enter the expiration date for this discount code in the format of
                    yyyy-mm-dd. For no expiration, leave blank.
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('max_uses', ' Max Uses:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::number('max_uses', null, ['class' => 'form-control', 'min' => 1]) !!}
                <div class="text-muted">The maximum number of times this discount can be used. Leave blank for
                    unlimited.
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('use_once', 'Single use per customer?') !!}
            </div>
            <div class="col-md-7">
                {!! Form::select('use_once', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) !!}
                <div class="text-muted">Limit this discount to a single-use per customer?</div>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('products[]', 'Valid for products:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::select('products[]', $products, null, ['class' => 'form-control', 'multiple']) !!}
                <div class="text-muted">
                    Coupon code can be applied on selected products.
                    Leave empty to be applied on all products.
                    Hold the CTRL/⌘ key to choose multiple products.
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                {!! Form::label('on_subtotal', 'Apply discount on:') !!}
            </div>
            <div class="col-md-7">
                {!! Form::select('on_subtotal', [1 => 'Cart Subtotal', 0 => 'Cart Total'], null, ['class' => 'form-control']) !!}
                <div class="text-muted"><strong>Cart Subtotal:</strong> Product and addons sum. <br><strong>Cart
                        Total: </strong> Product, addons and tax sum.
                </div>
            </div>
        </div>
    </div>

    <div class="box-footer">
        {!! Form::input('submit', null, $submitLabel, ['class' => 'btn btn-primary']) !!}
        @if ( Request::is('*/edit') )
            <a href="{{route('ch-admin.coupon.index')}}" class="btn btn-default">Cancel</a>
        @endif
    </div>
</div>
