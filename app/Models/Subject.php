<?php

namespace App\Models;

use App\Enums\User\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];


    private function baseUserRel() : BelongsToMany
    {
        return $this
            ->belongsToMany(User::class,'subject_user')
            ->withPivot(['role' , 'count' , 'teacher_id'])
            ->withTimestamps()
            ->using(SubjectUser::class);
    }


    public function users(): BelongsToMany
    {
        return $this->baseUserRel();
    }

    public function teachers (): BelongsToMany
    {
        return $this->baseUserRel()->wherePivot('role',RoleEnum::TEACHER->value);
    }
    public function students (): BelongsToMany
    {
        return $this->baseUserRel()->wherePivot('role',RoleEnum::STUDENT->value);
    }
}
