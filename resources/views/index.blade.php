@extends('layouts/default')

@section('content')
<div class="block">
    <img src="{{asset('img/logo.svg')}}" alt="logo" width="30%">
    <br>
    <h1><span class="text-horizom-pink">H</span><span class="text-horizom-black">O</span><span class="text-horizom-pink">R</span><span class="text-horizom-black">I</span><span class="text-horizom-pink">Z</span><span class="text-horizom-black">O</span><span class="text-horizom-pink">M</span> <span class="text-horizom-black">Framework</span></h1>
    <h2>Introducing the future of framework</h2>
    <p class="text-bold">Welcome to the lightness PHP framework.</p>

    <div class="menu">
        <ul>
            <li>
                <a href="https://horizom.github.io/" class="text-horizom-pink" title="See the documentation" target="blank"><h2>Documentation</h1></a>
            </li>
        </ul>
    </div>
</div>
@endsection