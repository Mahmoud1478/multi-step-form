@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ __('globals.user_subjects') }} / {{ $subjectUser->subject->name }}
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input disabled type="text" class="form-control" id="subject"
                                       value="{{ $subjectUser->subject->name }}">
                            </div>
                            @if($subjectUser->role == \App\Enums\User\RoleEnum::TEACHER)
                                <div class="mb-3">
                                    <label for="count" class="form-label">@lang('globals.count')</label>
                                    <input type="number" min="1"  step="1" class="form-control" id="count" name="count" value="{{$subjectUser->count}}">
                                </div>
                            @else
                                <div class="mb-3">
                                    <label for="teacher_id" class="form-label">@lang('globals.teacher')</label>
                                    <select  class="form-control" id="teacher_id">
                                        @foreach($subjectUser->subject->teachers as $teacher)
                                            <option value="{{$teacher->id}}" @selected($teacher->id == $subjectUser->teacher_id)> {{$teacher->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
