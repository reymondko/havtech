<!DOCTYPE html>
<html data-wf-page="5e221d91bd16c0ad923fcbf6" data-wf-site="5e20755d88f6a4ca9d586892">
<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  {!! MetaTag::openGraph() !!}
  {!! MetaTag::twitterCard() !!}
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ url('/eventshub/images/favicon.png')}}">
  <link href="/eventshub/css/normalize.css" rel="stylesheet" type="text/css">
  <link href="/eventshub/css/webflow.css" rel="stylesheet" type="text/css">
  <link href="/eventshub/css/havtech-events-menu.webflow.css" rel="stylesheet" type="text/css">
  <link href="/eventshub/css/havtech-events.webflow.css" rel="stylesheet" type="text/css">
  <script src="https://use.typekit.net/zaq1ikf.js" type="text/javascript"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
  <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
  <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

  <script>
$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();
    $('html, body').animate({
        scrollTop: $($.attr(this, 'href')).offset().top + -200
    }, 1000);
});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-142827597-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-142827597-1');
</script>
@yield('css')
</head>
<body class="body">
  <div data-collapse="medium" data-animation="default" data-duration="400" class="navbar w-nav">
    <div class="navbar-container"><a href="/" class="brand w-nav-brand"><img src="/eventshub/images/Havtech-Eventshub-logo.svg" width="450" alt="Havtech Eventshub" class="logo"></a>
      <nav role="navigation" class="navmenu w-nav-menu">
        <a href="/events" class="nav-events w-nav-link">Events</a>
        <a href="/event-types" class="nav-programs w-nav-link">Event Types</a>
        <a href="/faqs" class="nav-faqs w-nav-link">FAQs</a>
        <a href="/download-mobile-app" class="nav-download w-nav-link">Download App</a>
        <!--<div data-delay="0" data-hover="1" class="dropdown w-dropdown">
          <div class="nav-about w-dropdown-toggle">
            <div class="nav-icon w-icon-dropdown-toggle"></div>
            <div class="nav-dropdown-text">About</div>
          </div>
          <nav class="nav-dropdown w-dropdown-list">
            <a href="/about" class="subnav-about w-dropdown-link">About Us</a>
            <a href="/mission" class="subnav-mission w-dropdown-link">Mission</a>
            <a href="/faqs" class="subnav-faqs w-dropdown-link">FAQs</a>
          </nav>
        </div>
        <a href="/about" class="nav-about-m w-nav-link">About</a>
        <a href="/mission" class="nav-mission-m w-nav-link">Mission</a>!-->
        <a href="/contact" class="nav-contact w-nav-link">Contact</a>

        @if(Auth::check())
        <a href="{{route('edit_user_account')}}" class="nav-create-account w-nav-link">Account</a>
        @else
        <a href="{{route('register')}}" class="nav-create-account w-nav-link">Account</a>
        @endif

        @if (Auth::check())
          <a href="{{ route('logout') }}" class="nav-login w-nav-link">Logout</a></nav>
        @else
          <a href="{{ route('login') }}" class="nav-login w-nav-link">Login</a></nav>
        @endif

      <div class="menu-button w-nav-button">
        <div class="icon-3 w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  @if(!Route::is('homepage'))
    @yield('header_image')
    <!-- <div class="page-section events"> -->
      <div class="caption-container">
        <div class="caption">
          <h1 class="page-heading">@yield('header_title')</h1>
        </div>
      </div>
    </div>
  @endif

  @yield('content')

  <div class="footer">
    <div class="main-container">
      <div class="footer-row">
        <div class="footer-column"><img src="/eventshub/images/Havtech-Logo.svg" alt="Havtech" class="footer-logo">
          <p class="footer-paragraph">9505 Berger Road, Columbia, MD 21046 <br></p>
          <a href="tel:3012069225" class="footer-link w-inline-block">
            <div>(301) 206-9225</div>
          </a>
          <div class="social-container"><a href="https://www.facebook.com/HavtechLLC/" target="_blank" class="social-link facebook w-inline-block"></a><a href="https://twitter.com/havtech" target="_blank" class="social-link twitter w-inline-block"></a>
            <!--<a href="https://www.instagram.com/explore/tags/havtech" target="_blank"  class="social-link instagram w-inline-block"></a>!-->
            <a href="https://www.linkedin.com/company/havtech" target="_blank"  class="social-link linkedin w-inline-block"></a>
          </div>
        </div>
        <div class="footer-column">
          <h5 class="heading-5">About Havtech</h5>
          <p class="footer-paragraph">Havtech represents the most innovative HVAC equipment and building automation system manufacturers serving both the commercial and industrial markets. Combined with our expertise in energy efficient HVAC system design and green building methods, we offer cost-effective solutions that provide a low environmental impact at the highest return on investment for our customers.
			  <p class="more"><a class="more" href="http://www.havtech.com" target="_blank" >Learn more about Havtech</a></p>
        </div>
        <div class="footer-column">
          <h5 class="heading-5">Site Navigation</h5>
          <div class="footer-nav">
            <a href="/" class="footer-link w-inline-block">
              <div>Home</div>
            </a>
            <a href="/events" class="footer-link w-inline-block">
              <div>Events</div>
            </a>
            <a href="/event-types" class="footer-link w-inline-block">
              <div>Event Types</div>
            </a>
            <a href="/faqs" class="footer-link w-inline-block">
              <div>FAQs</div>
            </a>
            <a href="/download-mobile-app" class="footer-link w-inline-block">
              <div>Download App</div>
            </a>
            <!--<a href="/about" class="footer-link w-inline-block">
              <div>About</div>
            </a>
            <a href="/mission" class="footer-link w-inline-block">
              <div>Mission</div>
            </a>!-->
            <a href="/contact" class="footer-link w-inline-block">
              <div>Contact</div>
            </a>
            @if (Auth::check())
            <a href="/account/edit" class="footer-link w-inline-block">
              <div>Account</div>
            </a>
            @else
              <a href="{{ route('login') }}" class="footer-link w-inline-block">Account</a></nav>
            @endif
            @if (Auth::check())
            <a href="{{ route('logout') }}" class="footer-link w-inline-block">Logout</a></nav>
            @else
              <a href="{{ route('login') }}" class="footer-link w-inline-block">Login</a></nav>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mojo-section">
    <p class="et-mojo-p">powered by: </p><a href="https://mojo.biz" target="_blank" class="et-mojo-lb w-inline-block"><img src="/eventshub/images/moo.svg" alt="Mojo Creative Digital" class="et-mojo-logo"></a></div>
  <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.4.1.min.220afd743d.js" type="text/javascript" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="/eventshub/js/webflow.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->

	@yield('js')
</body>
</html>
