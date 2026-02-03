@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto mt-20 bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label class="block mb-2">Email</label>
        <input type="email" name="email" class="w-full border rounded p-2 mb-4" required>
        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="w-full border rounded p-2 mb-4" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
    </form>
</div>
@endsection
