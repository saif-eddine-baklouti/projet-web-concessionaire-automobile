@extends('layouts.app')
@section('title')
    @lang('Car')
@endsection
@section('content')
<!-- référence: https://mdbootstrap.com/snippets/standard/mdbootstrap/4852176?view=project -->
<article class="py-5">
  <div class="container">
    <div class="row gx-5">
      <aside class="col-lg-6">
        <div class="border img-container rounded-4 mb-3 d-flex justify-content-center">
            <img style="max-width:100%; max-height:100vh; margin:auto;" class="rounded-4 fit" src="{{asset('images/'.$voiture->id.'/'.$voiture->photos[0]->photo_titre)}}" id="imglarge"/>
        </div>
        <div class="d-flex justify-content-center mb-3" id="imglist">
          @foreach($voiture->photos as $photo)
            <img width="101" height="60" class="rounded-2 m-1" src="{{asset('images/'.$voiture->id.'/'.$photo->photo_titre)}}" />
          @endforeach
        </div>
        <!-- thumbs-wrap.// -->
        <!-- gallery-wrap .end// -->
      </aside>
      <section class="col-lg-6">
        <div class="ps-lg-3">
          <h4 class="title text-dark mb-4 text-uppercase">
            {{$marques->find($voiture->modele->modele_marque_id)->marque_en}} {{$voiture->modele->modele_en}}
          </h4>

          <div class="mb-3">
            <span class="h1">{{$voiture->prix_paye}}</span>
            <span class="text-muted">CAD</span>
          </div>
          @php session('locale')=='en'? $description = 'description_en' : $description = 'description_fr' @endphp
          <p>
          {{$voiture->$description}}
          </p>

          <div class="row">
            {{--dump($voiture->photos)--}}
            <dt class="col-3">@lang('Year'):</dt>
            <dd class="col-9">{{$voiture->annee}}</dd>
            <!-- carrosserie_en et carrosserie_fr sont inversés dans la bd -->
            @php session('locale')=='en'? $carrosserie = 'carrosserie_fr' : $carrosserie = 'carrosserie_en' @endphp
            <dt class="col-3">@lang('Bodywork')</dt>
            <dd class="col-9">{{$voiture->carrosserie->$carrosserie}}</dd>

            @php session('locale')=='en'? $le_pays = 'pays_en' : $le_pays = 'pays_fr' @endphp
            <dt class="col-3">@lang('Country of origin')</dt>
            <dd class="col-9">{{$voiture->pays->$le_pays}}</dd>

            <dt class="col-3">@lang('Arrival date')</dt>
            <dd class="col-9">{{$voiture->date_arrivee}}</dd>
          </div>
          <hr class="mb-4" />
          <!-- Auth::user() -->
          <div class="d-flex gap-3">
            <form action="{{ route('reservation.store') }}" method="post">
              @csrf
              <input type="hidden" name="voiture_id" value="{{ $voiture->id }}">
              <button type="submit" class="btn btn-light border border-secondary py-2 icon-hover px-3"> <i class="me-1 fa fa-heart fa-lg"></i>@lang('Reserve')</button>
            </form>
              <form action="{{ route('panier.store') }}" method="post">
                @csrf
                <input type="hidden" name="voiture_id" value="{{ $voiture->id }}">
                <input type="hidden" name="photo_principale" value=" {{ $voiture->photos[0]['photo_titre'] }} ">
                <input type="hidden" name="marque" value="{{$marques->find($voiture->modele->modele_marque_id)->marque_en}}" >
                <input type="hidden" name="modele" value="{{$voiture->modele->modele_en}}" >
                <input type="hidden" name="prix" value="{{$voiture->prix_paye}}" >
                @auth
                <input type="hidden" name="province_user_id" value="{{ Auth::user()->province_id }}" >
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }} ">
                @endauth
                <button type="submit" class="btn btn-primary p-2 shadow-0"> @lang('Add to cart') </button>
              </form>
            </div>
        </div>
        @if($voiture->user_reserve)
							<div class="text-dark m-4 shadow-sm rounded border bg-light text-center pt-2"><h5>@lang('Reserved car')</h5>
            @foreach($voitures_reservees as $voiture_reservee)
                <p><span class="text-muted text-center">@lang('Remaining time'): {{$voiture_reservee[2]}}</span></p>
            @endforeach
              </div>
        @endif
      </section>
    </div>
  </div>
</article>
<script>
  /** changer l'image lorsque cliquer */
  const imglarge = document.getElementById('imglarge');
  const imglist = document.getElementById('imglist').children;

  for (const i in imglist){
    imglist[i].addEventListener('click', function(e){
      imglarge.src = e.target.src;
    });
  };
</script>
@endsection