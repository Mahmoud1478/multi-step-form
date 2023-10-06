@php
    use \App\Models\User;
    use \App\Enums\User\RoleEnum;
@endphp
@extends('layouts.app')
@section('css')
    <style>
        #signUpForm {
            max-width: 500px;
            background-color: #ffffff;
            margin: 40px auto;
            padding: 40px;
            box-shadow: 0px 6px 18px rgb(0 0 0 / 9%);
            border-radius: 12px;
        }

        #signUpForm .form-header {
            gap: 5px;
            text-align: center;
            font-size: .9em;
        }

        #signUpForm .form-header .stepIndicator {
            position: relative;
            flex: 1;
            padding-bottom: 30px;
        }

        #signUpForm .form-header .stepIndicator.active {
            font-weight: 600;
        }

        #signUpForm .form-header .stepIndicator.finish {
            font-weight: 600;
            color: #009688;
        }

        #signUpForm .form-header .stepIndicator::before {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            z-index: 9;
            width: 20px;
            height: 20px;
            background-color: #d5efed;
            border-radius: 50%;
            border: 3px solid #ecf5f4;
        }

        #signUpForm .form-header .stepIndicator.active::before {
            background-color: #a7ede8;
            border: 3px solid #d5f9f6;
        }

        #signUpForm .form-header .stepIndicator.finish::before {
            background-color: #009688;
            border: 3px solid #b7e1dd;
        }

        #signUpForm .form-header .stepIndicator::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 8px;
            width: 100%;
            height: 3px;
            background-color: #f3f3f3;
        }

        #signUpForm .form-header .stepIndicator.active::after {
            background-color: #a7ede8;
        }

        #signUpForm .form-header .stepIndicator.finish::after {
            background-color: #009688;
        }

        #signUpForm .form-header .stepIndicator:last-child:after {
            display: none;
        }

        #signUpForm input, #signUpForm select {
            padding: 15px 20px;
            width: 100%;
            font-size: 1em;
            border: 1px solid #e3e3e3;
            border-radius: 5px;
        }

        #signUpForm input:focus {
            border: 1px solid #009688;
            outline: 0;
        }

        #signUpForm input.invalid {
            border: 1px solid #ffaba5;
        }

        #signUpForm .step {
            display: none;
        }

        #signUpForm .form-footer {
            overflow: auto;
            gap: 20px;
        }

        #signUpForm .form-footer button {
            background-color: #009688;
            border: 1px solid #009688 !important;
            color: #ffffff;
            border: none;
            padding: 13px 30px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            flex: 1;
            margin-top: 5px;
        }

        #signUpForm .form-footer button:hover {
            opacity: 0.8;
        }

        #signUpForm .form-footer #prevBtn {
            background-color: #fff;
            color: #009688;
        }

        .dots {
            display: flex;
            text-align: center;
            width: 100%;
            align-items: center;
            justify-content: center;
        }

        .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 100%;
            border-bottom: 2px solid white;
            border-top: 2px solid white;
            border-right: 2px solid white;
        }

        .animate {
            animation: rotate linear 1s infinite;
        }

        .delay-1 {
            animation-delay: .1s;
        }

        .delay-2 {
            animation-delay: .2s;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{--                <div class="card">--}}

                {{--                <div class="card-body">--}}
                <form id="signUpForm" action="#!">

                    <div class="card-header mb-5 d-flex justify-content-center fw-bold align-items-center"
                         style="font-size: 25px">
                        {{ __('globals.subject') }} / @lang('globals.create')
                    </div>

                    <!-- start step indicators -->
                    <div class="form-header d-flex mb-4">
                        <span class="stepIndicator">@lang('globals.role_setup')</span>
                        <span class="stepIndicator">@lang('globals.subject_setup')</span>
                        <span class="stepIndicator">@lang('globals.subject_details')</span>
                        <span class="stepIndicator">@lang('globals.subject_finalization')</span>
                    </div>
                    <!-- end step indicators -->
                    <input type="hidden" class="prev" name="id" id="prev_id" value="{{$step->id}}">
                    <!-- step one -->
                    <div class="step">
                        <p class="text-center mb-4">@lang('globals.chose_role')</p>
                        <div class="mb-3">
                            <select oninput="this.className = ''" name="role">
                                <option value=" ">@lang('globals.chose_role')</option>
                                @foreach(RoleEnum::cases() as $role)
                                    <option
                                        value="{{$role->value}}" @selected(($step->data['role'] ?? null) == $role->value)>{{$role->toString()}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="invalid-1" class="invalid alert alert-danger d-none ">
                        </div>
                    </div>

                    <!-- step two -->
                    <div class="step">
                        <p class="text-center mb-4">@lang('globals.chose_subject')</p>
                        <div class="mb-3">
                            <select oninput="this.className = ''" name="subject_id">
                                <option value=" ">@lang('globals.chose_subject')</option>
                                @foreach( $subjects as $subject)
                                    <option
                                        value="{{$subject->id}}" @selected(($step->data['subject_id']?? null) == $subject->id)>{{$subject->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="invalid-2" class="invalid alert alert-danger d-none ">
                        </div>
                    </div>

                    <!-- step three -->
                    <div class="step">
                        <p class="text-center mb-4">@lang('globals.subject_details')</p>
                        <div class="mb-3" id="content">
                            @if($step->data['role'] ?? null == RoleEnum::STUDENT)
                                <select name="teacher_id" class="form-control">
                                    <option value=" ">@lang('globals.chose_teacher')</option>
                                    @foreach(User::query()
                                        ->whereHas('subjectUsers',
                                         fn ($q) => $q->where('role', RoleEnum::TEACHER)->where('subject_id', $step->data['subject_id']))
                                         ->get(['id', 'name']) as $teacher)
                                        <option value='{{$teacher->id}}' @selected(($step->data['teacher_id']??null) == $teacher->id) >{{$teacher->name}}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="number" class="form-control" name="count" step="1" min="1"
                                       placeholder="student count">
                            @endif
                        </div>
                        <div id="invalid-3" class="invalid alert alert-danger d-none ">
                        </div>
                    </div>
                    <div class="step">
                        <p class="text-center mb-4">@lang('globals.subject_finalization')</p>
                        <div class="mb-3 d-flex justify-content-center align-items-center">
                            <img class="image-fluid" src="{{url('check.jpeg')}}">
                        </div>
                        <div class="mb-3">
                            <input type="text"  @class(['form-control prev','d-none' => !isset($step->data['subject_id'])]) class="form-control d-none" value="{{$step->subject()?->name}}" id="prev_subject" readonly>
                        </div>
                        <div class="mb-3">
                            <input type="text" id="prev_role"  @class(['form-control prev','d-none' => !isset($step->data['role'])]) value="{{$step->role()}}" readonly>
                        </div>
                        <div class="mb-3">
                            <input type="number" id="prev_count" @class(['form-control prev','d-none' => !isset($step->data['count'])]) value="{{$step->count_()}}" readonly>
                        </div>
                        <div class="mb-3">
                            <input type="text" id="prev_teacher" @class(['form-control prev','d-none' => !isset($step->data['teacher_id'])]) value="{{$step->teacher()?->name}}" readonly>
                        </div>
                        <div id="invalid-4" class="invalid alert alert-danger d-none ">
                        </div>
                    </div>

                    <!-- start previous / next buttons -->
                    <div class="form-footer d-flex">
                        <button type="button" id="prevBtn" onclick="prev()">Previous</button>
                        <button type="button" id="nextBtn" onclick="next(this)">Next</button>
                    </div>
                    <!-- end previous / next buttons -->
                </form>
            </div>
        </div>
    </div>
    {{--    </div>--}}
    {{--    </div>--}}
@endsection

@section('js')
    <script>
        let currentTab = {{ $step->number ?? 0 }}; // Current tab is set to be the first tab (0)

        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("step");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function next(btn) {
            const form = document.getElementById('signUpForm');
            const ele = document.getElementById(`invalid-${currentTab + 1}`)

            const html = btn.innerHTML;
            const urls = ["{{route('subjects.store_one')}}", "{{route('subjects.store_two')}}", "{{route('subjects.store_three')}}",'{{route('subjects.finalization')}}'];
            if (currentTab >= 3){
                if (!confirm('Are yot sure ?! ')){
                    return
                }
            }
            $.ajax({
                url: urls[currentTab],
                data: new FormData(form),
                processData: false,
                contentType: false,
                method: 'post',
                success: (res) => {
                    ele.classList.add('d-none')
                    console.log(res)
                    var x = document.getElementsByClassName("step");
                    x[currentTab].style.display = "none";
                    if (currentTab === 1) {
                        document.getElementById('content').innerHTML = res.content
                    }
                    if (currentTab === 2) {
                        const eles = document.getElementsByClassName('prev')
                        for (const node of eles) {
                            node.classList.add('d-none')
                        }
                        for (const x of Object.keys(res)) {
                            const el = document.getElementById(`prev_${x}`);
                            el.value = res[x];
                            el.classList.remove('d-none')
                        }
                    }
                    currentTab++;
                    if (currentTab >= x.length) {
                        window.location = "{{route('subjects.index')}}"
                        // document.getElementById("signUpForm").submit();
                        return false;
                    }
                    showTab(currentTab);
                },
                beforeSend: () => {
                    $('invalid').hide();
                    btn.innerHTML = `<div class="dots"><span class="dot animate"></span></div>`
                },
                complete: () => {
                    btn.innerHTML = html
                },
                statusCode: {
                    422: (x) => {
                        ele.classList.remove('d-none')
                        ele.innerText = x.responseJSON.message;
                    },
                    400: () => {
                        ele.classList.add('d-none');
                        console.log(res)
                        var x = document.getElementsByClassName("step");
                        x[currentTab].style.display = "none";
                        currentTab++;
                        if (currentTab >= x.length) {
                            document.getElementById("signUpForm").submit();
                            return false;
                        }
                        showTab(currentTab);
                    },
                }
            });
        }

        function prev() {
            if (currentTab === 0) return false;
            var x = document.getElementsByClassName("step");
            x[currentTab].style.display = "none";
            currentTab--;
            console.log(currentTab)
            showTab(currentTab);
        }

        function validateForm() {
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            x = document.getElementsByClassName("step");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
                }
            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("stepIndicator")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("stepIndicator");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }

    </script>

@endsection
