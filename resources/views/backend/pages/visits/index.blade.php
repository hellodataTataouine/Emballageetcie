@extends('backend.layouts.master')

@section('title')
    {{ localize('Visites') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection
@section('contents')
<style>
.charts_orb {
  display: flex;
  align-items: flex-start;
  justify-content: left;
  flex-wrap: wrap;
  font-family: arial;
  color: white;
}
.charts_orb .orb {
  padding: 20px;
}
.charts_orb .orb .orb_graphic {
  position: relative;
}
.charts_orb .orb .orb_graphic .orb_value {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5em;
  font-weight: bold;
}
.charts_orb .orb .orb_label {
  text-transform: uppercase;
  text-align: center;
  margin-top: 1em;
}
.charts_orb svg {
  width: 110px;
  height: 110px;
}
.charts_orb svg circle {
  transform: rotate(-90deg);
  transform-origin: 50% 50%;
  stroke-dasharray: 314.16, 314.16;
  stroke-width: 2;
  fill: transparent;
  r: 50;
  cx: 55;
  cy: 55;
}
.charts_orb svg circle.fill {
  stroke: #d3d3d3;
}
.charts_orb svg circle.progress {
  stroke: #ff6b00;
  transition: stroke-dashoffset 0.35s;
  stroke-dashoffset: 214.16;
  -webkit-animation: NAME-YOUR-ANIMATION 1.5s forwards;
  -webkit-animation-timing-function: linear;
}
@-webkit-keyframes NAME-YOUR-ANIMATION {
  0% {
    stroke-dashoffset: 314.16;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

.orb_label{
    color: #000;
}
.orb_value{
    color: #000;
}
</style>
<h1 style="margin: 15px;">{{localize('Nombre de visites')}}</h1>
@foreach ($countries as $countryCode => $countryData)
<span>
    <img src="{{ $countryData['flag'] }}" alt="{{ $countryData['name'] }}" style="width: 32px; height: auto;">
    <h2 style="margin: 15px; display: inline;">{{$countryData['name']}}</h2>
</span>
<section class="charts_orb">
	<article class="orb">
		<div class="orb_graphic">
			<svg>
				<circle class="fill"></circle>
				<circle class="progress"></circle>
			</svg>
			<div class="orb_value count"> 
                {{$countryData['today'] ?? 0}}
            </div>
		</div>
		<div class="orb_label">
			{{localize('Ce Jour')}}
		</div>
	</article>
	
	<article class="orb">
		<div class="orb_graphic">
			<svg>
				<circle class="fill"></circle>
				<circle class="progress"></circle>
			</svg>
			<div class="orb_value count">
                {{ $countryData['week'] ?? 0 }}
            </div>
		</div>
		<div class="orb_label">
			{{localize('Cette Semaine')}}
		</div>
	</article>
	
	<article class="orb">
		<div class="orb_graphic">
			<svg>
				<circle class="fill"></circle>
				<circle class="progress"></circle>
			</svg>
			<div class="orb_value count">
                {{ $countryData['year'] ?? 0 }}
            </div>
		</div>
		<div class="orb_label">
			{{localize('Cette année')}}
		</div>
	</article>
	
	
</section>
@endforeach

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 1500,
        easing: 'linear',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});
</script>

@endsection