@extends('layouts.admin.guest')
@section('content')
  <div class="container">
    <div class="row">

            <h3 class="text-center">Reset Your Password</h3>
            @if(session()->has('error')) 
            <p class="alert alert-danger small">{{session('error')}}</p>
            @endif
        
            @if(session()->has('success')) 
            <p class="alert alert-success small text-center">{{session('success')}}</p>
            @endif
            <form method="POST" action="{{ route('admin.new.password.submit') }}">
                @csrf
                <input type="text" name="token" value="{{$token}}" hidden>
                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{$email}}" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
        
                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
        
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
        
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
        
                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        
                    <x-text-input id="confirm_password" class="block mt-1 w-full"
                                    type="password"
                                    name="confirm_password" required autocomplete="new-password" />
        
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
        
                <div class="flex items-center justify-center mt-4">
        
                    <x-primary-button class="ml-4">
                        Reset Your Password
                    </x-primary-button>
                </div>
            </form>
 
    </div>
  </div>
    @endsection
