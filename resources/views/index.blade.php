@extends('layouts/default')

@section('content')
<div class="block">
    <h1>{{ $name }}</h1>
    <h2>Introducing the future of framework</h2>
    <p>Welcome to the light PHP framework.</p>

    <div class="menu">
        <ul>
            <li><a href="#" title="See the documentation">Documentation</a></li>
            <li>Thank !</li>
            <li>
                <a href="https://github.com/Hen-Dricks/horizon-framework" title="Fork on github"
                    target="blank">Github</a>
            </li>
        </ul>
    </div>
</div>
@endsection