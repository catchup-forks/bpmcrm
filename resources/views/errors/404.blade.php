@extends('layouts.minimal')
@section('content')
<div class="error-404">
    <div class="error-404-icon">
      <img src="/img/robot.png"/>
    </div>
    <div class="error-content">
      <h1>Oops!</h1>
      <p>The page you are looking for could not be found</p>
    </div>
</div>
@endsection

@section('css')

<style lang="scss" scoped>
    .error-404 {
      display: flex;
      flex-direction: column;
      justify-content: center;
      margin-top: 150px;
    }
    .error-404-icon {
      margin: auto;
    }
    img {
      margin-left: -33px;
    }
    .error-content {
      margin-top: 0px;
    }

</style>
@endsection