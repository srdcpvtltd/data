@extends('themes.default.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div style="padding: 180px 0">
                    <h1>Complete your order</h1>
                    <p class="lead">Please click the button below and complete your order.</p>

                    @if($order->getMeta('_payment_method') == 'razorpay')
                        <form action="{{route('ch_verify_payment', $order->id)}}" method="POST" id="payment-form">
                            @method('POST')
                            @csrf
                            <button type="button" id="submit-payment" class="btn btn-primary">Pay now</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('ch_footer')
    @if($order->getMeta('_payment_method') == 'razorpay')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            (function () {
                // $("#submit-payment")[0].click();
                setTimeout(function(){
                    $("#submit-payment").click();
                },1);
            })();

            $("#submit-payment").on('click', function (e) {
                e.preventDefault();

                let razorOption = {!! json_encode($paymentData)  !!};

                razorOption.handler = function (response){

                    let razorpay_payment_id = '<input type="hidden" name="razorpay_payment_id" value="'+response.razorpay_payment_id+'">';
                    let razorpay_signature = '<input type="hidden" name="razorpay_signature" value="'+response.razorpay_signature+'">';

                    $("#payment-form").append(razorpay_payment_id);
                    $("#payment-form").append(razorpay_signature);
                    $("#payment-form").submit();
                };

                var rzp = new Razorpay(razorOption);

                rzp.open();

            });


        </script>
    @endif

    @if($order->getMeta('_payment_method') == 'instamojo')
        <script src="https://js.instamojo.com/v1/checkout.js"></script>
        <script>
            Instamojo.open('{{$order->getMeta('instamojo_longurl')}}');
        </script>
    @endif
@endpush