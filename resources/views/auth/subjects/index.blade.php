@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ __('globals.user_subjects') }}
                        <a class="btn btn-primary" href="{{ route('subjects.create') }}" style="">@lang('globals.create')</a>

                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('globals.subject')</th>
                                <th scope="col">@lang('globals.role')</th>
                                <th scope="col">@lang('globals.count')</th>
                                <th scope="col">@lang('globals.teacher')</th>
                                <th scope="col">@lang('globals.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $subjects as $subject)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$subject->subject->name}}</td>
                                    <td>{{ $subject->role->toString() }}</td>
                                    <td>{{ $subject->count }}</td>
                                    <td>{{ $subject->teacher?->name }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a class="btn btn-secondary"
                                               href="{{ route('subjects.edit',$subject->id) }}"
                                               style="margin-inline-end: 10px">@lang('globals.edit')</a>
                                            <form action="{{ route('subjects.destroy',$subject->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="delete btn btn-danger">@lang('globals.delete')</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{$subjects->withQueryString()->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete').forEach(function (node) {
                node.addEventListener('click', function () {
                    if (confirm('Are you sure ?')) {
                        let form = null
                        for (const child of this.parentNode.children) {
                            console.log(child.tagName);
                            if (child.tagName === 'FORM') {
                                form = child
                                break
                            }
                        }
                        console.log(form)
                        if (!form) {
                            console.error('form error')
                            return;
                        }
                        form.submit();
                    }
                })
            })
        })
    </script>

@endsection
