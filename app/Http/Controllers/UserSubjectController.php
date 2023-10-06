<?php

namespace App\Http\Controllers;

use App\Enums\Step\NumberStep;
use App\Enums\Step\StateEnum;
use App\Enums\User\RoleEnum;
use App\Http\Requests\StepOneRequest;
use App\Http\Requests\StepThreeRequest;
use App\Http\Requests\StepTwoRequest;
use App\Models\Step;
use App\Models\Subject;
use App\Models\SubjectUser;
use App\Models\User;
use http\Env\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $subjects = auth()->user()->subjectUsers()->with(['teacher', 'subject'])->paginate(10);
        return view('auth.subjects.index', compact([
            'subjects'
        ]));
    }


    public function create(?Step $step = null)
    {
        $step = optional($step);
        abort_if(isset($step->id) and ($step->state == StateEnum::COMPLETED or $step->user_id != auth()->id()),
            404
        );
        $subjects = Subject::all(['id', 'name']);

        return view('auth.subjects.create', compact([
            'subjects',
            'step'
        ]));
    }

    public function storeFormStepOne(StepOneRequest $request)
    {
        $step = auth()->user()->step()->firstOrCreate([
            'state' => StateEnum::UNCOMPLETED,
        ],[
            'data' => []
        ]);
        $step->data = array_merge($step->data, $request->validated());
        $step->number = max($step->number, NumberStep::FIRST->value);
        $step->save();
        $content = '';
        return response()->json([
            'record' => $step,
            'content' => $content
        ]);
    }


    public function storeFormStepTwo(StepTwoRequest $request)
    {
        $step = auth()->user()->step()->where('state', StateEnum::UNCOMPLETED)->first();
        $step->number = max($step->number, NumberStep::SECOND->value);
        $step->data = array_merge($step->data, $request->validated());
        $step->save();
        if ($step->data['role'] == RoleEnum::TEACHER->value) {
            $content = '<input type="number" class="form-control" name="count" step="1" min="1" placeholder="student count"> ';
        } else {
            $teachers = User::whereHas('subjectUsers', function (Builder $builder) use ($step) {
                $builder->where('role', RoleEnum::TEACHER)->where('subject_id', $step->data['subject_id']);
            })->get(['id', 'name']);
            $content = '<select name="teacher_id" class="form-control">';
            $content.= "<option value=' '>".__('globals.chose_teacher')."</option>";
            foreach ($teachers as $teacher) {
                $selected = $teacher->id == ($step->data['teacher_id']??null) ? 'selected' : '';
                $content .= "<option value='$teacher->id' $selected >$teacher->name</option>";
            }
            $content .= '</select>';
        }
        return response()->json([
            'record' => $step,
            'content' => $content
        ]);

    }

    public function storeFormStepThree(StepThreeRequest $request)
    {
        $step = auth()->user()->step()->where('state', StateEnum::UNCOMPLETED)->first();
        $step->number = max($step->number, NumberStep::THIRD->value);
        $step->data = array_merge($step->data, $request->validated());
        $step->save();
        $res = [
            'id' => $step->id,
            'subject' => $step->subject()->name,
            'role' => $step->role(),
        ];
        if ($step->role() == RoleEnum::TEACHER->toString()){
            $res['count'] = $step->count_();
        }
        else{
            $res['teacher'] =$step->teacher()->name;

        }
        return response()->json($res);

    }

    public function finalization(Request $request){

        $step = auth()->user()->step()
            ->where('id',$request->id)
            ->where('state', StateEnum::UNCOMPLETED)
            ->firstOrFail();
        \DB::beginTransaction();
        try {
            $step->update([
                'state'=>StateEnum::COMPLETED
            ]);
            $subjectUser = auth()->user()->subjectUsers()->create($step->data);
            \DB::commit();
            $res =[
                'subject' => $subjectUser->subject->name,
                'role' => $subjectUser->role->toString(),
            ];
            if ($subjectUser->role == RoleEnum::TEACHER){
                $res['count'] = $subjectUser->count;
            }
            else{
                $res['teacher'] = $subjectUser->teacher->name;

            }
            return response($res);
        }catch (\Exception $exception){
            \DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage()
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubjectUser $subjectUser)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubjectUser $subjectUser)
    {
        return view('auth.subjects.edit', compact([
            'subjectUser'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubjectUser $subjectUser)
    {
        $subjectUser->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject Deleted successfully');
    }
}
