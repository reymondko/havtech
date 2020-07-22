@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Programs')
@section('header_image')
  <div class="page-section programs">
@endsection
@section('header_title', 'Event Types')

@section('content')
<div class="content-section intro-block programs">
  <h1 class="heading-2 centered">About Our Events</h1>
  <p class="paragraph centered">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxim.</p>
</div>
  <div class="full-width-section hli">
    <div class="main-container hli">
      <div class="intro-content programs">
        <h2 class="heading-2">Havtech Learning Institute</h2>
        <p class="paragraph">
          At Havtech Learning Institute, we feel it is our responsibility to lay the strongest foundation possible while training others in the HVAC industry, setting up the finest building blocks for your continued success. Constantly educating ourselves on energy and efficiency requirements while readily evaluating and embracing emerging manufacturer technologies and systems, our expertise and experience is what sets us apart as benchmark leaders among our peers and enables us to help you achieve the same standard. 
        </p>  
        <p class="paragraph">
          Our state-of-the-art training center is centrally located in Columbia, Maryland, and we also offer in-house training for your convenience. Each course employs a syllabus tailored to meet the needs and demands of our students. Havtech is the Mid-Atlantic’s largest and most experienced commercial HVAC equipment, building automation systems, service and energy solutions provider in Maryland and the metro DC region.

        </p>
        <div class="button-row">
          <a href="{{ route('eventsall') }}" class="base-button blue-button first-button w-button" data-ix="button-hover">View  Events</a>
          <!--<a href="#" target="_blank" class="base-button orange-button w-button" data-ix="button-hover">View PDF</a>!-->
        </div>
      </div>
    </div>
  </div>
  <div class="havtech-blue-section hvac-bootcamp" data-ix="fade-in">
    <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
    <div class="main-container hvac">
      <div class="hvac-graphic"></div>
      <div class="intro-content programs">
        <div class="hvac-bootcamp"></div>
        <p class="paragraph light-blue">Our new 6-week HVAC Bootcamp Series is offered biannually and emphasizes the fundamentals of commercial HVAC knowledge and systems. Ideal for engineers, building operators, and contractors, this series is also the perfect refresher course for those looking to stay current in the HVAC industry.
        </p>
        <div class="button-row">
          <a href="/events"  class="base-button orange-button first-button w-button" data-ix="button-hover">View All Events</a>
          <!--<a href="#" target="_blank" class="base-button w-button" data-ix="button-hover">View PDF</a>!-->
        </div>
      </div>
    </div>
  </div>
  <div class="havtech-orange-section" data-ix="fade-in">
    <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
    <div class="main-container hli">
      <div class="intro-content programs">
        <h2 class="heading-2 white">Innovative Solutions Seminar</h2>
        <p class="paragraph white">Stay on the forefront of evolving energy and efficiency requirements with Havtech’s monthly educational seminars! We offer on-site and factory training through our manufacturers to keep you well-informed of and well-versed in any industry advancements.
</p>
        <div class="button-row">
          <a href="/events" class="base-button blue-button first-button w-button" data-ix="button-hover">View All Events</a>
          <!--<a href="#" target="_blank" class="base-button w-button" data-ix="button-hover">View PDF</a>!-->
        </div>
      </div>
      <div class="image-container iss"></div>
    </div>
  </div>
@stop