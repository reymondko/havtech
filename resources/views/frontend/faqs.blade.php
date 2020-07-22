@extends('frontend.layout')

@section('title', 'Havtech Events Hub - FAQs')
@section('header_image')
  <div class="page-section download-app">
@endsection
@section('header_title', 'FAQ\'s')

@section('content')
  <div class="content-section margins">
    <div class="heading-row margins border-bottom">
      <h2 class="heading-2">FAQs</h2>
      <!--<a href="/eventshub/about/mission.html" class="base-button orange-button faq w-button" data-ix="button-hover">Download</a>!-->
      </div>
    <div class="event-row no-border top-align" data-ix="fade-in">
      <div class="event-content full-width">
        <!--<div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">What is a General Event?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">
              Havtech offers building owners, design professionals, engineers, and contractors the opportunity to participate in free and paid hands-on roadshow demonstrations and market specific events/seminars including our Innovative Solution Seminars.
            </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">What is a Special Event?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">
              Havtech provides opportunities for our partners to see the most innovative solutions in the HVAC industry and further their professional development including special conferences and factory tours. These events are exclusive and by invitation only.
            </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">What is an HLI event?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">
              Havtech Learning Institute offers industry-leading training programs including PDH and AIA learning sessions, Courses include course instruction, training materials, light breakfast, and catered lunch.
            </p>
          </div>
        </div>!-->
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">Do I have to be a Havtech customer to attend events?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">
              •	General Events are open to those interested with HVAC experience and knowledge.<br>
              •	Learning Institute courses that are PDH and AIA eligible may require previous course experience.<br>
              •	Special Events are open to customers and partners by invite only.
            </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">What are the costs of Havtech Events?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">
              Havtech offers complimentary events as well as paid trainings. Tuition and event registration fees are per registrant, per course/event. All payments must be received upon enrollment. Payments can be made by providing a purchase order, credit card, or business check. Payment must be made in full prior to the event.
            </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">Where are your HLI classes located?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">Havtech Learning Institute boasts a state of the art, in-house classroom and training center at our Columbia location: 9505 Berger Rd. Columbia, MD 21046.</p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">How do I change my account login or password?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">
              Please visit our <a href="{{url('register')}}">“Account”</a> page to reset your username and password on the Havtech Events Hub website.
            </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">Who do I contact about working at Havtech?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">
              <a href="https://workforcenow.adp.com/mascsr/default/mdf/recruitment/recruitment.html?cid=8fb411ad-9387-470f-b615-4fddbb07c21e&ccId=2554070838_932&type=MP&lang=en_US" target="_blank">Click here</a> for a current list of Havtech job opportunities and/or find information on working at Havtech on our LinkedIn page and Facebook page.
              <!--<a href="https://workforcenow.adp.com/mascsr/default/mdf/recruitment/recruitment.html?cid=8fb411ad-9387-470f-b615-4fddbb07c21e&ccId=2554070838_932&type=MP&lang=en_US">https://workforcenow.adp.com/mascsr/default/mdf/recruitment/recruitment.html?cid=8fb411ad-9387-470f-b615-4fddbb07c21e&ccId=2554070838_932&type=MP&lang=en_US</a> for a current list of Havtech job opportunities and/or find information on working at Havtech on our LinkedIn page and Facebook page.!-->
            </p>
          </div>
        </div>
        <!-- <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">Who do I contact about working at Havtech?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusant doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">Lorem ipsum dolor sit amen, consectetur adipiscing elit?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusant doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">Voluptatem accusant doloremque laudantium, total rem aperiam, eaque ipsa quad ab illo?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusant doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
          </div>
        </div>
        <div class="q-holder">
          <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
            <div class="faq-icon">+</div>
            <p class="question-title">Ut enim ad minim venom, quis nostrud exercitation?</p>
          </a>
          <div class="answer blue">
            <p class="paragraph description gray">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusant doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
          </div>
        </div> -->
      </div>
    </div>
  </div>
@stop
