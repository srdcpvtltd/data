<div class="col-md-8">

    @include('admin.layouts.errors')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Order #456 details</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-4">
                <Label>General</Label>

                        <div class="form-group">
                            {!! Form::label('date', 'Date Created:') !!}
                            {!! Form::text('date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Select date']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('status', 'Status:') !!}
                            {{-- {!! Form::select('status', null, ['class' => 'form-control', 'placeholder' => 'Select status']) !!} --}}
                            <select name="status" id="Status" class='form-control'>
                                <option value="1">pending payment</option>
                            </select>
                        </div>

                        <div class="form-group">
                                {!! Form::label('guest', 'Guest:') !!}
                                <select name="guest" id="guest" class='form-control'>
                                    <option value="1">Guest</option>
                                </select>
                            </div>
            </div>

            <div class="col-md-4">
                    <Label>Billing</Label>
                    <a href="#" class="pull-right fa fa-pencil"></a>

                            <div class="form-group">
                                {!! Form::label('billing_address', 'Address:') !!}
                                {!! Form::textarea('billing_address', null, ['class' => 'form-control', 'placeholder' => 'No Billing address set']) !!}
                            </div>

                </div>

                <div class="col-md-4">
                        <Label>Shiping</Label>
                        <a href="#" class="pull-right fa fa-pencil"></a>
                                <div class="form-group">
                                    {!! Form::label('shipping_address', 'Address:') !!}
                                    {!! Form::textarea('shipping_address', null, ['class' => 'form-control', 'placeholder' => 'No Shipping address set']) !!}

                                </div>

                    </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">PDF Invoice data</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('invoice', 'Invoice:') !!}<br>
                {!! Form::input('button', null, 'Set Invoice number & date', ['class' => 'btn btn-default']) !!}

            </div>
        </div>
    </div>

    <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Feature image</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group uploader" data-gallery-images="@if (isset($order->feature_image)) {{json_encode($order->feature_image)}} @endif">
                    @include('admin.media.upload')
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Cost</th>
                            <th>Qty</th>
                            <th></th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total:</th>
                                <th></th>
                            </tr>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-default">Add item(s)</button>
                <button type="button" class="btn btn-default">Add Coupon</button>
                <button type="button" class="pull-right btn btn-primary">Recalculate</button>

            </div><!-- /.box-body -->
        </div><!-- /.box -->


    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Downloadable Product Permission</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('product', 'Product:') !!}
                    <select name="product" id="product" class='form-control'>
                        <option value="1">List</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <button type="button" class="btn btn-default" style="margin-top: 26px;">Grant access</button>
            </div>

        </div>
    </div>

</div>

<div class="col-md-4">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Send Order Email</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <select name="order_email" id="order_email" class='form-control'>
                    <option value="1">List</option>
                </select>
                <br>
                {!! Form::input('submit', null, 'Save order and send email', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>


    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Order Action</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                    <select name="order_action" id="order_action" class='form-control'>
                        <option value="1">List</option>
                    </select>
                    <br>
                    <button type="button" class="pull-right btn btn-primary">Create</button>
            </div>
        </div>
    </div>

    <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create PDF</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                        <button type="button" class="btn btn-default">PDF Invoice</button>
                        <button type="button" class="pull-right btn btn-default">PDF Packing Slip</button>
                </div>
            </div>
        </div>

</div>

@section('ch_header')
    <link rel="stylesheet" href="{{url('assets/backend/js/vendors/bootstrap3-wysihtml5/bootstrap3-wysihtml5.css')}}">
@endsection



