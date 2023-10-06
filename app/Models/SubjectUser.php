<?php

namespace App\Models;

use App\Enums\User\RoleEnum;
use Couchbase\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;


class SubjectUser extends Pivot
{
    protected $fillable = [
        'role' , 'count' , 'teacher_id' , 'subject_id', 'user_id',
    ];
    public $timestamps = true;
    protected $casts = [
        'count' => 'integer',
        'role' => RoleEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class,'teacher_id')->withDefault([
            'name' => '--',
            'id' => null
        ]);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
