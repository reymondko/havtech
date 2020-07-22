@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Programs')
@section('header_image')
  <div class="page-section programs">
@endsection
@section('header_title', 'Event Types')

@section('content')
    <div class="content-section intro-block programs">
        <h1 class="heading-2 centered">About Our Events</h1>
        <p class="paragraph centered">Havtech provides different types of industry led training events and programs to give building owners, design professionals, engineers, and contractors the opportunity to learn about new products from manufacturers and partner with us to build a solid foundation for success.</p>
        
        <div class="button-row center">
            <a href="{{ route('faqs') }}" class="base-button orange-button w-button" data-ix="button-hover">View  FAQS</a>
        </div>

    </div>
    <div class="full-width-section general" data-ix="fade-in">
        <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
        <div class="main-container hli">
        <div class="intro-content programs">
            <h2 class="heading-2" style="color:#FFF">General Events</h2>
            <p class="paragraph">
                Havtech offers building owners, design professionals, engineers, and contractors the opportunity to participate in General Events. These events are open to industry-related professionals interested in learning about market-specific events and seminars, hands-on roadshow demonstrations, and Innovative Solution Seminars.
            </p>  
            <!--<p class="paragraph">
            Our state-of-the-art training center is centrally located in Columbia, Maryland, and we also offer in-house training for your convenience. Each course employs a syllabus tailored to meet the needs and demands of our students. Havtech is the Mid-Atlantic’s largest and most experienced commercial HVAC equipment, building automation systems, service and energy solutions provider in Maryland and the metro DC region.

            </p>!-->
            <div class="button-row">
            <a href="{{ route('eventsall') }}" class="base-button orange-button first-button w-button" data-ix="button-hover">View  Events</a>
            <!--<a href="#" target="_blank" class="base-button orange-button w-button" data-ix="button-hover">View PDF</a>!-->
            </div>
        </div>
        <div class="image-container general"></div>
        </div>
        
    </div>
    <div class="full-width-section learning" data-ix="fade-in">
        <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
        <div class="main-container roadshow">
        <div class="image-container learning"></div>
        <div class="intro-content programs">
            <h2 class="heading-2 white">Learning Institute Events</h2>
            <p class="paragraph white">
                Havtech Learning Institute (HLI) offers manufacturer training with a tailored syllabus to meet the needs of your team. Train with us in our state-of-the-art training center, located at our corporate headquarters in Columbia, MD, or receive hands-on training at your preferred location
            </p>
            <div class="button-row">
            <a href="{{ route('eventsall') }}" class="base-button orange-button w-button" data-ix="button-hover">View  Events</a>
            <!--<a href="#" target="_blank" class="base-button orange-button w-button" data-ix="button-hover">View PDF</a>!-->
            </div>
        </div>
        </div>
    </div>
    <div class="havtech-orange-section" data-ix="fade-in">
        <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
        <div class="main-container hli">
        <div class="intro-content programs">
            <h2 class="heading-2 white">Special Events </h2>
            <p class="paragraph white">
                Havtech provides enriching opportunities for our partners to see the most innovative solutions in the HVAC industry to further their professional development. Special Events, including conferences, factory tours, and exclusive events are by invitation only.
            </p>
            <div class="button-row">
            <a href="{{ route('eventsall') }}" class="base-button blue-button first-button w-button" data-ix="button-hover">View  Events</a>
            <!--<a href="#" target="_blank" class="base-button orange-button w-button" data-ix="button-hover">View PDF</a>!-->
            </div>
        </div>
        <div class="image-container special"></div>
        </div>
    </div>
@stop