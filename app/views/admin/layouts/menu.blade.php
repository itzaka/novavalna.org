    <li @if(Request::segment(2)=='') class="active" @endif><a href="{{URL::to('admin')}}">{{Lang::get('menu.home')}}</a>
    <li @if(Request::segment(2)=='posts') class="active" @endif><a href="{{URL::to('admin/posts')}}">{{Lang::get('menu.posts')}}</a>
    <ul>
      <li @if(Request::get('type')=='about' && Request::segment(2)=='posts') class="active" @endif><a href="{{URL::to('admin/posts?type=about')}}">{{Lang::get('menu.about')}}</a></li>
      <li @if(Request::get('type')=='news' && Request::segment(2)=='posts') class="active" @endif><a href="{{URL::to('admin/posts?type=news')}}">{{Lang::get('menu.news')}}</a></li>
      <li @if(Request::get('type')=='events' && Request::segment(2)=='posts') class="active" @endif><a href="{{URL::to('admin/posts?type=events')}}">{{Lang::get('menu.events')}}</a></li>
      <li @if(Request::get('type')=='activities' && Request::segment(2)=='posts') class="active" @endif><a href="{{URL::to('admin/posts?type=activities')}}">{{Lang::get('menu.activities')}}</a></li>
      <li @if(Request::get('type')=='summer-camp' && Request::segment(2)=='posts') class="active" @endif><a href="{{URL::to('admin/posts?type=summer-camp')}}">{{Lang::get('menu.summer-camp')}}</a></li>
      <li @if(Request::get('type')=='vlog' && Request::segment(2)=='posts') class="active" @endif><a href="{{URL::to('admin/posts?type=vlog')}}">{{Lang::get('menu.vlog')}}</a></li>
    </ul>
  </li>
  <li @if(Request::segment(2)=='categories') class="active" @endif><a href="{{URL::to('admin/categories')}}">{{Lang::get('menu.categories')}}</a></li>
  <li @if(Request::segment(2)=='albums') class="active" @endif><a href="{{URL::to('admin/albums')}}">{{Lang::get('menu.photos')}}</a></li>
  <li @if(Request::segment(2)=='banners') class="active" @endif><a href="{{URL::to('admin/banners')}}">{{Lang::get('menu.banners')}}</a></li>
  <li @if(Request::segment(2)=='polls') class="active" @endif><a href="{{URL::to('admin/polls')}}">{{Lang::get('menu.polls')}}</a></li>
  <li class="divider"></li>
  <li @if(Request::segment(2)=='users') class="active" @endif><a href="{{URL::to('admin/users')}}">{{Lang::get('menu.users')}}</a>
    <ul>
      <li @if(Request::segment(2)=='roles') class="active" @endif><a href="{{URL::to('admin/roles')}}">{{Lang::get('menu.roles')}}</a></li>
    </ul>
  </li>
  <li @if(Request::segment(2)=='profile') class="active" @endif><a href="{{URL::to('admin/profile')}}">{{Lang::get('menu.profile')}}</a></li>
  <li><a href="{{URL::to('user/logout')}}">Изход</a></li>
