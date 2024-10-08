@extends('template')
@section('title', 'Search Result')

@section('content')
    <div class="row mt-4">
        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="mb-2">
            <div class="row g-2">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search by name"
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2" style="width: 20%">
                    <div class="row g-2">
                        <!-- Gender Filter -->
                        <div class="col-md-6">
                            <select name="gender" class="form-select">
                                <option value="">All Genders</option>
                                <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Hobbies Filter -->
                        <div class="col-md-6">
                            <select name="hobbies" class="form-select">
                                <option value="">All Hobbies</option>
                                @php
                                    $possibleHobbies = [
                                        'Reading',
                                        'Travelling',
                                        'Sports',
                                        'Music',
                                        'Gaming',
                                        'Cooking',
                                        'Gardening',
                                        'Photography',
                                        'Crafting',
                                        'Painting',
                                    ];
                                @endphp
                                @foreach ($possibleHobbies as $hobby)
                                    <option value="{{ $hobby }}"
                                        {{ request('hobbies') == $hobby ? 'selected' : '' }}>
                                        {{ $hobby }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
    </div>

    <h3>Search Results for "{{ $query }}"</h3>

    @if ($users->isEmpty())
        <p>No users found.</p>
    @else
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            @foreach ($users as $user)
                <div class="card" style="width: 18rem;">
                    <img src="{{ Storage::url('photos/' . $user->images_url) }}" class="card-img-top" alt="User Image">

                    <div class="card-body">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <p class="card-text">Gender: {{ $user->gender }}</p>
                        <p class="card-text">Hobbies:</p>
                        <ul>
                            @foreach (json_decode($user->hobbies, true) as $hobby)
                                <li>{{ $hobby }}</li>
                            @endforeach
                        </ul>

                        @php
                            // Check if the logged-in user is friends with this user
                            $isFriend = \App\Models\Friend::where('user_id', Auth::id())
                                ->where('friend_id', $user->id)
                                ->exists();
                        @endphp

                        @if ($isFriend)
                            <a href="{{ route('message', ['user_id' => $user->id]) }}" class="btn btn-success">Send
                                Message</a>
                        @else
                            <form action="{{ route('friends-request.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-primary">Thumbs Up!</button>
                            </form>
                        @endif


                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
