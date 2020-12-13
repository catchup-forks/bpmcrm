<div id="inner-sidebar" class="closed">
<div>innerbar</div>
    <ul class="nav flex-column expanded" id="innerbar-toggle">
    <div>
      <li class="logo">
        <a href="#" >
            <img src="/img/processmaker-logo-white-sm.png">
        </a>
      </li>
      <li class="logo-closed" id="innerbar-toggle">
        <a href="#">
            <img src="/img/processmaker_icon_logo-md.png">
        </a>
      </li>
    </ul>
      <ul class="nav flex-column">
    @foreach($innersidebar->topMenu()->items as $section)
      <li class="section">{{$section->title}}</li>
      @foreach($section->children() as $item)
        <li class="nav-item">
          <a href="{{ $item->url() }}" class="nav-link" title="{{$item->title}}">
                <i class="fas {{$item->attr('icon')}} nav-icon"></i> <span class="nav-text">{{$item->title}}</span>
              </a>
        </li>
        @endforeach
        @endforeach
  </ul>
</div>
