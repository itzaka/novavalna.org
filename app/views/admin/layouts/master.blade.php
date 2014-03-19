<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{Lang::get('basic.site.name')}}</title>
    <link rel="stylesheet" href="{{asset('foundation/css/app-admin.css')}}" />
    <link rel="stylesheet" href="{{asset('packages/foundation-icons/foundation-icons.css')}}" />
    <script src="{{asset('foundation/bower_components/modernizr/modernizr.js')}}"></script>
  </head>
  <body>
  <section id="wrapper">
  <header>
    <div class="row heading">
      <div class="large-9 medium-8 columns">
        <h2>{{Lang::get('basic.site.name')}}</h2>
        <h4>Admin panel</h4>
      </div>
      <div class="large-3 medium-4 hide-for-small columns text-right">
        <a href="{{URL::to('/')}}" class="button margintop-20px" target="_blank">към уеб сайт</a>
      </div>
    </div>
  </header>

    <section id="container" class="row">
      <div class="off-canvas-wrap">
        <div class="inner-wrap">
          <nav class="tab-bar hide-for-large">
            <section class="right-small">
              <a class="right-off-canvas-toggle menu-icon" ><span></span></a>
            </section>
          </nav>
          <!-- Off Canvas Menu -->
          <aside class="right-off-canvas-menu hide-for-large">
            <ul class="off-canvas-list no-bullet">
              @include('admin.layouts.menu')
            </ul>
          </aside>

          <section class="main-section">
            <div class="large-10 left small-12">
              @yield('content')
            </div>
            <div class="large-2 columns show-for-large-up">
              <div class="sidebar">
                <ul class="side-nav menu no-bullet">
                  @include('admin.layouts.menu')
                </ul>
              </div>
            </div>

          </section>
          <!-- close the off-canvas menu -->
          <a class="exit-off-canvas"></a>
        </div>
      </div>
    </section>
</div>
<div id="hidden_footer"></div>
</section>
<footer id="footer">
  <div class="row">
    
    <a href="http://yatanski.com" target="_blank"><span class="yatanski">yatanski</span> <span class="studio">studio</span></a>
    <p class="copyright">© 2008–<?php echo date('Y')?> YATANSKI ltd. All rights reserved.</p>
  </div>
</footer>
    <script src="{{asset('foundation/bower_components/jquery/jquery.js')}}"></script>
    <script src="{{asset('foundation/bower_components/foundation/js/foundation.min.js')}}"></script>
    <script src="{{asset('foundation/js/app.js')}}"></script>
    @yield('scripts')
  </body>
</html>
