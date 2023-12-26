@extends('layouts.admin.guest')
@section('content')

    <div class="container">
        <div class="row">
            @if(session()->has('error')) 
    <p class="alert alert-danger small">{{session('error')}}</p>
    @endif

    @if(session()->has('success')) 
    <p class="alert alert-success small text-center">{{session('success')}}</p>
    @endif
    <div class="text-center">
        <h4 class="py-4">Forgot Password?</h4>
        <form method="POST" action="{{ route('admin.password.reset.submit') }}">
            @csrf
           
            <!-- Email Address -->
            <div>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <div class="flex items-center justify-center mt-4">
                <x-primary-button>
                    Password Reset
                </x-primary-button>
            </div>
        </form>
    </div>
        </div>
    </div>
    @endsection
