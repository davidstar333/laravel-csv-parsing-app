@extends('layouts.app')
@section('styles')
{!! htmlScriptTagJsApi([
  'action' => 'homepage',
  'callback_then' => 'callbackThen',
  'callback_catch' => 'callbackCatch'
]) !!}
@endsection

@section('content')
<section class="probootstrap-hero prohttp://localhost/probootstrap/frame/#featuresbootstrap-slant"
style="background-image: url(./assets/img/image_1.jpg);" data-section="home" data-stellar-background-ratio="0.5">
<div class="container">
  <div class="row intro-text">
    <div class="col-md-8 col-md-offset-2 text-center">
      {!!$settings->banner_text!!}
      @guest
      <div class="probootstrap-subheading center">
        <p class="probootstrap-animate"><a href="{{ route('register') }}" role="button"
        class="btn btn-primary">Get Started</a></p>
      </div>
      @else
      <div class="probootstrap-subheading center">
        <p class="probootstrap-animate"><a href="{{ route('working_area') }}" role="button"
        class="btn btn-primary">Get Started</a></p>
      </div>
      @endguest
    </div>
  </div>
</div>
</section>

<section class="probootstrap-section" data-section="pricing">
  <div class="container">
    <div class="row justify-content-center text-center mb100">
      <div class="col-md-8 probootstrap-section-heading">
        {!!$settings->pk_title!!}
        {!!$settings->pk_text!!}
      </div>
    </div>
    <!-- END row -->
    <div class="row justify-content-center">

        @foreach($pricings as $pricing)
        <div class="col-md-4">
            <div class="probootstrap-pricing">
                <form action="{{ route('get_stripe_form') }}" method="post">
                    @csrf
                    <input type="hidden" name="_id" value="{{$pricing->id}}" />
                    <h2>{{$pricing->name}}</h2>
                    <p class="probootstrap-price"><strong>${{$pricing->price}}</strong></p>
                    <p class="probootstrap-note">{{$pricing->description}}</p>
                    <ul class="probootstrap-list text-left mb50">
                        <li class="probootstrap-check">Process {{$pricing->rows}} rows of your CSV file.</li>
                    </ul>
                    <p><button class="btn {{ $pricing->id === 2 ? 'btn-primary' : 'btn-black' }}">Get Started</button></p>
                </form>
            </div>
        </div>
        @endforeach

    </div>
  </div>
</section>
<!-- END section -->

<section class="probootstrap-section probootstrap-cta">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        {!!$settings->md_title!!}
        {!!$settings->md_text!!}
        @guest
          <p><a href="{{ route('register') }}" class="btn btn-black">Get Started</a></p>
        @else
          <p><a href="{{ route('working_area') }}" class="btn btn-black">Get Started</a></p>
        @endguest
      </div>
    </div>
  </div>
</section>
<section class="probootstrap-section probootstrap-bg-light" data-section="contact">
  <div class="container">
    <div class="row">
        @if (Session::has('success'))
            <div class="col-sm-12 col-xs-12">
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{Session::get('success')}}</p>
                </div>
            </div>
        @endif
      <div class="col-md-6 col-xs-12">
          <form action="{{ route('contact.post') }}" method="POST" class="probootstrap-form form">
              @csrf
              <h2 class="text-black mt0">Get In Touch</h2>
              <div class="form-group">
                  <input type="text" class="form-control" name="name" placeholder="Your Name" required>
              </div>
              <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Your Email" required>
              </div>
              <div class="form-group">
                  <input type="tel" class="form-control" name="phone" placeholder="Your Phone">
              </div>
              <div class="form-group">
                  <textarea class="form-control" cols="30" name="message" rows="10" placeholder="Write a Message" required></textarea>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Send Message">
              </div>
          </form>
      </div>
      <div class="col-md-3 col-xs-12 col-md-push-1">
        <ul class="probootstrap-contact-details">
          <li>
            <span class="text-uppercase">Email</span>
            <a href="mailto:{{$settings->email}}" class="to-email">{{$settings->email}}</a>
          </li>
          <li>
            <span class="text-uppercase">Phone</span>
            {{$settings->phone}}
          </li>
          <li>
            <span class="text-uppercase">Address</span>
            <pre>{{$settings->address}}</pre>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/custom.js') }}"></script>
@endsection