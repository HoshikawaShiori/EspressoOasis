@extends('layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('src/css/shop-card.css') }}">
@endsection
@section('title')
    Contact
@endsection

@section('content')

            <div class="container-fluid mx-0">
                <div class="row justify-content-center py-5" style="background: url('{{ asset('src/images/espressopull.jpg') }}');">
                    <div class="col-md-6 text-center mb-5">
                        <div class="header">
                            <h1 class="display-2 text-light">
                                Contact
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="wrapper">
                            <div class="row no-gutters mb-5">
                                <div class="col-md-7">
                                    <div class="contact-wrap w-100 p-md-5 p-4">
                                        <h3 class="mb-4">Contact Us</h3>
                                        <div id="form-message-warning" class="mb-4"></div>
                                        <div id="form-message-success" class="mb-4">
                                        </div>
                                        <form method="POST" id="contactForm" action="{{ route('contact.submit') }}" name="contactForm" class="contactForm">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="label" for="name">Full Name</label>
                                                        <input type="text" class="form-control" name="name" id="name"
                                                            placeholder="Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="label" for="email">Email Address</label>
                                                        <input type="email" class="form-control" name="email" id="email"
                                                            placeholder="Email" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="label" for="subject">Subject</label>
                                                        <input type="text" class="form-control" name="subject" id="subject"
                                                            placeholder="Subject" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="label" for="#">Message</label>
                                                        <textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="submit" value="Send Message" class="btn btn-primary" >
                                                        <div class="submitting"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-5 d-flex align-items-stretch pt-3">
                                    <div id="map">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d341.27180895634234!2d120.99004906465136!3d14.596240144908501!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9f7aa961479%3A0x12e7f7568a6e45a4!2s1512%20Arlegui%20St%2C%20Quiapo%2C%20Manila%2C%201001%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1701626825796!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
          @include('partials.footer')
  
@endsection
